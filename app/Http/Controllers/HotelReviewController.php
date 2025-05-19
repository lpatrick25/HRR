<?php

namespace App\Http\Controllers;

use App\Models\HotelReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class HotelReviewController extends Controller
{
    /**
     * Display all approved reviews for a specific hotel room.
     */
    public function index()
    {
        try {
            $reviews = HotelReview::where('status', '!=', 'Rejected') // Exclude rejected reviews
                ->with([
                    'user:id,first_name,last_name', // Fetch customer details
                    'hotelRoom:id,room_name,hotel_type_id', // Fetch room details
                    'hotelRoom.hotelType:id,type_name' // Fetch room type details
                ])
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($review, $index) {
                    // Default values to prevent errors
                    $roomName = ucwords(strtolower($review->hotelRoom->room_name ?? 'N/A'));
                    $roomType = ucwords(strtolower($review->hotelRoom->hotelType->type_name ?? 'N/A'));
                    $customerName = ucwords(strtolower(($review->user->first_name ?? '') . ' ' . ($review->user->last_name ?? '')));
                    $reviewText = ucfirst($review->review ?? 'No review provided');
                    $rating = $review->rating . ' ⭐';

                    // Approve & Reject buttons
                    if ($review->status === 'Pending') {
                        $approveButton = '<button onclick="approveReview(' . $review->id . ')" type="button" class="btn btn-success">Approve</button>';
                        $rejectButton = '<button onclick="rejectReview(' . $review->id . ')" type="button" class="btn btn-danger">Reject</button>';
                        $actionButton = $approveButton . ' ' . $rejectButton;
                    } else {
                        $actionButton = '<button type="button" class="btn btn-secondary" disabled>Approved</button>';
                    }

                    return [
                        'count' => $index + 1,
                        'room_name' => $roomName,
                        'room_type' => $roomType,
                        'customer_name' => $customerName,
                        'customer_review' => $reviewText,
                        'customer_rating' => $rating,
                        'action' => $actionButton,
                    ];
                });

            return response()->json($reviews);
        } catch (\Exception $e) {
            Log::error('Failed to fetch reviews: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to load reviews.',
            ], 500);
        }
    }

    /**
     * Approve or reject a review.
     */
    public function updateStatus(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'status' => 'required|in:Approved,Rejected',
            ]);

            $review = HotelReview::findOrFail($id);
            $review->status = $validated['status'];
            $review->save();

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Review status updated successfully.',
            ], 200);
        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json([
                'valid' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to update review status: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update review status. Please try again later.',
            ], 500);
        }
    }

    /**
     * Delete a review.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $review = HotelReview::findOrFail($id);
            $review->delete();

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Review deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to delete review: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to delete review. Please try again later.',
            ], 500);
        }
    }
}
