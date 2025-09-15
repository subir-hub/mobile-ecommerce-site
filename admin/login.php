<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require 'links.php' ?>
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa, #ffffffff);
            /* light and modern gradient */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 400px;
            background: #ffffff;
            /* white card for contrast */
        }

        .card-header {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            text-align: center;
            font-weight: bold;
            font-size: 1.5rem;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .form-control:focus {
            box-shadow: 0 0 5px rgba(38, 115, 255, 0.4);
            border-color: #2575fc;
        }

        .btn-custom {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
        }

        .alert {
            margin-top: 15px;
            padding: 12px 16px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-align: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .alert.success {
            background-color: #e6f9ec;
            border: 1px solid #4CAF50;
            color: #2d7a36;
        }

        .alert.error {
            background-color: #fdecea;
            border: 1px solid #f44336;
            color: #b71c1c;
        }

        .alert .icon {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-header">Admin Login</div>
        <div class="card-body">

            <!-- Login Form -->
            <form id="loginForm" autocomplete="off">
                <div class="mb-3">
                    <label for="loginEmail" class="form-label">Email</label> <span class="text-danger">*</span>
                    <input type="email" class="form-control" name="email" id="loginEmail" placeholder="example@mail.com">
                    <p id="loginEmailError" class="error text-danger ms-1"></p>
                </div>
                <div class="mb-3">
                    <label for="loginPassword" class="form-label">Password</label> <span class="text-danger">*</span>
                    <input type="password" class="form-control" name="password" id="loginPassword" placeholder="Enter password">
                    <p id="loginPasswordError" class="error text-danger ms-1"></p>
                </div>
                <button type="submit" class="btn btn-custom w-100" id="loginBtn">Login</button>

                <div id="loginMsg" class="mt-3"></div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("input").on("keyup", function() {
                $(this).parent().find(".error").text("");
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
                        url: "admin.php",
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
        })
    </script>
</body>

</html>