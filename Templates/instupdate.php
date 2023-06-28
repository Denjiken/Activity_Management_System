<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Monaco, Monospace;
	        background-image: url("https://images.unsplash.com/photo-1553356009-50faee7aa84c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fGdyZWVuJTIwYWJzdHJhY3R8ZW58MHx8MHx8fDA%3D&w=1000&q=80");
	        background-repeat: no-repeat;
 	        background-size: 115%;
 	        background-position: center;
	        background-color: #3b3a47;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }

        form {
            background-color: white;
            padding: 10px;
            border-radius: 10px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"]{
            width: 97%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"]:hover {
            background-color: #46A094;
        }
        button{
            tposition: absolute;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        include('db.php');
        ?>

        <!-- Update Instructor Information Form -->
        <h2>Update Instructor Information</h2>
        <a href="dash.php"><button>Go to Dashboard Page</button></a>
        <a href="instinfo.php"><button>Go to Instructor Information</button></a>
        <form method="POST" action="">
            <label for="instructor_id">Instructor ID:</label>
            <input type="text" name="instructor_id" placeholder="123456" required>

            <label for="instructor_name">Name:</label>
            <input type="text" name="instructor_name" required>

            <label for="instructor_gender">Gender:</label>
            <input type="text" name="instructor_gender" required>

            <label for="instructor_address">Address:</label>
            <input type="text" name="instructor_address" required>

            <label for="instructor_email">Email:</label>
            <input type="text" name="instructor_email" required>

            <input type="submit" name="update" value="Update">
        </form>
        
        <?php
        // Handle form submission for updating instructor information
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $instructor_id = $_POST['instructor_id'];
            $name = $_POST['instructor_name'];
            $gender = $_POST['instructor_gender'];
            $address = $_POST['instructor_address'];
            $email = $_POST['instructor_email'];

            // Check if the instructor exists in the database
            $checkSql = "SELECT * FROM instructors WHERE instructor_id = '$instructor_id'";
            $result = $conn->query($checkSql);

            if ($result->num_rows > 0) {
                // Update the instructor information in the database
                $updateSql = "UPDATE instructors SET instructor_name='$name', instructor_gender='$gender', instructor_address='$address', instructor_email='$email' WHERE instructor_id='$instructor_id'";
                if ($conn->query($updateSql) === TRUE) {
                    echo "<p>Instructor information updated successfully.</p>";
                } else {
                    echo "<p>Error updating instructor information: " . $conn->error . "</p>";
                }
            } else {
                // Insert the instructor information into the database
                $insertSql = "INSERT INTO instructors (instructor_id, instructor_name, instructor_gender, instructor_address, instructor_email) VALUES ('$instructor_id', '$name', '$gender', '$address', '$email')";
                if ($conn->query($insertSql) === TRUE) {
                    echo "<p>New instructor information inserted successfully.</p>";
                } else {
                    echo "<p>Error inserting instructor information: " . $conn->error . "</p>";
                }
            }
        }
        ?>
    </div>
</body>
</html>
