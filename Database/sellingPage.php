<?php
// Include config.php to connect to the database
include 'config.php';

// Check if the user is logged in (role is selected)
if (!isset($_SESSION['role'])) {
    header("Location: role_selection.php");  // Redirect to role selection if not selected
    exit();
}

// Handle the form submission for adding a new part
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Search query from user input
    $searchTerm = $_POST['search_term'];
    
    // SQL query to search for parts
    $sql = "SELECT * FROM Parts WHERE SerialCode LIKE '%$searchTerm%' OR PartName LIKE '%$searchTerm%' OR Model LIKE '%$searchTerm%' OR Brand LIKE '%$searchTerm%'";

    // Execute query
    $result = $conn->query($sql);

    $parts = [];
    
    if ($result && $result->num_rows > 0) {
        // Fetch the results and store them in an array
        while ($row = $result->fetch_assoc()) {
            $parts[] = $row;
        }
        
        // Return the results in JSON format
        echo json_encode($parts);
    } else {
        echo json_encode([]);
    }
    exit(); // Make sure to stop script after returning the result
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selling Page - Car Equipment Shop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #5e4b8b;
            color: #fff;
            padding: 20px;
            position: fixed;
            top: 0;
            left: -250px;
            bottom: 0;
            transition: 0.3s ease;
            z-index: 1000;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar h2 {
            text-align: center;
            color: #fff;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            display: block;
            padding: 10px;
        }

        .sidebar ul li a:hover {
            background-color: #4a3a7b;
        }

        .content {
            margin-left: 0;
            padding: 20px;
            flex: 1;
            background-color: #ffffff;
            transition: margin-left 0.3s;
        }

        .content.shift {
            margin-left: 250px;
        }

        .header {
            background-color: rgba(94, 75, 139, 0.8);
            color: #fff;
            padding: 20px 20px;
            text-align: center;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 2;
            flex-direction: column;
        }

        .header h1 {
            font-size: 32px;
            margin: 0;
        }

        .header .logout-btn {
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .header .logout-btn:hover {
            background-color: #d32f2f;
        }

        .form-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form label {
            margin: 10px 0 5px;
            font-weight: bold;
        }

        form input, form select, form textarea {
            padding: 10px;
            margin-bottom: 20px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
        }

        form button {
            padding: 10px;
            background-color: #5e4b8b;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }

        form button:hover {
            background-color: #4a3a7b;
        }

        .hamburger-menu {
            font-size: 30px;
            color: #fff;
            cursor: pointer;
            display: block;
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 1001;
        }

        .search-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .search-container input {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
        }

        .search-container button {
            padding: 10px;
            background-color: #5e4b8b;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 5px;
        }

        .search-container button:hover {
            background-color: #4a3a7b;
        }

        #searchResults {
            max-height: 200px;
            overflow-y: scroll;
        }

        .search-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .search-item:hover {
            background-color: #f4f4f4;
        }

        @media (max-width: 768px) {
            .content {
                margin-left: 0;
            }

            .sidebar {
                width: 200px;
            }

            .form-container {
                width: 90%;
            }

            .search-container input {
                width: 70%;
            }
        }

    </style>
</head>
<body>

<div class="content">
    <div class="header">
        <h1>Selling Section</h1>
        <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
    </div>

    <!-- Search for Part -->
    <div class="form-container">
        <div class="search-container">
            <input type="text" id="search_term" placeholder="Search by SerialCode, PartName, Model, Brand..." onkeyup="searchPart()">
            <button onclick="searchPart()">Search</button>
        </div>

        <div id="searchResults">
            <!-- Search results will be shown here -->
        </div>
    </div>
</div>

<!-- Sidebar for navigation -->
<div class="sidebar" id="sidebar">
    <h2>Categories</h2>
    <ul>
        <li><a href="dashboard.php" class="category-link">Dashboard</a></li>
        <li><a href="addingPart.php" class="category-link">Adding Part</a></li>
        <li><a href="selling.php" class="category-link">Selling Section</a></li>
        <li><a href="lending.php" class="category-link">Lending & Borrow</a></li>
        <li><a href="report.php" class="category-link">Report</a></li>
    </ul>
</div>

<!-- Hamburger Menu -->
<div class="hamburger-menu" onclick="toggleSidebar()">
    &#9776;
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        const content = document.getElementById("content");
        sidebar.classList.toggle("active");
        content.classList.toggle("shift");
    }

    function searchPart() {
        const searchTerm = document.getElementById('search_term').value;

        // Perform an AJAX request to search for parts
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'sellingPage.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                const results = JSON.parse(xhr.responseText);
                let resultHTML = '';

                if (results.length > 0) {
                    results.forEach(item => {
                        resultHTML += `
                            <div class="search-item">
                                <p>${item.PartName} - ${item.Model} - ${item.Brand}</p>
                                <button onclick="fillForm(${item.SerialCode})">Select</button>
                            </div>
                        `;
                    });
                } else {
                    resultHTML = "<p>No parts found matching your search.</p>";
                }
                document.getElementById('searchResults').innerHTML = resultHTML;
            }
        };
        xhr.send('search_term=' + searchTerm);
    }

    function fillForm(serialCode) {
        // Implement logic to auto-fill form fields with the selected part's information
        console.log("Selected Part Serial Code:", serialCode);
    }
</script>

</body>
</html>
