<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT uid, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password using password_verify
        if (password_verify($password, $user['password'])) {
            $_SESSION['uid'] = $user['uid'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            $msg = "❌ Incorrect password.";
        }
    } else {
        $msg = "❌ User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; padding: 40px; }
        form { background: white; padding: 20px; max-width: 400px; margin: auto; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        button { padding: 10px 20px; width: 100%; }
        .msg { color: red; margin-top: 10px; text-align: center; }
        a { text-decoration: none; color: #007bff; }
    </style>
</head>
<body>
    <form method="POST" action="">
        <h2>🔐 Login</h2>
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit">Login</button>
        <?php if ($msg): ?>
            <p class="msg"><?php echo $msg; ?></p>
        <?php endif; ?>
        <p><a href="register.php">Don't have an account? Register</a></p>
        <p><a href="index.php">← Back to recipes</a></p>
    </form>
</body>
</html>
