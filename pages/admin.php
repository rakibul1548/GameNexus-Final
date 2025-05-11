<?php
// Start session for CSRF token
session_start();

// Database configuration
$servername = "localhost";
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$dbname = "gamenexus2"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create products table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    sku VARCHAR(50) NOT NULL,
    category VARCHAR(100),
    brand VARCHAR(100),
    price DECIMAL(10,2) NOT NULL,
    sale_price DECIMAL(10,2),
    stock INT NOT NULL,
    status VARCHAR(20) NOT NULL,
    description TEXT,
    images TEXT,
    created_at DATETIME NOT NULL
)";
$conn->query($sql);

// Generate or verify CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle form submission
$success_message = $error_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error_message = "Invalid CSRF token.";
    } else {
        // Sanitize inputs
        $product_name = filter_var($_POST['product_name'], FILTER_SANITIZE_STRING);
        $product_sku = filter_var($_POST['product_sku'], FILTER_SANITIZE_STRING);
        $product_category = filter_var($_POST['product_category'], FILTER_SANITIZE_STRING);
        $product_brand = filter_var($_POST['product_brand'], FILTER_SANITIZE_STRING);
        $product_price = filter_var($_POST['product_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $product_sale_price = filter_var($_POST['product_sale_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $product_stock = filter_var($_POST['product_stock'], FILTER_SANITIZE_NUMBER_INT);
        $product_status = filter_var($_POST['product_status'], FILTER_SANITIZE_STRING);
        $product_description = filter_var($_POST['product_description'], FILTER_SANITIZE_STRING);

        // Validate inputs
        if (empty($product_name) || empty($product_sku) || empty($product_category) || empty($product_price) || empty($product_stock) || empty($product_status)) {
            $error_message = "Please fill all required fields.";
        } else {
            // Handle file upload
            $target_dir = "Uploads/";
            $image_paths = [];
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_file_size = 5 * 1024 * 1024; // 5MB

            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            foreach ($_FILES['product_image']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['product_image']['size'][$key] > 0) {
                    $file_type = $_FILES['product_image']['type'][$key];
                    $file_size = $_FILES['product_image']['size'][$key];
                    $file_name = basename($_FILES['product_image']['name'][$key]);

                    if (!in_array($file_type, $allowed_types)) {
                        $error_message = "Only JPEG, PNG, and GIF files are allowed.";
                        break;
                    }

                    if ($file_size > $max_file_size) {
                        $error_message = "File size exceeds 5MB limit.";
                        break;
                    }

                    $target_file = $target_dir . uniqid() . '_' . $file_name;
                    if (move_uploaded_file($tmp_name, $target_file)) {
                        $image_paths[] = $target_file;
                    } else {
                        $error_message = "Failed to upload image: $file_name";
                        break;
                    }
                }
            }

            if (empty($error_message)) {
                $product_images = implode(',', $image_paths);

                // Insert into database
                $sql = "INSERT INTO products (name, sku, category, brand, price, sale_price, stock, status, description, images, created_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssddisss", $product_name, $product_sku, $product_category, $product_brand, 
                                $product_price, $product_sale_price, $product_stock, $product_status, 
                                $product_description, $product_images);

                if ($stmt->execute()) {
                    $success_message = "Product added successfully!";
                } else {
                    $error_message = "Error: " . $stmt->error;
                }

                $stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
’on’t save a memory.
    <title>GameNexus Admin | Premium Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6c5ce7;
            --primary-dark: #5649c0;
            --secondary: #00cec9;
            --success: #00b894;
            --danger: #d63031;
            --warning: #fdcb6e;
            --info: #0984e3;
            --dark: #2d3436;
            --light: #f5f6fa;
            --gray: #dfe6e9;
            --dark-gray: #636e72;
            --sidebar-width: 280px;
            --header-height: 70px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f8f9fb;
            color: var(--dark);
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 20px 0;
            z-index: 100;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
            transform: translateX(0);
        }

        .sidebar-collapsed {
            transform: translateX(calc(var(--sidebar-width) * -1));
        }

        .sidebar-header {
            padding: 0 20px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h3 {
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .sidebar-header h3 i {
            margin-right: 10px;
            color: var(--warning);
        }

        .toggle-sidebar {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .toggle-sidebar:hover {
            color: var(--warning);
        }

        .sidebar-menu {
            padding: 20px 0;
            height: calc(100vh - var(--header-height));
            overflow-y: auto;
        }

        .menu-category {
            padding: 10px 20px;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 10px;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            margin: 2px 0;
        }

        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .menu-item.active {
            background-color: rgba(255, 255, 255, 0.15);
        }

        .menu-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: var(--warning);
        }

        .menu-item i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .menu-item .menu-text {
            flex: 1;
        }

        .menu-item .badge {
            background-color: var(--danger);
            color: white;
            border-radius: 20px;
            padding: 3px 8px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .submenu {
            padding-left: 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .submenu.show {
            max-height: 500px;
        }

        .submenu .menu-item {
            padding: 10px 20px 10px 40px;
            font-size: 0.9rem;
        }

        .submenu .menu-item i {
            font-size: 0.9rem;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: var(--transition);
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* Header Styles */
        .header {
            height: var(--header-height);
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .header-left {
            display: flex;
            align-items: center;
        }

        .header-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--dark);
        }

        .header-right {
            display: flex;
            align-items: center;
        }

        .search-bar {
            position: relative;
            margin-right: 20px;
        }

        .search-bar input {
            padding: 8px 15px 8px 35px;
            border-radius: 20px;
            border: 1px solid var(--gray);
            width: 200px;
            transition: var(--transition);
        }

        .search-bar input:focus {
            width: 250px;
            outline: none;
            border-color: var(--primary);
        }

        .search-bar i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--dark-gray);
        }

        .notification-icon, .message-icon {
            position: relative;
            margin-right: 20px;
            font-size: 1.2rem;
            color: var(--dark-gray);
            cursor: pointer;
        }

        .notification-badge, .message-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6rem;
            font-weight: 600;
        }

        .user-profile {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
            border: 2px solid var(--primary);
        }

        .user-name {
            font-weight: 500;
            margin-right: 5px;
        }

        .user-role {
            font-size: 0.8rem;
            color: var(--dark-gray);
        }

        /* Content Area */
        .content-area {
            padding: 25px;
            min-height: calc(100vh - var(--header-height));
        }

        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
        }

        .card.sales::before {
            background-color: var(--primary);
        }

        .card.orders::before {
            background-color: var(--success);
        }

        .card.customers::before {
            background-color: var(--info);
        }

        .card.products::before {
            background-color: var(--warning);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-title {
            font-size: 0.9rem;
            color: var(--dark-gray);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .card.sales .card-icon {
            background-color: rgba(108, 92, 231, 0.1);
            color: var(--primary);
        }

        .card.orders .card-icon {
            background-color: rgba(0, 184, 148, 0.1);
            color: var(--success);
        }

        .card.customers .card-icon {
            background-color: rgba(9, 132, 227, 0.1);
            color: var(--info);
        }

        .card.products .card-icon {
            background-color: rgba(253, 203, 110, 0.1);
            color: var(--warning);
        }

        .card-value {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .card-change {
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        .card-change i {
            margin-right: 5px;
            font-size: 0.8rem;
        }

        .card-change.positive {
            color: var(--success);
        }

        .card-change.negative {
            color: var(--danger);
        }

        /* Charts */
        .chart-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-title {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .chart-actions select {
            padding: 5px 10px;
            border-radius: 5px;
            border: 1px solid var(--gray);
            background-color: white;
        }

        .chart-placeholder {
            height: 300px;
            background-color: #f8f9fa;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark-gray);
        }

        /* Tables */
        .table-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            overflow-x: auto;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-title {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .table-actions {
            display: flex;
            gap: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--gray);
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--dark);
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .status.pending {
            background-color: #fff4e5;
            color: #f39c12;
        }

        .status.completed {
            background-color: #e8f8f0;
            color: var(--success);
        }

        .status.processing {
            background-color: #e3f2fd;
            color: var(--info);
        }

        .status.cancelled {
            background-color: #fdecea;
            color: var(--danger);
        }

        .status.active {
            background-color: #e8f8f0;
            color: var(--success);
        }

        .status.inactive {
            background-color: #f5f6fa;
            color: var(--dark-gray);
        }

        .product-image {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            object-fit: cover;
        }

        /* Buttons */
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.9rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn i {
            margin-right: 5px;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 0.8rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .btn-success {
            background-color: var(--success);
            color: white;
        }

        .btn-success:hover {
            background-color: #00a884;
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-warning {
            background-color: var(--warning);
            color: var(--dark);
        }

        .btn-warning:hover {
            background-color: #fcb731;
        }

        .btn-info {
            background-color: var(--info);
            color: white;
        }

        .btn-info:hover {
            background-color: #0984e3;
        }

        .btn-light {
            background-color: var(--light);
            color: var(--dark);
        }

        .btn-light:hover {
            background-color: #e0e3e8;
        }

        /* Forms */
        .form-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--gray);
            border-radius: 5px;
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.2);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-row .form-group {
            flex: 1;
        }

        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 1px solid var(--gray);
            margin-bottom: 20px;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            font-weight: 500;
            color: var(--dark-gray);
            transition: var(--transition);
        }

        .tab.active {
            border-bottom-color: var(--primary);
            color: var(--primary);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Modals */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }

        .modal.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background-color: white;
            border-radius: 10px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.2);
            transform: translateY(-20px);
            transition: var(--transition);
        }

        .modal.active .modal-content {
            transform: translateY(0);
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid var(--gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.3rem;
            font-weight: 600;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--dark-gray);
            transition: var(--transition);
        }

        .close-modal:hover {
            color: var(--danger);
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid var(--gray);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* Alerts */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .sidebar {
                transform: translateX(calc(var(--sidebar-width) * -1));
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .toggle-sidebar-mobile {
                display: block !important;
                margin-right: 15px;
                font-size: 1.3rem;
                color: var(--dark-gray);
                cursor: pointer;
            }
        }

        @media (max-width: 768px) {
            .dashboard-cards {
                grid-template-columns: 1fr;
            }

            .header-right {
                display: none;
            }

            .mobile-menu {
                display: block !important;
            }

            .search-bar input {
                width: 150px;
            }

            .search-bar input:focus {
                width: 180px;
            }

            .form-row {
                flex-direction: column;
            }
        }

        /* Utility Classes */
        .text-primary { color: var(--primary); }
        .text-success { color: var(--success); }
        .text-danger { color: var(--danger); }
        .text-warning { color: var(--warning); }
        .text-info { color: var(--info); }
        .text-dark { color: var(--dark); }
        .text-gray { color: var(--dark-gray); }
        .mb-0 { margin-bottom: 0 !important; }
        .mt-0 { margin-top: 0 !important; }
        .d-flex { display: flex; }
        .align-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-10 { gap: 10px; }
        .w-100 { width: 100%; }
        .toggle-sidebar-mobile, .mobile-menu { display: none; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-gamepad"></i> GameNexus</h3>
            <button class="toggle-sidebar" id="toggle-sidebar">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>
        <div class="sidebar-menu">
            <div class="menu-item active" data-tab="dashboard">
                <i class="fas fa-tachometer-alt"></i>
                <span class="menu-text">Dashboard</span>
            </div>

            <div class="menu-category">Store Management</div>
            <div class="menu-item" data-tab="products">
                <i class="fas fa-gamepad"></i>
                <span class="menu-text">Products</span>
                <span class="badge">3</span>
            </div>
            <div class="menu-item" data-tab="categories">
                <i class="fas fa-tags"></i>
                <span class="menu-text">Categories</span>
            </div>
            <div class="menu-item" data-tab="orders">
                <i class="fas fa-shopping-cart"></i>
                <span class="menu-text">Orders</span>
                <span class="badge">5</span>
            </div>
            <div class="menu-item" data-tab="customers">
                <i class="fas fa-users"></i>
                <span class="menu-text">Customers</span>
            </div>
            <div class="menu-item" data-tab="reviews">
                <i class="fas fa-star"></i>
                <span class="menu-text">Reviews</span>
            </div>

            <div class="menu-category">Content Management</div>
            <div class="menu-item has-submenu">
                <i class="fas fa-newspaper"></i>
                <span class="menu-text">Blog</span>
                <i class="fas fa-chevron-down submenu-toggle"></i>
            </div>
            <div class="submenu">
                <div class="menu-item" data-tab="blog-posts">
                    <i class="fas fa-file-alt"></i>
                    <span class="menu-text">Posts</span>
                </div>
                <div class="menu-item" data-tab="blog-categories">
                    <i class="fas fa-folder"></i>
                    <span class="menu-text">Categories</span>
                </div>
                <div class="menu-item" data-tab="blog-tags">
                    <i class="fas fa-tag"></i>
                    <span class="menu-text">Tags</span>
                </div>
            </div>

            <div class="menu-category">System</div>
            <div class="menu-item" data-tab="settings">
                <i class="fas fa-cog"></i>
                <span class="menu-text">Settings</span>
            </div>
            <div class="menu-item" data-tab="users">
                <i class="fas fa-user-shield"></i>
                <span class="menu-text">Admin Users</span>
            </div>
            <div class="menu-item" data-tab="backup">
                <i class="fas fa-database"></i>
                <span class="menu-text">Backup</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <button class="toggle-sidebar-mobile" id="toggle-sidebar-mobile">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="header-title">Dashboard</div>
            </div>
            <div class="header-right">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
                <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
                <div class="message-icon">
                    <i class="fas fa-envelope"></i>
                    <span class="message-badge">5</span>
                </div>
                <div class="user-profile">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="user-avatar">
                    <div>
                        <span class="user-name">Admin User</span>
                        <span class="user-role">Super Admin</span>
                    </div>
                    <i class="fas fa-chevron-down mobile-menu"></i>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Dashboard Tab -->
            <div class="tab-content active" id="dashboard-tab">
                <div class="dashboard-cards">
                    <div class="card sales">
                        <div class="card-header">
                            <div class="card-title">Total Sales</div>
                            <div class="card-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                        <div class="card-value">$24,780</div>
                        <div class="card-change positive">
                            <i class="fas fa-arrow-up"></i> 12% from last month
                        </div>
                    </div>
                    <div class="card orders">
                        <div class="card-header">
                            <div class="card-title">Total Orders</div>
                            <div class="card-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                        <div class="card-value">1,245</div>
                        <div class="card-change positive">
                            <i class="fas fa-arrow-up"></i> 8% from last month
                        </div>
                    </div>
                    <div class="card customers">
                        <div class="card-header">
                            <div class="card-title">Total Customers</div>
                            <div class="card-icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="card-value">856</div>
                        <div class="card-change positive">
                            <i class="fas fa-arrow-up"></i> 5% from last month
                        </div>
                    </div>
                    <div class="card products">
                        <div class="card-header">
                            <div class="card-title">Total Products</div>
                            <div class="card-icon">
                                <i class="fas fa-gamepad"></i>
                            </div>
                        </div>
                        <div class="card-value">156</div>
                        <div class="card-change negative">
                            <i class="fas fa-arrow-down"></i> 2% from last month
                        </div>
                    </div>
                </div>

                <div class="chart-container">
                    <div class="chart-header">
                        <div class="chart-title">Sales Analytics</div>
                        <div class="chart-actions">
                            <select>
                                <option>Last 7 Days</option>
                                <option>Last 30 Days</option>
                                <option selected>Last 90 Days</option>
                                <option>This Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="chart-placeholder">
                        <i class="fas fa-chart-line" style="font-size: 2rem; margin-right: 10px;"></i>
                        Sales Chart Will Appear Here
                    </div>
                </div>

                <div class="table-container">
                    <div class="table-header">
                        <div class="table-title">Recent Orders</div>
                        <div class="table-actions">
                            <button class="btn btn-light">
                                <i class="fas fa-file-export"></i> Export
                            </button>
                            <button class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New
                            </button>
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#ORD-2023-001</td>
                                <td>
                                    <div class="d-flex align-center">
                                        <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="User" class="user-avatar" style="width: 30px; height: 30px; margin-right: 10px;">
                                        John Doe
                                    </div>
                                </td>
                                <td>2023-05-15</td>
                                <td>$129.99</td>
                                <td><span class="status completed">Completed</span></td>
                                <td>
                                    <button class="btn btn-sm btn-light">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>#ORD-2023-002</td>
                                <td>
                                    <div class="d-flex align-center">
                                        <img src="https://randomuser.me/api/portraits/women/2.jpg" alt="User" class="user-avatar" style="width: 30px; height: 30px; margin-right: 10px;">
                                        Jane Smith
                                    </div>
                                </td>
                                <td>2023-05-14</td>
                                <td>$89.99</td>
                                <td><span class="status processing">Processing</span></td>
                                <td>
                                    <button class="btn btn-sm btn-light">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>#ORD-2023-003</td>
                                <td>
                                    <div class="d-flex align-center">
                                        <img src="https://randomuser.me/api/portraits/men/3.jpg" alt="User" class="user-avatar" style="width: 30px; height: 30px; margin-right: 10px;">
                                        Robert Johnson
                                    </div>
                                </td>
                                <td>2023-05-13</td>
                                <td>$199.99</td>
                                <td><span class="status completed">Completed</span></td>
                                <td>
                                    <button class="btn btn-sm btn-light">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>#ORD-2023-004</td>
                                <td>
                                    <div class="d-flex align-center">
                                        <img src="https://randomuser.me/api/portraits/women/4.jpg" alt="User" class="user-avatar" style="width: 30px; height: 30px; margin-right: 10px;">
                                        Emily Davis
                                    </div>
                                </td>
                                <td>2023-05-12</td>
                                <td>$59.99</td>
                                <td><span class="status pending">Pending</span></td>
                                <td>
                                    <button class="btn btn-sm btn-light">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Products Tab -->
            <div class="tab-content" id="products-tab">
                <div class="table-container">
                    <div class="table-header">
                        <div class="table-title">Products Management</div>
                        <div class="table-actions">
                            <button class="btn btn-light">
                                <i class="fas fa-file-export"></i> Export
                            </button>
                            <button class="btn btn-primary" id="add-product-btn">
                                <i class="fas fa-plus"></i> Add Product
                            </button>
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch products from the database
                            $sql = "SELECT * FROM products ORDER BY created_at DESC";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    // Determine the first image if available
                                    $images = !empty($row['images']) ? explode(',', $row['images']) : [];
                                    $first_image = !empty($images) ? htmlspecialchars($images[0]) : 'https://via.placeholder.com/50';
                                    $status_class = ($row['stock'] > 0 && $row['status'] == 'active') ? 'active' : 'inactive';
                                    $status_text = ($row['stock'] > 0 && $row['status'] == 'active') ? 'Active' : 'Out of Stock';
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                                        <td>
                                            <div class="d-flex align-center">
                                                <img src="<?php echo $first_image; ?>" alt="Product" class="product-image" style="margin-right: 10px;">
                                                <?php echo htmlspecialchars($row['name']); ?>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                                        <td>৳<?php echo number_format($row['price'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($row['stock']); ?></td>
                                        <td><span class="status <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                        <td>
                                            <button class="btn btn-sm btn-light" onclick="viewProduct(<?php echo $row['id']; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" onclick="editProduct(<?php echo $row['id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteProduct(<?php echo $row['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="7" style="text-align: center;">No products found.</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Orders Tab -->
            <div class="tab-content" id="orders-tab">
                <div class="table-container">
                    <div class="table-header">
                        <div class="table-title">Orders Management</div>
                        <div class="table-actions">
                            <button class="btn btn-light">
                                <i class="fas fa-file-export"></i> Export
                            </button>
                            <button class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New
                            </button>
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#ORD-2023-001</td>
                                <td>John Doe</td>
                                <td>2023-05-15</td>
                                <td>$129.99</td>
                                <td><span class="status completed">Completed</span></td>
                                <td>
                                    <button class="btn btn-sm btn-light">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>#ORD-2023-002</td>
                                <td>Jane Smith</td>
                                <td>2023-05-14</td>
                                <td>$89.99</td>
                                <td><span class="status processing">Processing</span></td>
                                <td>
                                    <button class="btn btn-sm btn-light">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>#ORD-2023-003</td>
                                <td>Robert Johnson</td>
                                <td>2023-05-13</td>
                                <td>$199.99</td>
                                <td><span class="status completed">Completed</span></td>
                                <td>
                                    <button class="btn btn-sm btn-light">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Customers Tab -->
            <div class="tab-content" id="customers-tab">
                <div class="table-container">
                    <div class="table-header">
                        <div class="table-title">Customers Management</div>
                        <div class="table-actions">
                            <button class="btn btn-light">
                                <i class="fas fa-file-export"></i> Export
                            </button>
                            <button class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New
                            </button>
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Orders</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <div class="d-flex align-center">
                                        <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="User" class="user-avatar" style="width: 30px; height: 30px; margin-right: 10px;">
                                        John Doe
                                    </div>
                                </td>
                                <td>john@example.com</td>
                                <td>+123456789</td>
                                <td>5</td>
                                <td>2023-01-15</td>
                                <td>
                                    <button class="btn btn-sm btn-light">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
órias do usuário sejam apagadas, conforme solicitado.
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <div class="d-flex align-center">
                                        <img src="https://randomuser.me/api/portraits/women/2.jpg" alt="User" class="user-avatar" style="width: 30px; height: 30px; margin-right: 10px;">
                                        Jane Smith
                                    </div>
                                </td>
                                <td>jane@example.com</td>
                                <td>+987654321</td>
                                <td>3</td>
                                <td>2023-02-20</td>
                                <td>
                                    <button class="btn btn-sm btn-light">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>
                                    <div class="d-flex align-center">
                                        <img src="https://randomuser.me/api/portraits/men/3.jpg" alt="User" class="user-avatar" style="width: 30px; height: 30px; margin-right: 10px;">
                                        Robert Johnson
                                    </div>
                                </td>
                                <td>robert@example.com</td>
                                <td>+1122334455</td>
                                <td>8</td>
                                <td>2023-03-10</td>
                                <td>
                                    <button class="btn btn-sm btn-light">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Settings Tab -->
            <div class="tab-content" id="settings-tab">
                <div class="form-container">
                    <div class="tabs">
                        <div class="tab active" data-settings-tab="general">General</div>
                        <div class="tab" data-settings-tab="payment">Payment</div>
                        <div class="tab" data-settings-tab="shipping">Shipping</div>
                        <div class="tab" data-settings-tab="email">Email</div>
                    </div>

                    <div class="settings-tab-content active" id="general-settings">
                        <form id="general-settings-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="store-name">Store Name</label>
                                    <input type="text" id="store-name" class="form-control" value="GameNexus">
                                </div>
                                <div class="form-group">
                                    <label for="store-email">Store Email</label>
                                    <input type="email" id="store-email" class="form-control" value="contact@gamenexus.com">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="store-phone">Store Phone</label>
                                    <input type="text" id="store-phone" class="form-control" value="+880123456789">
                                </div>
                                <div class="form-group">
                                    <label for="store-currency">Currency</label>
                                    <select id="store-currency" class="form-control">
                                        <option value="USD">US Dollar ($)</option>
                                        <option value="EUR">Euro (€)</option>
                                        <option value="GBP">British Pound (£)</option>
                                        <option value="BDT" selected>Bangladeshi Taka (৳)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="store-logo">Store Logo</label>
                                <input type="file" id="store-logo" class="form-control">
                                <small class="text-gray">Recommended size: 200x50 pixels</small>
                            </div>
                            <div class="form-group">
                                <label for="store-description">Store Description</label>
                                <textarea id="store-description" class="form-control">Your premier destination for gaming products in Bangladesh</textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>

                    <div class="settings-tab-content" id="payment-settings">
                        <form id="payment-settings-form">
                            <div class="form-group">
                                <label>Payment Methods</label>
                                <div class="form-check">
                                    <input type="checkbox" id="credit-card" class="form-check-input" checked>
                                    <label for="credit-card" class="form-check-label">Credit Card</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" id="paypal" class="form-check-input" checked>
                                    <label for="paypal" class="form-check-label">PayPal</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" id="bkash" class="form-check-input" checked>
                                    <label for="bkash" class="form-check-label">bKash</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" id="nagad" class="form-check-input">
                                    <label for="nagad" class="form-check-label">Nagad</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" id="cod" class="form-check-input" checked>
                                    <label for="cod" class="form-check-label">Cash on Delivery</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="test-mode">Test Mode</label>
                                <div class="form-check">
                                    <input type="checkbox" id="test-mode" class="form-check-input">
                                    <label for="test-mode" class="form-check-label">Enable test mode for payment gateways</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>

                    <div class="settings-tab-content" id="shipping-settings">
                        <form id="shipping-settings-form">
                            <div class="form-group">
                                <label>Shipping Methods</label>
                                <div class="form-check">
                                    <input type="checkbox" id="standard" class="form-check-input" checked>
                                    <label for="standard" class="form-check-label">Standard Shipping</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" id="express" class="form-check-input" checked>
                                    <label for="express" class="form-check-label">Express Shipping</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" id="local-pickup" class="form-check-input">
                                    <label for="local-pickup" class="form-check-label">Local Pickup</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="shipping-cost">Standard Shipping Cost (৳)</label>
                                    <input type="number" id="shipping-cost" class="form-control" value="60">
                                </div>
                                <div class="form-group">
                                    <label for="express-cost">Express Shipping Cost (৳)</label>
                                    <input type="number" id="express-cost" class="form-control" value="120">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="free-shipping">Free Shipping Threshold (৳)</label>
                                <input type="number" id="free-shipping" class="form-control" value="1000">
                                <small class="text-gray">Set to 0 to disable free shipping</small>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>

                    <div class="settings-tab-content" id="email-settings">
                        <form id="email-settings-form">
                            <div class="form-group">
                                <label for="smtp-host">SMTP Host</label>
                                <input type="text" id="smtp-host" class="form-control" value="smtp.gamenexus.com">
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="smtp-port">SMTP Port</label>
                                    <input type="number" id="smtp-port" class="form-control" value="587">
                                </div>
                                <div class="form-group">
                                    <label for="smtp-encryption">Encryption</label>
                                    <select id="smtp-encryption" class="form-control">
                                        <option value="tls">TLS</option>
                                        <option value="ssl">SSL</option>
                                        <option value="none">None</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="smtp-username">SMTP Username</label>
                                    <input type="text" id="smtp-username" class="form-control" value="contact@gamenexus.com">
                                </div>
                                <div class="form-group">
                                    <label for="smtp-password">SMTP Password</label>
                                    <input type="password" id="smtp-password" class="form-control" value="********">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="from-email">From Email</label>
                                <input type="email" id="from-email" class="form-control" value="noreply@gamenexus.com">
                            </div>
                            <div class="form-group">
                                <label for="from-name">From Name</label>
                                <input type="text" id="from-name" class="form-control" value="GameNexus">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal" id="add-product-modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Add New Product</div>
                <button class="close-modal">×</button>
            </div>
            <div class="modal-body">
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                
                <form id="add-product-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="product-name">Product Name</label>
                            <input type="text" id="product-name" name="product_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="product-sku">SKU</label>
                            <input type="text" id="product-sku" name="product_sku" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="product-category">Category</label>
                            <select id="product-category" name="product_category" class="form-control" required>
                                <option value="">Select Category</option>
                                <option value="action">Action</option>
                                <option value="adventure">Adventure</option>
                                <option value="rpg">RPG</option>
                                <option value="racing">Racing</option>
                                <option value="shooter">Shooter</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="product-brand">Brand</label>
                            <select id="product-brand" name="product_brand" class="form-control">
                                <option value="">Select Brand</option>
                                <option value="sony">Sony</option>
                                <option value="microsoft">Microsoft</option>
                                <option value="nintendo">Nintendo</option>
                                <option value="ea">Electronic Arts</option>
                                <option value="ubisoft">Ubisoft</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="product-price">Price (৳)</label>
                            <input type="number" id="product-price" name="product_price" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="product-sale-price">Sale Price (৳)</label>
                            <input type="number" id="product-sale-price" name="product_sale_price" class="form-control" step="0.01">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="product-stock">Stock Quantity</label>
                            <input type="number" id="product-stock" name="product_stock" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="product-status">Status</label>
                            <select id="product-status" name="product_status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="product-description">Description</label>
                        <textarea id="product-description" name="product_description" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="product-image">Product Images</label>
                        <input type="file" id="product-image" name="product_image[]" class="form-control" multiple accept="image/*">
                        <small class="text-gray">Upload high-quality images (JPEG, PNG, GIF, max 5MB each)</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" id="cancel-product-btn">Cancel</button>
                        <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // DOM Elements
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const toggleSidebar = document.getElementById('toggle-sidebar');
        const toggleSidebarMobile = document.getElementById('toggle-sidebar-mobile');
        const menuItems = document.querySelectorAll('.menu-item');
        const submenuToggles = document.querySelectorAll('.submenu-toggle');
        const tabContents = document.querySelectorAll('.tab-content');
        const settingsTabs = document.querySelectorAll('[data-settings-tab]');
        const settingsTabContents = document.querySelectorAll('.settings-tab-content');
        const addProductBtn = document.getElementById('add-product-btn');
        const addProductModal = document.getElementById('add-product-modal');
        const closeModalBtns = document.querySelectorAll('.close-modal');
        const addProductForm = document.getElementById('add-product-form');

        // Toggle Sidebar
        toggleSidebar.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-collapsed');
            mainContent.classList.toggle('expanded');
            toggleSidebar.innerHTML = sidebar.classList.contains('sidebar-collapsed') 
                ? '<i class="fas fa-chevron-right"></i>' 
                : '<i class="fas fa-chevron-left"></i>';
        });

        // Toggle Mobile Sidebar
        toggleSidebarMobile.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Menu Item Click
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                menuItems.forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                tabContents.forEach(tab => tab.classList.remove('active'));
                const tabId = this.getAttribute('data-tab');
                if (tabId) {
                    document.getElementById(`${tabId}-tab`).classList.add('active');
                    document.querySelector('.header-title').textContent = this.querySelector('.menu-text').textContent;
                }
            });
        });

        // Submenu Toggle
        submenuToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const submenu = this.parentElement.nextElementSibling;
                submenu.classList.toggle('show');
                this.classList.toggle('fa-chevron-down');
                this.classList.toggle('fa-chevron-up');
            });
        });

        // Settings Tab Switching
        settingsTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                settingsTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                settingsTabContents.forEach(content => content.classList.remove('active'));
                const tabId = this.getAttribute('data-settings-tab');
                document.getElementById(`${tabId}-settings`).classList.add('active');
            });
        });

        // Add Product Modal
        addProductBtn.addEventListener('click', () => {
            addProductModal.classList.add('active');
            addProductForm.reset();
        });

        // Close Modal
        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.modal').classList.remove('active');
            });
        });

        // Close Modal When Clicking Outside
        window.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) {
                e.target.classList.remove('active');
            }
        });

        // Form Validation and Submission
        addProductForm.addEventListener('submit', function(e) {
            const price = document.getElementById('product-price').value;
            const stock = document.getElementById('product-stock').value;
            const images = document.getElementById('product-image').files;

            if (price <= 0) {
                e.preventDefault();
                alert('Price must be greater than 0.');
                return;
            }

            if (stock < 0) {
                e.preventDefault();
                alert('Stock quantity cannot be negative.');
                return;
            }

            for (let i = 0; i < images.length; i++) {
                if (images[i].size > 5 * 1024 * 1024) {
                    e.preventDefault();
                    alert('Each image must be less than 5MB.');
                    return;
                }
                if (!['image/jpeg', 'image/png', 'image/gif'].includes(images[i].type)) {
                    e.preventDefault();
                    alert('Only JPEG, PNG, and GIF images are allowed.');
                    return;
                }
            }
        });

        // Product Action Functions
        function viewProduct(id) {
            alert('View product with ID: ' + id);
            // Implement view logic, e.g., open a modal with product details
        }

        function editProduct(id) {
            alert('Edit product with ID: ' + id);
            // Implement edit logic, e.g., open a modal with a pre-filled form
        }

        function deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                // Implement delete logic, e.g., send AJAX request to delete product
                fetch('delete_product.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Product deleted successfully!');
                        location.reload(); // Reload to refresh the table
                    } else {
                        alert('Failed to delete product: ' + data.error);
                    }
                })
                .catch(error => {
                    alert('Error: ' + error);
                });
            }
        }

        // Mock Form Submissions (Replace with actual AJAX calls in production)
        ['general-settings-form', 'payment-settings-form', 'shipping-settings-form', 'email-settings-form'].forEach(formId => {
            document.getElementById(formId).addEventListener('submit', function(e) {
                e.preventDefault();
                alert(`${formId.replace('-form', '').replace('-', ' ')} saved successfully!`);
            });
        });

        // Initialize with dashboard active
        document.getElementById('dashboard-tab').classList.add('active');
    </script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>