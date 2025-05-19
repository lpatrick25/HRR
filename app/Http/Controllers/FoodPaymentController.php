<?php

namespace App\Http\Controllers;

use App\Models\FoodPayment;
use App\Models\FoodTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Illuminate\Validation\ValidationException;

class FoodPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = FoodPayment::all()->map(function ($payment, $index) {
            $actionUpdate = '<button onclick="update(' . "'" . $payment->id . "'" . ')" type="button" title="Update" class="btn btn-custon-rounded-three btn-primary"><i class="fas fa-edit"></i></button>';
            $actionDelete = '<button onclick="trash(' . "'" . $payment->id . "'" . ')" type="button" title="Delete" class="btn btn-custon-rounded-three btn-danger"><i class="fas fa-trash"></i></button>';
            $action = $actionUpdate . $actionDelete;

            return [
                'count' => $index + 1,
                'payment_method' => ucwords(strtolower($payment->payment_method)),
                'amount_paid' => $payment->amount_paid,
                'checkout_session_id' => $payment->checkout_session_id,
                'food_transaction_id' => $payment->food_transaction_id,
                'action' => $action,
            ];
        })->toArray();

        return response()->json($response);
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
                'food_transaction_id' => 'required|exists:food_transactions,id',
                'payment_method' => 'required|string|max:255',
                'amount_paid' => 'required|numeric|min:0',
                'checkout_session_id' => 'required|string|max:255',
            ], [
                'food_transaction_id.required' => 'Food transaction ID is required.',
                'food_transaction_id.exists' => 'Food transaction not found.',
                'payment_method.required' => 'Payment method is required.',
                'payment_method.string' => 'Payment method must be a string.',
                'payment_method.max' => 'Payment method cannot exceed 255 characters.',
                'amount_paid.required' => 'Amount paid is required.',
                'amount_paid.numeric' => 'Amount paid must be a valid number.',
                'amount_paid.min' => 'Amount paid cannot be negative.',
                'checkout_session_id.required' => 'Checkout session ID is required.',
                'checkout_session_id.string' => 'Checkout session ID must be a string.',
                'checkout_session_id.max' => 'Checkout session ID cannot exceed 255 characters.',
            ]);

            // Create the food payment
            $payment = FoodPayment::create($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Food payment successfully stored.',
                'payment' => $payment,
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
            Log::error('Failed to store food payment: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to store food payment. Please try again later.',
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
            // Retrieve the food payment
            $payment = FoodPayment::findOrFail($id);

            // Commit the transaction if successful
            DB::commit();

            return response()->json($payment, 201);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to retrieve food payment: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to retrieve food payment. Please try again later.',
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
                'food_transaction_id' => 'required|exists:food_transactions,id',
                'payment_method' => 'required|string|max:255',
                'amount_paid' => 'required|numeric|min:0',
                'checkout_session_id' => 'required|string|max:255',
            ], [
                'food_transaction_id.required' => 'Food transaction ID is required.',
                'food_transaction_id.exists' => 'Food transaction not found.',
                'payment_method.required' => 'Payment method is required.',
                'payment_method.string' => 'Payment method must be a string.',
                'payment_method.max' => 'Payment method cannot exceed 255 characters.',
                'amount_paid.required' => 'Amount paid is required.',
                'amount_paid.numeric' => 'Amount paid must be a valid number.',
                'amount_paid.min' => 'Amount paid cannot be negative.',
                'checkout_session_id.required' => 'Checkout session ID is required.',
                'checkout_session_id.string' => 'Checkout session ID must be a string.',
                'checkout_session_id.max' => 'Checkout session ID cannot exceed 255 characters.',
            ]);

            // Find and update the food payment
            $payment = FoodPayment::findOrFail($id);
            $payment->update($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Food payment successfully updated.',
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
            Log::error('Failed to update food payment: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update food payment. Please try again later.',
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
            // Find and delete the food payment
            $payment = FoodPayment::findOrFail($id);
            $payment->delete();

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Food payment successfully deleted.',
            ], 201);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to delete food payment: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to delete food payment. Please try again later.',
            ], 500);
        }
    }
}
