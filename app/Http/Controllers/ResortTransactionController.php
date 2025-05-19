<?php

namespace App\Http\Controllers;

use App\Models\ResortPayment;
use App\Models\ResortTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Illuminate\Validation\ValidationException;

class ResortTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = ResortTransaction::with(['user', 'resortCottage.resortType'])
            ->whereIn('status', ['Pending', 'Confirmed', 'Completed'])
            ->get()
            ->sortBy(function ($transaction) {
                // Custom order by status
                $statusOrder = [
                    'Pending' => 1,
                    'Confirmed' => 2,
                    'Completed' => 3,
                    'Cancelled' => 4,
                    'No-show' => 5,
                ];
                return $statusOrder[$transaction->status] ?? 999;
            })
            ->values()
            ->map(function ($transaction, $index) {
                $statusBadge = $this->getStatusBadge($transaction->status);
                $action = $this->getActionButton($transaction);

                return [
                    'count' => $index + 1,
                    'transaction_number' => $transaction->transaction_number,
                    'customer_name' => ucwords(strtolower($transaction->customer_name)),
                    'customer_number' => $transaction->customer_number,
                    'customer_email' => $transaction->customer_email,
                    'customer_type' => $transaction->customer_type,
                    'cottage_name' => $transaction->resortCottage->cottage_name . ' - ' . $transaction->resortCottage->resortType->type_name,
                    'booking_date' => \Carbon\Carbon::parse($transaction->booking_date)->format('F j, Y'),
                    'total_amount' => '₱' . number_format((float)$transaction->total_amount, 2, '.', ','),
                    'status' => $statusBadge,
                    'action' => $action,
                ];
            });

        return response()->json($response);
    }

    private function getStatusBadge($status)
    {
        $labels = [
            'Pending' => 'label label-warning',
            'Confirmed' => 'label label-info',
            'Completed' => 'label label-success',
            'Cancelled' => 'label label-danger',
            'No-show' => 'label label-dark',
        ];

        return '<span class="' . ($labels[$status] ?? 'label label-default') . '">' . $status . '</span>';
    }

    private function getActionButton($transaction)
    {
        $id = $transaction->id;
        $bookingDate = \Carbon\Carbon::parse($transaction->booking_date);
        $today = \Carbon\Carbon::today();

        switch ($transaction->status) {
            case 'Pending':
                return '<button onclick="confirmReservation(' . $id . ')" type="button" class="btn btn-sm btn-success">Confirm</button>';

            case 'Confirmed':
                $disabled = $today->lt($bookingDate) ? 'disabled' : '';
                $tooltip = $today->lt($bookingDate) ? 'data-toggle="tooltip" title="This reservation is for ' . $bookingDate->format('F j, Y') . '"' : '';

                $actionCheckIn = '<button onclick="completeReservation(' . $id . ')" type="button" class="btn btn-block btn-sm btn-primary" ' . $disabled . ' ' . $tooltip . '>Mark as Completed</button>';
                $actionNoShow = '<button onclick="noShowReservation(' . $id . ')" type="button" class="btn btn-block btn-sm btn-info" ' . $disabled . ' ' . $tooltip . '>No-show</button>';
                $action = $actionCheckIn . $actionNoShow;

                return $action;

            case 'Completed':
                return '';

            case 'Cancelled':
                return '';

            case 'No-show':
                return '';

            default:
                return '';
        }
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
                'transaction_number' => 'required|string|max:100|unique:resort_transactions,transaction_number',
                'user_id' => 'nullable|exists:users,id',
                'customer_name' => 'required|string|max:50',
                'customer_number' => 'required|string|max:20',
                'customer_email' => 'required|email|max:50',
                'customer_type' => 'required|string|in:Registered,Walk-in,Online',
                'resort_cottage_id' => 'required|exists:resort_cottages,id',
                'booking_date' => 'required|date',
                'total_amount' => 'required|numeric|min:0',
                'status' => 'nullable|string|in:Pending,Confirmed,Completed,Cancelled,No-show',
            ], [
                'transaction_number.required' => 'Transaction number is required.',
                'transaction_number.unique' => 'This transaction number is already taken.',
                'user_id.exists' => 'The selected user ID is invalid.',
                'customer_name.required' => 'Customer name is required.',
                'customer_number.required' => 'Customer number is required.',
                'customer_email.required' => 'Customer email is required.',
                'customer_email.email' => 'Customer email must be a valid email address.',
                'customer_type.in' => 'Customer type must be either "Registered" or "Walk-in".',
                'resort_cottage_id.required' => 'Cottage ID is required.',
                'resort_cottage_id.exists' => 'The selected cottage ID is invalid.',
                'booking_date.required' => 'Booking date is required.',
                'total_amount.required' => 'Total amount is required.',
                'total_amount.numeric' => 'Total amount must be a number.',
                'total_amount.min' => 'Total amount must be at least 0.',
                'status.in' => 'Status must be one of "Pending", "Confirmed", "Completed", "Cancelled", or "No-show".',
            ]);

            // Create the resort transaction
            $transaction = ResortTransaction::create($validated);

            if ($validated['customer_type'] !== 'Online') {
                // Create the hotel payment record for this transaction
                // Create the hotel payment record for this transaction
                $payment = ResortPayment::create([
                    'resort_transaction_id' => $transaction->id,
                    'payment_method' => 'Cash',
                    'total_amount' => $transaction->total_amount,
                    'amount_paid' => $transaction->total_amount,
                    'checkout_session_id' => null, // optional field, if applicable
                ]);
            }

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Resort transaction successfully stored.',
                'transaction' => $transaction,
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
            Log::error('Failed to store resort transaction: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to store resort transaction. Please try again later.',
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
            // Retrieve the resort transaction
            $transaction = ResortTransaction::with(['user', 'resortCottage'])->findOrFail($id);

            // Commit the transaction if successful
            DB::commit();

            return response()->json($transaction, 201);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to retrieve resort transaction: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to retrieve resort transaction. Please try again later.',
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
                'transaction_number' => 'required|string|max:255|unique:resort_transactions,transaction_number,' . $id,
                'user_id' => 'nullable|exists:users,id',
                'customer_name' => 'required|string|max:50',
                'customer_number' => 'required|string|max:20',
                'customer_email' => 'required|email|max:50',
                'customer_type' => 'required|string|in:Registered,Walk-in',
                'resort_cottage_id' => 'required|exists:resort_cottages,id',
                'booking_date' => 'required|date',
                'total_amount' => 'required|numeric|min:0',
                'status' => 'required|string|in:Pending,Confirmed,Completed,Cancelled,No-show',
            ], [
                'transaction_number.required' => 'Transaction number is required.',
                'transaction_number.unique' => 'This transaction number is already taken.',
                'user_id.exists' => 'The selected user ID is invalid.',
                'customer_name.required' => 'Customer name is required.',
                'customer_number.required' => 'Customer number is required.',
                'customer_email.required' => 'Customer email is required.',
                'customer_email.email' => 'Customer email must be a valid email address.',
                'customer_type.in' => 'Customer type must be either "Registered" or "Walk-in".',
                'resort_cottage_id.required' => 'Cottage ID is required.',
                'resort_cottage_id.exists' => 'The selected cottage ID is invalid.',
                'booking_date.required' => 'Booking date is required.',
                'total_amount.required' => 'Total amount is required.',
                'total_amount.numeric' => 'Total amount must be a number.',
                'total_amount.min' => 'Total amount must be at least 0.',
                'status.in' => 'Status must be one of "Pending", "Confirmed", "Completed", "Cancelled", or "No-show".',
            ]);

            // Find and update the resort transaction
            $transaction = ResortTransaction::findOrFail($id);
            $transaction->update($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Resort transaction successfully updated.',
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
            Log::error('Failed to update resort transaction: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update resort transaction. Please try again later.',
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
            // Find and delete the resort transaction
            $transaction = ResortTransaction::findOrFail($id);
            $transaction->delete();

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Resort transaction successfully deleted.',
            ], 201);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to delete resort transaction: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to delete resort transaction. Please try again later.',
            ], 500);
        }
    }
}
