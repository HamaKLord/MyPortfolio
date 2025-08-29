<?php
// Include the config.php file to manage session and database connection
include 'config.php';

// If the user is already logged in (role is selected), redirect them to the dashboard
if (isset($_SESSION['role'])) {
    header("Location: dashboard.php");
    exit();
}

// Fetch workers from the database for role selection
$sql = "SELECT WorkerID, Name, Role FROM Workers";
$result = $conn->query($sql);

$workers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $workers[] = $row;
    }
} else {
    echo "No workers found in the database.";
}

$conn->close(); // Close the connection after fetching data

// Handle form submission for role selection
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['role'] = $_POST['role']; // Save the selected role in session
    header("Location: dashboard.php"); // Redirect to the dashboard after selection
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Role - Car Equipment Shop</title>
    <style>
/* General styling */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f4f4f4;
}

/* Role Selection Box Styling */
.role-selection {
    background-color: #ffffff;
    padding: 40px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    width: 100%;
    max-width: 400px;
    text-align: center;
}

h1 {
    color: #5e4b8b;
    font-size: 36px;
    margin-bottom: 20px;
}

/* Label Styling */
label {
    font-size: 18px;
    color: #333;
    margin-bottom: 10px;
    display: block;
}

/* Select Box Styling */
select {
    padding: 12px 20px;
    width: 100%;
    font-size: 16px;
    color: #333;
    background-color: #f7f7f7;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 20px;
    box-sizing: border-box;
}

select:focus {
    border-color: #5e4b8b;
    outline: none;
    background-color: #fff;
}

/* Button Styling */
button {
    padding: 12px 20px;
    background-color: #5e4b8b;
    color: white;
    font-size: 16px;
    border: none;
    cursor: pointer;
    width: 100%;
    border-radius: 5px;
    box-sizing: border-box;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #4a3a7b;
}

/* Responsive Styling */
@media (max-width: 768px) {
    .role-selection {
        padding: 30px;
    }

    h1 {
        font-size: 30px;
    }

    label, select, button {
        font-size: 14px;
    }
}


    </style>

</head>
<body>
    <div class="container">
        <div class="role-selection">
            <h1>Select Your Role</h1>
            <form action="role_selection.php" method="POST">
                <label for="role">Choose Role:</label>
                <select name="role" id="role" required>
                    <?php 
                    // Loop through the workers array and create an option for each worker
                    foreach ($workers as $worker) {
                        echo "<option value='" . $worker['Role'] . "'>" . $worker['Name'] . " </option>";
                    }
                    ?>
                </select>
                <button type="submit">Select</button>
            </form>
        </div>
    </div>
</body>
</html>
