<?php
// Database connection and functions
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "game_store";

// Create connection
$conn = new mysqli(localhost , $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create orders table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    zip_code VARCHAR(20) NOT NULL,
    country VARCHAR(50) NOT NULL,
    payment_method VARCHAR(20) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    discount DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    items TEXT NOT NULL
)";

if ($conn->query($sql) === FALSE) {
    error_log("Error creating table: " . $conn->error);
}

function saveOrder($data) {
    global $conn;
    
    $stmt = $conn->prepare("INSERT INTO orders (
        first_name, last_name, email, address, city, zip_code, country,
        payment_method, subtotal, discount, total, items
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param(
        "ssssssssddds",
        $data['firstName'],
        $data['lastName'],
        $data['email'],
        $data['address'],
        $data['city'],
        $data['zip'],
        $data['country'],
        $data['paymentMethod'],
        $data['subtotal'],
        $data['discount'],
        $data['total'],
        $data['items']
    );
    
    return $stmt->execute();
}

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'place_order') {
    header('Content-Type: application/json');
    
    try {
        $orderData = [
            'firstName' => $_POST['firstName'],
            'lastName' => $_POST['lastName'],
            'email' => $_POST['email'],
            'address' => $_POST['address'],
            'city' => $_POST['city'],
            'zip' => $_POST['zip'],
            'country' => $_POST['country'],
            'paymentMethod' => $_POST['paymentMethod'],
            'subtotal' => floatval($_POST['subtotal']),
            'discount' => floatval($_POST['discount']),
            'total' => floatval($_POST['total']),
            'items' => json_encode($_POST['items'])
        ];
        
        $success = saveOrder($orderData);
        
        echo json_encode(['success' => $success]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elite Game Vault</title>
    <style>
        /* Your existing CSS styles here */
        :root {
            --primary-color: #1a1a2e;
            --secondary-color: #16213e;
            /* ... rest of your CSS variables ... */
        }
        /* ... rest of your CSS ... */
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Your existing HTML structure here -->
    <div class="cart-icon-container" id="cart-icon">
        <!-- ... rest of your HTML ... -->
    </div>

    <script>
        // Your existing JavaScript code with the updated placeOrder function
        function placeOrder() {
            if (!termsAgree.checked) {
                alert('Please agree to the Terms of Service and Privacy Policy');
                return;
            }
            
            // Collect order data
            const firstName = document.getElementById('first-name').value;
            const lastName = document.getElementById('last-name').value;
            const email = document.getElementById('email').value;
            const address = document.getElementById('address').value;
            const city = document.getElementById('city').value;
            const zip = document.getElementById('zip').value;
            const country = document.getElementById('country').value;
            const paymentMethod = document.querySelector('input[name="payment"]:checked').value;
            
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            let discount = 0;
            
            if (appliedPromo && promoCodes[appliedPromo]) {
                const promoValue = promoCodes[appliedPromo];
                discount = promoValue < 1 ? subtotal * promoValue : promoValue;
            }
            
            const total = Math.max(0, subtotal - discount);
            
            const orderItems = cart.map(item => ({
                id: item.id,
                title: item.title,
                price: item.price,
                quantity: item.quantity,
                platform: item.platform
            }));
            
            // Send data to server
            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'place_order',
                    firstName: firstName,
                    lastName: lastName,
                    email: email,
                    address: address,
                    city: city,
                    zip: zip,
                    country: country,
                    paymentMethod: paymentMethod,
                    subtotal: subtotal,
                    discount: discount,
                    total: total,
                    items: JSON.stringify(orderItems)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show confirmation modal
                    showOrderConfirmation();
                    
                    // Close checkout modal
                    closeCheckoutModal();
                    
                    // Clear cart
                    cart = [];
                    updateCart();
                    appliedPromo = null;
                    promoInput.value = '';
                } else {
                    alert('Error placing order: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error placing order');
            });
        }

        // ... rest of your JavaScript code ...
    </script>
</body>
</html>