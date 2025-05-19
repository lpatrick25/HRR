<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ResortCottage;
use App\Models\ResortPayment;
use App\Models\ResortTransaction;
use App\Models\SeasonalPromo;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\View;

class ResortReservationController extends Controller
{
    public function getAvailableCottages(Request $request)
    {
        try {
            // Validation rules
            $validated = $request->validate([
                'booking_date' => 'required|date',
            ], [
                'booking_date.required' => 'Booking date is required.',
                'booking_date.date' => 'Booking date must be a valid date.',
            ]);

            $bookingDate = Carbon::parse($request->booking_date)->startOfDay();

            // Fetching reserved cottage IDs based on status
            $reservedCottageIds = ResortTransaction::whereNotIn('status', ['Cancelled', 'Completed', 'No-show'])
                ->whereNotNull('resort_cottage_id')
                ->whereDate('booking_date', $bookingDate)
                ->pluck('resort_cottage_id');

            // Fetch available cottages
            $availableCottages = ResortCottage::with('resortType')
                ->where('cottage_status', 'Available')
                ->whereNotIn('id', $reservedCottageIds)
                ->get();

            return response()->json($availableCottages);
        } catch (ValidationException $e) {
            return response()->json([
                'valid' => false,
                'msg' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to fetch available cottages: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to fetch available cottages. Please try again later.',
            ], 500);
        }
    }

    public function getCottageAvailability(Request $request)
    {
        try {
            // Validation rules
            $validated = $request->validate([
                'booking_date' => 'required|date',
                'cottage_id' => 'required|exists:resort_cottages,id'
            ], [
                'booking_date.required' => 'Booking date is required.',
                'booking_date.date' => 'Booking date must be a valid date.',
                'cottage_id.required' => 'Cottage is required.',
                'cottage_id.exists' => 'Cottage not found.',
            ]);

            $bookingDate = Carbon::parse($request->booking_date)->startOfDay();

            // Fetching reserved cottage IDs based on status
            $isCottageReserved = ResortTransaction::whereNotIn('status', ['Cancelled', 'Completed', 'No-show'])
                ->where('resort_cottage_id', $validated['cottage_id'])
                ->whereDate('booking_date', $bookingDate)
                ->first();

            if ($isCottageReserved) {
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
            Log::error('Failed to fetch available cottages: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to fetch available cottages. Please try again later.',
            ], 500);
        }
    }

    public function checkCottageAvailability(Request $request)
    {
        $validated = $request->validate([
            'booking_date' => 'required|date',
        ]);

        $bookingDate = Carbon::parse($request->booking_date);

        // Get cottages that are already reserved on this date
        $reservedCottages = ResortTransaction::whereNotIn('status', ['Cancelled', 'No-show'])
            ->whereDate('booking_date', $bookingDate)
            ->pluck('resort_cottage_id')
            ->toArray();

        // Fetch available cottages
        $availableCottages = ResortCottage::with(['resortType', 'pictures'])
            ->where('cottage_status', 'Available')
            ->whereNotIn('id', $reservedCottages)
            ->get();

        return view('components.cottages-list', compact('availableCottages', 'bookingDate'))->render();
    }

    public function getCottageReservations($encryptedId)
    {
        try {
            $cottageId = Crypt::decrypt($encryptedId);

            $reservations = ResortTransaction::where('resort_cottage_id', $cottageId)
                ->whereIn('status', ['Confirmed', 'Pending'])
                ->get();

            $events = [];

            foreach ($reservations as $reservation) {
                $events[] = [
                    'title' => 'Reserved',
                    'start' => $reservation->booking_date,
                    'end' => $reservation->booking_date, // Single-day bookings
                    'display' => 'background',
                    'backgroundColor' => '#dc3545',
                    'borderColor' => '#dc3545',
                    'textColor' => '#ffffff',
                ];
            }

            return response()->json($events);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return response()->json(['error' => 'Invalid Cottage ID'], 400);
        }
    }

    public function getResortTransactionCount()
    {
        $count = ResortTransaction::count();
        return response()->json(['count' => $count]);
    }

    public function reservationResortStatus(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validate input
            $validated = $request->validate([
                'transaction_id' => 'required|exists:resort_transactions,id',
                'status' => 'required|in:Pending,Confirmed,Completed,Cancelled,No-show',
            ]);

            // Retrieve transaction and store previous status
            $transaction = ResortTransaction::findOrFail($request->transaction_id);
            $previousStatus = $transaction->status;

            // Update transaction status
            $transaction->update(['status' => $validated['status']]);

            // Send notification only if status is changed to 'Confirmed'
            if ($validated['status'] === 'Confirmed') {
                $this->resortNotification($transaction);

                $payment = ResortPayment::create([
                    'resort_transaction_id' => $transaction->id,
                    'payment_method' => 'Cash',
                    'total_amount' => $transaction->total_amount,
                    'amount_paid' => $transaction->total_amount,
                    'checkout_session_id' => null, // optional field, if applicable
                ]);
            }

            // Define response messages based on status transitions
            $msg = match (true) {
                $previousStatus === 'Pending' && $validated['status'] === 'Confirmed' => 'Resort transaction has been confirmed.',
                $previousStatus === 'Confirmed' && $validated['status'] === 'Completed' => 'Resort transaction marked as completed.',
                $previousStatus === 'Confirmed' && $validated['status'] === 'Cancelled' => 'Resort transaction has been cancelled.',
                default => 'Resort transaction status successfully updated.',
            };

            DB::commit();

            return response()->json(['valid' => true, 'msg' => $msg], 200);
        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json(['valid' => false, 'msg' => 'Validation failed.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to update resort transaction: ' . $e->getMessage());

            return response()->json(['valid' => false, 'msg' => 'Failed to update resort transaction. Please try again later.'], 500);
        }
    }

    /**
     * Handles sending resort notifications, including seasonal promos.
     */
    private function resortNotification(ResortTransaction $transaction)
    {
        // Retrieve the associated cottage with its type and pictures
        $resortCottage = ResortCottage::with('resortType', 'pictures')->findOrFail($transaction->resort_cottage_id);

        // Fetch active seasonal promo (if available)
        $seasonalPromo = SeasonalPromo::whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        // Determine the theme based on the season (Optional)
        $theme = match (true) {
            in_array(now()->format('m'), ['12', '01']) => 'christmas',
            in_array(now()->format('m'), ['06', '07', '08']) => 'summer',
            default => 'default',
        };

        $contact = Contact::first();

        // Generate the email content using Laravel's view
        $mailContent = View::make('notifications.resort', compact('transaction', 'resortCottage', 'seasonalPromo', 'theme', 'contact'))->render();

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
            $mail->Subject = 'Your Booking Confirmation - Tia Inday Haven Resort';
            $mail->Body = $mailContent;

            // Send email
            $mail->send();
            Log::info('Booking confirmation email sent successfully!');

            return ['msg' => 'Booking confirmation email sent successfully!'];
        } catch (Exception $e) {
            Log::error("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return ['msg' => 'Failed to send booking confirmation email.'];
        }
    }
}
