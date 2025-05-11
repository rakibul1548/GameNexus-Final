<?php
// Include database configuration
require_once 'config.php';

// Initialize error and success messages
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $error = 'Username must be between 3 and 50 characters.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } else {
        try {
            // Check for existing username
            $stmt = $pdo->prepare('SELECT id FROM signup WHERE username = ?');
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = 'Username is already taken.';
            }

            // Check for existing email
            $stmt = $pdo->prepare('SELECT id FROM signup WHERE email = ?');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'Email is already registered.';
            }

            // If no errors, proceed with signup
            if (empty($error)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
                $stmt->execute([$username, $email, $hashedPassword]);

                $success = 'Account created successfully! Redirecting to login...';
                header('Refresh: 2; URL=login.php'); // Redirect after 2 seconds
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
  <title>Signup - GameNexus</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('https://cdn.wallpapersafari.com/98/68/q3drmN.jpg') no-repeat center center/cover;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: #fff;
    }

    .signup-container {
      background-color: rgba(0, 0, 0, 0.85);
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 0 20px #ffaa00;
      width: 90%;
      max-width: 400px;
    }

    .signup-container h1 {
      text-align: center;
      margin-bottom: 25px;
      font-size: 30px;
    }

    .signup-container h1 span {
      color: #ffaa00;
    }

    .signup-form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .signup-form input {
      padding: 12px;
      border-radius: 6px;
      border: 1px solid #555;
      background-color: #1f1f1f;
      color: #fff;
      font-size: 16px;
    }

    .signup-form input:focus {
      outline: none;
      border-color: #ffaa00;
    }

    .signup-form button {
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

    .signup-form button:hover {
      background-color: #ffcc00;
    }

    .signup-form p {
      text-align: center;
      margin-top: 10px;
    }

    .signup-form a {
      color: #ffaa00;
      text-decoration: none;
    }

    .error-message {
      color: #ff4444;
      text-align: center;
      margin-top: 10px;
    }

    .success-message {
      color: #00cc00;
      text-align: center;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="signup-container" role="region" aria-label="Signup Form">
    <h1>Join <span>GameNexus</span></h1>
    <form class="signup-form" method="POST" action="signup.php">
      <label for="username" class="sr-only">Username</label>
      <input
        type="text"
        id="username"
        name="username"
        placeholder="Username"
        required
        aria-required="true"
        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
      />
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
      <label for="confirmPassword" class="sr-only">Confirm Password</label>
      <input
        type="password"
        id="confirmPassword"
        name="confirmPassword"
        placeholder="Confirm Password"
        required
        aria-required="true"
      />
      <button type="submit">Create Account</button>
      <p>Already have an account? <a href="login.php">Login here</a></p>
      <?php if ($error): ?>
        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>
      <?php if ($success): ?>
        <p class="success-message"><?php echo htmlspecialchars($success); ?></p>
      <?php endif; ?>
    </form>
  </div>
</body>
</html>