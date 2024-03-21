<?php
include('pdoaction.php');
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
                                <a href="forgot_password_page.php" class="btn btn-primary circle-btn rounded-circle">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                    <div class="px-4 py-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-grey-900 mb-4">
                                           Create new password 
                                            </h1>
                                        </div>
                                        <!-- CSRF Token -->
                                        <form method="POST" class="user" id="login_form">
    <input type="hidden" name="csrfToken" value="<?php echo $token; ?>">
    <input type="hidden" name="key" value="<?php echo $key; ?>">
    <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">

    <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" class="form-control form-control-user password-field" name="new_password" id="new_password" autocomplete="off" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one UPPERCASE">
                            </div>

                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" class="form-control form-control-user password-field" name="confirm_password" id="confirm_password" autocomplete="off" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Need to match your new password">
                            </div>

    <div class="form-group">
        <p class="ml-2"><input type="checkbox" onclick="myFunction()"> Show Password</p>
    </div>

    <input type="submit" class="btn btn-primary btn-user btn-block" name="login" value="Save" />
</form>

<script>
    function myFunction() {
        var x = document.getElementsByClassName("password-field");
        for (var i = 0; i < x.length; i++) {
            if (x[i].type === "password") {
                x[i].type = "text";
            } else {
                x[i].type = "password";
            }
        }
    }
</script>
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
            url: "forgot_password_cp_func.php",
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
                    case "successfully":
                        Swal.fire({
                            icon: "success",
                            title: "You have a new password!",
                            text: "Click continue to log in.",
                            confirmButtonColor: "#4e73df",
                            confirmButtonText: "Continue",
                        }).then(() => {
                                    var delay = 100;
                                    setTimeout(function() {
                                        window.location = "index.php";
                                    }, delay);
                                });
                        break;
                    case "unmatch":
                        Toast.fire({
                            icon: 'error',
                            title: 'Please match your password.',
                            iconColor: '#dc3545', 
                        });
                        break;
                    case "not_found":
                        Toast.fire({
                            icon: 'error',
                            title: 'Please provide a new password!',
                            iconColor: '#dc3545', 
                        });
                        break;
                    case "invalidCSRFToken":
                        Toast.fire({
                            icon: 'error',
                            title: 'Creating new password failed.',
                            text: "Invalid CSRF Token",
                            iconColor: '#dc3545', 
                        });
                        break;
                    default:
                        Toast.fire({
                            icon: 'error',
                            title: 'Please provide a new password!',
                            iconColor: '#dc3545', 
                        });
                        break;
                }
            },
        });
        return false;
    });

});
    </script>
</body>

</html>
