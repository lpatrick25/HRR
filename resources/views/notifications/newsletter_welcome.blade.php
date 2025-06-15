<table class="container"
    style="width: 100%; max-width: 600px; margin: auto; font-family: 'Georgia', serif; background-color: #f9f6f1; padding: 20px;">

    <!-- Header -->
    <tr>
        <td style="text-align: center; background-color: #003366; padding: 20px; border-radius: 8px 8px 0 0;">
            <img src="https://www.facebook.com/p/Belles-Bistro-and-Resort-Hotel-100076196200673/?_rdr/{{ asset('homepage/img/logo.png') }}"
                alt="Belle's Bistro Resort and Hotel Logo" style="max-width: 120px; margin-bottom: 10px;">
            <h2 style="color: #f4c430; margin: 0; text-align: center;">Welcome to Belle's Bistro Resort and Hotel</h2>
        </td>
    </tr>

    <!-- Personalized Welcome Message -->
    <tr>
        <td
            style="background-color: #ffffff; padding: 25px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
            <p style="font-size: 16px; color: #333;"><strong>Dear {{ ucfirst($user->first_name) }},</strong></p>
            <p style="font-size: 14px; color: #666;">
                We are delighted to have you as part of the Belle's Bistro Resort and Hotel! 🌿
                Expect exclusive updates, special offers, and exciting resort experiences straight to your inbox.
            </p>

            <p style="text-align: center;">
                <span
                    style="background-color: #f4c430; color: #003366; padding: 5px 10px; border-radius: 5px; font-size: 14px; font-weight: bold;">
                    🎉 Exclusive Subscriber Perks Await!
                </span>
            </p>

            <h4 style="border-bottom: 2px solid #003366; padding-bottom: 5px; color: #003366;">What You Can Look Forward
                To:</h4>
            <p>✅ First access to seasonal promotions</p>
            <p>✅ Special discounts on accommodations & events</p>
            <p>✅ Insider updates on new resort features</p>

            <hr style="border: 1px solid #ddd;">

            <h4 style="border-bottom: 2px solid #003366; padding-bottom: 5px; color: #003366;">Discover Your Getaway
            </h4>
            <p>🏡 Stay in our cozy cottages with a breathtaking farm view.</p>
            <p>☕ Savor freshly brewed coffee at our <strong>Kapehan sa Bukid</strong>.</p>
            <p>🏊 Enjoy a refreshing swim in our private pools.</p>
            <p>🎉 Celebrate life’s moments in our event spaces.</p>

            <p style="text-align: center;">
                <a href="https://www.facebook.com/p/Belles-Bistro-and-Resort-Hotel-100076196200673/?_rdr"
                    style="background-color: #f4c430; color: #003366; text-decoration: none; padding: 10px 20px; border-radius: 5px; display: inline-block; font-weight: bold;">
                    Explore Belle's Bistro Resort and Hotel
                </a>
            </p>

            <hr style="border: 1px solid #ddd;">

            <p style="text-align: center;">
                <a href="{{ route('homepage-bookHotel') }}"
                    style="background-color: #003366; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 5px; display: inline-block; font-weight: bold;">
                    Book Your Stay Now
                </a>
            </p>

            <hr style="border: 1px solid #ddd;">

            <p style="text-align: center; color: #666; font-size: 14px;">Need assistance? <a
                    href="{{ $transaction->support_link ?? '#' }}" style="color: #003366;">Contact Us</a></p>

            <div style="padding: 20px; background-color: #f9f6f1; font-size: 14px; color: #666;">
                <p style="text-align: center;">We can’t wait to welcome you! If there’s anything we can do to make your
                    stay more special, just let
                    us
                    know.</p>
                <p style="font-style: italic; text-align: center;">Sincerely,</p>
                <p style="font-weight: bold; color: #003366; text-align: center;">Resort Manager Name</p>
                <p style="margin: 0; font-size: 12px; text-align: center;">Resort Manager | Belle's Bistro Resort and
                    Hotel
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
