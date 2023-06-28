<!DOCTYPE html>
<html>
<head>
    <title>Update Activity</title>
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
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 2px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="date"],
        textarea,
        select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            width: 100%;
            box-sizing: border-box;
        }

        select {
            height: 35px;
        }

        textarea {
            height: 100px;
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
            background-color: #006400;
        }
    </style>
</head>
<body>
    <?php
    require_once 'db.php';

    // Function to update an activity
    function updateActivity($activity_id, $activity_name, $activity_date, $activity_description) {
        global $conn;

        $activityStmt = $conn->prepare("UPDATE activities SET activity_name = ?, activity_date = ?, activity_description = ? WHERE activity_id = ?");
        $activityStmt->bind_param("sssi", $activity_name, $activity_date, $activity_description, $activity_id);

        if ($activityStmt->execute()) {
            echo "Activity updated successfully.";
        } else {
            echo "Error updating activity: " . $activityStmt->error;
        }

        $activityStmt->close();
    }
    ?>

    <!-- Update Activity Form -->
    <h2>Update Activity</h2>
    <a href="dash.php"><button>Go to Dashboard Page</button></a><br><br>
    <form method="POST" action="">
        <label for="activity_id">Activity ID:</label>
        <input type="number" name="activity_id" required><br><br>

        <?php
        // Fetch activity details based on activity ID
        if (isset($_POST['activity_id'])) {
            $activity_id = $_POST['activity_id'];

            $activityStmt = $conn->prepare("SELECT activity_name, activity_date, activity_description FROM activities WHERE activity_id = ?");
            $activityStmt->bind_param("i", $activity_id);
            $activityStmt->execute();
            $activityStmt->bind_result($activity_name, $activity_date, $activity_description);
            $activityStmt->fetch();
            $activityStmt->close();
        }
        ?>

        <label for="activity">Activity:</label>
        <input type="text" name="activity_name" value="<?php echo isset($activity_name) ? $activity_name : ''; ?>" required><br><br>

        <label for="date">Date:</label>
        <input type="date" name="activity_date" value="<?php echo isset($activity_date) ? $activity_date : ''; ?>" required><br><br>

        <label for="description">Description:</label>
        <textarea name="activity_description" required><?php echo isset($activity_description) ? $activity_description : ''; ?></textarea><br><br>

        <label for="category_id">Category ID:</label>
        <select name="category_id">
            <?php
            // Fetch category options from activity_categories table
            $categoryStmt = $conn->prepare("SELECT category_id, study_area FROM activity_categories");
            $categoryStmt->execute();
            $categoryStmt->bind_result($category_id, $study_area);
            
            while ($categoryStmt->fetch()) {
                echo '<option value="' . $category_id . '">' . $study_area . '</option>';
            }
            
            $categoryStmt->close();
            ?>
        </select><br><br>

        <input type="submit" name="update_activity" value="Update Activity">
    </form><br><br>

    <?php
    // Update Activity form submission handling
    if (isset($_POST['update_activity'])) {
        $activity_id = $_POST['activity_id'];
        $activity_name = $_POST['activity_name'];
        $activity_date = $_POST['activity_date'];
        $activity_description = $_POST['activity_description'];
        $category_id = $_POST['category_id'];

        // Update activity
        updateActivity($activity_id, $activity_name, $activity_date, $activity_description);
    }
    ?>
</body>
</html>
