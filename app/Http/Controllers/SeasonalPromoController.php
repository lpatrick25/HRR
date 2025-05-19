<?php

namespace App\Http\Controllers;

use App\Models\SeasonalPromo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Illuminate\Validation\ValidationException;

class SeasonalPromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = SeasonalPromo::all()->map(function ($promo, $index) {
            // $actionUpdate = '<button onclick="view_promo(' . "'" . $promo->id . "'" . ')" type="button" title="Update" class="btn btn-custon-rounded-three btn-primary"><i class="fa fa-edit"></i></button>';
            $actionDelete = '<button onclick="trash_promo(' . "'" . $promo->id . "'" . ')" type="button" title="Delete" class="btn btn-custon-rounded-three btn-danger"><i class="fa fa-trash"></i></button>';
            $action = $actionDelete;

            return [
                'count' => $index + 1,
                'title' => ucwords(strtolower($promo->title)),
                'description' => ucwords(strtolower($promo->description)),
                'start_date' => date('l F j, Y', strtotime($promo->start_date)),
                'end_date' => date('l F j, Y', strtotime($promo->end_date)),
                'created_at' => date('l F j, Y', strtotime($promo->created_at)),
                'promo_code' => strtoupper($promo->promo_code),
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
            // Validation
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'promo_code' => 'required|string|max:15|unique:seasonal_promos,promo_code',
            ], [
                'end_date.after_or_equal' => 'End date must be after or equal to the start date.',
                'promo_code.unique' => 'This promo code already exists.',
            ]);

            // Create Promo
            $promo = SeasonalPromo::create($validated);

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Promo successfully stored.',
                'promo' => $promo,
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
            Log::error('Failed to store promo: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to store promo. Please try again later.',
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
            $promo = SeasonalPromo::findOrFail($id);

            DB::commit();

            return response()->json($promo, 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to retrieve promo: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to retrieve promo. Please try again later.',
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
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'promo_code' => 'required|string|max:15|unique:seasonal_promos,promo_code,' . $id,
            ], [
                'end_date.after_or_equal' => 'End date must be after or equal to the start date.',
                'promo_code.unique' => 'This promo code already exists.',
            ]);

            $promo = SeasonalPromo::findOrFail($id);
            $promo->update($validated);

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Promo successfully updated.',
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
            Log::error('Failed to update promo: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update promo. Please try again later.',
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
            $promo = SeasonalPromo::findOrFail($id);
            $promo->delete();

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Promo successfully deleted.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to delete promo: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to delete promo. Please try again later.',
            ], 500);
        }
    }
}
