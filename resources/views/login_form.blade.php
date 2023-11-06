@extends('layouts.layout') <!-- Assuming you have a 'layouts' folder with layout.blade.php inside -->

@section('title', 'Feedback Form')


    <style>
        body {
            background-color: #f4f4f4;
            background-image: url({{ asset('images/login-bg.jpg') }})
        }

        .container {
            margin-top: 50px;
        }

        .form-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .switch-form {
            margin-top: 10px;
            text-align: center;
        }

        .switch-form a {
            color: #007bff;
            text-decoration: none;
        }

        .switch-form a:hover {
            text-decoration: underline;
        }

        .signup-form {
            display: none;
        }
    </style>
</head>
@section('content')

<body>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-6">
                <div class="form-container">
                    {{-- Login Form --}}
                    <form id="login-form">
                        <h4 class="text-center">Login Form</h4>

                        <div class="form-group">
                            <label for="login_email">Email:</label>
                            <input type="text" class="form-control" id="login_email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="login_password">Password:</label>
                            <input type="password" class="form-control" id="login_password" name="password" required>
                        </div>

                        <button type="submit" class="btn btn-success">Login</button>
                    </form>

                    {{-- Signup Form --}}
                    <form id="signup-form" class="signup-form">
                        <h4 class="text-center">Signup Form</h4>
                        <div class="form-group">
                            <label for="full_name">Full Name:</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                        </div>

                        <div class="form-group">
                            <label for="signup_email">Email:</label>
                            <input type="email" class="form-control" id="signup_email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="signup_password">Password:</label>
                            <input type="password" class="form-control" id="signup_password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="">Confirm Password:</label>
                            <input type="password" class="form-control" id="" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Sign Up</button>
                    </form>

                    <div class="switch-form">
                        <p>Don't have an account? <a href="#" onclick="toggleForm()">Sign up</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('scripts')
   <script>
        let loginForm = $('#login-form');
        let signupForm = $('#signup-form');
        let signupLink = $('.switch-form a');

        function toggleForm() {
            if (loginForm.is(':visible')) {
                loginForm.hide();
                signupForm.show();
                signupLink.text("Already have an account? Login");
            } else {
                loginForm.show();
                signupForm.hide();
                signupLink.text("Don't have an account? Sign up");
            }
        }
        let path = window.location.pathname;
        if (path.endsWith('/login')) {
            loginForm.show();
            signupForm.hide();
        } else {
            signupForm.show();
            loginForm.hide();
        }

        $(document).ready(function() {
            $('#login-form').on('submit', function(e) {
                $('.form-errors').remove();
                $('.border-danger').removeClass('border-danger');
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    method: "POST",
                    url: "{{ route('login-user') }}",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: formData,
                    success: function(data) {
                        if (!data.success) {
                            $('#login-form').find('#login_email').after(
                                '<p class="text-danger form-errors">' + data.errors + '</p>'
                                );
                        } else {
                            toastr.success(
                                    'Success!',
                                    'You have Succefully Logged in', {
                                        positionClass: 'toast-bottom-right'
                                    }
                                );
                            window.location.href = "{{ url('/') }}";
                        }
                    },
                    error: function(response) {
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;

                            $.each(errors, function(field, messages) {
                                var input = $('[name="' + field + '"]');
                               $('#signup-form').find(input).addClass('border-danger')
                                var errorList = $(
                                    '<ul class="text-danger form-errors"></ul>');
                                $.each(messages, function(index, message) {
                                    errorList.append($('<li></li>').text(
                                        message));
                                });
                                $('#signup-form').find(input).closest('.form-group').append(errorList);
                            });
                        }
                    }
                });
            });
            $('#signup-form').on('submit', function(e) {
                $('.form-errors').remove();
                $('.border-danger').removeClass('border-danger');
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    method: "POST",
                    url: "{{ route('register-user') }}",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: formData,
                    success: function(data) {
                        if (!data.success) {
                            $('#signup-form').find('#login_email').after(
                                '<p class="text-danger form-errors">' + data.errors + '</p>'
                                );
                        } else {
                            toastr.success(
                                    'Success!',
                                    'Your account has been registered!', {
                                        positionClass: 'toast-bottom-right'
                                    }
                                );
                            setTimeout(() => {
                            window.location.href = "{{ url('/login') }}";
                                
                            }, 2000);

                        }
                    },
                    error: function(response) {
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;

                            $.each(errors, function(field, messages) {
                                var input = $('[name="' + field + '"]');
                                $('#signup-form').find(input).addClass('border-danger')
                                var errorList = $(
                                    '<ul class="text-danger form-errors"></ul>');
                                $.each(messages, function(index, message) {
                                    errorList.append($('<li></li>').text(
                                        message));
                                });
                                $('#signup-form').find(input).closest('.form-group').append(errorList);
                            });
                        }
                    }
                });
            });

        })
    </script>
@endsection
