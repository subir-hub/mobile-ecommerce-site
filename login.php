<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup & Login</title>
    <?php require 'includes/links.php' ?>

    <link rel="stylesheet" href="./assets/css/login.css">


</head>

<body>

    <?php require 'includes/header.php' ?>

    <div class="card">
        <div class="card-header">Welcome</div>
        <div class="card-body">
            <!-- Signup Form -->
            <form id="signupForm" autocomplete="off">
                <div class="mb-3">
                    <label for="signupName" class="form-label">Name</label> <span class="text-danger">*</span>
                    <input type="text" class="form-control" name="signupName" id="signupName" placeholder="Your Name">
                    <p id="signupNameError" class="error text-danger ms-1"></p>
                </div>
                <div class="mb-3">
                    <label for="signupEmail" class="form-label">Email</label> <span class="text-danger">*</span>
                    <input type="email" class="form-control" name="signupEmail" id="signupEmail" placeholder="example@mail.com">
                    <p id="signupEmailError" class="error text-danger ms-1"></p>
                </div>
                <div class="mb-3">
                    <label for="signupPassword" class="form-label">Password</label> <span class="text-danger">*</span>
                    <input type="password" class="form-control" name="signupPassword" id="signupPassword" placeholder="Enter password">
                    <p id="signupPasswordError" class="error text-danger ms-1"></p>
                </div>
                <button type="submit" class="btn btn-custom w-100" id="signupBtn">Sign Up</button>
                <p class="mt-3 text-center">Already have an account? <span class="toggle-link" onclick="toggleForms()">Login</span></p>

                <div id="signupMsg" class="mt-3"></div>

            </form>

            <!-- Login Form -->
            <form id="loginForm">
                <div class="mb-3">
                    <label for="loginEmail" class="form-label">Email</label> <span class="text-danger">*</span>
                    <input type="email" class="form-control" name="loginEmail" id="loginEmail" placeholder="example@mail.com">
                    <p id="loginEmailError" class="error text-danger ms-1"></p>
                </div>
                <div class="mb-3">
                    <label for="loginPassword" class="form-label">Password</label> <span class="text-danger">*</span>
                    <input type="password" class="form-control" name="loginPassword" id="loginPassword" placeholder="Enter password">
                    <p id="loginPasswordError" class="error text-danger ms-1"></p>
                </div>
                <button type="submit" class="btn btn-custom w-100" id="loginBtn">Login</button>
                <p class="mt-3 text-center">Don't have an account? <span class="toggle-link" onclick="toggleForms()">Sign Up</span></p>

                <div id="loginMsg" class="mt-3"></div>
            </form>
        </div>
    </div>

    <script>
        function toggleForms() {
            $('#signupForm, #loginForm').toggle(); // toggles both forms
        }

        $(document).ready(function() {
            // By default, show login and hide signup
            $('#loginForm').show();
            $('#signupForm').hide();

            $("input").on("keyup", function() {
                $(this).parent().find(".error").text("");
            })

            $("#signupForm").on("submit", function(e) {
                e.preventDefault();

                let signupName = $("#signupName").val().trim();
                let signupEmail = $("#signupEmail").val();
                let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                let signupPassword = $("#signupPassword").val();
                let flag = true;

                if (signupName == '') {
                    $("#signupNameError").text("Name is required");
                    flag = false;
                }

                if (signupEmail == '' || !emailRegex.test(signupEmail)) {
                    $("#signupEmailError").text("Enter a valid email");
                    flag = false;
                }

                if (signupPassword == '' || signupPassword.length < 6) {
                    $("#signupPasswordError").text("Password must be at least 6 characters");
                    flag = false;
                }

                if (flag) {

                    let signupFormData = $("#signupForm").serialize();

                    $.ajax({
                        type: "POST",
                        url: "ajax/auth.php",
                        data: signupFormData + '&action=signup',
                        dataType: "json",
                        success: function(response) {
                            if (response.code === 200) {
                                $("#signupMsg").html(`<div class="alert success"><span class="icon">✔</span> ${response.msg}</div>`).fadeIn().delay(4000).fadeOut();

                                $("#signupForm")[0].reset();
                            } else {
                                $("#signupMsg").html(`<div class="alert error"><span class="icon">⚠</span> ${response.msg}</div>`).fadeIn().delay(4000).fadeOut();

                                $("#signupForm")[0].reset();
                            }

                        },
                        error: function(status, error, xhr) {
                            $("#signupMsg").html(`<div class="alert error"><span class="icon">⚠</span> Something went wrong! Please try again later</div>`).fadeIn().delay(4000).fadeOut();

                            $("#signupForm")[0].reset();

                            console.error(error);
                        }
                    });
                }
            });

            $("#loginForm").on("submit", function(e) {
                e.preventDefault();

                let loginEmail = $("#loginEmail").val();
                let loginPassword = $("#loginPassword").val();
                let valid = true;

                if (loginEmail == '') {
                    $("#loginEmailError").text("Email is required");
                    valid = false;
                }

                if (loginPassword == '') {
                    $("#loginPasswordError").text("Password is required");
                    valid = false;
                }

                if (valid) {

                    let loginFormData = $("#loginForm").serialize();

                    $.ajax({
                        type: "POST",
                        url: "ajax/auth.php",
                        data: loginFormData + '&action=login',
                        dataType: "json",
                        success: function(response) {
                            if (response.code === 200) {
                                $("#loginMsg").html(`<div class="alert success"><span class="icon">✔</span> ${response.msg}</div>`).fadeIn().delay(4000).fadeOut();

                                $("#loginForm")[0].reset();

                                setTimeout(() => {
                                    window.location.href = './index.php';
                                }, 3000);
                            } else {
                                $("#loginMsg").html(`<div class="alert error"><span class="icon">⚠</span> ${response.msg}</div>`).fadeIn().delay(4000).fadeOut();

                                $("#loginForm")[0].reset();
                            }
                        },
                        error: function(status, error, xhr) {
                            $("#loginMsg").html(`<div class="alert error"><span class="icon">⚠</span> Something went wrong! Please try again later</div>`).fadeIn().delay(4000).fadeOut();

                            $("#loginForm")[0].reset();

                            console.error(error);
                        }
                    });
                }
            });

        });
    </script>

</body>

</html>