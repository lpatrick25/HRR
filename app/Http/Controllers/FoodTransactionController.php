<?php

namespace App\Http\Controllers;

use App\Models\FoodTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class FoodTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = FoodTransaction::with('user', 'food')->get()->map(function ($transaction, $index) {
            $actionUpdate = '<button onclick="update(' . "'" . $transaction->id . "'" . ')" type="button" title="Update" class="btn btn-custon-rounded-three btn-primary"><i class="fas fa-edit"></i></button>';
            $actionDelete = '<button onclick="trash(' . "'" . $transaction->id . "'" . ')" type="button" title="Delete" class="btn btn-custon-rounded-three btn-danger"><i class="fas fa-trash"></i></button>';
            $action = $actionUpdate . $actionDelete;

            return [
                'count' => $index + 1,
                'customer_name' => ucwords(strtolower($transaction->customer_name)),
                'customer_email' => $transaction->customer_email,
                'food_item' => $transaction->food->food_name, // Assuming 'food_name' is a column in 'Food'
                'quantity' => $transaction->quantity,
                'total_amount' => $transaction->total_amount,
                'reservation_date' => $transaction->reservation_date,
                'status' => $transaction->status,
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
                'transaction_number' => 'required|string|max:255|unique:food_transactions,transaction_number',
                'user_id' => 'nullable|exists:users,id',
                'customer_name' => 'required|string|max:255',
                'customer_number' => 'required|string|max:15',
                'customer_email' => 'required|email|max:255',
                'customer_type' => 'required|string|max:50',
                'food_id' => 'required|exists:foods,id',
                'quantity' => 'required|integer|min:1',
                'total_amount' => 'required|numeric|min:0',
                'reservation_date' => 'required|date',
                'status' => 'required|string|max:50',
            ], [
                'transaction_number.required' => 'Transaction number is required.',
                'transaction_number.unique' => 'This transaction number already exists.',
                'user_id.exists' => 'Selected user does not exist.',
                'customer_name.required' => 'Customer name is required.',
                'customer_name.string' => 'Customer name must be a string.',
                'customer_number.required' => 'Customer number is required.',
                'customer_email.required' => 'Customer email is required.',
                'customer_email.email' => 'Please provide a valid email address.',
                'food_id.required' => 'Food is required.',
                'food_id.exists' => 'Selected food does not exist.',
                'quantity.required' => 'Quantity is required.',
                'quantity.integer' => 'Quantity must be a valid number.',
                'quantity.min' => 'Quantity must be at least 1.',
                'total_amount.required' => 'Total amount is required.',
                'total_amount.numeric' => 'Total amount must be a valid number.',
                'total_amount.min' => 'Total amount must be a positive value.',
                'reservation_date.required' => 'Reservation date is required.',
                'reservation_date.date' => 'Reservation date must be a valid date.',
                'status.required' => 'Transaction status is required.',
                'status.string' => 'Status must be a valid string.',
            ]);

            // Create the food transaction
            $foodTransaction = FoodTransaction::create($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Food transaction successfully created.',
                'food_transaction' => $foodTransaction,
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
            Log::error('Failed to create food transaction: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to create food transaction. Please try again later.',
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
            // Retrieve the food transaction with associated user and food data
            $foodTransaction = FoodTransaction::with('user', 'food')->findOrFail($id);

            // Commit the transaction if successful
            DB::commit();

            return response()->json($foodTransaction, 200);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to retrieve food transaction: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to retrieve food transaction. Please try again later.',
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
                'transaction_number' => 'required|string|max:255|unique:food_transactions,transaction_number,' . $id,
                'user_id' => 'required|exists:users,id',
                'customer_name' => 'required|string|max:255',
                'customer_number' => 'required|string|max:15',
                'customer_email' => 'required|email|max:255',
                'customer_type' => 'required|string|max:50',
                'food_id' => 'required|exists:foods,id',
                'quantity' => 'required|integer|min:1',
                'total_amount' => 'required|numeric|min:0',
                'reservation_date' => 'required|date',
                'status' => 'required|string|max:50',
            ], [
                'transaction_number.required' => 'Transaction number is required.',
                'transaction_number.unique' => 'This transaction number already exists.',
                'user_id.exists' => 'Selected user does not exist.',
                'customer_name.required' => 'Customer name is required.',
                'customer_name.string' => 'Customer name must be a string.',
                'customer_number.required' => 'Customer number is required.',
                'customer_email.required' => 'Customer email is required.',
                'customer_email.email' => 'Please provide a valid email address.',
                'food_id.required' => 'Food is required.',
                'food_id.exists' => 'Selected food does not exist.',
                'quantity.required' => 'Quantity is required.',
                'quantity.integer' => 'Quantity must be a valid number.',
                'quantity.min' => 'Quantity must be at least 1.',
                'total_amount.required' => 'Total amount is required.',
                'total_amount.numeric' => 'Total amount must be a valid number.',
                'total_amount.min' => 'Total amount must be a positive value.',
                'reservation_date.required' => 'Reservation date is required.',
                'reservation_date.date' => 'Reservation date must be a valid date.',
                'status.required' => 'Transaction status is required.',
                'status.string' => 'Status must be a valid string.',
            ]);

            // Find and update the food transaction
            $foodTransaction = FoodTransaction::findOrFail($id);
            $foodTransaction->update($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Food transaction successfully updated.',
            ], 200);
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
            Log::error('Failed to update food transaction: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update food transaction. Please try again later.',
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
            // Find and delete the food transaction
            $foodTransaction = FoodTransaction::findOrFail($id);
            $foodTransaction->delete();

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Food transaction successfully deleted.',
            ], 200);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to delete food transaction: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to delete food transaction. Please try again later.',
            ], 500);
        }
    }
}
