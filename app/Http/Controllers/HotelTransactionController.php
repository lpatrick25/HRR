<?php

namespace App\Http\Controllers;

use App\Models\HotelPayment;
use App\Models\HotelTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Illuminate\Validation\ValidationException;

class HotelTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = HotelTransaction::with('hotelRoom.hotelType', 'user')
            ->whereIn('status', ['Pending', 'Confirmed', 'Checked-in'])
            ->get()
            ->sortBy(function ($transaction) {
                // Custom order by status
                $statusOrder = [
                    'Pending' => 1,
                    'Confirmed' => 2,
                    'Checked-in' => 3,
                    'Checked-out' => 4
                ];
                return $statusOrder[$transaction->status] ?? 999;
            })
            ->values()
            ->map(function ($transaction, $index) {
                $checkIn = \Carbon\Carbon::parse($transaction->check_in_date);
                $checkOut = \Carbon\Carbon::parse($transaction->check_out_date);
                $totalDays = $checkOut->diffInDays($checkIn);

                $statusBadge = $this->getStatusBadge($transaction->status);
                $action = $this->getActionButton($transaction);

                return [
                    'count' => $index + 1,
                    'transaction_number' => $transaction->transaction_number,
                    'customer_name' => $transaction->customer_name,
                    'room_name' => $transaction->hotelRoom->room_name . ' - ' . $transaction->hotelRoom->hotelType->type_name,
                    'check_in_date' => $checkIn->format('F j, Y'),
                    'check_out_date' => $checkOut->format('F j, Y'),
                    'total_days' => $totalDays . ' Day(s)',
                    'total_amount' => '₱' . number_format((float) $transaction->total_amount, 2, '.', ','),
                    'status' => $statusBadge,
                    'action' => $action,
                ];
            });

        return response()->json($response);
    }

    /**
     * Get status badge HTML.
     */
    private function getStatusBadge($status)
    {
        $labels = [
            'Pending' => 'label label-warning',
            'Confirmed' => 'label label-info',
            'Checked-in' => 'label label-success',
            'Checked-out' => 'label label-default',
            'Cancelled' => 'label label-danger',
            'No-show' => 'label label-dark',
            'Walk-in' => 'label label-primary',
        ];

        return '<span class="' . ($labels[$status] ?? 'label label-default') . '">' . $status . '</span>';
    }

    private function getActionButton($transaction)
    {
        $id = $transaction->id;
        $checkInDate = \Carbon\Carbon::parse($transaction->check_in_date);
        $today = \Carbon\Carbon::today();

        switch ($transaction->status) {
            case 'Pending':
                return '<button onclick="confirmBooking(' . $id . ')" type="button" class="btn btn-sm btn-success">Confirm</button>';

            case 'Confirmed':
                $disabled = $today->lt($checkInDate) ? 'disabled' : '';
                $tooltip1 = $today->lt($checkInDate) ? 'data-toggle="tooltip" title="Check-in is only allowed on ' . $checkInDate->format('F j, Y') . '"' : '';
                $tooltip2 = $today->lt($checkInDate) ? 'data-toggle="tooltip" title="No-show is only allowed on ' . $checkInDate->format('F j, Y') . '"' : '';

                $actionCheckIn = '<button onclick="checkInBooking(' . $id . ')" type="button" class="btn btn-block btn-sm btn-primary" ' . $disabled . ' ' . $tooltip1 . '>Check-in</button>';
                $actionNoShow = '<button onclick="noShowBooking(' . $id . ')" type="button" class="btn btn-block btn-sm btn-info" ' . $disabled . ' ' . $tooltip2 . '>No-show</button>';
                $action = $actionCheckIn . $actionNoShow;

                return $action;

            case 'Checked-in':
                return '<button onclick="checkOutBooking(' . $id . ')" type="button" class="btn btn-sm btn-warning">Check-out</button>';

            default:
                return '<span class="text-muted">No Actions</span>';
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
                'transaction_number' => 'required|string|max:100|unique:hotel_transactions,transaction_number',
                'user_id' => 'nullable|exists:users,id',
                'customer_name' => 'required|string|max:100',
                'customer_number' => 'required|string|max:20',
                'customer_email' => 'required|email|max:100',
                'customer_type' => 'required|in:Registered,Walk-in,Online',
                'hotel_room_id' => 'required|exists:hotel_rooms,id',
                'check_in_date' => 'required|date',
                'check_out_date' => 'required|date|after:check_in_date',
                'total_amount' => 'required|numeric|min:0',
                'status' => 'nullable|in:Pending,Confirmed,Checked-in,Checked-out,Cancelled,No-show,Walk-in',
            ], [
                'transaction_number.required' => 'Transaction number is required.',
                'transaction_number.unique' => 'This transaction number already exists.',
                'user_id.exists' => 'Selected user does not exist.',
                'customer_name.required' => 'Customer name is required.',
                'customer_number.required' => 'Customer number is required.',
                'customer_number.max' => 'Customer number cannot exceed 20 characters.',
                'customer_email.required' => 'Customer email is required.',
                'customer_email.email' => 'Please provide a valid email address.',
                'customer_type.required' => 'Customer type is required.',
                'customer_type.in' => 'Customer type must be either Registered or Walk-in.',
                'hotel_room_id.exists' => 'Selected room does not exist.',
                'check_in_date.date' => 'Check-in date must be a valid date.',
                'check_out_date.date' => 'Check-out date must be a valid date.',
                'check_out_date.after' => 'Check-out date must be after the check-in date.',
                'total_amount.required' => 'Total amount is required.',
                'total_amount.numeric' => 'Total amount must be a number.',
                'total_amount.min' => 'Total amount cannot be negative.',
                // 'status.required' => 'Transaction status is required.',
                'status.in' => 'Status must be one of "Pending", "Confirmed", "Checked-in", "Checked-out", "Cancelled", "Walk-in", or "No-show".',
            ]);

            // Create the hotel transaction
            $transaction = HotelTransaction::create($validated);

            if ($validated['customer_type'] !== 'Online') {
                // Create the hotel payment record for this transaction
                $payment = HotelPayment::create([
                    'hotel_transaction_id' => $transaction->id,
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
                'msg' => 'Hotel transaction successfully created.',
                'hotel_transaction' => $transaction,
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
            Log::error('Failed to create hotel transaction: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to create hotel transaction. Please try again later.',
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
            // Retrieve the hotel transaction along with related user and hotel room
            $transaction = HotelTransaction::with('hotelRoom', 'user')->findOrFail($id);

            // Commit the transaction if successful
            DB::commit();

            return response()->json($transaction, 200);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to retrieve hotel transaction: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to retrieve hotel transaction. Please try again later.',
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
                'transaction_number' => 'required|string|max:255|unique:hotel_transactions,transaction_number,' . $id,
                'user_id' => 'nullable|exists:users,id',
                'customer_name' => 'required|string|max:255',
                'customer_number' => 'required|string|max:20',
                'customer_email' => 'required|email|max:255',
                'customer_type' => 'required|in:Registered,Walk-in,Online',
                'hotel_room_id' => 'required|exists:hotel_rooms,id',
                'check_in_date' => 'required|date',
                'check_out_date' => 'required|date|after:check_in_date',
                'total_amount' => 'required|numeric|min:0',
                'status' => 'nullable|in:Pending,Confirmed,Checked-in,Checked-out,Cancelled,No-show,Walk-in',
            ], [
                'transaction_number.required' => 'Transaction number is required.',
                'transaction_number.unique' => 'This transaction number already exists.',
                'user_id.exists' => 'Selected user does not exist.',
                'customer_name.required' => 'Customer name is required.',
                'customer_name.string' => 'Customer name must be a string.',
                'customer_number.required' => 'Customer number is required.',
                'customer_email.required' => 'Customer email is required.',
                'customer_email.email' => 'Please provide a valid email address.',
                'customer_type.required' => 'Customer type is required.',
                'customer_type.in' => 'Customer type must be either Registered or Walk-in.',
                'hotel_room_id.required' => 'Room is required.',
                'hotel_room_id.exists' => 'Selected room does not exist.',
                'check_in_date.required' => 'Check-in date is required.',
                'check_out_date.required' => 'Check-out date is required.',
                'total_amount.required' => 'Total amount is required.',
                // 'status.required' => 'Transaction status is required.',
                'status.in' => 'Status must be one of "Pending", "Confirmed", "Checked-in", "Checked-out", "Cancelled", "Walk-in", or "No-show".',
            ]);

            // Find and update the hotel transaction
            $transaction = HotelTransaction::findOrFail($id);
            $transaction->update($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Hotel transaction successfully updated.',
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
            Log::error('Failed to update hotel transaction: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update hotel transaction. Please try again later.',
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
            // Find and delete the hotel transaction
            $transaction = HotelTransaction::findOrFail($id);
            $transaction->delete();

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Hotel transaction successfully deleted.',
            ], 200);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to delete hotel transaction: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to delete hotel transaction. Please try again later.',
            ], 500);
        }
    }
}
