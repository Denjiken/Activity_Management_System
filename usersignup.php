<?php
session_start();
include("db.php");

// Generate a random string of specified length
function random_string($length) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $characters_length = strlen($characters);
    $random_string = '';

    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[rand(0, $characters_length - 1)];
    }

    return $random_string;
}

// User Signup Form submission handling
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userType = $_POST['user_type']; /* In this code we added a POST method so that When the form is submitted, the value entered in the "user_type" field will be accessed using the $_POST['user_type'] expression. This value will then be assigned to the $userType variable for further processing or usage in our PHP code.
if the user enters "student" in the "user_type" field and submits the form, the value "student" will be assigned to the $userType variable, same as if the user input "instructor" as a user type */

    if (!empty($username) && !empty($password)) {
        // Check if the username already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $warning = "The username already exists. Please choose a different one.";
        } else {
            // Generate a unique user ID
            $max_attempts = 10;
            $attempt = 1;
            $user_id = false;

            while ($attempt <= $max_attempts) {
                $user_id = time() . random_string(6); // Generate an ID using timestamp and random characters

                // Check if the generated user ID already exists
                $stmt = $conn->prepare("SELECT COUNT(*) FROM user WHERE id = ?");
                $stmt->bind_param("s", $user_id);
                $stmt->execute();
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();

                if ($count == 0) {
                    break; // Unique ID generated
                }

                $attempt++;
            }

            if ($user_id === false) {
                echo "Unable to generate a unique user ID. Please try again later.";
                die;
            }

            // Insert the new user into the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO user (id, username, user_password, user_type) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssss", $user_id, $username, $hashedPassword, $userType);
            $stmt->execute();
            $stmt->close();

            $successMessage = "Success! You can now sign in.";
            header("refresh:3; url=userlogin.php"); // Redirect after 3 seconds
        }
    } else {
        $warning = "The information is not valid.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup Page</title>
    <link rel="stylesheet" href="css/signup.css">
    <style>
        body {
            font-family: Monaco, Monospace;
	        background-image: url("https://img.freepik.com/free-photo/liquid-marbling-paint-texture-background-fluid-painting-abstract-texture-intensive-color-mix-wallpaper_1258-82821.jpg?w=2000");
	        background-repeat: no-repeat;
 	        background-size: 133%;
 	        background-position: center;
	        background-color: #3b3a47;
        }
        
        .signup-container {
            background-color: grey;
            border-radius: 20px;
            padding: 20px;
            width: 300px;
        }

        h1, p, label {
            margin: 0;
            padding: 0;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            font-size: 14px;
            margin-bottom: 10px;
        }

        form {
            margin-top: 10px;
        }

        input[type="text"], input[type="password"] {
            display: block;
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #CCCCCC;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }

        button[type="submit"] {
            background-color: #355E3B;
            color: #FFFFFF;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            cursor: pointer;
        }

        a {
            color: #D31C32;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h1>Sign Up</h1>
        <p>Fill out the form below.</p>
        <?php if (isset($warning)): ?>
            <p style="color: red;"><?php echo $warning; ?></p>
        <?php elseif (isset($successMessage)): ?>
            <p style="color: green;"><?php echo $successMessage; ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" placeholder="Username" id="username" name="username" required>
            <input type="password" placeholder="Password" id="password" name="password" required>
            <label>Select user type:</label>
            <input type="radio" name="user_type" value="student" checked> Student
            <input type="radio" name="user_type" value="instructor"> Instructor
            <button type="submit">Sign Up</button><br>
            <label>Already have an account?</label>
            <a href="userlogin.php" style="color: #D31C32;">Login</a>
        </form>
    </div>
</body>
</html>



