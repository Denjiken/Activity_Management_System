<!DOCTYPE html>
<html>
<head>
    <title>Delete Instructor Information</title>
    <style>
        body {
            font-family: Monaco, Monospace;
	        background-image: url("https://images.unsplash.com/photo-1553356009-50faee7aa84c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fGdyZWVuJTIwYWJzdHJhY3R8ZW58MHx8MHx8fDA%3D&w=1000&q=80");
	        background-repeat: no-repeat;
 	        background-size: 115%;
 	        background-position: center;
	        background-color: #3b3a47;
        }

        h2 {
            text-align: center;
            color: white;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"],
        select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            width: 100%;
            box-sizing: border-box;
            color: black;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>
    <?php
    require_once 'db.php';

    // Function to delete instructor information
    function deleteInstructor($instructor_id, $column) {
        global $conn;

        $deleteInstructorStmt = $conn->prepare("DELETE FROM instructors WHERE instructor_id = ?");
        $deleteInstructorStmt->bind_param("i", $instructor_id);

        if ($deleteInstructorStmt->execute()) {
            echo "Instructor information deleted successfully.";
        } else {
            echo "Error deleting instructor information: " . $deleteInstructorStmt->error;
        }

        $deleteInstructorStmt->close();
    }

    // Delete Instructor form submission handling
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_instructor'])) {
        $instructor_id = $_POST['instructor_id'];
        $column = $_POST['column'];

        deleteInstructor($instructor_id, $column);
    }
    ?>

    <!-- Delete Instructor Form -->
    <h2>Delete Instructor Information</h2>
    <form method="POST" action="">
        <label for="instructor_id">Instructor ID:</label>
        <input type="number" name="instructor_id" required><br><br>

        <label for="column">Select Column:</label>
        <select name="column">
            <option value="instructor_name">Instructor Name</option>
            <option value="instructor_address">Instructor Address</option>
            <option value="instructor_email">Instructor Email</option>
            <option value="instructor_gender">Instructor Gender</option>
        </select><br><br>

        <input type="submit" name="delete_instructor" value="Delete Instructor Information">
    </form><br><br>
    <a href="instinfo.php"><button>Go to Instructor Information</button></a>
</body>
</html>
