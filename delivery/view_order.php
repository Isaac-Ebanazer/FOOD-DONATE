<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f3f3f3;
        }
        h2 {
            color: #333;
            margin-bottom: 10px;
        }
        p {
            margin: 5px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .activity {
            margin-top: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .location {
            margin-bottom: 20px;
        }
        .logo {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .table-container {
            overflow-x: auto;
        }
        .table-wrapper {
            overflow-y: auto;
            max-height: 400px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        session_start();
        include '../connection.php'; // Include your database connection file
        include("connect.php"); 
        // Check if the order ID is set in the POST request
        if(isset($_POST['order_id'])) {
            // Sanitize the input to prevent SQL injection
            $order_id = mysqli_real_escape_string($connection, $_POST['order_id']);

            // Define the SQL query to fetch the details of the selected order
            $sql = "SELECT * FROM food_donations WHERE Fid = '$order_id'";
            
            // Execute the query
            $resu = mysqli_query($connection, $sql);

            // Check if the query was successful
            if($resu) {
                // Fetch the data as an associative array
                $row = mysqli_fetch_assoc($resu);

                // Display the details of the order
                echo "<h2>Order Details</h2>";
                echo "<p><strong>Name:</strong> " . $row['name'] . "</p>";
                echo "<p><strong>Phone Number:</strong> " . $row['phoneno'] . "</p>";
                echo "<p><strong>Date/Time:</strong> " . $row['date'] . "</p>";
                echo "<p><strong>Pickup Address:</strong> " . $row['address'] . "</p>";
                // Add more details as needed
            } else {
                // Display an error message if the query fails
                echo "Error: " . mysqli_error($connection);
            }
        } 

        // Close the database connection
        
        ?>
        <a href="deliverymyord.php" class="back-btn">Back to My Orders</a>
    </div>
  
    <div class="activity">
        <div class="location">
            <form method="post">
                <label for="location" class="logo">Select Location to find the nearest organization in need :</label>
                <select id="location" name="location">
                    <?php
                    // Query to retrieve distinct locations from food_donations table
                    $location_query = "SELECT DISTINCT location FROM food_donations";
                    $location_result = mysqli_query($connection, $location_query);
                    while($location_row = mysqli_fetch_assoc($location_result)) {
                        $location_value = $location_row['location'];
                        echo "<option value=\"$location_value\">$location_value</option>";
                    }
                    ?>
                </select>
                <input type="submit" value="Get Details">
            </form>
            <br>
            <?php
            // Get the selected location from the form
            if(isset($_POST['location'])) {
                $location = $_POST['location'];
                
                // Query the database for organizations in the selected location
                $org_sql = "SELECT * FROM organizations WHERE location='$location'";
                $org_result = mysqli_query($connection, $org_sql);
                
                // If there are results, display them in a table
                if ($org_result->num_rows > 0) {
                    echo "<div class=\"table-container\">";
                    echo "<div class=\"table-wrapper\">";
                    echo "<table class=\"table\">";
                    echo "<thead><tr>
                            <th>Name</th>
                            <th>Organizations Name</th>
                            <th>Category</th>
                            <th>phoneno</th>
                            <th>date/time</th>
                            <th>address</th>
                            <th>Quantity</th>
                        </tr></thead><tbody>";
                    
                    while($row = $org_result->fetch_assoc()) {
                        echo "<tr>
                                <td data-label=\"name\">" . $row['name'] . "</td>
                                <td data-label=\"food\">" . $row['organization_name'] . "</td>
                                <td data-label=\"category\">" . $row['category'] . "</td>
                                <td data-label=\"phoneno\">" . $row['phoneno'] . "</td>
                                <td data-label=\"date\">" . $row['date'] . "</td>
                                <td data-label=\"Address\">" . $row['address'] . "</td>
                                <td data-label=\"quantity\">" . $row['quantity'] . "</td>
                            </tr>";
                    }
                    echo "</tbody></table></div></div>";
                } else {
                    echo "<p>No results found.</p>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
