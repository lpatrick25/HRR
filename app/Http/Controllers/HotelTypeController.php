<?php

namespace App\Http\Controllers;

use App\Models\HotelType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class HotelTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = HotelType::with('hotelRooms')->get()->map(function ($type, $index) {
            $actionUpdate = '<button onclick="view_type(' . "'" . $type->id . "'" . ')" type="button" title="Update" class="btn btn-custon-rounded-three btn-primary"><i class="fa fa-edit"></i></button>';
            $actionDelete = '<button onclick="trash_type(' . "'" . $type->id . "'" . ')" type="button" title="Delete" class="btn btn-custon-rounded-three btn-danger"><i class="fa fa-trash"></i></button>';
            $action = $actionUpdate . $actionDelete;

            return [
                'count' => $index + 1,
                'type_name' => ucwords(strtolower($type->type_name)),
                'type_description' => $type->type_description,
                'room_count' => $type->hotelRooms->count(),  // Number of rooms related to this room type
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
                'type_name' => 'required|string|max:255|unique:hotel_types,type_name',
                'type_description' => 'nullable|string|max:500',
            ], [
                'type_name.required' => 'Type name is required.',
                'type_name.string' => 'Type name must be a string.',
                'type_name.max' => 'Type name cannot exceed 255 characters.',
                'type_name.unique' => 'This type name is already taken.',
                'type_description.string' => 'Description must be a string.',
                'type_description.max' => 'Description cannot exceed 500 characters.',
            ]);

            // Create the room type
            $type = HotelType::create($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Room type successfully stored.',
                'type' => $type,
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
            Log::error('Failed to store room type: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to store room type. Please try again later.',
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
            // Retrieve the room type along with its cottages
            $type = HotelType::with('hotelRooms')->findOrFail($id);

            // Commit the transaction if successful
            DB::commit();

            return response()->json($type, 200);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to retrieve room type: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to retrieve room type. Please try again later.',
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
                'type_name' => 'required|string|max:255|unique:hotel_types,type_name,' . $id,
                'type_description' => 'nullable|string|max:500',
            ], [
                'type_name.required' => 'Type name is required.',
                'type_name.string' => 'Type name must be a string.',
                'type_name.max' => 'Type name cannot exceed 255 characters.',
                'type_name.unique' => 'This type name is already taken.',
                'type_description.string' => 'Description must be a string.',
                'type_description.max' => 'Description cannot exceed 500 characters.',
            ]);

            // Find and update the room type
            $type = HotelType::findOrFail($id);
            $type->update($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Room type successfully updated.',
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
            Log::error('Failed to update room type: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update room type. Please try again later.',
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
            // Find and delete the room type
            $type = HotelType::findOrFail($id);
            $type->delete();

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Room type successfully deleted.',
            ], 200);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to delete room type: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to delete room type. Please try again later.',
            ], 500);
        }
    }

    public function getAll()
    {
        $types = HotelType::all();
        return response()->json($types);
    }
}
