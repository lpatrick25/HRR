<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\FoodPayment;
use App\Models\FoodTransaction;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class FoodPaymentController extends Controller
{
    public function foodPayment($transactionNumber)
    {
        $transactions = FoodTransaction::where('transaction_number', $transactionNumber)->get();

        if ($transactions->isEmpty() || $transactions->contains('status', '!=', 'Pending')) {
            return redirect()->route('food.index');
        }

        $totalAmount = $transactions->sum('total_amount');
        $customerName = $transactions->first()->customer_name;
        $customerEmail = $transactions->first()->customer_email;
        $customerNumber = $transactions->first()->customer_number;

        // Prepare line items for PayMongo
        $lineItems = $transactions->map(function ($transaction) {
            return [
                'currency' => 'PHP',
                'amount' => intval($transaction->total_amount * 100), // Convert to centavos
                'description' => 'Food Order',
                'name' => $transaction->food->food_name . ' x ' . $transaction->quantity,
                'quantity' => 1,
            ];
        })->toArray();

        // Guzzle client
        $client = new Client();

        // PayMongo payload
        $payload = [
            'data' => [
                'attributes' => [
                    'billing' => [
                        'name' => $customerName,
                        'email' => $customerEmail,
                        'phone' => $customerNumber,
                    ],
                    'send_email_receipt' => true,
                    'show_description' => true,
                    'show_line_items' => true,
                    'description' => 'Food Order Payment',
                    'success_url' => route('successFoodOrder', ['transactionNumber' => $transactionNumber]),
                    'statement_descriptor' => 'Belles Bistro',
                    'line_items' => $lineItems,
                    'payment_method_types' => ['gcash'],
                ],
            ],
        ];

        // API Call
        $response = $client->request('POST', 'https://api.paymongo.com/v1/checkout_sessions', [
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode(env('PAYMONGO_API_KEY')),
            ],
            'body' => json_encode($payload),
        ]);

        $body = json_decode($response->getBody(), true);
        $checkoutUrl = $body['data']['attributes']['checkout_url'];

        return redirect()->away($checkoutUrl);
    }

    public function successFoodOrder($transactionNumber)
    {
        $transactions = FoodTransaction::where('transaction_number', $transactionNumber)->get();

        if ($transactions->isEmpty()) {
            return redirect()->route('food.index');
        }

        $totalAmount = $transactions->sum('total_amount');

        // Update all transactions to Confirmed
        $transactions->each(function ($transaction) {
            $transaction->status = 'Confirmed';
            $transaction->save();
        });

        // Create payment record
        FoodPayment::create([
            'transaction_number' => $transactionNumber,
            'total_amount' => $totalAmount,
            'amount_paid' => $totalAmount,
        ]);

        return view('homepage.success', [
            'transactionNumber' => $transactionNumber,
            'totalAmount' => $totalAmount,
        ]);
    }
}
