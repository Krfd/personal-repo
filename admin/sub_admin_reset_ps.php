<?php
include('../pdoaction.php');
include "layout/header.php";
$key = bin2hex(random_bytes(64));
$token = hash_hmac('sha256', '', $key);
?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper" style="height: 100vh;">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item mt-2 my-0">
                <!-- the original -->
                <a class="nav-link collapsed btn" id="collapseProfile" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <img class="img-profile rounded-circle object-fit-cover" src="<?php
                                                                                    if ($profile == "") {
                                                                                        echo "./img/random.jpg";
                                                                                    } else {
                                                                                        echo "profile/" . $profile;
                                                                                    }
                                                                                    ?>" alt="<?php echo $fullname ?>">
                    <span class="small mt-3"><?php echo $fullname; ?></span>

                </a>

                <!-- The original collapse -->
                <div id="collapseTwo" class="collapse mx-2" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" data-toggle="modal" data-target="#profile">Profile</a>
                    </div>
                </div>


                <!-- Edit Modal -->
                <div class="modal fade" id="profile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Profile</h5>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <img src="<?php
                                                if ($profile == "") {
                                                    echo "./img/random.jpg";
                                                } else {
                                                    echo "profile/" . $profile;
                                                } ?>" class="rounded-circle object-fit-cover shadow-lg w-25 h-auto mb-3" alt="<?php echo $fullname ?>">
                                    <div class="text-start">
                                        <h1 class="font-weight-bold"><?php echo $fullname; ?></h1>
                                        <p>Email: <?php echo $email ?></p>
                                        <p>Contact: <?php echo $phone ?></p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa">&#xf00d;</i> Cancel</button>
                                    <a class="btn btn-primary" data-toggle="modal" data-target="#edit"> <i class="fas fa-pen"></i> Edit</a>
                                </div>
                            </div>
                        </div>
                    </div>
            </li>

            <!--Update profile Modal-->
            <?php
$get_user = $conn->prepare("SELECT * FROM client WHERE client_id = :client_id");
$get_user->bindParam(':client_id', $client_id);
$get_user->execute();

$user = $get_user->fetch(PDO::FETCH_ASSOC);

if ($user) {
?>
    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Profile</h5>
                </div>
                <div class="modal-body">

                    <form action="admin_function/sub_admin_update_profile.php" method="POST" class="user" enctype="multipart/form-data" id="update_profile">

                        <input type="hidden" name="client_id" value="<?php echo $user['client_id']; ?>">
                        <div class="form-group">
                            <label>Profile picture</label>
                            <div class=" custom-file">
                                <input type="file" class="custom-file-input btn rounded-pill" name="updateProfile" id="updateProfile" value="<?php echo $user['profile']; ?>" onchange="updateFileName()">
                                <script>
                                    function updateFileName() {
                                        // Get the input element and its value
                                        var input = document.getElementById('updateProfile');
                                        var fileName = input.files[0].name;

                                        // Update the label with the file name
                                        var label = document.querySelector('.custom-file-label');
                                        label.textContent = fileName;
                                    }
                                </script>
                                <label class="custom-file-label btn d-flex float-left rounded-pill" for="updateProfile"><?php echo empty($user['profile']) ? "Upload Image" : $user['profile']; ?></label>
                            </div>
                        </div>

                        <div class="row d-flex justify-content-between mx-3">
                            <div class="form-group">
                                <label for="updatefullname">Full name</label>
                                <input autocomplete="off" type="text" class="form-control form-control-user" name="updatefullname" id="updateFullname" value="<?php echo $user['fullname']; ?>" title="Full name">
                            </div>

                            <div class="form-group">
                                <label for="updateemail">Email address</label>
                                <input autocomplete="off" type="email" class="form-control form-control-user" name="updateemail" id="email" value="<?php echo $user['email']; ?>" title="Email address">
                            </div>

                            <div class="form-group">
                                <label for="updatephone">Phone</label>
                                <input type="tel" class="form-control form-control-user" name="updatephone" id="updatephone" value="<?php echo $user['phone']; ?>" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label for="old_password">Old Password</label>
                                <input type="password" class="form-control form-control-user password-field" name="old_password" id="old_password" autocomplete="off" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Enter your old password">
                            </div>

                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" class="form-control form-control-user password-field" name="new_password" id="new_password" autocomplete="off" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one UPPERCASE">
                            </div>

                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" class="form-control form-control-user password-field" name="confirm_password" id="confirm_password" autocomplete="off" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Need to match your new password">
                            </div>

                            <div class="form-group">
                                <p class="mx-3 mt-2"><input type="checkbox" onclick="myFunction()"> Show Password</p>
                            </div>

                            <script>
                               document.addEventListener('DOMContentLoaded', function() {
    // Get original values
    var originalValues = {
        updatefullname: '<?php echo $user['fullname']; ?>',
        updateemail: '<?php echo $user['email']; ?>',
        updatephone: '<?php echo $user['phone']; ?>',
        old_password: '',
        new_password: '',
        confirm_password: ''
    };

    // Add event listener to input fields
    var inputFields = document.querySelectorAll('input');
    inputFields.forEach(function(input) {
        input.addEventListener('input', function() {
            var isChanged = false;
            inputFields.forEach(function(input) {
                if (input.type !== 'file' && input.value !== originalValues[input.name]) {
                    isChanged = true;
                }
            });
            var saveButton = document.getElementById("save_btn");
            if (isChanged) {
                saveButton.disabled = false;
            } else {
                saveButton.disabled = true;
            }
        });
    });
});

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
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa">&#xf00d;</i> Cancel</button>
                    <button id="save_btn" class="btn btn-primary" type="submit" name="edit"  disabled><i class="fas fa-check"></i> Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>

<?php

} else {
    echo "User not found.";
}
?>


            <hr class="sidebar-divider text-white">

            <div class="sidebar-heading mb-2">OPR Admin</div>

            <li class="nav-item link m-1 rounded">
                <a class="nav-link" href="sub_admin_page.php">
                    <i class="fas fa-users"></i>
                    <span>Accounts</span>
                </a>
            </li>
           

            <li class="nav-item link bg-light m-1 rounded mb-5">
                <a class="nav-link  text-secondary" href="sub_admin_reset_ps.php">
                    <i class="fas fa-key  text-secondary"></i>
                    <span>Reset Password</span>
                </a>
            </li>
           
            <li class="nav-item link mx-1 mb-1 rounded mt-5">
                <a class="nav-link" href="../pdoaction.php?logout=true">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw"></i>
                    <span>Logout</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider text-white">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <div class="text-center mt-auto text-white mb-3">
                <small id="date"></small>
                <br>
                <small id="time"></small>
            </div>

            <script>
                // Date and Time
                const date = document.querySelector("#date");
                const time = document.querySelector("#time");

                setInterval(() => {
                    const options = {
                        weekday: "long",
                        year: "numeric",
                        month: "long",
                        day: "numeric"
                    };

                    const today = new Date();

                    date.innerHTML = today.toLocaleDateString("en-US", options);
                    time.innerHTML = today.toLocaleTimeString("en-US");
                }, 1000);
            </script>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content" class="bg-gradient-light">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                 
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto ">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="sub_admin_page.php">
                                <img class="h-75 w-auto m-2" src="../img/city-gov-logo.png" />
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- Body Content -->
                <div class="container-fluid mx-auto">
                    <div class="header">
                        <h1 class="font-weight-semibold">Reset Password</h1>
                    </div>
                  
                    <?php
$get_user = $conn->prepare("SELECT * FROM client ORDER BY client_id DESC");
$get_user->execute();
?>

<div class="container">

    <div class="table-responsive">
      
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Account ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Profile</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="myList">
                <?php while ($user = $get_user->fetch(PDO::FETCH_ASSOC)) { ?>
                    <?php if ($user['is_admin'] != 0 && $user['is_deleted'] != 1) { ?>
                        <tr>
                            <td><?php echo $user['client_id']; ?></td>
                            <td><?php echo $user['fullname']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['profile']; ?></td>
                            <td><?php echo $user['phone']; ?></td>
                            <td>
                                <!-- Change password button -->
                                            
<a href="#" class="btn btn-success change_pass" id="change_pass_<?php echo $user['client_id']; ?>" data-toggle="modal" data-target="#change_pass_modal_<?php echo $user['client_id']; ?>" title="Change password">
    <i class="fas fa-key"></i> 
</a>

<div class="modal fade" id="change_pass_modal_<?php echo $user['client_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="changePassModalLabel_<?php echo $user['client_id']; ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePassModalLabel_<?php echo $user['client_id']; ?>">Change password of <?php echo $user['fullname']; ?></h5>
            </div>
            <div class="modal-body">

                <form class="user password_change_form" id="password_change_form_<?php echo $user['client_id']; ?>" method="post">
                    <input type="hidden" name="csrfToken" value="<?php echo $token; ?>">
                    <input type="hidden" name="key" value="<?php echo $key; ?>">
                    <input type="hidden" name="client_id" value="<?php echo $user['client_id']; ?>">
                    <div class="form-group">

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
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> 
                         
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>



                    
                </div>
            </div>
            <!-- End of Main Content -->

            <?php include "layout/footer.php" ?>

        </div>
        <!-- End of Content Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Add event listener to category dropdown items
                var categoryLinks = document.querySelectorAll('#categoryDropdown a');
                var fileItems = document.querySelectorAll('#myList li');
                // var folderItems = document.querySelectorAll('#myFolder li');

                categoryLinks.forEach(function(link) {
                    link.addEventListener('click', function(event) {
                        event.preventDefault();
                        var selectedCategory = link.textContent.trim();

                        fileItems.forEach(function(item) {
                            var itemCategory = item.getAttribute('data-category');
                            if (selectedCategory === 'All' || selectedCategory === itemCategory) {
                                item.style.display = 'block';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    });
                });
            });

            $('#collapseProfile').click(function() {
                $('#collapseTwo').toggle(500);
            });

            $('#toggleCategory').click(function() {
                $('#categoryDropdown').toggle(300);
            });

            $('.dropdown-item').click(function() {
                $('#categoryDropdown').hide();
            });
        </script>
        
        <script>
$(document).ready(function() {
    $(".password_change_form").submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            type: "POST",
            url: "admin_function/admin_reset_ps_func.php",
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
                            text: "Change password successfully.",
                            confirmButtonColor: "#4e73df",
                            confirmButtonText: "Continue",
                        }).then(() => {
                            var delay = 100;
                            setTimeout(function() {
                                window.location = "sub_admin_reset_ps.php";
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
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
        return false;
    });
});
</script>
        <?php include "layout/scripts.php" ?>
</body>

</html>
