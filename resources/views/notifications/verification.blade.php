<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome & Verify Your Email - Belle's Bistro Resort and Hotel</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #f9f6f1;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            background-color: #003366;
            padding: 30px 20px;
            border-radius: 8px 8px 0 0;
        }

        .header img {
            max-width: 100px;
            margin-bottom: 10px;
        }

        .header h2 {
            color: #f4c430;
            margin: 0;
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .content {
            padding: 30px;
            text-align: center;
            font-size: 16px;
        }

        .content p {
            color: #555;
            margin-bottom: 18px;
            line-height: 1.6;
        }

        .verify-button,
        .explore-button {
            display: inline-block;
            background-color: #f4c430;
            color: #003366;
            padding: 12px 30px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            transition: 0.3s ease-in-out;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
        }

        .verify-button:hover,
        .explore-button:hover {
            background-color: #003366;
            color: #f4c430;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #666;
            padding: 20px;
            background-color: #f9f6f1;
            border-radius: 0 0 8px 8px;
        }

        .footer a {
            color: #003366;
            text-decoration: none;
            font-weight: bold;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .disclaimer {
            font-size: 12px;
            color: #999;
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <table class="container">
        <!-- Header -->
        <tr>
            <td class="header">
                <img src="https://www.facebook.com/p/Belles-Bistro-and-Resort-Hotel-100076196200673/?_rdr/homepage/img/logo.png" alt="Belle's Bistro Resort and Hotel Logo">
                <h2>Welcome to Belle's Bistro Resort and Hotel</h2>
            </td>
        </tr>

        <!-- Email Content -->
        <tr>
            <td class="content">
                <p><strong>Hello {{ ucfirst($user->first_name) }} {{ ucfirst($user->last_name) }},</strong></p>
                <p>We are delighted to welcome you to <strong>Belle's Bistro Resort and Hotel</strong>! 🌿 Your relaxing escape
                    begins here.</p>

                <p>Before you enjoy exclusive perks and updates, please confirm your email by clicking the button below:
                </p>

                <p>
                    @php
                        use Illuminate\Support\Facades\Crypt;
                        $encryptedEmail = Crypt::encryptString($user->email);
                    @endphp

                    <a href="{{ route('homepage-confirmEmail', ['email' => $encryptedEmail]) }}" class="verify-button">
                        Verify My Email
                    </a>
                </p>

                <p>If you didn’t create an account, simply ignore this email.</p>

                <p class="disclaimer">
                    ⏳ This verification link will expire in 5 minutes for security reasons.
                </p>

                <hr style="border: 1px solid #ddd;">

                <h4 style="border-bottom: 2px solid #003366; padding-bottom: 5px; color: #003366;">What You Can Look
                    Forward To:</h4>
                <p>✅ First access to seasonal promotions</p>
                <p>✅ Special discounts on accommodations & events</p>
                <p>✅ Insider updates on new resort features</p>

                <hr style="border: 1px solid #ddd;">

                <h4 style="border-bottom: 2px solid #003366; padding-bottom: 5px; color: #003366;">Discover Your Getaway
                </h4>
                <p>🏡 Stay in our cozy cottages with a breathtaking farm view.</p>
                <p>☕ Savor freshly brewed coffee at <strong>Kapehan sa Bukid</strong>.</p>
                <p>🏊 Enjoy a refreshing swim in our private pools.</p>
                <p>🎉 Celebrate life’s moments in our event spaces.</p>

                <p style="text-align: center;">
                    <a href="https://www.facebook.com/p/Belles-Bistro-and-Resort-Hotel-100076196200673/?_rdr" class="explore-button">
                        Explore B
                    </a>
                </p>

                <hr style="border: 1px solid #ddd;">

                <p style="text-align: center;">
                    <a href="{{ route('homepage-bookHotel') }}" class="verify-button">
                        Book Your Stay Now
                    </a>
                </p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td class="footer">
                <p>Need help? <a >Contact Support</a></p>
                <p>📍 <a href="https://www.google.com/maps/embed/v1/place?q=Poblacion+Zone+1,+Mayorga,+Leyte,+Eastern+Visayas,+Philippines,+Mayorga,+Philippines,&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8">Find Us</a> |
                    📧 <a href="https://www.facebook.com/p/Belles-Bistro-and-Resort-Hotel-100076196200673/?_rdr">Facebook Page</a> |
                    ☎️ +1 234 567 8900
                </p>
                <p class="disclaimer">
                    &copy; {{ date('Y') }} Belle's Bistro Resort and Hotel. All rights reserved.
                </p>
            </td>
        </tr>
    </table>

</body>

</html>
