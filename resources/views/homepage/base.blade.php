<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Resort Reservation">
    <meta name="keywords" content="Belle's Bistro, Mayorga, resort and hotel">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Belle's Bistro Resort & Hotel | @yield('APP-TITLE')</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('homepage/img/logo1.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('homepage/img/logo.jpg') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('homepage/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('homepage/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('homepage/css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('homepage/css/plyr.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('homepage/css/nice-select.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('homepage/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('homepage/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('homepage/css/style.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('homepage/css/Lobibox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('homepage/css/notifications.css') }}">
    <style type="text/css">
        /* General Styles */
        html,
        body,
        .header,
        .footer,
        #preloder {
            background-image: linear-gradient(to right, rgb(230, 220, 255), rgb(200, 160, 255), rgb(180, 140, 255));
        }


        .mapouter,
        .gmap_canvas {
            position: relative;
            text-align: right;
            height: 300px;
            width: 100%;
            overflow: hidden;
            background: none;
        }

        .mapouter iframe {
            height: 100%;
            width: 100%;
        }

        .section-title h4,
        .section-title h5 {
            color: #000;
            font-family: 'Montserrat', sans-serif;
        }

        .section-title h4::after,
        .section-title h5::after,
        .section-title h4::before,
        .section-title h5::before {
            position: absolute;
            right: 0;
            top: -6px;
            height: 0;
            width: 0;
            background: #9400D3;
            content: "";
        }

        .section-title h5::after {
            height: 32px;
            width: 4px;
        }

        .hr-one {
            margin: 20px auto;
            width: 40%;
            border: 0;
            height: 2px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0), rgb(148, 0, 211), rgba(255, 255, 255, 0));
        }

        .product__item_details {
            color: #000;
            line-height: 26px;
            margin: 0 50px;
        }

        .product__item__pic {
            height: 500px;
        }

        .set-bg {
            background-position: center;
        }

        /* Header & Footer */
        .header {
            border-bottom: 1px solid #9400D3;
        }

        .footer {
            border-top: 1px solid #9400D3;
        }

        .header__menu ul li a,
        .footer__nav ul li a,
        .breadcrumb__links a,
        .breadcrumb__links span,
        .product__sidebar .section-title h5,
        .product__item__text h5 a,
        .resort__details__title h3,
        .resort__details__text p,
        .resort__details__widget ul li,
        .resort__details__widget ul li span {
            color: #9400D3;
        }

        .header__menu ul li.active a {
            background: #9400D3;
            color: #fff;
        }

        .header__menu ul li a:hover,
        .breadcrumb__links a i {
            color: #9400D3;
        }

        .header__menu ul li.active:hover a:hover {
            color: #000;
        }

        .header__right a {
            color: #9400D3;
        }

        /* Buttons & Links */
        .hero__text a span,
        .hero__text a i,
        .page-up a,
        .resort__details__btn .follow-btn,
        .resort__details__form form button,
        .product__sidebar__view__item .ep {
            background: #9400D3;
            color: #fff;
        }

        /* Owl Carousel */
        .hero__slider.owl-carousel .owl-nav button {
            background: transparent;
            color: #fff;
        }

        .hero__slider.owl-carousel .owl-nav button::after {
            background: #9400D3;
        }

        /* Forms */
        .resort__details__form form input,
        .resort__details__form form textarea {
            width: 100%;
            font-size: 15px;
            color: black;
            padding-left: 20px;
            padding-top: 12px;
            border: none;
            border-radius: 5px;
            resize: none;
            margin-bottom: 24px;
        }

        .resort__details__form form input {
            height: 60px;
        }

        .resort__details__form form textarea {
            height: 110px;
        }

        /* Content Sections */
        .product__page__title {
            border-bottom: 2px solid rgba(9, 9, 9, 0.2);
        }

        .chatbox {
            display: none;
            flex-direction: column;
            height: 450px;
            width: 340px;
            border-radius: 12px;
            background: #fff;
            position: fixed;
            bottom: 20px;
            right: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .chatbox-body::-webkit-scrollbar {
            display: none;
        }

        .chatbox-bubble {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            padding: 15px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .chat-option-card {
            display: flex;
            align-items: center;
            background-color: #007bff;
            color: white;
            padding: 10px 14px;
            margin: 8px 0;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .chat-option-card:hover {
            background-color: #0056b3;
        }

        .chat-option-icon {
            margin-right: 10px;
            font-size: 1.4rem;
        }

        .user-message,
        .bot-message {
            margin-bottom: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            max-width: 80%;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .user-message {
            background-color: #f1f1f1;
            align-self: flex-end;
        }

        .bot-message {
            background-color: #e3f2fd;
            align-self: flex-start;
        }

        .chat-option-card[disabled] {
            pointer-events: none;
            opacity: 0.6;
        }
    </style>
    @yield('custom-css')
</head>

<body>

    <!-- Header Section Begin -->
    @include('homepage.navigation_desktop')
    <!-- Header End -->

    @yield('APP-CONTENT')

    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="page-up">
            <a href="#" id="scrollToTopButton"><span class="arrow_carrot-up"></span></a>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="footer__logo">
                        <a href="#"><img src="{{ asset('homepage/img/logo.png') }}" alt=""
                                style="height: 40px;"></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="footer__nav">
                        <ul>
                            <li class="@yield('active-homepage')"><a href="{{ route('homepage-homepage') }}">Homepage</a></li>
                            <li class="@yield('active-categories')"><a href="{{ route('homepage-categories') }}">Categories</a>
                            </li>
                            <li class="@yield('active-experience')"><a href="{{ route('homepage-experience') }}">Experience</a>
                            </li>
                            <li class="@yield('active-contact')"><a href="{{ route('homepage-contact') }}">Contacts</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3">
                    <p>
                        Copyright &copy;
                        <script>
                            document.write(new Date().getFullYear());
                        </script> Tia Inday Haven Farm Resort <i class="fa fa-heart"
                            aria-hidden="true"></i>
                    </p>

                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

    <div class="chatbox-wrapper">
        <div class="chatbox" id="chatbox">
            <div
                class="chatbox-header d-flex justify-content-between align-items-center bg-primary text-white p-2 rounded-top">
                <span><i class="fa fa-commenting"></i> Chatbot</span>
                <span class="close-chat" style="cursor: pointer; font-size: 1.5rem;">&times;</span>
            </div>
            <div class="chatbox-body" id="chatboxBody"
                style="overflow-y: auto; max-height: 350px; -ms-overflow-style: none; scrollbar-width: none; padding: 10px; background-color: #f9f9f9;">
            </div>
        </div>
        <div class="chatbox-bubble" id="chatBubble">
            <i class="fa fa-comments"></i>
        </div>
    </div>

    <!-- Js Plugins -->
    <script src="{{ asset('homepage/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('homepage/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('homepage/js/player.js') }}"></script>
    <script src="{{ asset('homepage/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('homepage/js/mixitup.min.js') }}"></script>
    <script src="{{ asset('homepage/js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('homepage/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('homepage/js/Lobibox.js') }}"></script>
    <script src="{{ asset('js/input-mask/jasny-bootstrap.min.js') }}"></script>
    <script type="text/javascript">
        let step = 'default';
        let queryId = null;

        document.getElementById('chatBubble').addEventListener('click', function() {
            document.getElementById('chatbox').style.display = 'flex';
            this.style.display = 'none';
            fetchResponse('');
        });

        document.querySelector('.close-chat').addEventListener('click', function() {
            document.getElementById('chatbox').style.display = 'none';
            document.getElementById('chatBubble').style.display = 'block';
        });

        function appendMessage(text, isUser = false) {
            const body = document.getElementById('chatboxBody');
            const div = document.createElement('div');
            div.classList.add(isUser ? 'user-message' : 'bot-message');
            div.textContent = text;
            body.appendChild(div);
            body.scrollTop = body.scrollHeight;
        }

        function appendOptions(options) {
            const body = document.getElementById('chatboxBody');

            // Disable previous options by adding 'disabled' state
            const oldOptionsWrappers = body.querySelectorAll('.chat-options-wrapper');
            oldOptionsWrappers.forEach(wrapper => {
                wrapper.querySelectorAll('.chat-option-card').forEach(card => {
                    card.style.pointerEvents = 'none'; // Disable click
                    card.style.opacity = '0.6'; // Dim
                });
            });

            // Append new options
            const optionsWrapper = document.createElement('div');
            optionsWrapper.classList.add('chat-options-wrapper');

            options.forEach(option => {
                const card = document.createElement('div');
                card.classList.add('chat-option-card');

                card.onclick = () => handleOptionClick(option);

                const icon = document.createElement('i');
                icon.classList.add('fa', 'chat-option-icon');

                switch (option.value) {
                    case '1':
                        icon.classList.add('fa-ticket');
                        break;
                    case '2':
                        icon.classList.add('fa-home');
                        break;
                    case '3':
                        icon.classList.add('fa-bed');
                        break;
                    case 'back':
                        icon.classList.add('fa-arrow-circle-left');
                        break;
                    case 'bookHotel':
                        icon.classList.add('fa-calendar-check-o');
                        break;
                    case 'bookResort':
                        icon.classList.add('fa-calendar-check-o');
                        break;
                    default:
                        icon.classList.add('fa-circle-o');
                }

                const text = document.createElement('span');
                text.textContent = option.text;

                card.appendChild(icon);
                card.appendChild(text);
                optionsWrapper.appendChild(card);
            });

            body.appendChild(optionsWrapper);
            body.scrollTop = body.scrollHeight;
        }

        function handleOptionClick(option) {
            // Display user choice as chat message
            appendMessage(option.text, true);

            if (option.value === 'bookHotel') {
                window.location.href = "{{ route('homepage-bookHotel') }}";
                return;
            }

            if (option.value === 'bookResort') {
                window.location.href = "{{ route('homepage-bookResort') }}";
                return;
            }

            // Proceed to fetch bot response if not navigation
            fetchResponse(option.value);
        }

        function fetchResponse(message) {
            fetch('/guest/chatbot/response', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message,
                        step
                    })
                })
                .then(response => response.json())
                .then(data => {
                    appendMessage(data.response);
                    appendOptions(data.options);
                    step = data.nextStep;
                    queryId = data.queryId;
                });
        }

        function showInfoMessage(message) {
            Lobibox.notify('info', {
                title: 'Information',
                position: 'top right',
                iconSource: 'fontAwesome',
                msg: message
            });
        }

        function showSuccessMessage(message) {
            Lobibox.notify('success', {
                title: 'Success',
                position: 'top right',
                iconSource: 'fontAwesome',
                msg: message
            });
        }

        function showErrorMessage(message) {
            Lobibox.notify('error', {
                title: 'Error',
                position: 'top right',
                iconSource: 'fontAwesome',
                msg: message
            });
        }

        function showWarningMessage(message) {
            Lobibox.notify('warning', {
                title: 'Warning',
                position: 'top right',
                iconSource: 'fontAwesome',
                msg: message
            });
        }

        $(document).ready(function() {

            $('.set-bg').each(function() {
                var bg = $(this).data('setbg');
                $(this).css('background-image', 'url(' + bg + ')');
            });

            $(".mobile-menu").slicknav({
                prependTo: '#mobile-menu-wrap',
                allowParentLinks: true
            });

            $("#scrollToTopButton").click(function() {
                $("html, body").animate({
                    scrollTop: 0
                }, "slow");
                return false;
            });

            var hero_s = $(".hero__slider");
            hero_s.owlCarousel({
                loop: true,
                margin: 0,
                items: 1,
                dots: true,
                nav: true,
                navText: ["<span class='arrow_carrot-left'></span>",
                    "<span class='arrow_carrot-right'></span>"
                ],
                animateOut: 'fadeOut',
                animateIn: 'fadeIn',
                smartSpeed: 1200,
                autoHeight: false,
                autoplay: true,
                mouseDrag: false
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#logout').click(function(event) {
                event.preventDefault();

                $.ajax({
                    url: '{{ route('logoutAccount') }}',
                    type: 'POST',
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response.valid) {
                            window.location.href = '{{ route('homepage-homepage') }}';
                        }
                    },
                    error: function(jqXHR) {
                        if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                            let errors = jqXHR.responseJSON.errors;
                            let errorMsg = `${jqXHR.responseJSON.msg}\n`;
                            for (const [field, messages] of Object.entries(
                                    errors)) {
                                errorMsg += `- ${messages.join(', ')}\n`;
                            }
                            showErrorMessage(errorMsg);
                        } else {
                            showErrorMessage(
                                "An unexpected error occurred. Please try again."
                            );
                        }
                    }
                });
            });

        });
    </script>
    @yield('APP-SCRIPT')
</body>

</html>
