<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Order\FoodOrderController;
use App\Mail\NewsletterSubscriptionMail;
use App\Models\Contact;
use App\Models\ContactInquiry;
use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\FoodPicture;
use App\Models\FoodTransaction;
use App\Models\HotelReview;
use App\Models\HotelRoom;
use App\Models\HotelTransaction;
use App\Models\HotelType;
use App\Models\HotelView;
use App\Models\ResortCottage;
use App\Models\ResortReview;
use App\Models\ResortTransaction;
use App\Models\ResortType;
use App\Models\ResortView;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


class NavigationController extends Controller
{
    // Homepage
    public function homepage()
    {
        $hotelRooms = HotelRoom::with('pictures')->get();
        $resortCottages = ResortCottage::with('pictures')->get();
        return view('homepage.index', compact('hotelRooms', 'resortCottages'));
    }

    public function categories()
    {
        $hotelRooms = HotelRoom::with('pictures')->get();
        $resortCottages = ResortCottage::with('pictures')->get();
        $foods = Food::with('pictures')->get();
        return view('homepage.categories', compact('hotelRooms', 'resortCottages', 'foods'));
    }

    public function categoryFood()
    {
        $categories = FoodCategory::all();
        $foodcat = Food::with('category')->get();
        $foods = Food::with('pictures')->get();
        return view('homepage.food', compact('foods', 'categories', 'foodcat'));
    }

    public function foodOrder(Request $request)
    {
        $categories = FoodCategory::all();
        $selectedCategory = $request->query('category_id');

        $foods = Food::when($selectedCategory, function ($query, $selectedCategory) {
            return $query->where('food_category_id', $selectedCategory);
        })->where('food_status', 'Available')->get();

        $cart = session()->get('cart', []);
        return view('homepage.foodorder', compact('categories', 'foods', 'selectedCategory', 'cart'));
    }

    public function categoryHotel()
    {
        $hotelRooms = HotelRoom::with('pictures')->get();
        return view('homepage.hotel', compact('hotelRooms'));
    }

    public function roomDetails(Request $request, $encryptedId)
    {
        try {
            $roomId = Crypt::decrypt($encryptedId);
            $room = HotelRoom::with(['hotelType', 'amenities', 'pictures'])
                ->findOrFail($roomId);

            // Fetch only approved reviews along with the user details
            $roomReviews = HotelReview::where('status', 'Approved')
                ->with('user')
                ->get();

            // Check if user already viewed this cottage today
            $existingView = HotelView::where('hotel_room_id', $roomId)
                ->where(function ($query) {
                    if (Auth::check()) {
                        $query->where('user_id', Auth::id());
                    } else {
                        $query->where('ip_address', request()->ip());
                    }
                })
                ->whereDate('created_at', now()->toDateString())
                ->first();

            if (!$existingView) {
                HotelView::create([
                    'hotel_room_id' => $roomId,
                    'user_id' => Auth::id(),
                    'ip_address' => $request->ip(),
                ]);
            }

            return view('homepage.room-details', compact('room', 'roomReviews'));
        } catch (DecryptException $e) {
            abort(404, 'Invalid Room ID');
        }
    }

    public function insertRoomReviews(Request $request)
    {
        try {
            $user = auth()->user();

            // Attempt to decrypt the ID
            try {
                $hotelRoomId = Crypt::decrypt($request->input('hotel_room_id'));
            } catch (DecryptException $e) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'Invalid request data. Please try again.',
                ], 400);
            }

            // Validate the request
            $validated = $request->validate([
                'review' => 'required|string|min:10',
                'rating' => 'required|integer|between:1,5',
                'recaptcha_token' => 'required|string',
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

            // Check if the user already has an approved review
            $existingReview = HotelReview::where('user_id', $user->id)
                ->where('hotel_room_id', $hotelRoomId)
                ->where('status', 'Approved')
                ->first();

            if ($existingReview) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'You have already submitted an approved review for this room.',
                ], 403);
            }

            // Insert the review (default status is 'Pending')
            HotelReview::create([
                'user_id' => $user->id,
                'hotel_room_id' => $hotelRoomId,
                'review' => $validated['review'],
                'rating' => $validated['rating'],
                'status' => 'Pending',
            ]);

            return response()->json([
                'valid' => true,
                'msg' => 'Your review has been submitted for approval!',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed: ' . json_encode($e->errors()));

            return response()->json([
                'valid' => false,
                'msg' => 'Validation failed. Please check the errors below.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to submit review: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to submit your review. Please try again later.',
            ], 500);
        }
    }

    public function getRoomInformation($encryptedId)
    {
        try {
            $roomId = Crypt::decrypt($encryptedId);
            $room = HotelRoom::with(['hotelType', 'amenities', 'pictures'])->findOrFail($roomId);
            return response()->json($room);
        } catch (DecryptException $e) {
            abort(404, 'Invalid Room ID');
        }
    }

    public function getRoomBookingDetails(Request $request)
    {
        try {
            $transaction = HotelTransaction::with(['hotelRoom.hotelType'])
                ->where('customer_name', $request->search_name)
                ->where('customer_email', $request->search_email)
                ->where('customer_number', $request->search_number)
                ->firstOrFail();

            $stayDays = $transaction->check_in_date->diffInDays($transaction->check_out_date);

            return response()->json([
                'transaction_number' => $transaction->transaction_number,
                'customer_name' => $transaction->customer_name,
                'customer_email' => $transaction->customer_email,
                'customer_number' => $transaction->customer_number,
                'check_in_date' => $transaction->check_in_date->format('l, F j, Y'),
                'check_out_date' => $transaction->check_out_date->format('l, F j, Y'),
                'stay_days' => $stayDays,
                'total_amount' => $transaction->total_amount,
                'status' => $transaction->status,
                'room' => [
                    'room_name' => $transaction->hotelRoom->room_name,
                    'room_rate' => $transaction->hotelRoom->room_rate,
                    'type' => [
                        'type_name' => $transaction->hotelRoom->hotelType->type_name
                    ],
                ],
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'msg' => 'Booking not found. Please check your details and try again.',
            ], 404);
        }
    }

    public function getCottageInformation($encryptedId)
    {
        try {
            $cottageId = Crypt::decrypt($encryptedId);
            $cottage = ResortCottage::with(['resortType', 'pictures'])->findOrFail($cottageId);
            return response()->json($cottage);
        } catch (DecryptException $e) {
            return response()->json(['error' => 'Invalid Cottage ID'], 400);
        }
    }

    public function getCottageBookingDetails(Request $request)
    {
        try {
            $transaction = ResortTransaction::with(['resortCottage.resortType'])
                ->where('customer_name', $request->search_name)
                ->where('customer_email', $request->search_email)
                ->where('customer_number', $request->search_number)
                ->firstOrFail();

            return response()->json([
                'transaction_number' => $transaction->transaction_number,
                'customer_name' => $transaction->customer_name,
                'customer_email' => $transaction->customer_email,
                'customer_number' => $transaction->customer_number,
                'booking_date' => $transaction->booking_date->format('l, F j, Y'),
                'total_amount' => $transaction->total_amount,
                'status' => $transaction->status,
                'cottage' => [
                    'cottage_name' => $transaction->resortCottage->cottage_name,
                    'cottage_rate' => $transaction->resortCottage->cottage_rate,
                    'type' => [
                        'type_name' => $transaction->resortCottage->resortType->type_name
                    ],
                ],
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['msg' => 'Booking not found. Please check your details and try again.'], 404);
        }
    }

    public function categoryResort()
    {
        $resortCottages = ResortCottage::with('pictures')->get();
        return view('homepage.resort', compact('resortCottages'));
    }

    public function cottageDetails(Request $request, $encryptedId)
    {
        try {
            $cottageId = Crypt::decrypt($encryptedId);
            $cottage = ResortCottage::with(['resortType', 'pictures'])
                ->findOrFail($cottageId);

            // Fetch only approved reviews along with the user details
            $cottageReviews = ResortReview::where('status', 'Approved')
                ->with('user')
                ->get();

            // Check if user already viewed this cottage today
            $existingView = ResortView::where('resort_cottage_id', $cottageId)
                ->where(function ($query) {
                    if (Auth::check()) {
                        $query->where('user_id', Auth::id());
                    } else {
                        $query->where('ip_address', request()->ip());
                    }
                })
                ->whereDate('created_at', now()->toDateString())
                ->first();

            if (!$existingView) {
                ResortView::create([
                    'resort_cottage_id' => $cottageId,
                    'user_id' => Auth::id(),
                    'ip_address' => $request->ip(),
                ]);
            }

            return view('homepage.cottage-details', compact('cottage', 'cottageReviews'));
        } catch (DecryptException $e) {
            abort(404, 'Invalid Cottage ID');
        }
    }

    public function insertCottageReviews(Request $request)
    {
        try {
            $user = auth()->user();

            // Attempt to decrypt the ID
            try {
                $resortCottageId = Crypt::decrypt($request->input('resort_cottage_id'));
            } catch (DecryptException $e) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'Invalid request data. Please try again.',
                ], 400);
            }

            // Validate the request
            $validated = $request->validate([
                'review' => 'required|string|min:10',
                'rating' => 'required|integer|between:1,5',
                'recaptcha_token' => 'required|string',
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

            // Check if the user already has an approved review
            $existingReview = ResortReview::where('user_id', $user->id)
                ->where('resort_cottage_id', $resortCottageId)
                ->where('status', 'Approved')
                ->first();

            if ($existingReview) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'You have already submitted an approved review for this cottage.',
                ], 403);
            }

            // Insert the review (default status is 'Pending')
            ResortReview::create([
                'user_id' => $user->id,
                'resort_cottage_id' => $resortCottageId,
                'review' => $validated['review'],
                'rating' => $validated['rating'],
                'status' => 'Pending',
            ]);

            return response()->json([
                'valid' => true,
                'msg' => 'Your review has been submitted for approval!',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed: ' . json_encode($e->errors()));

            return response()->json([
                'valid' => false,
                'msg' => 'Validation failed. Please check the errors below.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to submit review: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to submit your review. Please try again later.',
            ], 500);
        }
    }

    public function experience()
    {
        return view('homepage.experience');
    }

    public function bookHotel()
    {
        return view('homepage.book-hotel');
    }

    public function bookResort()
    {
        return view('homepage.book-resort');
    }

    public function contact()
    {
        $contact = Contact::first();
        return view('homepage.contact', compact('contact'));
    }

    public function login()
    {
        return view('homepage.login');
    }

    public function verifyEmail(Request $request)
    {
        // Validate signed URL
        if (!$request->hasValidSignature()) {
            return redirect()->route('homepage-homepage')->with('error', 'Verification link has expired or is invalid.');
        }

        try {
            // Decrypt the email
            $email = Crypt::decryptString($request->query('email'));
        } catch (\Exception $e) {
            return redirect()->route('homepage-homepage')->with('error', 'Invalid verification link.');
        }

        // Find user
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('homepage-homepage')->with('error', 'User not found.');
        }

        // Check if already verified
        if ($user->active) {
            return redirect()->route('homepage-homepage')->with('info', 'Your email is already verified.');
        }

        return view('homepage.verify-email', compact('user'));
    }

    public function confirmEmail(Request $request)
    {
        try {
            // Attempt to decrypt the email from the query parameter
            $decryptedEmail = Crypt::decryptString($request->query('email'));

            // Find the user by email
            $user = User::where('email', $decryptedEmail)->first();

            if (!$user) {
                // If no user found, redirect to a 404 error page
                return abort(404, 'User not found.');
            }

            // if ($user->active) {
            //     // If user is already verified, redirect home with an info message
            //     return redirect()->route('homepage-homepage')->with('info', 'Your email is already verified.');
            // }
            if ($user->active) {
                // Redirect to the "already verified" page instead of just going to the homepage
                return redirect()->route('homepage-emailVerified');
            }

            // Mark user as active and save
            $user->active = 1;
            $user->save();

            // ✅ Send a welcome email if the user subscribes to the newsletter
            if ($user->news_letter) {
                Mail::to($user->email)->send(new NewsletterSubscriptionMail($user, Contact::first()));
            }

            // Log in the user
            Auth::login($user);

            return view('homepage.confirm-email', compact('user'));
        } catch (DecryptException $e) {
            // If decryption fails, return a 419 error (Session Expired or Invalid Link)
            return abort(419, 'Invalid or expired verification link.');
        } catch (\Exception $e) {
            // Handle unexpected errors
            return abort(500, 'Something went wrong. Please try again.');
        }
    }

    public function emailVerified()
    {
        $user = User::findOrFail(auth()->user()->id);
        return view('homepage.verified', compact('user'));
    }

    public function success()
    {
        return view('homepage.success');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function hotelManagement()
    {
        $roomTypes = HotelType::all();
        return view('hotel.index', compact('roomTypes'));
    }

    public function hotelRoomInfo($room_id)
    {
        $roomInfo = HotelRoom::with('pictures')->findOrFail($room_id);
        return view('hotel.info', compact('roomInfo'));
    }

    public function resortManagement()
    {
        $cottageTypes = ResortType::all();
        return view('resort.index', compact('cottageTypes'));
    }

    public function resortCottageInfo($room_id)
    {
        $cottageInfo = ResortCottage::with('pictures')->findOrFail($room_id);
        return view('resort.info', compact('cottageInfo'));
    }

    public function foodManagement()
    {
        $foodCategories = FoodCategory::all();
        return view('food.index', compact('foodCategories'));
    }
    public function restoFoodInfo($food_id)
    {
        $foodInfo = Food::with('pictures')->findOrFail($food_id);
        return view('food.info', compact('foodInfo'));
    }

    public function getFoodOrderDetails(Request $request)
    {
        try {
            $transaction = FoodTransaction::with(['food.foodCategory'])
                ->where('customer_name', $request->search_name)
                ->where('customer_email', $request->search_email)
                ->where('customer_number', $request->search_number)
                ->firstOrFail();

            $transactions = FoodTransaction::with(['food.foodCategory'])
                ->where('transaction_number', $transaction->transaction_number)
                ->get();

            $totalAmount = $transactions->sum('total_amount');

            return response()->json([
                'transaction_number' => $transaction->transaction_number,
                'customer_name' => $transaction->customer_name,
                'customer_email' => $transaction->customer_email,
                'customer_number' => $transaction->customer_number,
                'reservation_date' => $transaction->reservation_date->format('l, F j, Y'),
                'total_amount' => $totalAmount,
                'status' => $transaction->status,
                'foods' => $transactions->map(function ($trans) {
                    return [
                        'food_name' => $trans->food->food_name,
                        'food_price' => $trans->food->food_price,
                        'quantity' => $trans->quantity,
                        'unit' => $trans->food->food_unit,
                        'category' => [
                            'category_name' => $trans->food->foodCategory->category_name,
                        ],
                    ];
                })->toArray(),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['msg' => 'Order not found. Please check your details and try again.'], 404);
        }
    }

    public function hotelTransactions()
    {
        return view('transactions.hotel');
    }

    public function resortTransactions()
    {
        return view('transactions.resort');
    }

    public function foodTransactions()
    {
        $categories = FoodCategory::all();
        return view('transactions.food', compact('categories'));
    }

    public function hotelBillings()
    {
        return view('billings.hotel');
    }

    public function resortBillings()
    {
        return view('billings.resort');
    }

    public function foodBillings()
    {
        return view('billings.food');
    }

    public function contactInquiries()
    {
        return view('messages');
    }

    public function replyInquiry($inquiry_id)
    {
        $inquiry = ContactInquiry::findOrFail(Crypt::decrypt($inquiry_id));
        return view('reply', compact('inquiry'));
    }

    public function seasonalPromos()
    {
        return view('promos');
    }

    public function userManagement()
    {
        return view('user_management');
    }

    public function profile()
    {
        if (!auth()->check()) {
            return response()->json([
                'valid' => false,
                'msg' => 'User is not authenticated.',
            ], 401); // Unauthorized
        }

        try {
            $user = auth()->user(); // Get the currently authenticated user
            return response()->json($user);
        } catch (\Exception $e) {
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
    public function updateProfile(Request $request)
    {
        DB::beginTransaction();

        try {
            $id = auth()->user()->id;

            // Validation rules
            $validated = $request->validate([
                'first_name' => 'required|string|max:50|regex:/^[a-zA-Z\s]+$/',
                'last_name' => 'required|string|max:50|regex:/^[a-zA-Z\s]+$/',
                'phone_number' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('users', 'phone_number')->ignore($id)
                ],
            ], [
                'first_name.required' => 'First name is required.',
                'last_name.required' => 'Last name is required.',
                'phone_number.required' => 'Phone number is required.',
                'phone_number.unique' => 'This phone number is already registered.',
                'first_name.regex' => 'First name can only contain letters and spaces.',
                'last_name.regex' => 'Last name can only contain letters and spaces.',
            ]);

            // Find and update the user
            $user = User::findOrFail($id);
            $user->update($validated);

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Profile updated successfully!',
            ], 200);
        } catch (ValidationException $e) {
            DB::rollback();

            return response()->json([
                'valid' => false,
                'msg' => 'Validation failed. Please check the errors below.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to update user: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'An error occurred while updating your profile. Please try again later.',
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail(auth()->id());

            // Validate request
            $validated = $request->validate([
                'current_password' => [
                    'required',
                    'string',
                    function ($attribute, $value, $fail) use ($user) {
                        if (!Hash::check($value, $user->password)) {
                            $fail('Current password is incorrect.');
                        }
                    }
                ],
                'new_password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                'confirm_password' => 'required|same:new_password',
            ], [
                'current_password.required' => 'Current password is required.',
                'new_password.required' => 'New password is required.',
                'new_password.min' => 'New password must be at least 8 characters.',
                'new_password.regex' => 'New password must include uppercase, lowercase, number, and special character.',
                'confirm_password.same' => 'Confirmation password must match the new password.',
            ]);

            // Update password
            $user->password = Hash::make($validated['new_password']);
            $user->save();


            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Password updated successfully!',
            ], 200);
        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json([
                'valid' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Password update failed: ' . $e->getMessage());
            return response()->json([
                'valid' => false,
                'msg' => 'An error occurred. Please try again later.',
            ], 500);
        }
    }
}
