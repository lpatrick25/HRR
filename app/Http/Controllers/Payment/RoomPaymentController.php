<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\HotelPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\HotelRoom;
use App\Models\HotelTransaction;
use GuzzleHttp\Client;

class RoomPaymentController extends Controller
{
    public function roomPayment($transactionNumber)
    {
        $transaction = HotelTransaction::where('transaction_number', $transactionNumber)->first();

        if (!$transaction || $transaction->status !== 'Pending') {
            return redirect()->route('homepage-bookHotel');
        }

        $room = HotelRoom::findOrFail($transaction->hotel_room_id);

        // Calculate number of nights
        $checkIn = \Carbon\Carbon::parse($transaction->check_in_date);
        $checkOut = \Carbon\Carbon::parse($transaction->check_out_date);
        $numberOfNights = $checkOut->diffInDays($checkIn);

        if ($numberOfNights <= 0) {
            return back()->with('error', 'Invalid booking dates.');
        }

        // Calculate total amount
        $totalAmount = $room->room_rate * $numberOfNights;
        $transaction->total_amount = $totalAmount;
        $transaction->save();

        // Guzzle client
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
                    "description" => "Booking Room",
                    "success_url" => url('/guest/successBookingRoom/' . $transactionNumber),
                    "statement_descriptor" => "Belles Bistro",
                    "line_items" => [
                        [
                            "currency" => "PHP",
                            "amount" => intval($totalAmount * 100), // Convert to centavos
                            "description" => "Belles Bistro",
                            "name" => $room->room_name . " - " . $numberOfNights . " night(s)",
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

        return redirect()->away($checkoutUrl);
    }

    public function successBookingRoom($transactionNumber)
    {
        $transaction = HotelTransaction::where('transaction_number', $transactionNumber)->first();

        if (!$transaction) {
            return redirect()->back();
        }

        $transaction->status = 'Confirmed';

        $transactionPayment = HotelPayment::create([
            'hotel_transaction_id' => $transaction->id,
            'total_amount' => $transaction->total_amount,
            'amount_paid' => $transaction->total_amount,
        ]);

        $transaction->save();
        $transactionPayment->save();

        return redirect()->route('homepage-success');
    }
}
