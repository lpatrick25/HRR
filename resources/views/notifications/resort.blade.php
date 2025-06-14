<table class="container"
    style="width: 100%; max-width: 600px; margin: auto; font-family: 'Georgia', serif; background-color: #f9f6f1; padding: 20px;">

    <!-- Header -->
    <tr>
        <td style="text-align: center; background-color: #003366; padding: 20px; border-radius: 8px 8px 0 0;">
            <img src="https://www.facebook.com/p/Belles-Bistro-and-Resort-Hotel-100076196200673/?_rdr/{{ asset('homepage/img/logo.png') }}"
                alt="Belle's Bistro Resort and Hotel Logo" style="max-width: 120px; margin-bottom: 10px;">
            <h2 style="color: #f4c430; margin: 0;">Your Exclusive Booking is Confirmed</h2>
        </td>
    </tr>

    <!-- Personalized Welcome Message -->
    <tr>
        <td
            style="background-color: #ffffff; padding: 25px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
            <p style="font-size: 16px; color: #333;"><strong>Dear {{ $transaction->customer_name }},</strong></p>
            <p style="font-size: 14px; color: #666;">We are delighted to welcome you to Belle's Bistro Resort and Hotel!
                Your oasis of relaxation awaits. Below are your booking details:</p>

            <!-- Booking Status Badge -->
            <p style="text-align: center;">
                <span
                    style="background-color: #f4c430; color: #003366; padding: 5px 10px; border-radius: 5px; font-size: 14px; font-weight: bold;">
                    🌟 Booking Confirmed
                </span>
            </p>

            <h4 style="border-bottom: 2px solid #003366; padding-bottom: 5px; color: #003366;">Booking Details</h4>
            <p><strong>Transaction Number:</strong> {{ $transaction->transaction_number }}</p>
            <p><strong>Customer Email:</strong> {{ $transaction->customer_email }}</p>
            <p><strong>Contact Number:</strong> {{ $transaction->customer_number }}</p>
            <p><strong>Booking Date:</strong> {{ date('l, F j, Y', strtotime($transaction->booking_date)) }}</p>
            <p><strong>Arrival Time:</strong> <span style="color: #dc3545;">(Before 10:00 AM)</span></p>

            <hr style="border: 1px solid #ddd;">

            <!-- Cottage Information Card -->
            <h4 style="border-bottom: 2px solid #003366; padding-bottom: 5px; color: #003366;">Your Private Retreat</h4>
            <div
                style="display: flex; align-items: center; background-color: #fdf7e3; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <img src="{{ asset($resortCottage->pictures->first()->picture ?? 'img/profile/1.jpg') }}"
                    alt="Cottage Image" style="width: 120px; height: 80px; border-radius: 5px; margin-right: 10px;">
                <div>
                    <p style="margin: 0; font-size: 14px;"><strong>{{ $resortCottage->cottage_name }}</strong> -
                        {{ $resortCottage->resortType->type_name }}</p>
                    <p style="margin: 0; font-size: 14px;"><strong>Capacity:</strong>
                        {{ $resortCottage->cottage_capacity }} guests</p>
                    <p style="margin: 0; font-size: 14px;"><strong>Rate per Night:</strong>
                        {{ $resortCottage->cottage_rate }}</p>
                </div>
            </div>
            <p><strong>Stay Duration:</strong> 1 day(s)</p>
            <p><strong>Total Amount:</strong>
                <span style="color: #28a745; font-size: 16px;">
                    ₱{{ number_format($transaction->total_amount, 2) }}
                </span>
            </p>

            <hr style="border: 1px solid #ddd;">

            <!-- Cancellation Policy -->
            <h4 style="color: #dc3545;">Cancellation & No-Show Policy</h4>
            <p>🚫 Cancellations made at least <strong>3 days before</strong> the booking date will receive a full
                refund.</p>
            <p>⏳ Guests must arrive <strong>before the specified arrival time</strong> to avoid a no-show status.</p>

            <hr style="border: 1px solid #ddd;">

            <!-- Special Touches -->
            <h4 style="border-bottom: 2px solid #003366; padding-bottom: 5px; color: #003366;">Make Your Stay Even More
                Memorable</h4>
            <p>🏔 Wake up to serenity in our Hotel Rooms with stunning mountain views, where fresh air and nature
                surround you.</p>
            <p>🏡 Unwind in comfort at our Resort Cottages, just steps from the swimming pool—perfect for a
                refreshing dip under the sun.</p>
            <p>☕ Enjoy a farm-to-cup coffee experience at Kapehan sa Bukid, serving freshly brewed coffee,
                morning delights, and local snacks.</p>
            <p>🎉 Celebrate special moments in our Function Hall, ideal for weddings, corporate events, and family
                gatherings.</p>

            <p style="text-align: center;">
                <a href="{{ $transaction->add_ons_link ?? '#' }}"
                    style="background-color: #f4c430; color: #003366; text-decoration: none; padding: 10px 20px; border-radius: 5px; display: inline-block; font-weight: bold;">
                    Discover More at Belle's Bistro Resort and Hotel
                </a>
            </p>

            <hr style="border: 1px solid #ddd;">

            <!-- Seasonal Promo (Only Displays If Active) -->
            @if ($seasonalPromo)
                <div
                    style="background-color: #fff9e6; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
                    <h4 style="border-bottom: 2px solid #d9534f; padding-bottom: 5px; color: #d9534f;">🌟 Limited-Time
                        Offer!</h4>
                    <p><strong>{{ $seasonalPromo->title }}</strong></p>
                    <p style="color: #555;">{{ $seasonalPromo->description }}</p>

                    @if ($seasonalPromo->promo_code)
                        <p><strong>Use Promo Code:</strong>
                            <span
                                style="background-color: #d9534f; color: #fff; padding: 5px 10px; border-radius: 5px; font-weight: bold;">
                                {{ $seasonalPromo->promo_code }}
                            </span>
                        </p>
                    @endif

                    <p style="color: #666; font-size: 14px;">Offer valid from
                        <strong>{{ date('F j, Y', strtotime($seasonalPromo->start_date)) }}</strong> to
                        <strong>{{ date('F j, Y', strtotime($seasonalPromo->end_date)) }}</strong>.
                    </p>

                    <p style="text-align: center;">
                        <a href="{{ $seasonalPromo->link ?? '#' }}"
                            style="background-color: #d9534f; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 5px; display: inline-block; font-weight: bold;">
                            Grab This Deal!
                        </a>
                    </p>
                </div>
            @endif

            <!-- Call to Action -->
            <p style="text-align: center;">
                <a href="{{ $transaction->booking_link ?? '#' }}"
                    style="background-color: #003366; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 5px; display: inline-block; font-weight: bold;">
                    View My Booking
                </a>
            </p>

            <p style="text-align: center; color: #666; font-size: 14px;">Need assistance? <a
                    href="{{ $transaction->support_link ?? '#' }}" style="color: #003366;">Contact Us</a></p>

            <div style="padding: 20px; background-color: #f9f6f1; font-size: 14px; color: #666;">
                <p style="text-align: center;">We can’t wait to welcome you! If there’s anything we can do to make your
                    stay more special, just let
                    us
                    know.</p>
                <p style="font-style: italic; text-align: center;">Sincerely,</p>
                <p style="font-weight: bold; color: #003366; text-align: center;">Resort Manager Name</p>
                <p style="margin: 0; font-size: 12px; text-align: center;">Resort Manager | Belle's Bistro Resort and Hotel
                </p>

                <p style="margin-top: 10px; text-align: center;">
                    📍 <a href="https://maps.app.goo.gl/zb3kLxuLbT8ANifbA"
                        style="text-decoration: none; text-align: center;">Find Us</a> |
                    📧 <a href="mailto:{{ $contact->email }}" style="text-decoration: none; text-align: center;">Email
                        Support</a> |
                    ☎️ {{ $contact->contact }}
                </p>
                <p style="margin-top: 15px; text-align: center; font-size: 12px; color: #999;">
                    &copy; {{ date('Y') }} Belle's Bistro Resort and Hotel. All rights reserved.
                </p>
            </div>
        </td>
    </tr>
</table>
