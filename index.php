<?php

// Generate a random key and token
$key = bin2hex(random_bytes(64));
$token = hash_hmac('sha256', '', $key);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Office Personal Repository</title>
    <link rel="icon" type="icon" href="img/city-gov-logo.png" />
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet" />

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
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
                                            <h1 class="h4 text-grey-900 mb-4">
                                                Office Personal Repository
                                            </h1>
                                        </div>
                                        <!-- CSRF Token -->
                                        <form method="POST" class="user" id="login_form">
                                            <input type="hidden" name="csrfToken" value="<?php echo $token; ?>">
                                            <input type="hidden" name="key" value="<?php echo $key; ?>">
                                            <div class="form-group">
                                                <input type="email" class="form-control form-control-user" name="email" id="email" placeholder="Email address" autocomplete="off" required title="Email address" />
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control form-control-user" name="password" id="password" placeholder="Password" autocomplete="off" required data-toggle="tooltip" title="Must contain at least one number and one UPPERCASE, and lowercase letter, and at least 8 or more characters." pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" minlength="8" maxlength="20" />
                                            </div>
                                            <div class="form-group ml-2">
                                                <a href="forgot_password_page.php">Forgot password?</a>
                                            </div>

                                            <input type="submit" class="btn btn-primary btn-user btn-block" name="login" value="Log In" />

                                            <hr />
                                            <p class="text-center">
                                                Don't have an account?
                                                <a href="createaccount.php">Create an account!</a>
                                            </p>
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
            $("#login_form").submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    type: "POST",
                    url: "login.php",
                    data: formData,
                    success: function(response) {
                        switch (response) {
                            case "valid":
                                Swal.fire({
                                    icon: "success",
                                    title: "Welcome!",
                                    text: "Access Granted",
                                    confirmButtonColor: "#4e73df",
                                    confirmButtonText: "Continue",
                                }).then(() => {
                                    var delay = 100;
                                    setTimeout(function() {
                                        window.location = "storage.php";
                                    }, delay);
                                });
                                break;
                            case "admin":
                                Swal.fire({
                                    icon: "success",
                                    title: "Welcome Admin!",
                                    text: "Access Granted",
                                    confirmButtonColor: "#4e73df",
                                    confirmButtonText: "Countinue",
                                }).then(() => {
                                    window.location.href = "admin/admin_page.php";
                                });
                                break;
                            case "sub_admin":
                                Swal.fire({
                                    icon: "success",
                                    title: "Welcome Admin!",
                                    text: "Access Granted",
                                    confirmButtonColor: "#4e73df",
                                    confirmButtonText: "Countinue",
                                }).then(() => {
                                    window.location.href = "admin/sub_admin_page.php";
                                });
                                break;
                            case "banned":
                                Swal.fire({
                                    icon: "error",
                                    title: "Your account has been banned.",
                                    text: "Access Denied",
                                    confirmButtonColor: "#4e73df",
                                    confirmButtonText: "Try Again",
                                }).then(() => {
                                    window.location.href = "index.php";
                                });
                                break;
                            case "unknown":
                                Swal.fire({
                                    icon: "error",
                                    title: "Invalid email or password",
                                    text: "Please check your email or password.",
                                    confirmButtonColor: "#4e73df",
                                    confirmButtonText: "Try Again",
                                }).then(() => {
                                    window.location.href = "index.php";
                                });
                                break;
                            case "notfound":
                                Swal.fire({
                                    icon: "error",
                                    title: "Account not found",
                                    text: "Please register an account first.",
                                    confirmButtonColor: "#4e73df",
                                    confirmButtonText: "Try Again",
                                }).then(() => {
                                    window.location.href = "index.php";
                                });
                                break;
                            case "invalidCSRFToken":
                                Swal.fire({
                                    icon: "error",
                                    title: "Login Failed",
                                    text: "Invalid CSRF Token",
                                    confirmButtonColor: "#4e73df",
                                    confirmButtonText: "Try Again",
                                }).then(() => {
                                    window.location.href = "index.php";
                                });
                                break;
                            default:
                                Swal.fire({
                                    icon: "error",
                                    title: response,
                                    confirmButtonColor: "#4e73df",
                                    confirmButtonText: "Try Again",
                                }).then(() => {
                                    window.location.href = "index.php";
                                });

                        }
                    },
                });
                return false;
            });

        });
    </script>
</body>

</html>