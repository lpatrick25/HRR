<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterSubscriptionMail;
use App\Mail\VerificationEmail;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;
use Mews\Captcha\Captcha;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = User::with(['hotelTransactions', 'resortTransactions', 'foodTransactions'])->get()->map(function ($user, $index) {
            $actionUpdate = '<button onclick="view_user(' . "'" . $user->id . "'" . ')" type="button" title="Update" class="btn btn-custon-rounded-three btn-primary"><i class="fa fa-edit"></i></button>';
            $actionDelete = '<button onclick="trash_user(' . "'" . $user->id . "'" . ')" type="button" title="Delete" class="btn btn-custon-rounded-three btn-danger"><i class="fa fa-trash"></i></button>';
            $action = $actionUpdate . $actionDelete;

            return [
                'count' => $index + 1,
                'full_name' => $user->first_name . ' ' . $user->last_name,
                'phone_number' => $user->phone_number,
                'email' => $user->email,
                'role' => $user->user_role,
                'hotel_transactions' => $user->hotelTransactions->count(),
                'resort_transactions' => $user->resortTransactions->count(),
                'food_transactions' => $user->foodTransactions->count(),
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
                'first_name' => 'required|string|max:50',
                'last_name' => 'required|string|max:50',
                'phone_number' => 'required|string|max:20|unique:users,phone_number',
                'email' => 'required|email|max:50|unique:users,email',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#^-_=+]).+$/'
                ],
                'user_role' => 'required|in:Owner,Admin,Front Desk - Hotel,Front Desk - Resort,Front Desk - Food,Customer',
                'recaptcha_token' => 'required|string',
            ], [
                'first_name.required' => 'First name is required.',
                'last_name.required' => 'Last name is required.',
                'phone_number.required' => 'Phone number is required.',
                'phone_number.unique' => 'Phone number already exists.',
                'email.required' => 'Email is required.',
                'email.email' => 'Please provide a valid email address.',
                'email.unique' => 'This email address is already registered.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.confirmed' => 'Password confirmation does not match.',
                'password.regex' => 'Password must contain at least one uppercase letter, one number, and one special character (@ $ ! % * ? & # ^ - _ = +).',
                'user_role.required' => 'User role is required.',
                'user_role.in' => 'Invalid user role selected.',
                'recaptcha_token.required' => 'Captcha is required.',
                'recaptcha_token.string' => 'Captcha must be string.',
            ]);

            // Verify Google reCAPTCHA
            $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $validated['recaptcha_token'],
            ]);

            if (!$recaptchaResponse->json()['success']) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'reCAPTCHA verification failed. Please try again.',
                ], 422);
            }

            // Hash password before storing
            $validated['password'] = Hash::make($validated['password']);

            // Create the user
            $user = User::create($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'User successfully created.',
                'user' => $user,
            ], 201);
        } catch (ValidationException $e) {
            // Handle validation errors
            DB::rollback();

            Log::error('Validation failed. Please check the errors below.');
            Log::error($e->errors());

            return response()->json([
                'valid' => false,
                'msg' => 'Validation failed. Please check the errors below.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to create user: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to create user. Please try again later.',
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
            // Retrieve the user along with their related transactions
            $user = User::with(['hotelTransactions', 'resortTransactions', 'foodTransactions'])->findOrFail($id);

            // Commit the transaction if successful
            DB::commit();

            return response()->json($user, 200);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to retrieve user: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to retrieve user. Please try again later.',
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
                'first_name' => 'required|string|max:50',
                'last_name' => 'required|string|max:50',
                'phone_number' => 'required|string|max:20|unique:users,phone_number,' . $id,
                'email' => 'required|email|max:50|unique:users,email,' . $id,
                'password' => [
                    'nullable',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#^-_=+]).+$/'
                ],
                'user_role' => 'required|in:Owner,Admin,Front Desk - Hotel,Front Desk - Resort,Front Desk - Food,Customer',
                'recaptcha_token' => 'required|string',
            ], [
                'first_name.required' => 'First name is required.',
                'last_name.required' => 'Last name is required.',
                'phone_number.required' => 'Phone number is required.',
                'phone_number.unique' => 'This phone number is already registered.',
                'email.required' => 'Email is required.',
                'email.email' => 'Please provide a valid email address.',
                'email.unique' => 'This email address is already registered.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.confirmed' => 'Password confirmation does not match.',
                'password.regex' => 'Password must contain at least one uppercase letter, one number, and one special character (@ $ ! % * ? & # ^ - _ = +).',
                'user_role.required' => 'User role is required.',
                'user_role.in' => 'Invalid user role selected.',
                'recaptcha_token.required' => 'Captcha is required.',
                'recaptcha_token.string' => 'Captcha must be string.',
            ]);

            // Verify Google reCAPTCHA
            $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $validated['recaptcha_token'],
            ]);

            if (!$recaptchaResponse->json()['success']) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'reCAPTCHA verification failed. Please try again.',
                ], 422);
            }

            // If password is provided, hash it; otherwise, keep it unchanged
            if ($validated['password'] ?? false) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            // Find and update the user
            $user = User::findOrFail($id);
            $user->update($validated);

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'User successfully updated.',
            ], 200);
        } catch (ValidationException $e) {
            // Handle validation errors
            DB::rollback();

            return response()->json([
                'valid' => false,
                'msg' => 'Validation failed. Please check the errors below.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to update user: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update user. Please try again later.',
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
            // Find and delete the user
            $user = User::findOrFail($id);
            $user->delete();

            // Commit the transaction if successful
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'User successfully deleted.',
            ], 200);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to delete user: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to delete user. Please try again later.',
            ], 500);
        }
    }

    public function loginAccount(Request $request)
    {
        try {
            // Validate the login request
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:8',
                'recaptcha_token' => 'required',
            ], [
                'email.required' => 'Email is required.',
                'email.email' => 'Enter a valid email address.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 8 characters.',
                'recaptcha_token.required' => 'Captcha verification failed. Please try again.',
            ]);

            // Verify reCAPTCHA
            $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $validated['recaptcha_token'],
            ]);

            if (!$recaptchaResponse->json()['success']) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'reCAPTCHA verification failed. Please try again.',
                ], 422);
            }

            // Check user credentials
            $user = User::where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'Invalid email or password. Please try again.',
                ], 401);
            }

            if (!$user->active) {
                // Encrypt the email before adding it to the URL
                $encryptedEmail = Crypt::encryptString($user->email);

                $signedUrl = URL::temporarySignedRoute(
                    'homepage-verifyEmail',
                    now()->addMinutes(10),
                    ['email' => $encryptedEmail] // Store encrypted email
                );

                return response()->json([
                    'valid' => true,
                    'msg' => 'Your account is not verified. Please verify your email first.',
                    'redirect' => $signedUrl,
                ], 200);
            }

            // Log in the user
            Auth::login($user);

            return response()->json([
                'valid' => true,
                'msg' => 'Login successful! Redirecting...',
                'redirect' => route('homepage-homepage'), // Redirect to homepage
            ]);
        } catch (ValidationException $e) {
            // Handle validation errors
            DB::rollback();
            Log::error('Validation failed: ' . json_encode($e->errors()));

            return response()->json([
                'valid' => false,
                'msg' => 'Validation failed. Please check the errors below.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollback();
            Log::error('Failed to create user: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to create user. Please try again later.',
            ], 500);
        }
    }

    public function registerAccount(Request $request)
    {
        // Start transaction
        DB::beginTransaction();

        try {
            // Validation rules
            $validated = $request->validate([
                'first_name' => 'required|string|max:50',
                'last_name' => 'required|string|max:50',
                'phone_number' => 'required|string|max:20|unique:users,phone_number',
                'email' => 'required|email|max:50|unique:users,email',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#^-_=+]).+$/'
                ],
                'news_letter' => 'nullable|boolean', // ✅ New field
                'recaptcha_token' => 'required',
            ], [
                'first_name.required' => 'First name is required.',
                'last_name.required' => 'Last name is required.',
                'phone_number.required' => 'Phone number is required.',
                'phone_number.unique' => 'Phone number already exists.',
                'email.required' => 'Email is required.',
                'email.email' => 'Please provide a valid email address.',
                'email.unique' => 'This email address is already registered.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.confirmed' => 'Password confirmation does not match.',
                'password.regex' => 'Password must contain at least one uppercase letter, one number, and one special character (@ $ ! % * ? & # ^ - _ = +).',
                'recaptcha_token.required' => 'Captcha verification is required.',
            ]);

            // Verify reCAPTCHA
            $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $request->input('recaptcha_token'),
            ]);

            if (!$recaptchaResponse->json()['success']) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'reCAPTCHA verification failed. Please try again.',
                ], 422);
            }

            // Hash password before storing
            $validated['password'] = Hash::make($validated['password']);
            $validated['user_role'] = 'Customer';
            $validated['phone_number'] = '+63' . $validated['phone_number'];

            // ✅ Store newsletter subscription (default false)
            $validated['news_letter'] = $request->has('news_letter') ? 1 : 0;

            // Create the user
            $user = User::create($validated);

            // Commit transaction
            DB::commit();

            // Log in the user
            Auth::login($user);

            return response()->json([
                'valid' => true,
                'msg' => 'Your account has been created successfully! Welcome aboard.',
                'user' => $user,
            ], 201);
        } catch (ValidationException $e) {
            DB::rollback();
            Log::error('Validation failed: ' . json_encode($e->errors()));

            return response()->json([
                'valid' => false,
                'msg' => 'Validation failed. Please check the errors below.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to create user: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to create user. Please try again later.',
            ], 500);
        }
    }

    public function logoutAccount(Request $request)
    {
        // Logout the authenticated user
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate CSRF token to prevent session fixation attacks
        $request->session()->regenerateToken();

        return response()->json([
            'valid' => true,
            'msg' => 'Logout successful! Redirecting...',
            'redirect' => route('homepage-homepage'),
        ]);
    }

    public function resendVerification(Request $request)
    {
        try {
            // Decrypt email safely
            $email = Crypt::decryptString($request->email);
        } catch (Exception $e) {
            return response()->json([
                'valid' => false,
                'msg' => 'Invalid or tampered verification request.',
            ], 400);
        }

        // Validate email input
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'valid' => false,
                'msg' => 'Validation failed. Please check the errors below.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Find the user
            $user = User::where('email', $email)->firstOrFail();

            // Send email using Laravel Mailable
            Mail::to($user->email)->send(new VerificationEmail($user, Contact::first()));

            Log::info("Verification email sent successfully to {$user->email}.");

            return response()->json([
                'valid' => true,
                'msg' => 'Verification email sent successfully!',
            ]);
        } catch (Exception $e) {
            Log::error("Email could not be sent: " . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to send verification email. Please try again later.',
            ], 500);
        }
    }
}
