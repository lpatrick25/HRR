<?php

namespace App\Http\Controllers;

use App\Models\FoodPicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class FoodPictureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = FoodPicture::with('Food')->get()->map(function ($picture, $index) {
            $actionDelete = '<button onclick="trash_picture(' . "'" . $picture->id . "'" . ')" type="button" title="Delete" class="btn btn-custon-rounded-three btn-danger btn-block"><i class="fa fa-trash"></i></button>';
            $action = $actionDelete;

            return [
                'count' => $index + 1,
                'food_name' => $picture->Food->food_name ?? 'N/A',
                'picture' => $picture->picture,
                'action' => $action,
            ];
        })->toArray();

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $validated = $request->validate([
                'food_id' => 'required|exists:foods,id',
                'picture' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB Max
            ], [
                'food_id.required' => 'Food is required.',
                'food_id.exists' => 'Selected food does not exist.',
                'picture.required' => 'Picture is required.',
                'picture.image' => 'Uploaded file must be an image.',
                'picture.mimes' => 'Picture must be a file of type: jpeg, png, jpg, gif.',
                'picture.max' => 'Picture must not exceed 10MB.',
            ]);

            // Handle image upload
            $foodId = $request->food_id;
            $imageFile = $request->file('picture');

            $uploadPath = 'uploads/food_pictures/' . $foodId; // public/uploads/hotel_pictures/{hotel_room_id}
            $fileName = time() . '_' . $imageFile->getClientOriginalName();
            $imageFile->move(public_path($uploadPath), $fileName);

            // Save to DB
            $picture = FoodPicture::create([
                'food_id' => $foodId,
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
            $pictures = FoodPicture::where('food_id', $id)->get();


            return view('partials.food_pictures', compact('pictures'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve picture: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to retrieve picture. Please try again later.',
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FoodPicture $foodPicture)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'food_id' => 'nullable|exists:foods,id',
                'picture' => 'required|string',
            ], [
                'food_id.exists' => 'Selected food does not exist.',
                'picture.required' => 'Picture is required.',
                'picture.string' => 'Picture must be a string.',
            ]);

            $picture = FoodPicture::findOrFail($id);
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
            $picture = FoodPicture::findOrFail($id);

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
        $pictures = FoodPicture::all();
        return response()->json($pictures);
    }
}
