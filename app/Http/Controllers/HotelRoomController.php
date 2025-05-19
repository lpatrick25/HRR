<?php

namespace App\Http\Controllers;

use App\Models\HotelRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class HotelRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = HotelRoom::with('hotelType', 'hotelTransactions')->get()->map(function ($room, $index) {

            $actionInfo = '';
            // $user_role = auth()->user()->user_role;
            $user_role = 'Admin';
            if ($user_role === 'Admin') {
                $actionInfo = '<a href="/owner/' . $room->id . '/roomInfo" type="button" title="Info" class="btn btn-custon-rounded-three btn-default"><i class="fa fa-file-image-o"></i></a>';
            } else {
                $actionInfo = '<a href="/hotel/' . $room->id . '/roomInfo" type="button" title="Info" class="btn btn-custon-rounded-three btn-default"><i class="fa fa-file-image-o"></i></a>';
            }

            $actionUpdate = '<button onclick="view_room(' . "'" . $room->id . "'" . ')" type="button" title="Update" class="btn btn-custon-rounded-three btn-primary"><i class="fa fa-edit"></i></button>';
            $actionDelete = '<button onclick="trash_room(' . "'" . $room->id . "'" . ')" type="button" title="Delete" class="btn btn-custon-rounded-three btn-danger"><i class="fa fa-trash"></i></button>';
            $action = $actionUpdate . $actionDelete . $actionInfo;

            return [
                'count' => $index + 1,
                'room_name' => ucwords(strtolower($room->room_name)),
                'hotel_type_id' => $room->hotelType->type_name,
                'room_status' => $room->room_status,
                'room_rate' => $room->room_rate . ' PHP',
                'room_capacity' => $room->room_capacity . ' Person',
                'picture' => '<img src="' . asset($room->picture) . '" alt="Room Picture" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">',
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
                'room_name' => 'required|string|max:100|unique:hotel_rooms,room_name',
                'hotel_type_id' => 'required|exists:hotel_types,id',
                'room_status' => 'required|in:Available,Maintenance',
                'room_rate' => 'required|numeric|min:0',
                'room_capacity' => 'required|integer|min:1',
            ], [
                'room_name.required' => 'The room name is required.',
                'room_name.string' => 'The room name must be a string.',
                'room_name.max' => 'The room name must not exceed 100 characters.',
                'room_name.unique' => 'The room name has already been taken.',

                'hotel_type_id.required' => 'The room type is required.',
                'hotel_type_id.exists' => 'The selected room type is invalid.',

                'room_status.required' => 'The room status is required.',
                'room_status.in' => 'The room status is invalid. Valid options: Available, Maintenance.',

                'room_rate.required' => 'The room rate is required.',
                'room_rate.numeric' => 'The room rate must be a numeric value.',
                'room_rate.min' => 'The room rate must be at least 0.',

                'room_capacity.required' => 'The room capacity is required.',
                'room_capacity.integer' => 'The room capacity must be an integer.',
                'room_capacity.min' => 'The room capacity must be at least 1.',
            ]);

            // Create the hotel room
            $room = HotelRoom::create($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Hotel room successfully created.',
                'hotel_room' => $room,
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
            Log::error('Failed to create hotel room: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to create hotel room. Please try again later.',
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
            // Retrieve the hotel room along with the associated hotel type
            $room = HotelRoom::with('hotelType')->findOrFail($id);

            // Commit the transaction if successful
            DB::commit();

            return response()->json($room, 200);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to retrieve hotel room: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to retrieve hotel room. Please try again later.',
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
                'room_name' => 'required|string|max:255|unique:hotel_rooms,room_name,' . $id,
                'hotel_type_id' => 'required|exists:hotel_types,id',
                'room_status' => 'required|in:Available,Maintenance',
                'room_rate' => 'required|numeric|min:0',
                'room_capacity' => 'required|integer|min:1',
            ], [
                'room_name.required' => 'The room name is required.',
                'room_name.string' => 'The room name must be a string.',
                'room_name.max' => 'The room name must not exceed 100 characters.',
                'room_name.unique' => 'The room name has already been taken.',

                'hotel_type_id.required' => 'The room type is required.',
                'hotel_type_id.exists' => 'The selected room type is invalid.',

                'room_status.required' => 'The room status is required.',
                'room_status.in' => 'The room status is invalid. Valid options: Available, Maintenance.',

                'room_rate.required' => 'The room rate is required.',
                'room_rate.numeric' => 'The room rate must be a numeric value.',
                'room_rate.min' => 'The room rate must be at least 0.',

                'room_capacity.required' => 'The room capacity is required.',
                'room_capacity.integer' => 'The room capacity must be an integer.',
                'room_capacity.min' => 'The room capacity must be at least 1.',
            ]);

            // Find and update the hotel room
            $room = HotelRoom::findOrFail($id);
            $room->update($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Hotel room successfully updated.',
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
            Log::error('Failed to update hotel room: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update hotel room. Please try again later.',
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
            // Find and delete the hotel room
            $room = HotelRoom::findOrFail($id);
            $room->delete();

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Hotel room successfully deleted.',
            ], 200);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to delete hotel room: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to delete hotel room. Please try again later.',
            ], 500);
        }
    }

    public function roomPicture(Request $request)
    {

        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'hotel_room_id' => 'required|exists:hotel_rooms,id',
                'picture' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:20240', // 10MB Max
            ], [
                'hotel_room_id.required' => 'Room is required.',
                'hotel_room_id.exists' => 'Selected room does not exist.',
                'picture.required' => 'Picture is required.',
                'picture.image' => 'Uploaded file must be an image.',
                'picture.mimes' => 'Picture must be a file of type: jpeg, png, jpg, gif.',
                'picture.max' => 'Picture must not exceed 20MB.',
            ]);

            // Handle image upload
            $roomId = $request->hotel_room_id;
            $imageFile = $request->file('picture');

            $uploadPath = 'uploads/hotel_pictures/' . $roomId; // public/uploads/hotel_pictures/{hotel_room_id}
            $fileName = time() . '_' . $imageFile->getClientOriginalName();
            $imageFile->move(public_path($uploadPath), $fileName);

            $room = HotelRoom::findOrFail($roomId);

            // Save to DB
            $room->update([
                'picture' => $uploadPath . '/' . $fileName,
            ]);

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Picture successfully stored.',
                'room' => $room,
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
            Log::error('Failed to store picture: ' . $e->getMessage());
            return response()->json([
                'valid' => false,
                'msg' => 'Failed to store picture. Please try again later.',
            ], 500);
        }
    }

    public function getRoomInfo(Request $request)
    {
        $roomId = decrypt($request->room_id); // Assuming you're encrypting the ID
        $room = HotelRoom::with(['pictures', 'amenities'])->findOrFail($roomId);

        $roomAmenitiesHtml = '<div class="row">';
        $half = ceil($room->amenities->count() / 2);

        $roomAmenitiesHtmlLeft = '<div class="col-lg-6 col-md-6"><ul>';
        $roomAmenitiesHtmlRight = '<div class="col-lg-6 col-md-6"><ul>';

        foreach ($room->amenities as $index => $amenity) {
            if ($index < $half) {
                $roomAmenitiesHtmlLeft .= "<li>{$amenity->amenity}</li>";
            } else {
                $roomAmenitiesHtmlRight .= "<li>{$amenity->amenity}</li>";
            }
        }

        $roomAmenitiesHtmlLeft .= '</ul></div>';
        $roomAmenitiesHtmlRight .= '</ul></div>';
        $roomAmenitiesHtml .= $roomAmenitiesHtmlLeft . $roomAmenitiesHtmlRight . '</div>';

        $roomPicturesHtml = '';
        $room_pictures = [];

        foreach ($room->pictures as $index => $picture) {
            $roomPicturesHtml .= '<div class="col-lg-12 col-sm-3 col-xs-3 col-3" style="padding: 5px 18px 5px 5px;">';
            $roomPicturesHtml .= "<img class='resort__details__pic set-bg' src='" . asset($picture->picture) . "' style='height: 100px; width: 100%; padding: 5px;' onclick='set_room(\"" . asset($picture->picture) . "\", \"$index\")' id='countRoom-$index'>";
            $roomPicturesHtml .= '</div>';
            $room_pictures[] = asset($picture->picture);
        }

        // Include main room picture as a selectable thumbnail
        $roomPicturesHtml .= '<div class="col-lg-12 col-sm-3 col-xs-3 col-3" style="padding: 5px 18px 5px 5px;">';
        $roomPicturesHtml .= "<img class='resort__details__pic set-bg' src='" . asset($room->picture) . "' style='height: 100px; width: 100%; padding: 5px;' onclick='set_room(\"" . asset($room->picture) . "\", \"" . count($room_pictures) . "\")' id='countRoom-" . count($room_pictures) . "'>";
        $roomPicturesHtml .= '</div>';
        $room_pictures[] = asset($room->picture);

        return response()->json([
            'image' => asset($room->picture),
            'room_name' => $room->room_name,
            'room_details' => 'Room capacity: ' . $room->room_capacity . ', Rate: ' . $room->room_rate . ', Status: ' . $room->room_status,
            'roomAmenitiesHtml' => $roomAmenitiesHtml,
            'roomPicturesHtml' => $roomPicturesHtml,
            'room_pictures' => $room_pictures,
        ]);
    }
}
