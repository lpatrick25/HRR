<?php

namespace App\Http\Controllers;

use App\Models\HotelPicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class HotelPictureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = HotelPicture::with('hotelRoom')->get()->map(function ($picture, $index) {
            $actionDelete = '<button onclick="trash_picture(' . "'" . $picture->id . "'" . ')" type="button" title="Delete" class="btn btn-custon-rounded-three btn-danger btn-block"><i class="fa fa-trash"></i></button>';
            $action = $actionDelete;

            return [
                'count' => $index + 1,
                'room_name' => $picture->hotelRoom->room_name ?? 'N/A',
                'picture' => $picture->picture,
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
                'picture' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB Max
            ], [
                'hotel_room_id.required' => 'Room is required.',
                'hotel_room_id.exists' => 'Selected room does not exist.',
                'picture.required' => 'Picture is required.',
                'picture.image' => 'Uploaded file must be an image.',
                'picture.mimes' => 'Picture must be a file of type: jpeg, png, jpg, gif.',
                'picture.max' => 'Picture must not exceed 10MB.',
            ]);

            // Handle image upload
            $roomId = $request->hotel_room_id;
            $imageFile = $request->file('picture');

            $uploadPath = 'uploads/hotel_pictures/' . $roomId; // public/uploads/hotel_pictures/{hotel_room_id}
            $fileName = time() . '_' . $imageFile->getClientOriginalName();
            $imageFile->move(public_path($uploadPath), $fileName);

            // Save to DB
            $picture = HotelPicture::create([
                'hotel_room_id' => $roomId,
                'picture' => $uploadPath . '/' . $fileName,
            ]);

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Picture successfully stored.',
                'picture' => $picture,
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

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $pictures = HotelPicture::where('hotel_room_id', $id)->get();


            return view('partials.room_pictures', compact('pictures'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve picture: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to retrieve picture. Please try again later.',
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
                'picture' => 'required|string',
            ], [
                'hotel_room_id.exists' => 'Selected room does not exist.',
                'picture.required' => 'Picture is required.',
                'picture.string' => 'Picture must be a string.',
            ]);

            $picture = HotelPicture::findOrFail($id);
            $picture->update($validated);

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Picture successfully updated.',
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
            Log::error('Failed to update picture: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update picture. Please try again later.',
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
            $picture = HotelPicture::findOrFail($id);

            // Delete the physical file from public path
            $picturePath = public_path($picture->picture);
            if (file_exists($picturePath)) {
                unlink($picturePath);
            }

            // Delete the database record
            $picture->delete();

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Picture successfully deleted.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to delete picture: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to delete picture. Please try again later.',
            ], 500);
        }
    }

    /**
     * Get all pictures without pagination.
     */
    public function getAll()
    {
        $pictures = HotelPicture::all();
        return response()->json($pictures);
    }
}
