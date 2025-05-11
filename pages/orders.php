<?php
// Database connection
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "game_store";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all orders
$sql = "SELECT * FROM orders ORDER BY order_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order History</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #1a1a2e;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .order-details {
            margin-top: 30px;
        }
        .order-details h2 {
            color: #333;
            margin-bottom: 15px;
        }
        .order-items {
            margin-top: 20px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #1a1a2e;
            text-decoration: none;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order History</h1>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Total</th>
                    <th>Payment Method</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= date('M j, Y g:i A', strtotime($row['order_date'])) ?></td>
                    <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td>$<?= number_format($row['total'], 2) ?></td>
                    <td><?= htmlspecialchars($row['payment_method']) ?></td>
                    <td>
                        <a href="#" onclick="showOrderDetails(<?= $row['id'] ?>)">View Details</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div id="order-details-container" class="order-details" style="display: none;">
            <h2>Order Details</h2>
            <div id="order-details-content"></div>
            <a href="#" class="back-link" onclick="hideOrderDetails()">Back to Orders</a>
        </div>

        <a href="game_store.php" class="back-link">Back to Store</a>
    </div>

    <script>
        function showOrderDetails(orderId) {
            fetch('get_order_details.php?order_id=' + orderId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const order = data.order;
                        const items = JSON.parse(order.items);
                        
                        let html = `
                            <p><strong>Order Date:</strong> ${new Date(order.order_date).toLocaleString()}</p>
                            <p><strong>Customer:</strong> ${order.first_name} ${order.last_name}</p>
                            <p><strong>Email:</strong> ${order.email}</p>
                            <p><strong>Address:</strong> ${order.address}, ${order.city}, ${order.zip_code}, ${order.country}</p>
                            <p><strong>Payment Method:</strong> ${order.payment_method}</p>
                            
                            <div class="order-items">
                                <h3>Items</h3>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Platform</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;
                        
                        items.forEach(item => {
                            html += `
                                <tr>
                                    <td>${item.title}</td>
                                    <td>${item.platform}</td>
                                    <td>$${item.price.toFixed(2)}</td>
                                    <td>${item.quantity}</td>
                                    <td>$${(item.price * item.quantity).toFixed(2)}</td>
                                </tr>
                            `;
                        });
                        
                        html += `
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4">Subtotal</th>
                                            <td>$${parseFloat(order.subtotal).toFixed(2)}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="4">Discount</th>
                                            <td>$${parseFloat(order.discount).toFixed(2)}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="4">Total</th>
                                            <td>$${parseFloat(order.total).toFixed(2)}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        `;
                        
                        document.getElementById('order-details-content').innerHTML = html;
                        document.getElementById('order-details-container').style.display = 'block';
                        window.scrollTo(0, document.body.scrollHeight);
                    } else {
                        alert('Error loading order details: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading order details');
                });
        }

        function hideOrderDetails() {
            document.getElementById('order-details-container').style.display = 'none';
        }
    </script>
</body>
</html>