<?php

// Connection for server and database
$dsn = "mysql:host=localhost;dbname=uiojt2023";
$username = "root";
$password = "";

try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

session_start();
function redirectTo($location)
{
    header("Location: $location");
    exit();
}

// Profile update function
if (isset($_POST['edit'])) {
    $client_id = htmlspecialchars($_POST['client_id']);
    $fullname = htmlspecialchars($_POST['updatefullname']);
    $email = htmlspecialchars($_POST['updateemail']);
    $phone = htmlspecialchars($_POST['updatephone']);
    $old_password = htmlspecialchars($_POST['old_password']);
    $new_password = htmlspecialchars($_POST['new_password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    $password_provided = !empty($old_password) || !empty($new_password);

    // Update profile information
    try {
       
        if (!empty($_FILES['updateProfile']['name'])) {
            $type = "Document";
            $updateProfile = $_FILES['updateProfile']['name'];
            $fileTmpName = $_FILES['updateProfile']['tmp_name'];
            $fileExt = explode('.', $updateProfile);
            $fileActualExt = strtolower(end($fileExt));
            $allowed = array('jpg', 'jpeg', 'png', 'svg');

            if (in_array($fileActualExt, $allowed)) {
                $newProfile = $updateProfile;
                $dir_path = "../profile/" . $updateProfile;

                move_uploaded_file($fileTmpName, $dir_path);
            } else {
                echo "<script>
                alert('Invalid file type. Only JPG, JPEG, PNG, and SVG files are allowed.');
                window.location.href = '../admin_page.php';
              </script>";
               
            }
        } else {
            // If the profile picture is exisitng
            $getProfile = "SELECT profile FROM client WHERE client_id = :client_id";
            $stmt = $conn->prepare($getProfile);
            $stmt->bindParam(':client_id', $client_id);
            $stmt->execute();
            $existingProfile = $stmt->fetchColumn();

            $newProfile = $existingProfile;
            $dir_path = "profile/" . $existingProfile;
        }

        // For updating password
        if ($password_provided) {
          
            $stmt = $conn->prepare("SELECT password FROM client WHERE client_id = :client_id");
            $stmt->bindParam(':client_id', $client_id);
            $stmt->execute();
            $hashed_password = $stmt->fetchColumn();

            if (!password_verify($old_password, $hashed_password)) {
                echo "<script>
                alert('Incorrect password. Please enter  your old password.');
                window.location.href = '../admin_page.php';
              </script>";
                exit;
            }

            if ($new_password !== $confirm_password) {
                echo "<script>
                alert('Unmatch password. Please match your ');
                window.location.href = '../admin_page.php';
              </script>";
                exit;
            }

            $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);
        }

        // Update the user's profile information including the new hashed password in the database
        $updateQuery = "UPDATE client SET fullname = :fullname, email = :email, phone = :phone";
      
        if ($password_provided) {
            $updateQuery .= ", password = :password";
        }
        // Always update profile picture
        $updateQuery .= ", profile = :profile";
        $updateQuery .= " WHERE client_id = :client_id";

        $stmt = $conn->prepare($updateQuery);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        
        if ($password_provided) {
            $stmt->bindParam(':password', $hashed_new_password);
        }
        $stmt->bindParam(':profile', $newProfile);
        $stmt->execute();

        redirectTo('../admin_page.php');
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


?>
