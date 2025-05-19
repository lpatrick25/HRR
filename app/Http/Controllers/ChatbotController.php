<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HotelRoom;
use App\Models\ResortCottage;
use App\Models\UserQuery;

class ChatbotController extends Controller
{
    public function getResponse(Request $request)
    {
        $message = strtolower($request->input('message'));
        $step = $request->input('step', 'default');

        switch ($step) {
            case 'default':
                $responseText = 'Hello! How can I assist you today?';
                $options = [
                    ['text' => 'Entrance Fee', 'value' => '1'],
                    ['text' => 'Cottage Rates', 'value' => '2'],
                    ['text' => 'Room Rates', 'value' => '3'],
                ];
                $nextStep = 'select_option';
                break;

            case 'select_option':
                if ($message == '1') {
                    $responseText = 'Entrance fee information is not available in the database. Please inquire at the resort.';
                    $options = [
                        ['text' => 'Back to Main Menu', 'value' => 'back'],
                    ];
                    $nextStep = 'default';
                } elseif ($message == '2') {
                    $cottages = ResortCottage::where('cottage_status', 'Available')->get(['cottage_name', 'cottage_rate']);
                    $responseText = "Available Cottages:\n";
                    foreach ($cottages as $cottage) {
                        $responseText .= $cottage->cottage_name . ' - ₱' . number_format($cottage->cottage_rate, 2) . "\n";
                    }
                    $options = [
                        ['text' => 'Proceed to Booking', 'value' => 'bookResort'],
                        ['text' => 'Back to Main Menu', 'value' => 'back'],
                    ];
                    $nextStep = 'default';
                } elseif ($message == '3') {
                    $rooms = HotelRoom::where('room_status', 'Available')->get(['room_name', 'room_rate']);
                    $responseText = "Available Rooms:\n";
                    foreach ($rooms as $room) {
                        $responseText .= $room->room_name . ' - ₱' . number_format($room->room_rate, 2) . "\n";
                    }
                    $options = [
                        ['text' => 'Proceed to Booking', 'value' => 'bookHotel'],
                        ['text' => 'Back to Main Menu', 'value' => 'back'],
                    ];
                    $nextStep = 'default';
                } elseif ($message == 'back') {
                    $responseText = 'Hello! How can I assist you today?';
                    $options = [
                        ['text' => 'Entrance Fee', 'value' => '1'],
                        ['text' => 'Cottage Rates', 'value' => '2'],
                        ['text' => 'Room Rates', 'value' => '3'],
                    ];
                    $nextStep = 'select_option';
                } else {
                    $responseText = 'Invalid option.';
                    $options = [
                        ['text' => 'Entrance Fee', 'value' => '1'],
                        ['text' => 'Cottage Rates', 'value' => '2'],
                        ['text' => 'Room Rates', 'value' => '3'],
                    ];
                    $nextStep = 'select_option';
                }
                break;

            default:
                $responseText = 'Hello! How can I assist you today?';
                $options = [
                    ['text' => 'Entrance Fee', 'value' => '1'],
                    ['text' => 'Cottage Rates', 'value' => '2'],
                    ['text' => 'Room Rates', 'value' => '3'],
                ];
                $nextStep = 'select_option';
                break;
        }

        $query = UserQuery::create([
            'query' => $message,
            'response' => $responseText,
            'usefulness' => null,
        ]);

        return response()->json([
            'response' => $responseText,
            'options' => $options,
            'nextStep' => $nextStep,
            'queryId' => $query->id,
        ]);
    }
}
