<!DOCTYPE html>
<html>
<head>
    <title>Instructor Information</title>
    
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
            background-color: white;
            padding: 20px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            border-top: 1px solid #ddd;

        }



        .delete-button {
            background-color: #8B0000;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
        }

        .update-button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
        }

        .main-page-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <?php
    require_once 'db.php';

    // Fetch instructor information
    $result = $conn->query("SELECT * FROM instructors");

    if ($result->num_rows > 0) {
        echo "<a class='main-page-button' href='dash.php'>Go to Dashboard Page</a>";
        echo "<div class='container'>";
        echo "<form action='instupdate.php' method='GET'>";
        echo "<table>";
        echo "<tr>
                <th>Instructor ID</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Address</th>
                <th>Email</th>
                <th>Actions</th>
              </tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['instructor_id'] . "</td>";
            echo "<td>" . $row['instructor_name'] . "</td>";
            echo "<td>" . $row['instructor_gender'] . "</td>";
            echo "<td>" . $row['instructor_address'] . "</td>";
            echo "<td>" . $row['instructor_email'] . "</td>";
            echo "<td>
                    <a class='delete-button' href='deleteinstinfo.php?id=" . $row['instructor_id'] . "'>Delete</a>
                    <button class='update-button' type='submit' name='id' value='" . $row['instructor_id'] . "'>Update</button>
                  </td>";
            echo "</tr>";
        }
        
        echo "</table>";
        echo "</form>";
        echo "</div>";
    } else {
        echo "<p>No instructor information found.</p>";
    }

    $conn->close();
    ?>
</body>
</html>
