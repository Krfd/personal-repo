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
                                <a href="index.php" class="btn btn-primary circle-btn rounded-circle">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                    <div class="px-4 py-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-grey-900 mb-4">
                                            Confirm email address
                                            </h1>
                                        </div>
                                        <!-- CSRF Token -->
                                        <form method="POST" class="user" id="login_form">
                                            <input type="hidden" name="csrfToken" value="<?php echo $token; ?>">
                                            <input type="hidden" name="key" value="<?php echo $key; ?>">
                                            <div class="form-group">
                                                <input type="email" class="form-control form-control-user" name="email" id="email" placeholder="Email address" autocomplete="off" required title="Email address" />
                                            </div>
                                          
                                            <input type="submit" class="btn btn-primary btn-user btn-block " name="login" value="Next" />
                                          
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
                    url: "forgot_password_func.php",
                    data: formData,
                    success: function(response) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top',
                            customClass: {
                                popup: 'colored-toast',
                            },
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: true,
                        });
                        switch (response) {
                            case "valid":
                        Toast.fire({
                                    icon: 'success',
                                    title: 'Your account has been verified.',
                                    iconColor: '#28a745', 
                                }).then(() => {
                                    var delay = 100;
                                    setTimeout(function() {
                                        window.location = "forgot_password_aq.php";
                                    }, delay);
                                });
                                break;
                                case "admin":
                                    Toast.fire({
                                    icon: 'error',
                                    title: 'Email not found',
                                    text: "Please check your email.",
                                    iconColor: '#dc3545', 
                                });
                                break;
                                case "banned":
                                Toast.fire({
                                    icon: "error",
                                    title: "Your account has been banned.",
                                    text: "Access Denied",
                                    iconColor: '#dc3545',
                                });
                                break;
                                case "invalidCSRFToken":
                                    Toast.fire({
                                    icon: 'error',
                                    title: 'Confirm email failed.',
                                    text: "Invalid CSRF Token",
                                    iconColor: '#dc3545', 
                                });
                                break;
                            default:
                            Toast.fire({
                                    icon: 'error',
                                    title: 'Email not found',
                                    text: "Please check your email.",
                                    iconColor: '#dc3545', 
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
