<?php

namespace App\Http\Controllers;

use App\Models\ResortCottage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Illuminate\Validation\ValidationException;

class ResortCottageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = ResortCottage::with(['resortType', 'resortTransactions'])->get()->map(function ($cottage, $index) {

            $actionInfo = '';
            // $user_role = auth()->user()->user_role;
            $user_role = 'Admin';
            if ($user_role === 'Admin') {
                $actionInfo = '<a href="/owner/' . $cottage->id . '/cottageInfo" type="button" title="Info" class="btn btn-custon-rounded-three btn-default"><i class="fa fa-file-image-o"></i></a>';
            } else {
                $actionInfo = '<a href="/cottage/' . $cottage->id . '/cottageInfo" type="button" title="Info" class="btn btn-custon-rounded-three btn-default"><i class="fa fa-file-image-o"></i></a>';
            }

            $actionUpdate = '<button onclick="view_cottage(' . "'" . $cottage->id . "'" . ')" type="button" title="Update" class="btn btn-custon-rounded-three btn-primary"><i class="fa fa-edit"></i></button>';
            $actionDelete = '<button onclick="trash_cottage(' . "'" . $cottage->id . "'" . ')" type="button" title="Delete" class="btn btn-custon-rounded-three btn-danger"><i class="fa fa-trash"></i></button>';
            $action = $actionUpdate . $actionDelete . $actionInfo;

            return [
                'count' => $index + 1,
                'cottage_name' => $cottage->cottage_name,
                'resort_type_id' => $cottage->resortType->type_name,
                'cottage_status' => $cottage->cottage_status,
                'cottage_rate' => $cottage->cottage_rate . ' PHP',
                'cottage_capacity' => $cottage->cottage_capacity . ' Person',
                'transactions_count' => $cottage->resortTransactions->count(),
                'picture' => '<img src="' . asset($cottage->picture) . '" alt="Room Picture" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">',
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
                'cottage_name' => 'required|string|max:100|unique:resort_cottages,cottage_name',
                'resort_type_id' => 'required|exists:resort_types,id',
                'cottage_status' => 'required|in:Available,Maintenance',
                'cottage_rate' => 'required|numeric|min:0',
                'cottage_capacity' => 'required|integer|min:1',
            ], [
                'cottage_name.required' => 'The cottage name is required.',
                'cottage_name.unique' => 'The cottage name has already been taken.',
                'cottage_name.max' => 'The cottage name must not exceed 100 characters.',

                'resort_type_id.required' => 'The cottage type is required.',
                'resort_type_id.exists' => 'The selected cottage type is invalid.',

                'cottage_status.required' => 'The cottage status is required.',
                'cottage_status.in' => 'The cottage status is invalid. Valid options: Available, Maintenance.',

                'cottage_rate.required' => 'The cottage rate is required.',
                'cottage_rate.numeric' => 'The cottage rate must be a numeric value.',
                'cottage_rate.min' => 'The cottage rate must be at least 0.',

                'cottage_capacity.required' => 'The cottage capacity is required.',
                'cottage_capacity.integer' => 'The cottage capacity must be an integer.',
                'cottage_capacity.min' => 'The cottage capacity must be at least 1.',
            ]);

            // Create the resort cottage
            $cottage = ResortCottage::create($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Resort cottage successfully created.',
                'cottage' => $cottage,
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
            Log::error('Failed to create resort cottage: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to create resort cottage. Please try again later.',
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
            // Retrieve the resort cottage along with its related data
            $cottage = ResortCottage::with(['resortType', 'resortTransactions'])->findOrFail($id);

            // Commit the transaction if successful
            DB::commit();

            return response()->json($cottage, 200);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to retrieve resort cottage: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to retrieve resort cottage. Please try again later.',
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
                'cottage_name' => 'required|string|max:255|unique:resort_cottages,cottage_name,' . $id,
                'resort_type_id' => 'required|exists:resort_types,id',
                'cottage_status' => 'required|in:Available,Maintenance',
                'cottage_rate' => 'required|numeric|min:0',
                'cottage_capacity' => 'required|integer|min:1',
            ], [
                'cottage_name.required' => 'The cottage name is required.',
                'cottage_name.unique' => 'The cottage name has already been taken.',
                'cottage_name.max' => 'The cottage name must not exceed 100 characters.',

                'resort_type_id.required' => 'The cottage type is required.',
                'resort_type_id.exists' => 'The selected cottage type is invalid.',

                'cottage_status.required' => 'The cottage status is required.',
                'cottage_status.in' => 'The cottage status is invalid. Valid options: Available, Maintenance.',

                'cottage_rate.required' => 'The cottage rate is required.',
                'cottage_rate.numeric' => 'The cottage rate must be a numeric value.',
                'cottage_rate.min' => 'The cottage rate must be at least 0.',

                'cottage_capacity.required' => 'The cottage capacity is required.',
                'cottage_capacity.integer' => 'The cottage capacity must be an integer.',
                'cottage_capacity.min' => 'The cottage capacity must be at least 1.',
            ]);

            // Find and update the resort cottage
            $cottage = ResortCottage::findOrFail($id);
            $cottage->update($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Resort cottage successfully updated.',
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
            Log::error('Failed to update resort cottage: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update resort cottage. Please try again later.',
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
            // Find and delete the resort cottage
            $cottage = ResortCottage::findOrFail($id);
            $cottage->delete();

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Resort cottage successfully deleted.',
            ], 200);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to delete resort cottage: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to delete resort cottage. Please try again later.',
            ], 500);
        }
    }

    public function cottagePicture(Request $request)
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

            $cottage = ResortCottage::findOrFail($cottageId);

            // Save to DB
            $cottage->update([
                'picture' => $uploadPath . '/' . $fileName,
            ]);

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Picture successfully stored.',
                'cottage' => $cottage,
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

    public function getCottageInfo(Request $request)
    {
        $cottageId = decrypt($request->cottage_id); // Assuming you're encrypting the ID
        $cottage = ResortCottage::with(['resortType', 'pictures'])->findOrFail($cottageId);

        $cottageAmenitiesHtml = '<ul>';
        $cottageAmenitiesHtml .= '<li><span>Cottage Type: </span>' . $cottage->resortType->type_name . '</li>';
        $cottageAmenitiesHtml .= '<li><span>Cottage Rate: </span>' . $cottage->cottage_rate . '</li>';
        $cottageAmenitiesHtml .= '<li><span>Cottage Capacity: </span>' . $cottage->cottage_capacity . '</li>';
        $cottageAmenitiesHtml .= '</ul>';

        $cottagePicturesHtml = '';
        $cottage_pictures = [];
        $count = 0;

        foreach ($cottage->pictures as $picture) {
            $imageUrl = asset($picture->picture);
            $cottage_pictures[] = $imageUrl;
            $cottagePicturesHtml .= '
            <div class="col-lg-12 col-sm-3 col-xs-3 col-3" style="padding: 5px;">
                <img class="resort__details__pic set-bg" src="' . $imageUrl . '" style="height: 100px; width: 100%; cursor: pointer;" onclick="set_cottage(\'' . $imageUrl . '\', ' . $count . ')" id="countCottage-' . $count . '">
            </div>';
            $count++;
        }

        // Add main image as last fallback
        $mainImageUrl = asset($cottage->picture);
        $cottage_pictures[] = $mainImageUrl;
        $cottagePicturesHtml .= '
        <div class="col-lg-12 col-sm-3 col-xs-3 col-3" style="padding: 5px;">
            <img class="resort__details__pic set-bg" src="' . $mainImageUrl . '" style="height: 100px; width: 100%; cursor: pointer;" onclick="set_cottage(\'' . $mainImageUrl . '\', ' . $count . ')" id="countCottage-' . $count . '">
        </div>';

        return response()->json([
            'image' => $mainImageUrl,
            'cottage_name' => $cottage->cottage_name,
            'cottage_details' => 'Rate: ₱' . $cottage->cottage_rate . ' | Status: ' . $cottage->cottage_status,
            'cottageAmenitiesHtml' => $cottageAmenitiesHtml,
            'cottagePicturesHtml' => $cottagePicturesHtml,
            'cottage_pictures' => $cottage_pictures,
        ]);
    }
}
