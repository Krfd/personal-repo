<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Profile</h5>
            </div>
            <div class="modal-body">
                <form action="update_profile.php" method="POST" class="user" enctype="multipart/form-data" id="update_profile">
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
                            <input type="tel" class="form-control form-control-user" name="updatephone" id="updatephone" minlength="11" maxlength="11" value="<?php echo $user['phone']; ?>" autocomplete="off">
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
                <button id="save_btn" class="btn btn-primary" type="submit" name="edit" disabled><i class="fas fa-check"></i> Save</button>
            </div>
            </form>
        </div>
    </div>
</div>