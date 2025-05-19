<?php

namespace App\Http\Controllers;

use App\Models\ResortPicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ResortPictureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = ResortPicture::with('resortCottage')->get()->map(function ($picture, $index) {
            $actionDelete = '<button onclick="trash_picture(' . "'" . $picture->id . "'" . ')" type="button" title="Delete" class="btn btn-custon-rounded-three btn-danger btn-block"><i class="fa fa-trash"></i></button>';
            $action = $actionDelete;

            return [
                'count' => $index + 1,
                'cottage_name' => $picture->resortCottage->cottage_name ?? 'N/A',
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
                'resort_cottage_id' => 'required|exists:resort_cottages,id',
                'picture' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB Max
            ], [
                'resort_cottage_id.required' => 'Cottage is required.',
                'resort_cottage_id.exists' => 'Selected cottage does not exist.',
                'picture.required' => 'Picture is required.',
                'picture.image' => 'Uploaded file must be an image.',
                'picture.mimes' => 'Picture must be a file of type: jpeg, png, jpg, gif.',
                'picture.max' => 'Picture must not exceed 5MB.',
            ]);

            // Handle image upload
            $cottageId = $request->resort_cottage_id;
            $imageFile = $request->file('picture');

            $uploadPath = 'uploads/resort_pictures/' . $cottageId; // public/uploads/resort_pictures/{resort_cottage_id}
            $fileName = time() . '_' . $imageFile->getClientOriginalName();
            $imageFile->move(public_path($uploadPath), $fileName);

            // Save to DB
            $picture = ResortPicture::create([
                'resort_cottage_id' => $cottageId,
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
            $pictures = ResortPicture::where('resort_cottage_id', $id)->get();


            return view('partials.cottage_pictures', compact('pictures'));
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
                'resort_cottage_id' => 'nullable|exists:resort_cottages,id',
                'picture' => 'required|string',
            ], [
                'resort_cottage_id.exists' => 'Selected cottage does not exist.',
                'picture.required' => 'Picture is required.',
                'picture.string' => 'Picture must be a string.',
            ]);

            $picture = ResortPicture::findOrFail($id);
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
            $picture = ResortPicture::findOrFail($id);

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
        $pictures = ResortPicture::all();
        return response()->json($pictures);
    }
}
