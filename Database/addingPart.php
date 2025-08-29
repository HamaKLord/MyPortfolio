<?php
// Include config.php to connect to the database
include 'config.php';

// Check if the user is logged in (role is selected)
if (!isset($_SESSION['role'])) {
    header("Location: role_selection.php");  // Redirect if no role is selected
    exit();
}

// Handle the form submission for adding a new part
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form inputs
    $serialCode           = $_POST['serial_code'];       // Serial code (text)
    $partName            = $_POST['part_name'];         // Part name
    $description         = $_POST['description'];       // Description
    $stockLevel          = $_POST['stock_level'];       // Stock quantity
    $priceValue          = $_POST['price'];             // Numeric purchase price
    $priceCurrency       = $_POST['price_currency'];    // Currency for purchase price (USD or IQD)
    $sellingPriceValue   = $_POST['selling_price'];     // Numeric selling price
    $sellingPriceCurrency= $_POST['selling_price_currency']; // Currency for selling price (USD or IQD)
    $brand               = $_POST['brand'];             // Brand (Kia/Hyundai)
    $model               = $_POST['model'];             // Model
    $yearRange           = $_POST['year_range'];        // Year (or range)
    $partLocation        = $_POST['part_location'];     // Part location (Outside, Inside, etc.)

    // First, insert the new Category attributes into the Category table
    $categorySql = "
        INSERT INTO Category (Brand, Model, YearRange, PartLocation) 
        VALUES ('$brand', '$model', '$yearRange', '$partLocation')
    ";

    if ($conn->query($categorySql) === TRUE) {
        // Retrieve the auto-incremented CategoryID
        $categoryId = $conn->insert_id;

        // Now insert the new Part into the Parts table
        // Note: Price and SellingPrice are numeric columns; PriceCurrency and SellingPriceCurrency hold the currency type
        $partSql = "
            INSERT INTO Parts 
                (SerialCode, PartName, Description, StockLevel, 
                 Price, PriceCurrency, SellingPrice, SellingPriceCurrency, CategoryID)
            VALUES 
                ('$serialCode', '$partName', '$description', '$stockLevel',
                 '$priceValue', '$priceCurrency', '$sellingPriceValue', '$sellingPriceCurrency', '$categoryId')
        ";

        if ($conn->query($partSql) === TRUE) {
            echo "New part added successfully!";
        } else {
            echo "Error inserting part data: " . $conn->error;
        }
    } else {
        echo "Error inserting category data: " . $conn->error;
    }

    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Part - Car Equipment Shop</title>
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
            left: -250px; /* Initially hidden */
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

        /* Content area */
        .content {
            margin-left: 0;
            padding: 20px;
            flex: 1;
            background-color: #ffffff;
            transition: margin-left 0.3s;
        }
        .content.shift {
            margin-left: 250px; /* Adjusted margin to match sidebar width */
        }

        /* Header */
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

        /* Form Container */
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
        form input, 
        form select, 
        form textarea {
            padding: 10px;
            margin-bottom: 20px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
        }
        form textarea {
            resize: vertical;
            height: 100px;
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
        }
    </style>
</head>
<body>

<!-- Sidebar and Content -->
<div class="content">
    <div class="header">
        <h1>Add New Part</h1>
        <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
    </div>

    <!-- Form to add a new part -->
    <div class="form-container">
        <form action="addingPart.php" method="POST">
            <label for="serial_code">Serial Code:</label>
            <input type="text" name="serial_code" id="serial_code" required>

            <label for="part_name">Part Name:</label>
            <input type="text" name="part_name" id="part_name" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>

            <label for="stock_level">Stock Level:</label>
            <input type="number" name="stock_level" id="stock_level" required>

            <label for="price">Price (Purchase Price):</label>
            <input type="number" name="price" id="price" step="0.01" required>

            <label for="price_currency">Purchase Price Currency:</label>
            <select name="price_currency" id="price_currency" required>
                <option value="USD">$</option>
                <option value="IQD">IQD</option>
            </select>

            <label for="selling_price">Selling Price:</label>
            <input type="number" name="selling_price" id="selling_price" step="0.01" required>

            <label for="selling_price_currency">Selling Price Currency:</label>
            <select name="selling_price_currency" id="selling_price_currency" required>
                <option value="USD">$</option>
                <option value="IQD">IQD</option>
            </select>

            <label for="brand">Brand:</label>
            <select name="brand" id="brand" required onchange="updateModels()">
                <option value="Kia">Kia</option>
                <option value="Hyundai">Hyundai</option>
            </select>

            <label for="model">Model:</label>
            <select name="model" id="model" required>
                <!-- Dynamic models populated via JS -->
            </select>

            <label for="year_range">Year Range:</label>
            <input type="number" name="year_range" id="year_range" required>

            <label for="part_location">Part Location:</label>
            <select name="part_location" id="part_location" required>
                <option value="Outside">Outside</option>
                <option value="Inside">Inside</option>
                <option value="Electronic">Electronic</option>
                <option value="Engine & Transmission">Engine & Transmission</option>
            </select>

            <button type="submit">Add Part</button>
        </form>
    </div>
</div>

<!-- Sidebar for navigation -->
<div class="sidebar" id="sidebar">
    <h2>Categories</h2>
    <ul>
        <li><a href="dashboard.php" class="category-link">Dashboard</a></li>
        <li><a href="addingPart.php" class="category-link">Adding Part</a></li>
        <li><a href="sellingPage.php" class="category-link">Selling Section</a></li>
        <li><a href="lending.php" class="category-link">Lending & Borrow</a></li>
        <li><a href="report.php" class="category-link">Report</a></li>
    </ul>
</div>

<!-- Hamburger Menu -->
<div class="hamburger-menu" onclick="toggleSidebar()">
    &#9776;
</div>

<script>
    // Toggle sidebar
    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        const content = document.getElementById("content");
        sidebar.classList.toggle("active");
        content.classList.toggle("shift");
    }

    // Update models based on the selected brand
    function updateModels() {
        const brand = document.getElementById("brand").value;
        const modelSelect = document.getElementById("model");

        // Clear current options
        modelSelect.innerHTML = "";

        // Hard-coded brand-model mapping for demonstration
        const models = {
            'Kia': ['Kia Model 1', 'Kia Model 2', 'Kia Model 3'],
            'Hyundai': ['Hyundai Model 1', 'Hyundai Model 2', 'Hyundai Model 3']
        };

        models[brand].forEach(model => {
            const option = document.createElement("option");
            option.value = model;
            option.textContent = model;
            modelSelect.appendChild(option);
        });
    }

    // Initialize with Kia models by default
    updateModels();
</script>

</body>
</html>
