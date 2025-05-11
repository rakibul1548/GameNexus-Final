<?php
// Start session
session_start();

// Include database configuration
require_once 'config.php';

// Initialize error message
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Validate inputs
    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            // Query user by email
            $stmt = $pdo->prepare('SELECT id, username, email, password FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Successful login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                header('Location: dashboard.php'); // Changed to dashboard.php
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        } catch (PDOException $e) {
            $error = 'An error occurred. Please try again later.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - GameNexus</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('https://wallpaperaccess.com/full/308644.jpg') no-repeat center center/cover;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: #fff;
    }

    .login-container {
      background-color: rgba(0, 0, 0, 0.85);
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 0 20px #ffaa00;
      width: 90%;
      max-width: 400px;
    }

    .login-container h1 {
      text-align: center;
      margin-bottom: 25px;
      font-size: 30px;
    }

    .login-container h1 span {
      color: #ffaa00;
    }

    .login-form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .login-form input {
      padding: 12px;
      border-radius: 6px;
      border: 1px solid #555;
      background-color: #1f1f1f;
      color: #fff;
      font-size: 16px;
    }

    .login-form input:focus {
      outline: none;
      border-color: #ffaa00;
    }

    .login-form button {
      padding: 12px;
      border: none;
      border-radius: 6px;
      background-color: #ffaa00;
      color: #000;
      font-weight: bold;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }

    .login-form button:hover {
      background-color: #ffcc00;
    }

    .login-form p {
      text-align: center;
      margin-top: 10px;
    }

    .login-form a {
      color: #ffaa00;
      text-decoration: none;
    }

    .error-message {
      color: #ff4444;
      text-align: center;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="login-container" role="region" aria-label="Login Form">
    <h1>Welcome to <span>GameNexus</span></h1>
    <form class="login-form" method="POST" action="login.php">
      <label for="email" class="sr-only">Email</label>
      <input
        type="email"
        id="email"
        name="email"
        placeholder="Email"
        required
        aria-required="true"
        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
      />
      <label for="password" class="sr-only">Password</label>
      <input
        type="password"
        id="password"
        name="password"
        placeholder="Password"
        required
        aria-required="true"
      />
      <button type="submit">Login</button>
      <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
      <?php if ($error): ?>
        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>
    </form>
  </div>
</body>
</html>