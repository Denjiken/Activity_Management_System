<?php
require_once 'db.php';

// Function to view activities
function viewActivities() {
    global $conn;

    $sql = "SELECT activities.activity_id AS 'Activity No.',
            activities.activity_name AS 'Activity',
            activities.activity_date AS 'Date',
            activities.activity_description AS 'Description',
            instructors.instructor_name AS 'Instructor',
            activity_categories.study_area AS 'Category'
            FROM activities
            INNER JOIN instructors ON activities.instructor_id = instructors.instructor_id
            INNER JOIN activity_categories ON activities.category_id = activity_categories.category_id";

    $result = $conn->query($sql);

    echo "<table class='activity-table'>";
    echo "<tr><th>Activity No.</th><th>Activity</th><th>Date</th><th>Description</th><th>Instructor</th><th>Category</th></tr>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Activity No.'] . "</td>";
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
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Activities</title>
</head>
<body>
    <div class="view-activities-container">
        <h1>View Activities</h1>
        <div style="text-align: right;">
            <form action="userlogin.php" method="POST">
                <button type="submit" style="background-color: red; color: white; border: none; padding: 10px; cursor: pointer;">Logout</button>
            </form>
        </div>
    </div>
    <style>
        body {
            font-family: Monaco, Monospace;
	        background-image: url("https://images.unsplash.com/photo-1553356009-50faee7aa84c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fGdyZWVuJTIwYWJzdHJhY3R8ZW58MHx8MHx8fDA%3D&w=1000&q=80");
	        background-repeat: no-repeat;
 	        background-size: 115%;
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

        .activity-table {
            padding: 10px;
            background: linear-gradient(to bottom right, #666666, #333333);
            border-radius: 10px;
            width: 100%;
        }

        .activity-table th,
        .activity-table td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
            color: white;
        }
    </style>

    <?php
    viewActivities();
    ?>
</body>
</html>
