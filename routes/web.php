<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactInquiryController;
use App\Http\Controllers\FoodCategoryController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\HotelAmenityController;
use App\Http\Controllers\HotelPaymentController;
use App\Http\Controllers\HotelPictureController;
use App\Http\Controllers\HotelReservationController;
use App\Http\Controllers\HotelReviewController;
use App\Http\Controllers\HotelRoomController;
use App\Http\Controllers\HotelTransactionController;
use App\Http\Controllers\HotelTypeController;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\ResortCottageController;
use App\Http\Controllers\ResortPaymentController;
use App\Http\Controllers\ResortPictureController;
use App\Http\Controllers\ResortReservationController;
use App\Http\Controllers\ResortReviewController;
use App\Http\Controllers\ResortTransactionController;
use App\Http\Controllers\ResortTypeController;
use App\Http\Controllers\SeasonalPromoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('homepage-homepage');
});

Route::prefix('/guest')->middleware('guest')->group(function () {
    Route::get('homepage', [NavigationController::class, 'homepage'])->name('homepage-homepage');

    Route::get('categories', [NavigationController::class, 'categories'])->name('homepage-categories');

    Route::get('categoryKapehan', [NavigationController::class, 'categoryKapehan'])->name('homepage-categoryKapehan');

    Route::get('categoryHotel', [NavigationController::class, 'categoryHotel'])->name('homepage-categoryHotel');
    Route::get('roomDetails/{id}', [NavigationController::class, 'roomDetails'])->name('homepage-roomDetails');
    Route::post('insertRoomReviews', [NavigationController::class, 'insertRoomReviews'])->name('homepage-insertRoomReviews');
    Route::post('roomInfo', [HotelRoomController::class, 'getRoomInfo'])->name('get-room-info');

    Route::get('categoryResort', [NavigationController::class, 'categoryResort'])->name('homepage-categoryResort');
    Route::get('cottageDetails/{id}', [NavigationController::class, 'cottageDetails'])->name('homepage-cottageDetails');
    Route::post('insertCottageReviews', [NavigationController::class, 'insertCottageReviews'])->name('homepage-insertCottageReviews');
    Route::post('cottageInfo', [ResortCottageController::class, 'getCottageInfo'])->name('get-cottage-info');

    Route::get('experience', [NavigationController::class, 'experience'])->name('homepage-experience');

    Route::get('bookHotel', [NavigationController::class, 'bookHotel'])->name('homepage-bookHotel');
    Route::get('available-rooms', [HotelReservationController::class, 'checkRoomAvailability'])->name('checkRoomAvailability');
    Route::get('room-reservations/{roomID}', [HotelReservationController::class, 'getRoomReservations'])->name('getRoomReservations');
    Route::get('room-information/{roomID}', [NavigationController::class, 'getRoomInformation'])->name('getRoomInformation');
    Route::get('room-booking-details', [NavigationController::class, 'getRoomBookingDetails'])->name('getRoomBookingDetails');

    Route::get('bookResort', [NavigationController::class, 'bookResort'])->name('homepage-bookResort');
    Route::get('available-cottages', [ResortReservationController::class, 'checkCottageAvailability'])->name('checkCottageAvailability');
    Route::get('cottage-reservations/{cottageID}', [ResortReservationController::class, 'getCottageReservations'])->name('getCottageReservations');
    Route::get('cottage-information/{cottageID}', [NavigationController::class, 'getCottageInformation'])->name('getCottageInformation');
    Route::get('cottage-booking-details', [NavigationController::class, 'getCottageBookingDetails'])->name('getCottageBookingDetails');

    Route::get('contact', [NavigationController::class, 'contact'])->name('homepage-contact');

    Route::get('login', [NavigationController::class, 'login'])->name('homepage-login')->middleware('guest');

    // Route::get('chatbot/response', [ChatbotController::class, 'getResponse'])->name('chatbot-getResponse');
    Route::post('chatbot/response', [ChatbotController::class, 'getResponse'])->name('chatbot-getResponse');

    // Thank You Message
    Route::get('success/{transactionNumber}', [NavigationController::class, 'success'])->name('homepage-success');
});

// Login, Registration
Route::post('loginAccount', [UserController::class, 'loginAccount'])->name('loginAccount');
Route::post('logoutAccount', [UserController::class, 'logoutAccount'])->name('logoutAccount');
Route::post('registerAccount', [UserController::class, 'registerAccount'])->name('registerAccount');
Route::post('forgotPassword', [UserController::class, 'forgotPassword'])->name('forgotPassword');
Route::get('verify-email', [NavigationController::class, 'verifyEmail'])->name('homepage-verifyEmail')->middleware('signed');
Route::get('confirm-email', [NavigationController::class, 'confirmEmail'])->name('homepage-confirmEmail');
Route::post('resend-verification', [UserController::class, 'resendVerification'])->name('resendVerification');
Route::get('email-verified', [NavigationController::class, 'emailVerified'])->name('homepage-emailVerified');

Route::prefix('admin')->middleware('user')->group(function () {
    // Management
    Route::get('userManagement', [NavigationController::class, 'userManagement'])->name('admin-userManagement');
});

Route::prefix('owner')->middleware('user')->group(function () {
    //Dashboard
    Route::get('dashboard', [NavigationController::class, 'dashboard'])->name('owner-dashboard');
    // Management
    Route::get('hotelManagement', [NavigationController::class, 'hotelManagement'])->name('owner-hotelManagement');
    Route::get('resortManagement', [NavigationController::class, 'resortManagement'])->name('owner-resortManagement');
    Route::get('foodManagement', [NavigationController::class, 'foodManagement'])->name('owner-foodManagement');
    Route::get('userManagement', [NavigationController::class, 'userManagement'])->name('owner-userManagement');

    // Transactions
    Route::get('hotelTransactions', [NavigationController::class, 'hotelTransactions'])->name('owner-hotelTransactions');
    Route::get('resortTransactions', [NavigationController::class, 'resortTransactions'])->name('owner-resortTransactions');
    Route::get('foodTransactions', [NavigationController::class, 'foodTransactions'])->name('owner-foodTransactions');

    // Billings
    Route::get('hotelBillings', [NavigationController::class, 'hotelBillings'])->name('owner-hotelBillings');
    Route::get('resortBillings', [NavigationController::class, 'resortBillings'])->name('owner-resortBillings');
    Route::get('foodBillings', [NavigationController::class, 'foodBillings'])->name('owner-foodBillings');

    // Info
    Route::get('{roomID}/roomInfo', [NavigationController::class, 'hotelRoomInfo'])->name('owner-hotelRoomInfo');
    Route::get('{cottageID}/cottageInfo', [NavigationController::class, 'resortCottageInfo'])->name('owner-resortCottageInfo');

    // Messages
    Route::get('contactInquiries', [NavigationController::class, 'contactInquiries'])->name('owner-contactInquiries');
    Route::get('replyInquiry/{inquiryID}', [NavigationController::class, 'replyInquiry'])->name('owner-replyInquiry');

    // Promos
    Route::get('seasonalPromos', [NavigationController::class, 'seasonalPromos'])->name('owner-seasonalPromos');
});

Route::prefix('hotel')->middleware('user')->group(function () {
    //Dashboard
    Route::get('dashboard', [NavigationController::class, 'dashboard'])->name('hotel-dashboard');
    // Management
    Route::get('hotelManagement', [NavigationController::class, 'hotelManagement'])->name('hotel-hotelManagement');
    // Transactions
    Route::get('hotelTransactions', [NavigationController::class, 'hotelTransactions'])->name('hotel-hotelTransactions');

    // Billings
    Route::get('hotelBillings', [NavigationController::class, 'hotelBillings'])->name('hotel-hotelBillings');
});

Route::prefix('resort')->middleware('user')->group(function () {
    //Dashboard
    Route::get('dashboard', [NavigationController::class, 'dashboard'])->name('resort-dashboard');
    // Management
    Route::get('hotelManagement', [NavigationController::class, 'hotelManagement'])->name('resort-hotelManagement');

    // Transactions
    Route::get('resortTransactions', [NavigationController::class, 'resortTransactions'])->name('resort-resortTransactions');

    // Billings
    Route::get('hotelBillings', [NavigationController::class, 'hotelBillings'])->name('resort-hotelBillings');
});

Route::prefix('food')->middleware('user')->group(function () {
    //Dashboard
    Route::get('dashboard', [NavigationController::class, 'dashboard'])->name('food-dashboard');
    // Management
    Route::get('hotelManagement', [NavigationController::class, 'hotelManagement'])->name('food-hotelManagement');

    // Transactions
    Route::get('foodTransactions', [NavigationController::class, 'foodTransactions'])->name('food-foodTransactions');

    // Billings
    Route::get('foodBillings', [NavigationController::class, 'foodBillings'])->name('food-foodBillings');
});

// Hotel Management
Route::get('hotelTypes/getAll', [HotelTypeController::class, 'getAll']);
Route::resource('hotelTypes', HotelTypeController::class);
Route::resource('hotelRooms', HotelRoomController::class);
Route::resource('hotelAmenities', HotelAmenityController::class);
Route::resource('hotelPictures', HotelPictureController::class);
Route::post('hotelRooms/roomPicture', [HotelRoomController::class, 'roomPicture']);

// Resort Management
Route::get('resortTypes/getAll', [ResortTypeController::class, 'getAll']);
Route::resource('resortTypes', ResortTypeController::class);
Route::resource('resortCottages', ResortCottageController::class);
Route::resource('resortPictures', ResortPictureController::class);
Route::post('resortCottages/cottagePicture', [ResortCottageController::class, 'cottagePicture']);

// Food Management
Route::get('foodCategories/getAll', [FoodCategoryController::class, 'getAll']);
Route::resource('foodCategories', FoodCategoryController::class);
Route::resource('foods', FoodController::class);

// Hotel Reservation
Route::get('getAvailableRooms', [HotelReservationController::class, 'getAvailableRooms'])->name('getAvailableRooms');
Route::get('getRoomAvailability', [HotelReservationController::class, 'getRoomAvailability'])->name('getRoomAvailability');
Route::get('transactionsHotel/count', [HotelReservationController::class, 'getHotelTransactionCount'])->name('getHotelTransactionCount');
Route::put('reservationHotelStatus', [HotelReservationController::class, 'reservationHotelStatus'])->name('reservationHotelStatus');

// Resort Reservation
Route::get('getAvailableCottages', [ResortReservationController::class, 'getAvailableCottages'])->name('getAvailableCottages');
Route::get('getCottageAvailability', [ResortReservationController::class, 'getCottageAvailability'])->name('getCottageAvailability');
Route::get('transactionsResort/count', [ResortReservationController::class, 'getResortTransactionCount'])->name('getResortTransactionCount');
Route::put('reservationResortStatus', [ResortReservationController::class, 'reservationResortStatus'])->name('reservationResortStatus');

// Hotel Transaction
Route::resource('hotelTransactions', HotelTransactionController::class);

// Resort Transaction
Route::resource('resortTransactions', ResortTransactionController::class);

// Hotel Billing
Route::resource('hotelPayments', HotelPaymentController::class);

// Resort Billing
Route::resource('resortPayments', ResortPaymentController::class);

// Hotel Reviews
Route::resource('hotelReviews', HotelReviewController::class);
Route::put('hotelReviews/updateStatus/{reviewID}', [HotelReviewController::class, 'updateStatus'])->name('updateHotelReviewStatus');

// Resort Reviews
Route::resource('resortReviews', ResortReviewController::class);
Route::put('resortReviews/updateStatus/{reviewID}', [ResortReviewController::class, 'updateStatus'])->name('updateResortReviewStatus');

// Contact Inquiries
Route::resource('contactInquiries', ContactInquiryController::class);
Route::post('contactInquiries/replyInquiry', [ContactInquiryController::class, 'replyInquiry'])->name('replyInquiry');
Route::post('contactInquiries/uploadImage', [ContactInquiryController::class, 'uploadImage'])->name('uploadImage');

// Seasonal Promos
Route::resource('seasonalPromos', SeasonalPromoController::class);

// Contacts
Route::resource('contacts', ContactController::class);

// User Management
Route::resource('users', UserController::class);
Route::put('users/activeStatus', [UserController::class, 'activeStatus'])->name('activeStatus');
Route::get('profile', [NavigationController::class, 'profile'])->name('profile');
Route::put('updateProfile', [NavigationController::class, 'updateProfile'])->name('updateProfile');
Route::put('updatePassword', [NavigationController::class, 'updatePassword'])->name('updatePassword');

// Analytics
Route::get('{type}-types', [AnalyticsController::class, 'getTypes'])->name('getTypes');
Route::get('analytics/views', [AnalyticsController::class, 'getViewsByCategory'])->name('getViewsByCategory');

Route::get('/clear-cache', function() {
    Artisan::call('optimize:clear'); // Clears cache, config, route, and view cache
    return "Cache cleared successfully!";
});
