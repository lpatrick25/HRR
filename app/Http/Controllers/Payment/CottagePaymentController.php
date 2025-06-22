<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\ResortPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ResortCottage;
use App\Models\ResortTransaction;
use GuzzleHttp\Client;

class CottagePaymentController extends Controller
{
    public function cottagePayment($transactionNumber)
    {
        $transaction = ResortTransaction::where('transaction_number', $transactionNumber)->first();

        if (!$transaction) {
            return redirect()->route('homepage-bookResort');
        }

        if ($transaction->status !== 'Pending') {
            return redirect()->route('homepage-bookResort');
        }

        // Get the selected cottage
        $cottage = ResortCottage::findOrFail($transaction->resort_cottage_id);

        // Setup Guzzle client
        $client = new Client();

        // PayMongo payload
        $payload = [
            "data" => [
                "attributes" => [
                    "billing" => [
                        "name" => $transaction->customer_name,
                        "email" => $transaction->customer_email,
                        "phone" => $transaction->customer_number
                    ],
                    "send_email_receipt" => true,
                    "show_description" => true,
                    "show_line_items" => true,
                    "description" => "Booking Cottage",
                    "success_url" => url('/guest/successBookingCottage/' . $transactionNumber),
                    "statement_descriptor" => "Belles Bistro", // Fixed: Removed space and single quote
                    "line_items" => [
                        [
                            "currency" => "PHP",
                            "amount" => intval($cottage->cottage_rate * 100),
                            "description" => "Belles Bistro", // Fixed: Removed space and single quote
                            "name" => $cottage->cottage_name,
                            "quantity" => 1
                        ]
                    ],
                    "payment_method_types" => ["gcash"]
                ]
            ]
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

        // Redirect to PayMongo checkout
        return redirect()->away($checkoutUrl);
    }

    public function successBookingCottage($transactionNumber)
    {
        $transaction = ResortTransaction::where('transaction_number', $transactionNumber)->first();

        if (!$transaction) {
            return redirect()->back();
        }

        $transaction->status = 'Confirmed';

        $transactionPayment = ResortPayment::create([
            'resort_transaction_id' => $transaction->id,
            'total_amount' => $transaction->total_amount,
            'amount_paid' => $transaction->total_amount,
        ]);

        $transaction->save();
        $transactionPayment->save();

        return redirect()->route('homepage-success');
    }
}
