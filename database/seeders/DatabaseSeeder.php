<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HotelType;
use App\Models\ResortType;
use App\Models\FoodCategory;
use App\Models\HotelRoom;
use App\Models\ResortCottage;
use App\Models\Food;
use App\Models\HotelTransaction;
use App\Models\ResortTransaction;
use App\Models\FoodTransaction;
use App\Models\HotelPayment;
use App\Models\ResortPayment;
use App\Models\FoodPayment;
use App\Models\HotelAmenity;
use App\Models\SeasonalPromo;
use App\Models\HotelReview;
use App\Models\ResortReview;
use App\Models\ResortView;
use App\Models\HotelView;
use App\Models\ContactInquiry;
use App\Models\User;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UsersTableSeeder::class);

        // Create Users (assuming a users table exists)
        $users = User::factory()->count(5)->create();

        // Hotel Types
        $hotelTypes = HotelType::factory()->createMany([
            [
                'type_name' => 'Standard',
                'type_description' => 'Basic hotel room with essential amenities.',
            ],
            [
                'type_name' => 'Deluxe',
                'type_description' => 'Spacious room with premium amenities.',
            ],
            [
                'type_name' => 'Suite',
                'type_description' => 'Luxurious suite with separate living area.',
            ],
        ]);

        // Resort Types
        $resortTypes = ResortType::factory()->createMany([
            [
                'type_name' => 'Beachfront',
                'type_description' => 'Cottage with direct beach access.',
            ],
            [
                'type_name' => 'Garden View',
                'type_description' => 'Cottage surrounded by lush gardens.',
            ],
        ]);

        // Food Categories
        $foodCategories = FoodCategory::factory()->createMany([
            ['category_name' => 'Appetizers'],
            ['category_name' => 'Main Course'],
            ['category_name' => 'Desserts'],
            ['category_name' => 'Beverages'],
        ]);

        // Hotel Rooms
        $hotelRooms = [];
        foreach ($hotelTypes as $hotelType) {
            $hotelRooms[] = HotelRoom::factory()->createMany([
                [
                    'room_name' => $hotelType->type_name . ' Room 101',
                    'hotel_type_id' => $hotelType->id,
                    'room_status' => 'Available',
                    'room_rate' => rand(100, 500) . '.00',
                    'room_capacity' => rand(2, 4),
                    'picture' => 'img/profile/1.jpg',
                ],
                [
                    'room_name' => $hotelType->type_name . ' Room 102',
                    'hotel_type_id' => $hotelType->id,
                    'room_status' => 'Available',
                    'room_rate' => rand(100, 500) . '.00',
                    'room_capacity' => rand(2, 4),
                    'picture' => 'img/profile/1.jpg',
                ],
            ]);
        }
        $hotelRooms = collect($hotelRooms)->flatten(1);

        // Resort Cottages
        $resortCottages = [];
        foreach ($resortTypes as $resortType) {
            $resortCottages[] = ResortCottage::factory()->createMany([
                [
                    'cottage_name' => $resortType->type_name . ' Cottage 1',
                    'resort_type_id' => $resortType->id,
                    'cottage_status' => 'Available',
                    'cottage_rate' => rand(150, 600) . '.00',
                    'cottage_capacity' => rand(4, 6),
                    'picture' => 'img/profile/1.jpg',
                ],
                [
                    'cottage_name' => $resortType->type_name . ' Cottage 2',
                    'resort_type_id' => $resortType->id,
                    'cottage_status' => 'Available',
                    'cottage_rate' => rand(150, 600) . '.00',
                    'cottage_capacity' => rand(4, 6),
                    'picture' => 'img/profile/1.jpg',
                ],
            ]);
        }
        $resortCottages = collect($resortCottages)->flatten(1);

        // Foods
        $foods = [];
        foreach ($foodCategories as $category) {
            $foods[] = Food::factory()->createMany([
                [
                    'food_name' => $category->category_name . ' Item 1',
                    'food_category_id' => $category->id,
                    'food_price' => rand(10, 50) . '.00',
                    'food_status' => 'Available',
                    'food_unit' => ['Piece', 'Slice', 'Serving', 'Platter', 'Plate', 'Set', 'Combo', 'Cup', 'Glass', 'Bottle', 'Can'][rand(0, 10)],
                    'picture' => 'img/profile/1.jpg',
                ],
                [
                    'food_name' => $category->category_name . ' Item 2',
                    'food_category_id' => $category->id,
                    'food_price' => rand(10, 50) . '.00',
                    'food_status' => 'Available',
                    'food_unit' => ['Piece', 'Slice', 'Serving', 'Platter', 'Plate', 'Set', 'Combo', 'Cup', 'Glass', 'Bottle', 'Can'][rand(0, 10)],
                    'picture' => 'img/profile/1.jpg',
                ],
            ]);
        }
        $foods = collect($foods)->flatten(1);

        // Hotel Transactions
        foreach ($hotelRooms as $room) {
            HotelTransaction::factory()->create([
                'transaction_number' => 'HTX-' . Str::random(10),
                'user_id' => $users->random()->id,
                'customer_name' => fake()->name,
                'customer_number' => fake()->phoneNumber,
                'customer_email' => fake()->email,
                'customer_type' => ['Registered', 'Walk-in', 'Online'][rand(0, 2)],
                'hotel_room_id' => $room->id,
                'check_in_date' => now()->addDays(rand(1, 10)),
                'check_out_date' => now()->addDays(rand(11, 20)),
                'total_amount' => $room->room_rate * rand(1, 5),
                'status' => ['Pending', 'Confirmed', 'Checked-in', 'Checked-out', 'Cancelled', 'No-show', 'Walk-in'][rand(0, 6)],
            ]);
        }

        // Resort Transactions
        foreach ($resortCottages as $cottage) {
            ResortTransaction::factory()->create([
                'transaction_number' => 'RTX-' . Str::random(10),
                'user_id' => $users->random()->id,
                'customer_name' => fake()->name,
                'customer_number' => fake()->phoneNumber,
                'customer_email' => fake()->email,
                'customer_type' => ['Registered', 'Walk-in', 'Online'][rand(0, 2)],
                'resort_cottage_id' => $cottage->id,
                'booking_date' => now()->addDays(rand(1, 10)),
                'total_amount' => $cottage->cottage_rate * rand(1, 5),
                'status' => ['Pending', 'Confirmed', 'Completed', 'Cancelled', 'No-show'][rand(0, 4)],
            ]);
        }

        // Food Transactions
        foreach ($foods as $food) {
            FoodTransaction::factory()->create([
                'transaction_number' => 'FTX-' . Str::random(10),
                'user_id' => $users->random()->id,
                'customer_name' => fake()->name,
                'customer_number' => fake()->phoneNumber,
                'customer_email' => fake()->email,
                'customer_type' => ['Registered', 'Walk-in'][rand(0, 1)],
                'food_id' => $food->id,
                'quantity' => rand(1, 10),
                'total_amount' => $food->food_price * rand(1, 10),
                'reservation_date' => now()->addDays(rand(1, 10)),
                'status' => ['Pending', 'Confirmed', 'Completed', 'Cancelled', 'No-show'][rand(0, 4)],
            ]);
        }

        // Hotel Payments
        foreach (HotelTransaction::all() as $transaction) {
            HotelPayment::factory()->create([
                'hotel_transaction_id' => $transaction->id,
                'payment_method' => ['Cash', 'Online'][rand(0, 1)],
                'total_amount' => $transaction->total_amount,
                'amount_paid' => $transaction->total_amount * (rand(50, 100) / 100),
                'checkout_session_id' => 'cs_' . Str::random(20),
            ]);
        }

        // Resort Payments
        foreach (ResortTransaction::all() as $transaction) {
            ResortPayment::factory()->create([
                'resort_transaction_id' => $transaction->id,
                'payment_method' => ['Cash', 'Online'][rand(0, 1)],
                'total_amount' => $transaction->total_amount,
                'amount_paid' => $transaction->total_amount * (rand(50, 100) / 100),
                'checkout_session_id' => 'cs_' . Str::random(20),
            ]);
        }

        // Food Payments
        foreach (FoodTransaction::all() as $transaction) {
            FoodPayment::factory()->create([
                'food_transaction_id' => $transaction->id,
                'payment_method' => ['Cash', 'Online'][rand(0, 1)],
                'total_amount' => $transaction->total_amount,
                'amount_paid' => $transaction->total_amount * (rand(50, 100) / 100),
                'checkout_session_id' => 'cs_' . Str::random(20),
            ]);
        }

        // Hotel Amenities
        foreach ($hotelRooms as $room) {
            HotelAmenity::factory()->createMany([
                [
                    'hotel_room_id' => $room->id,
                    'amenity' => 'Wi-Fi',
                ],
                [
                    'hotel_room_id' => $room->id,
                    'amenity' => 'Air Conditioning',
                ],
                [
                    'hotel_room_id' => $room->id,
                    'amenity' => 'Mini Bar',
                ],
            ]);
        }

        // Seasonal Promos
        SeasonalPromo::factory()->createMany([
            [
                'title' => 'Summer Getaway',
                'description' => '20% off on all bookings.',
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'promo_code' => 'SUMMER2025',
            ],
            [
                'title' => 'Winter Escape',
                'description' => 'Free breakfast with every stay.',
                'start_date' => now()->addMonths(6),
                'end_date' => now()->addMonths(7),
                'promo_code' => 'WINTER2025',
            ],
        ]);

        // Hotel Reviews
        foreach ($hotelRooms as $room) {
            HotelReview::factory()->create([
                'user_id' => $users->random()->id,
                'hotel_room_id' => $room->id,
                'review' => fake()->paragraph,
                'rating' => rand(1, 5),
                'status' => ['Pending', 'Approved', 'Rejected'][rand(0, 2)],
            ]);
        }

        // Resort Reviews
        foreach ($resortCottages as $cottage) {
            ResortReview::factory()->create([
                'user_id' => $users->random()->id,
                'resort_cottage_id' => $cottage->id,
                'review' => fake()->paragraph,
                'rating' => rand(1, 5),
                'status' => ['Pending', 'Approved', 'Rejected'][rand(0, 2)],
            ]);
        }

        // Resort Views
        foreach ($resortCottages as $cottage) {
            ResortView::factory()->create([
                'resort_cottage_id' => $cottage->id,
                'user_id' => $users->random()->id,
                'ip_address' => fake()->ipv4,
            ]);
        }

        // Hotel Views
        foreach ($hotelRooms as $room) {
            HotelView::factory()->create([
                'hotel_room_id' => $room->id,
                'user_id' => $users->random()->id,
                'ip_address' => fake()->ipv4,
            ]);
        }

        // Contact Inquiries
        ContactInquiry::factory()->createMany([
            [
                'customer_full_name' => fake()->name,
                'customer_email' => fake()->email,
                'customer_subject' => 'Room Availability',
                'customer_message' => fake()->paragraph,
                'status' => 'Unread',
            ],
            [
                'customer_full_name' => fake()->name,
                'customer_email' => fake()->email,
                'customer_subject' => 'Complaint',
                'customer_message' => fake()->paragraph,
                'status' => 'Unread',
            ],
        ]);
    }
}
