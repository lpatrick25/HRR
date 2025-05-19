<?php

namespace App\Http\Controllers;

use App\Models\FoodCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Illuminate\Validation\ValidationException;

class FoodCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = FoodCategory::all()->map(function ($category, $index) {
            $actionUpdate = '<button onclick="view_category(' . "'" . $category->id . "'" . ')" type="button" title="Update" class="btn btn-custon-rounded-three btn-primary"><i class="fa fa-edit"></i></button>';
            $actionDelete = '<button onclick="trash_category(' . "'" . $category->id . "'" . ')" type="button" title="Delete" class="btn btn-custon-rounded-three btn-danger"><i class="fa fa-trash"></i></button>';
            $action = $actionUpdate . $actionDelete;

            return [
                'count' => $index + 1,
                'category_name' => ucwords(strtolower($category->category_name)),
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
                'category_name' => 'required|string|max:255|unique:food_categories,category_name',
            ], [
                'category_name.required' => 'Category name is required.',
                'category_name.string' => 'Category name must be a string.',
                'category_name.max' => 'Category name cannot exceed 255 characters.',
                'category_name.unique' => 'This category name is already taken.',
            ]);

            // Create the food category
            $category = FoodCategory::create($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Food category successfully stored.',
                'category' => $category,
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
            Log::error('Failed to store food category: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to store food category. Please try again later.',
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
            // Retrieve the food category
            $category = FoodCategory::findOrFail($id);

            // Commit the transaction if successful
            DB::commit();

            return response()->json($category, 201);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to retrieve food category: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to retrieve food category. Please try again later.',
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
                'category_name' => 'required|string|max:255|unique:food_categories,category_name,' . $id,
            ], [
                'category_name.required' => 'Category name is required.',
                'category_name.string' => 'Category name must be a string.',
                'category_name.max' => 'Category name cannot exceed 255 characters.',
                'category_name.unique' => 'This category name is already taken.',
            ]);

            // Find and update the food category
            $category = FoodCategory::findOrFail($id);
            $category->update($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Food category successfully updated.',
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
            Log::error('Failed to update food category: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update food category. Please try again later.',
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
            // Find and delete the food category
            $category = FoodCategory::findOrFail($id);
            $category->delete();

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Food category successfully deleted.',
            ], 201);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to delete food category: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to delete food category. Please try again later.',
            ], 500);
        }
    }

    public function getAll()
    {
        $categories = FoodCategory::all();
        return response()->json($categories);
    }
}
