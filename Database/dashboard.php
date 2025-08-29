<?php
// Include the config.php file to manage session and database connection
include 'config.php';

// Check if the role is set in the session
if (!isset($_SESSION['role'])) {
    header("Location: role_selection.php"); // Redirect if no role is selected
    exit();
}

$role = $_SESSION['role']; // Get the selected role
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Car Equipment Shop</title>
    
</head>
<style> 
/* General Layout */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    display: flex;
    flex-direction: column;
    height: 100vh;
}

/* Sidebar (hidden by default) */
.sidebar {
    width: 250px;
    background-color: #5e4b8b;
    color: #fff;
    padding: 20px;
    position: fixed;
    top: 0;
    left: -250px; /* Initially hidden */
    bottom: 0;
    transition: 0.3s ease;
    z-index: 1000;
}

.sidebar.active {
    left: 0; /* Show sidebar when active */
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

/* Content Section */
.content {
    margin-left: 0;
    padding: 20px;
    flex: 1;
    background-color: #ffffff;
    transition: margin-left 0.3s;
}

/* This moves the content to the left when the sidebar is collapsed */
.content.shift {
    margin-left: 250px; /* Adjusted margin to match sidebar width */
}

/* Header Section */
.header {
    background-color: rgba(94, 75, 139, 0.8); /* Darker background */
    color: #fff;
    padding: 20px 20px;
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    z-index: 2;
    flex-direction: column;
}

/* Title styling */
.header h1 {
    font-size: 32px;
    margin: 0;
    text-align: center; /* Ensure title is centered */
    padding: 10px;
}

/* Styling for the Logout Button */
.header .logout-btn {
    padding: 10px;
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

/* Hamburger Menu */
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

/* Dashboard Content Styling */
.dashboard-content {
    text-align: center;
    padding: 40px;
}

.dashboard-content h3 {
    font-size: 30px;
    color: #5e4b8b;
}

.dashboard-content p {
    font-size: 18px;
    color: #555;
}

/* Background Images */
.background-images {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
}

.background-images img {
    width: 300px;
    border-radius: 10px;
    opacity: 0.7;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .content {
        margin-left: 0;
    }

    .background-images {
        flex-direction: column;
    }

    .sidebar {
        width: 200px;
    }

    .hamburger-menu {
        top: 10px;
        left: 10px;
    }
}


</style>

<body>

    <!-- Sidebar with Hamburger Menu -->
    <div class="hamburger-menu" id="hamburgerMenu" onclick="toggleSidebar()">&#9776;</div>
    <div class="sidebar" id="sidebar">
        <div class="close-menu" onclick="toggleSidebar()">&#10006;</div>
        <h2>Categories</h2>
        <ul>
            <li><a href="#dashboard" class="category-link">Dashboard</a></li>
            <li><a href="sellingPage.php" class="category-link">Selling Section</a></li>
            <li><a href="addingPart.php" class="category-link">Adding Part</a></li>
            <li><a href="#lending" class="category-link">Lending & Borrow</a></li>
            <li><a href="#report" class="category-link">Report</a></li>
        </ul>
    </div>

    <div class="content" id="content">
    <div class="header">
    <h1>Welcome to the Car Equipment Shop </h1>
    <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
</div>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <h3>Welcome to the Zanyar Shop Dashboard!</h3>
            <p>Welcome to the Zanyar Shop! This is where you manage all your car equipment. Here, you can manage your inventory, view your sales, add new parts to the system, and manage lending or borrowing parts with other shops. Use the menu to navigate through the different sections.</p>
            <p>Stay organized and keep track of your sales and inventory easily.</p>

            <!-- Background images -->
            <div class="background-images">
                <img src="1.jpg" alt="Image 1">
                <img src="2.jpg" alt="Image 2">
            </div>
        </div>
    </div>

    <script>
        // Toggle Sidebar visibility
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            const hamburgerMenu = document.getElementById("hamburgerMenu");
            const content = document.getElementById("content");

            sidebar.classList.toggle("active");
            hamburgerMenu.classList.toggle("hide");
            content.classList.toggle("shift");
        }
    </script>

</body>
</html>
