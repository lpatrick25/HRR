<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Illuminate\Validation\ValidationException;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Food::all()->map(function ($food, $index) {
            $actionInfo = '';
            // $user_role = auth()->user()->user_role;
            $user_role = 'Admin';
            if ($user_role === 'Admin') {
                $actionInfo = '<a href="/owner/' . $food->id . '/foodInfo" type="button" title="Info" class="btn btn-custon-rounded-three btn-default"><i class="fa fa-file-image-o"></i></a>';
            } else {
                $actionInfo = '<a href="/food/' . $food->id . '/foodInfo" type="button" title="Info" class="btn btn-custon-rounded-three btn-default"><i class="fa fa-file-image-o"></i></a>';
            }
            $actionUpdate = '<button onclick="view_food(' . "'" . $food->id . "'" . ')" type="button" title="Update" class="btn btn-custon-rounded-three btn-primary"><i class="fa fa-edit"></i></button>';
            $actionDelete = '<button onclick="trash_food(' . "'" . $food->id . "'" . ')" type="button" title="Delete" class="btn btn-custon-rounded-three btn-danger"><i class="fa fa-trash"></i></button>';
            $action = $actionUpdate . $actionDelete . $actionInfo;

            return [
                'count' => $index + 1,
                'food_name' => ucwords(strtolower($food->food_name)),
                'food_category_id' => $food->foodCategory->category_name,  // Assuming foodCategory is related to a category model
                'food_status' => $food->food_status,
                'food_price' => $food->food_price,
                'food_unit' => $food->food_unit,
                'picture' => '<img src="' . asset($food->picture) . '" alt="Food Picture" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">',
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
                'food_name' => 'required|string|max:255|unique:foods,food_name',
                'food_category_id' => 'required|exists:food_categories,id',
                'food_status' => 'required|in:Available,Not Available',
                'food_price' => 'required|numeric|min:0',
                'food_unit' => 'required|in:Piece,Slice,Serving,Platter,Plate,Set,Combo,Milliliter,Liter,Cup,Glass,Bottle,Can',
            ], [
                'food_name.required' => 'The food name is required.',
                'food_name.string' => 'The food name must be a string.',
                'food_name.max' => 'The food name must not exceed 255 characters.',
                'food_name.unique' => 'The food name has already been taken.',

                'food_category_id.required' => 'The food category is required.',
                'food_category_id.exists' => 'The selected food category is invalid.',

                'food_status.required' => 'The food status is required.',
                'food_status.in' => 'The selected food status is invalid. Valid options: Available, Not Available.',

                'food_price.required' => 'The food price is required.',
                'food_price.numeric' => 'The food price must be a numeric value.',
                'food_price.min' => 'The food price must be at least 0.',

                'food_unit.required' => 'The food unit is required.',
                'food_unit.in' => 'The selected food unit is invalid. Choose from Piece, Slice, Serving, Platter, Plate, Set, Combo, Milliliter, Liter, Cup, Glass, Bottle, Can.',
            ]);

            // Create the food item
            $food = Food::create($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Food item successfully stored.',
                'food' => $food,
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
            Log::error('Failed to store food item: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to store food item. Please try again later.',
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
            // Retrieve the food item
            $food = Food::findOrFail($id);

            // Commit the transaction if successful
            DB::commit();

            return response()->json($food, 201);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to retrieve food item: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to retrieve food item. Please try again later.',
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
                'food_name' => 'required|string|max:255|unique:foods,food_name,' . $id,
                'food_category_id' => 'required|exists:food_categories,id',
                'food_status' => 'required|in:Available,Not Available',
                'food_price' => 'required|numeric|min:0',
                'food_unit' => 'required|in:Piece,Slice,Serving,Platter,Plate,Set,Combo,Milliliter,Liter,Cup,Glass,Bottle,Can',
            ], [
                'food_name.required' => 'The food name is required.',
                'food_name.string' => 'The food name must be a string.',
                'food_name.max' => 'The food name must not exceed 255 characters.',
                'food_name.unique' => 'The food name has already been taken.',

                'food_category_id.required' => 'The food category is required.',
                'food_category_id.exists' => 'The selected food category is invalid.',

                'food_status.required' => 'The food status is required.',
                'food_status.in' => 'The selected food status is invalid. Valid options: Available, Not Available.',

                'food_price.required' => 'The food price is required.',
                'food_price.numeric' => 'The food price must be a numeric value.',
                'food_price.min' => 'The food price must be at least 0.',

                'food_unit.required' => 'The food unit is required.',
                'food_unit.in' => 'The selected food unit is invalid. Choose from Piece, Slice, Serving, Platter, Plate, Set, Combo, Milliliter, Liter, Cup, Glass, Bottle, Can.',
            ]);

            // Find and update the food item
            $food = Food::findOrFail($id);
            $food->update($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Food item successfully updated.',
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
            Log::error('Failed to update food item: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update food item. Please try again later.',
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
            // Find and delete the food item
            $food = Food::findOrFail($id);
            $food->delete();

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Food item successfully deleted.',
            ], 201);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to delete food item: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to delete food item. Please try again later.',
            ], 500);
        }
    }
    public function foodPicture(Request $request)
    {

        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'food_id' => 'required|exists:foods,id',
                'picture' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:20240', // 20MB Max
            ], [
                'food_id.required' => 'Food is required.',
                'food_id.exists' => 'Selected food does not exist.',
                'picture.required' => 'Picture is required.',
                'picture.image' => 'Uploaded file must be an image.',
                'picture.mimes' => 'Picture must be a file of type: jpeg, png, jpg, gif.',
                'picture.max' => 'Picture must not exceed 20MB.',
            ]);

            // Handle image upload
            $foodId = $request->food_id;
            $imageFile = $request->file('picture');

            $uploadPath = 'uploads/food_pictures/' . $foodId; // public/uploads/food_pictures/{food_id}
            $fileName = time() . '_' . $imageFile->getClientOriginalName();
            $imageFile->move(public_path($uploadPath), $fileName);

            $food = Food::findOrFail($foodId);

            // Save to DB
            $food->update([
                'picture' => $uploadPath . '/' . $fileName,
            ]);

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Picture successfully stored.',
                'food' => $food,
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

    public function getFoodInfo(Request $request)
    {
        $foodId = decrypt($request->food_id); // Assuming you're encrypting the ID
        $food = Food::with(['foodCategory', 'pictures'])->findOrFail($foodId);

        $foodHtml = '<ul>';
        $foodHtml .= '<li><span>Cottage Type: </span>' . $food->food_category_id->category_name . '</li>';
        $foodHtml .= '<li><span>Cottage Rate: </span>' . $food->food_price . '</li>';
        $foodHtml .= '<li><span>Cottage Capacity: </span>' . $food->food_unit . '</li>';
        $foodHtml .= '</ul>';

        $foodPicturesHtml = '';
        $food_pictures = [];
        $count = 0;

        foreach ($food->pictures as $picture) {
            $imageUrl = asset($picture->picture);
            $food_pictures[] = $imageUrl;
            $foodPicturesHtml .= '
            <div class="col-lg-12 col-sm-3 col-xs-3 col-3" style="padding: 5px;">
                <img class=food__details__pic set-bg" src="' . $imageUrl . '" style="height: 100px; width: 100%; cursor: pointer;" onclick="set_food(\'' . $imageUrl . '\', ' . $count . ')" id="countFood-' . $count . '">
            </div>';
            $count++;
        }

        // Add main image as last fallback
        $mainImageUrl = asset($food->picture);
        $food_pictures[] = $mainImageUrl;
        $foodPicturesHtml .= '
        <div class="col-lg-12 col-sm-3 col-xs-3 col-3" style="padding: 5px;">
            <img class="food__details__pic set-bg" src="' . $mainImageUrl . '" style="height: 100px; width: 100%; cursor: pointer;" onclick="set_food(\'' . $mainImageUrl . '\', ' . $count . ')" id="countFood-' . $count . '">
        </div>';

        return response()->json([
            'image' => $mainImageUrl,
            'food_name' => $food->food_name,
            'food_details' => 'Rate: ₱' . $food->food_price . ' | Unit: ' . $food->food_unit,
            'foodHtml' => $foodHtml,
            'foodPicturesHtml' => $foodHtml,
            'food_pictures' => $food_pictures,
        ]);
    }
}
