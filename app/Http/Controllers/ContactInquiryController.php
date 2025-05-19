<?php

namespace App\Http\Controllers;

use App\Models\ContactInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactInquiryController extends Controller
{
    /**
     * Display a listing of the contact inquiries.
     */
    public function index()
    {
        $response = ContactInquiry::latest()->get()->map(function ($inquiry, $index) {
            // Mark as Read button
            $actionRead = $inquiry->status === 'Unread'
                ? '<button onclick="markAsRead(' . $inquiry->id . ')" type="button" title="Mark as Read" class="btn btn-custon-rounded-three btn-success">
                <i class="fa fa-check"></i>
               </button>'
                : '';

            // Delete button
            $actionDelete = '<button onclick="deleteInquiry(' . $inquiry->id . ')" type="button" title="Delete" class="btn btn-custon-rounded-three btn-danger">
                            <i class="fa fa-trash"></i>
                         </button>';

            $actionReply = '<button onclick="replyInquiry(' . "'"  . Crypt::encrypt($inquiry->id) . "'" . ')" type="button" title="Reply" class="btn btn-custon-rounded-three btn-primary">
                            <i class="fa fa-reply"></i>
                         </button>';

            // Combine buttons
            $action = $actionReply;

            return [
                'count' => $index + 1,
                'customer_name' => ucwords(strtolower($inquiry->customer_full_name)),
                'customer_email' => $inquiry->customer_email,
                'customer_subject' => $inquiry->customer_subject,
                'customer_message' => $inquiry->customer_message,
                'status' => $inquiry->status, // 'Unread' or 'Read'
                'created_at' => $inquiry->created_at->format('d M Y'), // Example: 23 Feb 2025
                'action' => $action,
            ];
        });

        return response()->json($response);
    }

    /**
     * Store a newly created contact inquiry.
     */
    public function store(Request $request)
    {
        // Start transaction
        DB::beginTransaction();

        try {
            // Validation rules
            $validated = $request->validate([
                'customer_full_name' => 'required|string|max:50',
                'customer_email' => 'required|email|max:100',
                'customer_subject' => 'required|string|max:100',
                'customer_message' => 'required|string',
            ], [
                'customer_full_name.required' => 'Full name is required.',
                'customer_email.required' => 'Email is required.',
                'customer_email.email' => 'Invalid email format.',
                'customer_subject.required' => 'Subject is required.',
                'customer_message.required' => 'Message cannot be empty.',
            ]);

            // Create a new contact inquiry
            $contactInquiry = ContactInquiry::create($validated);

            // Commit the transaction
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Your message has been sent successfully!',
                'data' => $contactInquiry,
            ], 201);
        } catch (ValidationException $e) {
            // Rollback on validation error
            DB::rollback();

            return response()->json([
                'valid' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Rollback on general error
            DB::rollback();
            Log::error('Failed to store contact inquiry: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Something went wrong! Please try again later.',
            ], 500);
        }
    }

    /**
     * Update the status of a message (Mark as Read/Unread).
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // Validate request
            $validated = $request->validate([
                'status' => 'required|in:Unread,Read',
            ], [
                'status.required' => 'Status is required.',
                'status.in' => 'Invalid status value. Must be Unread or Read.',
            ]);

            // Find the inquiry
            $inquiry = ContactInquiry::findOrFail($id);
            $inquiry->update(['status' => $validated['status']]);

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Message status updated successfully.',
            ], 200); // 200 OK (Update successful)
        } catch (ValidationException $e) {
            DB::rollback();

            return response()->json([
                'valid' => false,
                'msg' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to update message status: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update message status. Please try again later.',
            ], 500);
        }
    }

    /**
     * Delete a message.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            // Find and delete the inquiry
            $inquiry = ContactInquiry::findOrFail($id);
            $inquiry->delete();

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Message deleted successfully.',
            ], 200); // 200 OK (Delete successful)
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to delete message: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to delete message. Please try again later.',
            ], 500);
        }
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB Max
        ], [
            'image.required' => 'Image is required.',
            'image.image' => 'Image file must be an image.',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'Image must not exceed 5MB.',
        ]);

        $imageFile = $request->file('image');

        // Define upload path inside the public folder
        $uploadPath = 'uploads/email/';
        $fullUploadPath = public_path($uploadPath);

        // Check if directory exists, if not create it
        if (!file_exists($fullUploadPath)) {
            mkdir($fullUploadPath, 0777, true);
        }

        $fileName = time() . '_' . $imageFile->getClientOriginalName();

        // Move the file to the public directory
        $imageFile->move($fullUploadPath, $fileName);

        // Generate the correct public URL
        return response()->json(['url' => asset($uploadPath . $fileName)]);
    }

    public function replyInquiry(Request $request)
    {
        try {
            $validated = $request->validate([
                'inquiry_id' => 'required|exists:contact_inquiries,id',
                'content' => 'required',
            ], [
                'inquiry_id.required' => 'Inquiry ID is required.',
                'content.required' => 'Message content is required.',
            ]);

            $inquiry = ContactInquiry::findOrFail($validated['inquiry_id']);
            $mailContent = $validated['content'];

            // Get the APP_URL from .env
            $appUrl = env('APP_URL', config('app.url'));

            // Ensure image URLs are absolute
            $mailContent = preg_replace('/src="\/?uploads\/email\//', 'src="' . $appUrl . '/uploads/email/', $mailContent);

            $mail = new PHPMailer(true);
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];

            try {
                // SMTP settings
                $mail->isSMTP();
                $mail->Host = env('MAIL_HOST');
                $mail->SMTPAuth = true;
                $mail->Username = env('MAIL_USERNAME');
                $mail->Password = env('MAIL_PASSWORD');
                $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'tls');
                $mail->Port = env('MAIL_PORT');

                // Sender & recipient
                $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                $mail->addAddress($inquiry->customer_email, $inquiry->customer_full_name);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Reply: ' . $inquiry->customer_subject;
                $mail->Body = $mailContent;

                // Send email
                if ($mail->send()) {
                    return response()->json([
                        'valid' => true,
                        'msg' => 'Message sent successfully.',
                    ], 200);
                } else {
                    return response()->json([
                        'valid' => false,
                        'msg' => 'Failed to send message.',
                    ], 500);
                }
            } catch (Exception $e) {
                Log::error("Email error: {$mail->ErrorInfo}");
                return response()->json([
                    'valid' => false,
                    'msg' => 'Failed to send message due to email server error.',
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error sending message: ' . $e->getMessage());
            return response()->json([
                'valid' => false,
                'msg' => 'An error occurred. Please try again later.',
            ], 500);
        }
    }
}
