<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\HotelPayment;
use App\Models\HotelRoom;
use App\Models\HotelTransaction;
use App\Models\SeasonalPromo;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class HotelReservationController extends Controller
{
    public function getAvailableRooms(Request $request)
    {
        try {
            // Validation rules
            $validated = $request->validate([
                'check_in_date' => 'required|date',
                'check_out_date' => 'required|date|after:check_in_date',
            ], [
                'check_in_date.required' => 'Check-in date is required.',
                'check_in_date.date' => 'Check-in date must be a valid date.',
                'check_out_date.required' => 'Check-out date is required.',
                'check_out_date.date' => 'Check-out date must be a valid date.',
                'check_out_date.after' => 'Check-out date must be after the check-in date.',
            ]);

            $checkInDate = Carbon::parse($request->check_in_date)->setHour(14)->setMinute(0)->setSecond(0);
            $checkOutDate = Carbon::parse($request->check_out_date)->setHour(12)->setMinute(0)->setSecond(0);

            // Fetching reserved room IDs based on the new status flow
            $reservedRoomIds = HotelTransaction::whereNotIn('status', ['Cancelled', 'Checked-out', 'No-show'])
                ->whereNotNull('hotel_room_id') // Make sure room is assigned
                ->where(function ($query) use ($checkInDate, $checkOutDate) {
                    $query->where(function ($q) use ($checkInDate, $checkOutDate) {
                        $q->where('check_in_date', '<', $checkOutDate)
                            ->where('check_out_date', '>', $checkInDate);
                    });
                })
                ->pluck('hotel_room_id');

            // Fetch available rooms
            $availableRooms = HotelRoom::with('hotelType')
                ->where('room_status', 'Available')
                ->whereNotIn('id', $reservedRoomIds)
                ->get();

            return response()->json($availableRooms);
        } catch (ValidationException $e) {
            return response()->json([
                'valid' => false,
                'msg' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to fetch available rooms: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to fetch available rooms. Please try again later.',
            ], 500);
        }
    }

    public function getRoomAvailability(Request $request)
    {
        try {
            // Validation rules
            $validated = $request->validate([
                'check_in_date' => 'required|date',
                'check_out_date' => 'required|date|after:check_in_date',
                'room_id' => 'required|exists:hotel_rooms,id'
            ], [
                'check_in_date.required' => 'Check-in date is required.',
                'check_in_date.date' => 'Check-in date must be a valid date.',
                'check_out_date.required' => 'Check-out date is required.',
                'check_out_date.date' => 'Check-out date must be a valid date.',
                'check_out_date.after' => 'Check-out date must be after the check-in date.',
                'room_id.required' => 'Room is required.',
                'room_id.exists' => 'Room not found.',
            ]);

            $checkInDate = Carbon::parse($request->check_in_date)->setHour(14)->setMinute(0)->setSecond(0);
            $checkOutDate = Carbon::parse($request->check_out_date)->setHour(12)->setMinute(0)->setSecond(0);

            // Fetching reserved room IDs based on the new status flow
            $isRoomReserved = HotelTransaction::whereNotIn('status', ['Cancelled', 'Checked-out', 'No-show'])
                ->where('hotel_room_id', $validated['room_id']) // Make sure room is assigned
                ->where(function ($query) use ($checkInDate, $checkOutDate) {
                    $query->where(function ($q) use ($checkInDate, $checkOutDate) {
                        $q->where('check_in_date', '<', $checkOutDate)
                            ->where('check_out_date', '>', $checkInDate);
                    });
                })->first();

            if ($isRoomReserved) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'Room not available from selected date.'
                ]);
            } else {
                return response()->json([
                    'valid' => true,
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'valid' => false,
                'msg' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to fetch available rooms: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to fetch available rooms. Please try again later.',
            ], 500);
        }
    }

    public function checkRoomAvailability(Request $request)
    {
        $validated = $request->validate([
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);

        $checkInDate = Carbon::parse($request->check_in_date)->setHour(14);
        $checkOutDate = Carbon::parse($request->check_out_date)->setHour(12);

        $reservedRooms = HotelTransaction::whereNotIn('status', ['Cancelled', 'Checked-out', 'No-show'])
            ->where(function ($query) use ($checkInDate, $checkOutDate) {
                $query->where(function ($q) use ($checkInDate, $checkOutDate) {
                    $q->where('check_in_date', '<', $checkOutDate)
                        ->where('check_out_date', '>', $checkInDate);
                });
            })
            ->get()
            ->groupBy('hotel_room_id');

        $reservedDates = [];
        foreach ($reservedRooms as $roomId => $transactions) {
            foreach ($transactions as $transaction) {
                $period = Carbon::parse($transaction->check_in_date)->daysUntil(Carbon::parse($transaction->check_out_date));
                foreach ($period as $date) {
                    $reservedDates[$roomId][$date->format('Y-m-d')] = true;
                }
            }
        }

        $availableRooms = HotelRoom::with(['hotelType', 'amenities', 'pictures'])
            ->where('room_status', 'Available')
            ->whereNotIn('id', array_keys($reservedDates))
            ->get();

        return view('components.rooms-list', compact('availableRooms', 'reservedDates', 'checkInDate', 'checkOutDate'))->render();
    }

    public function getRoomReservations($encryptedId)
    {
        $roomId = Crypt::decrypt($encryptedId);
        $reservations = HotelTransaction::where('hotel_room_id', $roomId)
            ->whereIn('status', ['Confirmed', 'Checked-in'])
            ->get();

        $events = [];

        foreach ($reservations as $reservation) {
            $checkIn = $reservation->check_in_date;
            $checkOut = date('Y-m-d', strtotime($reservation->check_out_date));
            // $checkOut = date('Y-m-d', strtotime($reservation->check_out_date . ' +1 day'));

            $events[] = [
                'title' => 'Reserved',
                'start' => $checkIn,
                'end' => $checkOut, // FullCalendar end is exclusive
                'display' => 'background', // This is the key!
                'backgroundColor' => '#dc3545',
                'borderColor' => '#dc3545',
                'textColor' => '#ffffff',
            ];
        }

        return response()->json($events);
    }

    public function getHotelTransactionCount()
    {
        $count = HotelTransaction::count();
        return response()->json(['count' => $count]);
    }

    public function reservationHotelStatus(Request $request)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Validate input
            $validated = $request->validate([
                'transaction_id' => 'required|exists:hotel_transactions,id',
                'status' => 'required|in:Pending,Confirmed,Checked-in,Checked-out,Cancelled,No-show,Walk-in',
            ]);

            // Find and update the hotel transaction
            $transaction = HotelTransaction::findOrFail($validated['transaction_id']);
            $previousStatus = $transaction->status;

            // Update status
            $transaction->update(['status' => $validated['status']]);

            // Send notification and create payment if status is 'Confirmed'
            if ($validated['status'] === 'Confirmed' && $previousStatus !== 'Confirmed') {
                $this->hotelNotification($transaction);

                HotelPayment::create([
                    'hotel_transaction_id' => $transaction->id,
                    'payment_method' => 'Cash',
                    'total_amount' => $transaction->total_amount,
                    'amount_paid' => $transaction->total_amount,
                    'checkout_session_id' => null, // Optional field
                ]);
            }

            // Commit the transaction if successful
            DB::commit();

            // Define message based on status change
            $statusMessages = [
                'Pending' => 'Your hotel reservation is pending confirmation.',
                'Confirmed' => 'Your hotel reservation has been confirmed!',
                'Checked-in' => 'You have successfully checked in.',
                'Checked-out' => 'You have successfully checked out.',
                'Cancelled' => 'Your hotel reservation has been cancelled.',
                'No-show' => 'Your reservation has been marked as a no-show.',
                'Walk-in' => 'Walk-in reservation has been recorded.',
            ];

            return response()->json([
                'valid' => true,
                'msg' => $statusMessages[$validated['status']] ?? 'Hotel transaction updated successfully.',
            ], 200);
        } catch (\Exception $e) {
            // Rollback in case of any error
            DB::rollback();
            Log::error('Failed to update hotel transaction: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update hotel transaction. Please try again later.',
            ], 500);
        }
    }

    private function hotelNotification(HotelTransaction $transaction)
    {
        // Retrieve hotel room details along with type, pictures, and amenities
        $hotelRoom = HotelRoom::with('hotelType', 'pictures', 'amenities')->findOrFail($transaction->hotel_room_id);

        // Fetch active seasonal promo (if available)
        $seasonalPromo = SeasonalPromo::whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        // Determine theme based on season
        $theme = match (true) {
            in_array(now()->format('m'), ['12', '01']) => 'christmas',
            in_array(now()->format('m'), ['06', '07', '08']) => 'summer',
            default => 'default',
        };

        $contact = Contact::first();

        // Generate email content using Laravel Blade
        $mailContent = View::make('notifications.hotel', compact('transaction', 'hotelRoom', 'seasonalPromo', 'theme', 'contact'))->render();

        // Initialize PHPMailer
        $mail = new PHPMailer(true);

        // SMTP configuration
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];

        try {
            // SMTP Settings
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION');
            $mail->Port = env('MAIL_PORT');

            // Set sender and recipient
            $mail->setFrom('no-reply@tiaindayhavenfarmresort.com', 'Tia Inday Haven Resort');
            $mail->addAddress($transaction->customer_email, $transaction->customer_name);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Your Hotel Reservation Confirmation - Tia Inday Haven Resort';
            $mail->Body = $mailContent;

            // Send email
            $mail->send();
            Log::info('Booking confirmation email sent successfully!');
        } catch (Exception $e) {
            Log::error("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }
}
