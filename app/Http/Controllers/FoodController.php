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
            $actionUpdate = '<button onclick="view_food(' . "'" . $food->id . "'" . ')" type="button" title="Update" class="btn btn-custon-rounded-three btn-primary"><i class="fa fa-edit"></i></button>';
            $actionDelete = '<button onclick="trash_food(' . "'" . $food->id . "'" . ')" type="button" title="Delete" class="btn btn-custon-rounded-three btn-danger"><i class="fa fa-trash"></i></button>';
            $action = $actionUpdate . $actionDelete;

            return [
                'count' => $index + 1,
                'food_name' => ucwords(strtolower($food->food_name)),
                'food_category_id' => $food->foodCategory->category_name,  // Assuming foodCategory is related to a category model
                'food_status' => $food->food_status,
                'food_price' => $food->food_price,
                'food_unit' => $food->food_unit,
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
}
