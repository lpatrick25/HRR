<?php

namespace App\Http\Controllers;

use App\Models\HotelPayment;
use App\Models\HotelTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Illuminate\Validation\ValidationException;

class HotelPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $billings = HotelPayment::with('hotelTransaction','hotelRoom')->get()->map(function ($payment, $index) {
            $transaction = $payment->hotelTransaction;

            return [
                'count' => $index + 1,
                'transaction_number' => $transaction->transaction_number ?? 'N/A',
                'customer_name' => $transaction->customer_name,
                'payment_method' => $payment->payment_method,
                'total_amount' => number_format($payment->total_amount, 2),
                'amount_paid' => number_format($payment->amount_paid, 2),
                'room_name' => $transaction->hotelRoom->room_name ?? 'N/A',
                'status' => $transaction->status,
                'action' => '<button onclick="view_billing(' . "'" . $payment->id . "'" . ')" type="button" title="View Details" class="btn btn-custon-rounded-three btn-info"><i class="fa fa-eye"></i></button>',
            ];
        });

        return response()->json($billings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Start transaction
        DB::beginTransaction();

        try {
            // Validation rules
            $validated = $request->validate([
                'hotel_transaction_id' => 'required|exists:hotel_transactions,id',
                'payment_method' => 'required|string|max:255',
                'amount_paid' => 'required|numeric|min:0',
                'checkout_session_id' => 'required|string|max:255',
            ], [
                'hotel_transaction_id.required' => 'Hotel transaction ID is required.',
                'hotel_transaction_id.exists' => 'The selected hotel transaction ID is invalid.',
                'payment_method.required' => 'Payment method is required.',
                'payment_method.string' => 'Payment method must be a string.',
                'payment_method.max' => 'Payment method cannot exceed 255 characters.',
                'amount_paid.required' => 'Amount paid is required.',
                'amount_paid.numeric' => 'Amount paid must be a numeric value.',
                'amount_paid.min' => 'Amount paid cannot be negative.',
                'checkout_session_id.required' => 'Checkout session ID is required.',
                'checkout_session_id.string' => 'Checkout session ID must be a string.',
                'checkout_session_id.max' => 'Checkout session ID cannot exceed 255 characters.',
            ]);

            // Create the hotel payment
            $hotelPayment = HotelPayment::create($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Hotel payment successfully created.',
                'payment' => $hotelPayment,
            ], 201);
        } catch (ValidationException $e) {
            // Handle validation errors
            DB::rollback();

            return response()->json([
                'valid' => false,
                'msg' => '',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to create hotel payment: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to create hotel payment. Please try again later.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Start transaction
        DB::beginTransaction();

        try {
            // Retrieve the hotel payment
            $payment = HotelPayment::with('hotelTransaction')->findOrFail($id);

            // Commit the transaction if successful
            DB::commit();

            return response()->json($payment, 201);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to retrieve hotel payment: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to retrieve hotel payment. Please try again later.',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Start transaction
        DB::beginTransaction();

        try {
            // Validation rules
            $validated = $request->validate([
                'hotel_transaction_id' => 'required|exists:hotel_transactions,id',
                'payment_method' => 'required|string|max:255',
                'amount_paid' => 'required|numeric|min:0',
                'checkout_session_id' => 'required|string|max:255',
            ], [
                'hotel_transaction_id.required' => 'Hotel transaction ID is required.',
                'hotel_transaction_id.exists' => 'The selected hotel transaction ID is invalid.',
                'payment_method.required' => 'Payment method is required.',
                'payment_method.string' => 'Payment method must be a string.',
                'payment_method.max' => 'Payment method cannot exceed 255 characters.',
                'amount_paid.required' => 'Amount paid is required.',
                'amount_paid.numeric' => 'Amount paid must be a numeric value.',
                'amount_paid.min' => 'Amount paid cannot be negative.',
                'checkout_session_id.required' => 'Checkout session ID is required.',
                'checkout_session_id.string' => 'Checkout session ID must be a string.',
                'checkout_session_id.max' => 'Checkout session ID cannot exceed 255 characters.',
            ]);

            // Find and update the hotel payment
            $payment = HotelPayment::findOrFail($id);
            $payment->update($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Hotel payment successfully updated.',
            ], 201);
        } catch (ValidationException $e) {
            // Handle validation errors
            DB::rollback();

            return response()->json([
                'valid' => false,
                'msg' => '',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to update hotel payment: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update hotel payment. Please try again later.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Start transaction
        DB::beginTransaction();

        try {
            // Find and delete the hotel payment
            $payment = HotelPayment::findOrFail($id);
            $payment->delete();

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Hotel payment successfully deleted.',
            ], 201);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to delete hotel payment: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to delete hotel payment. Please try again later.',
            ], 500);
        }
    }
}
