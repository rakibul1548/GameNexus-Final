<?php
// Start session and check authentication
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user data from session
$username = htmlspecialchars($_SESSION['username']);
$email = htmlspecialchars($_SESSION['email']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameNexus Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6c5ce7;
            --primary-dark: #5649c0;
            --success: #00b894;
            --danger: #d63031;
            --warning: #fdcb6e;
            --dark: #2d3436;
            --light: #f5f6fa;
            --sidebar-width: 250px;
            --header-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f8f9fb;
            color: var(--dark);
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
            transition: all 0.3s ease;
            z-index: 100;
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
            display: flex;
            align-items: center;
        }

        .sidebar-header h3 i {
            margin-right: 10px;
            color: var(--warning);
        }

        .sidebar-menu {
            padding: 20px 0;
            height: calc(100vh - var(--header-height));
            overflow-y: auto;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .menu-item.active {
            background-color: rgba(255, 255, 255, 0.15);
            border-left: 4px solid var(--warning);
        }

        .menu-item i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        .menu-text {
            flex: 1;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
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

        .header-title {
            font-size: 1.2rem;
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

        /* Content Area */
        .content-area {
            padding: 25px;
            min-height: calc(100vh - var(--header-height));
        }

        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-title {
            font-size: 0.9rem;
            color: #636e72;
            text-transform: uppercase;
        }

        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            background-color: rgba(108, 92, 231, 0.1);
            color: var(--primary);
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

        .card-change.positive {
            color: var(--success);
        }

        .card-change.negative {
            color: var(--danger);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .mobile-menu-btn {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-gamepad"></i> GameNexus</h3>
        </div>
        <div class="sidebar-menu">
            <div class="menu-item active">
                <i class="fas fa-tachometer-alt"></i>
                <span class="menu-text">Dashboard</span>
            </div>
            <div class="menu-item">
                <i class="fas fa-gamepad"></i>
                <span class="menu-text">Games</span>
            </div>
            <div class="menu-item">
                <i class="fas fa-users"></i>
                <span class="menu-text">Users</span>
            </div>
            <div class="menu-item">
                <i class="fas fa-cog"></i>
                <span class="menu-text">Settings</span>
            </div>
            <div class="menu-item">
                <i class="fas fa-sign-out-alt"></i>
                <span class="menu-text"><a href="logout.php" style="color: inherit; text-decoration: none;">Logout</a></span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <button class="mobile-menu-btn" id="mobile-menu-btn" style="display: none;">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="header-title">Dashboard</div>
            </div>
            <div class="user-profile">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($username); ?>&background=6c5ce7&color=fff" 
                     alt="User" class="user-avatar">
                <div>
                    <span class="user-name"><?php echo $username; ?></span>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <h2>Welcome back, <?php echo $username; ?>!</h2>
            <p>Here's what's happening with your store today.</p>

            <div class="dashboard-cards">
                <div class="card">
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
                <div class="card">
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
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Total Users</div>
                        <div class="card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="card-value">856</div>
                    <div class="card-change positive">
                        <i class="fas fa-arrow-up"></i> 5% from last month
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Total Games</div>
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
        </div>
    </div>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        
        // Show mobile menu button on small screens
        function checkScreenSize() {
            if (window.innerWidth <= 768) {
                mobileMenuBtn.style.display = 'block';
                sidebar.classList.remove('active');
            } else {
                mobileMenuBtn.style.display = 'none';
                sidebar.classList.add('active');
            }
        }
        
        // Initial check
        checkScreenSize();
        
        // Add event listener for resize
        window.addEventListener('resize', checkScreenSize);
        
        // Toggle sidebar on mobile
        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
        
        // Menu item click handler
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                // Remove active class from all items
                menuItems.forEach(i => i.classList.remove('active'));
                // Add active class to clicked item
                this.classList.add('active');
                
                // Close sidebar on mobile after selection
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>