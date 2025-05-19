<?php

namespace App\Http\Controllers;

use App\Models\HotelRoom;
use App\Models\ResortCottage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{

    public function getViewsByCategory(Request $request)
    {
        $type = $request->query('type', 'hotel'); // Default to hotel
        $category = $request->query('category'); // Specific room/cottage type
        $dateRange = $request->query('date_range', '30 days');

        // Determine the correct model
        $query = ($type === 'hotel') ? HotelRoom::query() : ResortCottage::query();

        if ($category) {
            $query->whereHas(($type === 'hotel') ? 'hotelType' : 'resortType', function ($q) use ($category) {
                $q->where('type_name', $category);
            });
        }

        // Count views within selected date range
        $query->withCount(['views' => function ($q) use ($dateRange) {
            $q->where('created_at', '>=', now()->subDays((int) $dateRange));
        }]);

        // Ensure correct naming for both hotel rooms and resort cottages
        $data = $query->orderByDesc('views_count')->get(['id', DB::raw("COALESCE(room_name, cottage_name) as name")]);

        return response()->json($data);
    }
}
