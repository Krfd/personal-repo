<?php

require "csrf.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Office Personal Repository - Register</title>
    <link rel="icon" href="img/city-gov-logo.png">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/custom.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

</head>

<body class="bg-gradient-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="card-body">
                                <div class="card-body">
                                    <div class="px-4 py-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-grey-900 mb-4">Create Account</h1>
                                        </div>

                                        <form method="POST" class="user" enctype="multipart/form-data" id="create_account">
                                            <!-- <?php CSRF::create_token(); ?> -->
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-user" name="fullname" id="fullname" aria-checked="" placeholder="Full Name" autocomplete="off" data-toggle="tooltip" minlength="3" maxlength="50" required title="Full name should not be less than 5 characters and not greater than 50 characters.">
                                            </div>

                                            <div class="form-group">
                                                <input type="email" class="form-control form-control-user" name="email" id="email" placeholder="Email address" autocomplete="off" data-toggle="tooltip" required maxlength="40" title="Please enter a valid email address.">
                                            </div>

                                            <div class="form-group">
                                                <input type="tel" name="phone" class="form-control form-control-user" id="phone" minlength="11" maxlength="11" placeholder="Contact" required>
                                            </div>

                                            <div class="form-group d-block d-md-flex align-items-center">
                                                <input type="password" class="form-control form-control-user mr-0 mr-md-1" name="password" id="password" placeholder="Password" autocomplete="off" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-toggle="tooltip" title="Must contain at least one number and one UPPERCASE, and lowercase letter, and at least 8 or more characters." minlength="8" maxlength="20" />
                                                <input type="password" class="form-control form-control-user mt-3 mt-md-0 ml-0 ml-md-1" name="confirm" id="confirm" placeholder="Confirm Password" autocomplete="off" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-toggle="tooltip" title="Must contain at least one number and one UPPERCASE, and lowercase letter, and at least 8 or more characters." minlength="8" maxlength="20" />
                                            </div>
                                            <input type="submit" class="btn btn-primary btn-user btn-block mt-3" name="createaccount" value="Create account">
                                            <hr>
                                            <p class="text-center">Have an account? <a href="index.php">Login here</a>.</p>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#create_account").submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    type: "POST",
                    url: "register.php",
                    data: formData,
                    success: function(response) {
                        switch (response) {
                            case 'true':
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Account Created!',
                                    text: 'Your account has been created successfully.',
                                    confirmButtonColor: "#4e73df",
                                    confirmButtonText: "Login",
                                }).then(() => {
                                    window.location.href = 'index.php';
                                });
                                break;
                            case 'emailExist':
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Email already exists.',
                                    text: 'Please use another email address.',
                                    confirmButtonColor: "#4e73df",
                                    confirmButtonText: "Try Again",
                                }).then(() => {
                                    window.location.href = 'createaccount.php';
                                });
                                break;
                            case 'unmatched':
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Password does not match.',
                                    text: 'Please try again.',
                                    confirmButtonColor: "#4e73df",
                                    confirmButtonText: "Try Again",
                                }).then(() => {
                                    window.location.href = 'createaccount.php';
                                });
                                break;
                            case "invalidCSRFToken.":
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Invalid CSRF Token.',
                                    text: 'Please try again.',
                                    confirmButtonColor: "#4e73df",
                                    confirmButtonText: "Try Again",
                                }).then(() => {
                                    window.location.href = 'createaccount.php';
                                });
                                break;
                            default:
                                Swal.fire({
                                    icon: 'error',
                                    title: response,
                                    confirmButtonColor: "#4e73df",
                                    confirmButtonText: "Continue",
                                }).then(() => {
                                    window.location.href = 'createaccount.php';
                                });
                        }
                    }
                });

                return false;
            });
        });

        function updateFileName() {
            var input = document.getElementById('customFile');
            var fileName = input.files[0].name;
            var label = document.querySelector('.custom-file-label');
            label.textContent = fileName;
        }
    </script>
</body>

</html>