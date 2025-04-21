<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        $msg = "❌ All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "❌ Invalid email format.";
    } elseif (strlen($password) < 6) {
        $msg = "❌ Password must be at least 6 characters.";
    } else {
        $checkStmt = $conn->prepare("SELECT uid FROM users WHERE username = ? OR email = ?");
        $checkStmt->bind_param("ss", $username, $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $msg = "❌ Username or email already exists.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                $msg = "✅ Registration successful! <a href='login.php'>Login here</a>";
            } else {
                $msg = "❌ Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding: 40px; }
        form {
            background: white;
            padding: 25px;
            max-width: 400px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        button { padding: 10px 20px; width: 100%; background: #28a745; color: white; border: none; border-radius: 5px; }
        .msg { margin-top: 10px; text-align: center; color: #d9534f; }
        .msg a { color: #007bff; text-decoration: none; }
        .nav-links { margin-top: 20px; text-align: center; }
    </style>
</head>
<body>
    <form method="POST" action="">
        <h2>Register</h2>
        <input type="text" name="username" placeholder="Username" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password (min 6 chars)" required />
        <button type="submit">Register</button>
        <p class="msg"><?php echo $msg; ?></p>
        <div class="nav-links">
            <p><a href="login.php">🔐 Already have an account? Login</a></p>
            <p><a href="index.php">← Back to recipes</a></p>
        </div>
    </form>
</body>
</html>
