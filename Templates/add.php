<?php
require('db.php');

// Function to add a student
function addStudent($student_name, $student_email, $student_year) {
    global $conn;

    $studentStmt = $conn->prepare("INSERT INTO students (student_name, student_email, student_year) VALUES (?, ?, ?)");
    $studentStmt->bind_param("sss", $student_name, $student_email, $student_year);

    if ($studentStmt->execute()) {
        $studentStmt->close();
        return $conn->insert_id;
    } else {
        echo "Error adding student: " . $studentStmt->error;
        $studentStmt->close();
        return null;
    }
}

// Function to add an activity
function addActivity($activity_name, $activity_date, $activity_description, $instructor_name, $instructor_email, $study_area) {
    global $conn;

    $instructor_id = addInstructor($instructor_name, $instructor_email);
    $category_id = addActivityCategory($study_area);

    $activityStmt = $conn->prepare("INSERT INTO activities (activity_name, activity_date, activity_description, instructor_id, category_id) VALUES (?, ?, ?, ?, ?)");
    $activityStmt->bind_param("ssssi", $activity_name, $activity_date, $activity_description, $instructor_id, $category_id);

    if ($activityStmt->execute()) {
        $activityStmt->close();
        return $conn->insert_id;
    } else {
        echo "Error adding activity: " . $activityStmt->error;
        $activityStmt->close();
        return null;
    }
}

// Function to add a registration
function addRegistration($student_id, $activity_id) {
    global $conn;

    $registrationStmt = $conn->prepare("INSERT INTO registrations (student_id, activity_id) VALUES (?, ?)");
    $registrationStmt->bind_param("ii", $student_id, $activity_id);

    if ($registrationStmt->execute()) {
        $registrationStmt->close();
        return $conn->insert_id;
    } else {
        echo "Error adding registration: " . $registrationStmt->error;
        $registrationStmt->close();
        return null;
    }
}

// Function to add an instructor
function addInstructor($instructor_name, $instructor_email) {
    global $conn;

    $instructorStmt = $conn->prepare("INSERT INTO instructors (instructor_name, instructor_email) VALUES (?, ?)");
    $instructorStmt->bind_param("ss", $instructor_name, $instructor_email);

    if ($instructorStmt->execute()) {
        $instructorStmt->close();
        return $conn->insert_id;
    } else {
        echo "Error adding instructor: " . $instructorStmt->error;
        $instructorStmt->close();
        return null;
    }
}

// Function to add an activity category
function addActivityCategory($study_area) {
    global $conn;

    $categoryStmt = $conn->prepare("INSERT INTO activity_categories (study_area) VALUES (?)");
    $categoryStmt->bind_param("s", $study_area);

    if ($categoryStmt->execute()) {
        $categoryStmt->close();
        return $conn->insert_id;
    } else {
        echo "Error adding activity category: " . $categoryStmt->error;
        $categoryStmt->close();
        return null;
    }
}

// Add Activity form submission handling
if (isset($_POST['add_activity'])) {
    $student_name = $_POST['student_name'];
    $student_email = $_POST['student_email'];
    $student_year = $_POST['student_year'];
    $activity_name = $_POST['activity_name'];
    $activity_date = $_POST['activity_date'];
    $activity_description = $_POST['activity_description'];
    $instructor_name = $_POST['instructor_name'];
    $instructor_email = $_POST['instructor_email'];
    $study_area = $_POST['study_area'];

    $student_id = addStudent($student_name, $student_email, $student_year);
    $activity_id = addActivity($activity_name, $activity_date, $activity_description, $instructor_name, $instructor_email, $study_area);
    $registration_id = addRegistration($student_id, $activity_id);
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Activity</title>
    <style>
        body {
            font-family: Monaco, Monospace;
	        background-image: url("https://img.freepik.com/free-vector/white-abstract-background-3d-paper-style_23-2148390812.jpg?w=2000");
	        background-repeat: no-repeat;
 	        background-size: 100%;
 	        background-position: center;
	        background-color: #3b3a47;
        }

        h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            background: #555555;
            padding: 20px;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #FFFFFF;
        }

        input[type="text"],
        select {
            padding: 8px;
            border: 1px solid #CCCCCC;
            border-radius: 3px;
            width: 100%;
            box-sizing: border-box;
        }

        select {
            height: 35px;
        }

        .name-field {
            display: flex;
        }

        .name-field input[type="text"] {
            flex: 1;
            margin-right: 5px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #6B8E23;
            color: #FFFFFF;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #8FBC8F;
        }
        
        a {
            text-decoration: none;
            color: #FFFFFF;
        }

        button {
            padding: 10px 20px;
            background-color: #6B8E23;
            color: #FFFFFF;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #8FBC8F;
        }
    </style>
</head>
<body>
    <!-- Add Activity Form -->
    <h2>Add Activity</h2>
    <form method="POST" action="">
        <label for="student_name">Student Name:</label>
        <input type="text" name="student_name" required><br><br>

        <label for="student_email">Student Email:</label>
        <input type="text" name="student_email" required><br><br>

        <label for="student_year">Student Year:</label>
        <input type="text" name="student_year" required><br><br>

        <label for="activity_name">Activity Name:</label>
        <input type="text" name="activity_name" required><br><br>

        <label for="activity_date">Date:</label>
        <input type="date" name="activity_date" required><br><br>

        <label for="activity_description">Description:</label>
        <input type="text" name="activity_description" required><br><br>

        <label for="instructor_name">Instructor Name:</label>
        <input type="text" name="instructor_name" required><br><br>

        <label for="instructor_email">Instructor Email:</label>
        <input type="text" name="instructor_email" required><br><br>

        <label for="study_area">Study Area:</label>
        <input type="text" name="study_area" required><br><br>

        <input type="submit" name="add_activity" value="Add Activity">
    </form><br><br>
    <a href="dash.php"><button>Go to Dashboard Page</button></a>
</body>
</html>
