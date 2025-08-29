<?php
// Include the config.php file to manage session and database connection
include 'config.php';  // This ensures the user is logged in and the database is connected

// If the user is already logged in (role is selected), redirect them to the dashboard
if (isset($_SESSION['role'])) {
    header("Location: dashboard.php");  // Redirect to the dashboard if the role is set
    exit();
}

// Fetch workers from the database for role selection
$sql = "SELECT WorkerID, Name, Role FROM Workers";
$result = $conn->query($sql);

$workers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $workers[] = $row;  // Store workers in an array
    }
} else {
    echo "No workers found in the database.";
}

$conn->close();  // Close the connection after fetching data

// Handle form submission for role selection
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['role'] = $_POST['role'];  // Save the selected role in session
    header("Location: dashboard.php");  // Redirect to the dashboard after selection
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Role - Car Equipment Shop</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Link to your CSS -->
</head>
<body>
    <div class="container">
        <h1>Select Your Role</h1>
        <form action="role_selection.php" method="POST">
            <label for="Name">Choose Role:</label>
            <select name="Name" id="Name" required>
                <?php 
                // Loop through the workers array and create an option for each worker
                foreach ($workers as $worker) {
                    echo "<option value='" . $worker['Role'] . "'>" . $worker['Role'] . " (" . $worker['Name'] . ")</option>";
                }
                ?>
            </select>
            <button type="submit">Select</button>
        </form>
    </div>
</body>
</html>
