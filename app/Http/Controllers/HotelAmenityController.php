<?php

namespace App\Http\Controllers;

use App\Models\HotelAmenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class HotelAmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = HotelAmenity::with('hotelRoom')->get()->map(function ($amenity, $index) {
            $actionDelete = '<button onclick="trash_amenity(' . "'" . $amenity->id . "'" . ')" type="button" title="Delete" class="btn btn-custon-rounded-three btn-danger"><i class="fa fa-trash"></i></button>';
            $action = $actionDelete;

            return [
                'count' => $index + 1,
                'room_name' => $amenity->hotelRoom->room_name ?? 'N/A',
                'amenity' => $amenity->amenity,
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
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'hotel_room_id' => 'required|exists:hotel_rooms,id',
                'amenity' => 'required|string|max:50',
            ], [
                'hotel_room_id.required' => 'Room is required.',
                'hotel_room_id.exists' => 'Selected room does not exist.',
                'amenity.required' => 'Amenity is required.',
                'amenity.string' => 'Amenity must be a string.',
                'amenity.max' => 'Amenity cannot exceed 50 characters.',
            ]);

            $amenity = HotelAmenity::create($validated);

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Amenity successfully stored.',
                'amenity' => $amenity,
            ], 201);
        } catch (ValidationException $e) {
            DB::rollback();

            return response()->json([
                'valid' => false,
                'msg' => '',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to store amenity: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to store amenity. Please try again later.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        DB::beginTransaction();

        try {
            $amenity = HotelAmenity::with('hotelRoom')->findOrFail($id);

            DB::commit();

            return response()->json($amenity, 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to retrieve amenity: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to retrieve amenity. Please try again later.',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'hotel_room_id' => 'nullable|exists:hotel_rooms,id',
                'amenity' => 'required|string|max:50',
            ], [
                'hotel_room_id.exists' => 'Selected room does not exist.',
                'amenity.required' => 'Amenity is required.',
                'amenity.string' => 'Amenity must be a string.',
                'amenity.max' => 'Amenity cannot exceed 50 characters.',
            ]);

            $amenity = HotelAmenity::findOrFail($id);
            $amenity->update($validated);

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Amenity successfully updated.',
            ], 200);
        } catch (ValidationException $e) {
            DB::rollback();

            return response()->json([
                'valid' => false,
                'msg' => '',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to update amenity: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update amenity. Please try again later.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $amenity = HotelAmenity::findOrFail($id);
            $amenity->delete();

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Amenity successfully deleted.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to delete amenity: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to delete amenity. Please try again later.',
            ], 500);
        }
    }

    /**
     * Get all amenities without pagination.
     */
    public function getAll()
    {
        $amenities = HotelAmenity::all();
        return response()->json($amenities);
    }
}
