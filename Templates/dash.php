<?php
require_once 'db.php';

// Function to view activities
function viewActivities()
{
    global $conn;

    // Retrieve distinct categories from the activity_categories table
    $categorySql = "SELECT DISTINCT study_area FROM activity_categories";
    $categoryResult = $conn->query($categorySql);

    echo "<div style='text-align: right;'>";
    echo "<form method='GET' action=''>";
    echo "<label for='category_filter'>Select Category:</label>";
    echo "<select name='category_filter' id='category_filter'>";

    // Display all option for no filter
    echo "<option value=''>All</option>";

    // Display category options retrieved from the database
    while ($categoryRow = $categoryResult->fetch_assoc()) {
        $studyArea = $categoryRow['study_area'];
        $selected = (isset($_GET['category_filter']) && $_GET['category_filter'] === $studyArea) ? "selected" : "";
        echo "<option value='$studyArea' $selected>$studyArea</option>";
    }

    echo "</select>";
    echo "<input type='submit' value='Apply Category'>";
    echo "</form><br><br>";
    echo "</div>";

    // Retrieve activities based on the selected category filter
    $filter = isset($_GET['category_filter']) ? $_GET['category_filter'] : '';
    $filterSql = ($filter !== '') ? " WHERE activity_categories.study_area = '$filter'" : "";

    $sql = "SELECT activities.activity_id AS 'Activity ID.',
            activities.activity_name AS 'Activity',
            activities.activity_date AS 'Date',
            activities.activity_description AS 'Description',
            instructors.instructor_name AS 'Instructor',
            activity_categories.study_area AS 'Category'
            FROM activities
            INNER JOIN instructors ON activities.instructor_id = instructors.instructor_id
            LEFT JOIN activity_categories ON activities.category_id = activity_categories.category_id
            $filterSql
            GROUP BY activities.activity_id"; // Modified to use activity_id as the primary key column

    $result = $conn->query($sql);

    echo "<table class='activity-table'>";
    echo "<tr><th>Activity ID.</th><th>Activity</th><th>Date</th><th>Description</th><th>Instructor</th><th>Category</th></tr>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Activity ID.'] . "</td>";
            echo "<td>" . $row['Activity'] . "</td>";
            echo "<td>" . $row['Date'] . "</td>";
            echo "<td>" . $row['Description'] . "</td>";
            echo "<td>" . $row['Instructor'] . "</td>";
            echo "<td>" . $row['Category'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No activities found.</td></tr>";
    }

    echo "</table>";
}

// Check if user is an instructor
function isInstructor()
{
    // check if the user is logged in as an instructor
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instructor';
}

// Check if the user is logged in as an instructor
function isUserInstructor()
{
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instructor';
}

if (isUserInstructor()) {
    // Redirect the instructor to the existing code
    header("Location: dash.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user selected the "Student" option
    if (isset($_POST['user_type']) && $_POST['user_type'] === 'student') {
        // Redirect the student to the view.php page
        header("Location: view.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Activity Management System</title>
</head>
<body>
    <div class="activity-management-system-container">
        <h1>Activity Management System</h1>
    </div>
    <style>
        body {
            font-family: Monaco, Monospace;
	        background-image: url("https://images.pexels.com/photos/3377414/pexels-photo-3377414.jpeg?cs=srgb&dl=pexels-el%C4%ABna-ar%C4%81ja-3377414.jpg&fm=jpg");
	        background-repeat: no-repeat;
 	        background-size: 125%;
 	        background-position: center;
	        background-color: #3b3a47;
        }
        
        h1 {
            padding: 10px;
            margin-left: -10px;
            margin-right: -10px;
            text-align: center;
            color: black;
        }

        label {
            color: white;
        }

        .activity-table {
            background: linear-gradient(to bottom right, #666666, #333333);
            border-radius: 10px;
            width: 100%;
        }

        .activity-table th,
        .activity-table td {
            border: 5px grey;
            padding: 10px;
            text-align: left;
            background-color: #808080;
            color: black;
        }

        .update-button {
            background-color: #4B5320;
            border-radius: 10px;
            color: white;
        }

        .delete-button {
            background-color: #8B0000;
            border-radius: 10px;
            color: white;
        }

        .add-button {
            background-color: #00008B;
            border-radius: 10px;
            color: white;
        }

        .logout {
            background-color: red;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            color: white;
            margin-top: 10px;
            float: right;
        }

        .logout a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php if (isInstructor()): ?>
    <div style='text-align: right';>
        <select name="activity_option" onchange="location = this.value;">
            <option value="">Edit Table</option>
            <option value="add.php">Add New Activity</option>
            <option value="update.php">Update Activity</option>
            <option value="delete.php">Delete Activity</option>
        </select><br><br>
    </div>
    <?php endif; ?>
    <a href="instinfo.php"><button>Go to Instructor Information</button></a>
    <a href="update.php"><button class="update-button">Update Activity</button></a>
    <a href="delete.php"><button class="delete-button">Delete Activity</button></a>
    <a href="add.php"><button class="add-button">Add Activity</button></a>

    <?php
    viewActivities();
    ?>

    <div class="logout">
        <a href="userlogin.php">Logout</a>
    </div>
</body>
</html>
