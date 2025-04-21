<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

// Make sure only logged-in users can add recipes
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $type = trim($_POST['type']);
    $description = trim($_POST['description']);
    $cookingtime = intval($_POST['cookingtime']);
    $ingredients = trim($_POST['ingredients']);
    $instructions = trim($_POST['instructions']);
    $uid = $_SESSION['uid'];

    // Validation
    if (empty($name) || empty($type) || empty($description) || empty($ingredients) || empty($instructions) || $cookingtime <= 0) {
        $msg = "❌ All fields are required and cooking time must be greater than 0.";
    } elseif (!in_array($type, ['French', 'Italian', 'Chinese', 'Indian', 'Mexican', 'Others'])) {
        $msg = "❌ Invalid recipe type.";
    } else {
        $stmt = $conn->prepare("INSERT INTO recipes (name, type, description, cookingtime, ingredients, instructions, uid) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssissi", $name, $type, $description, $cookingtime, $ingredients, $instructions, $uid);

        if ($stmt->execute()) {
            $msg = "✅ Recipe added successfully!";
        } else {
            $msg = "❌ Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Recipe</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; padding: 40px; }
        form { background: white; padding: 20px; max-width: 600px; margin: auto; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        input, textarea, select { width: 100%; padding: 10px; margin: 10px 0; }
        button { padding: 10px 20px; }
        .msg { color: green; margin-top: 10px; }
    </style>
</head>
<body>
    <form method="POST" action="">
        <h2>🍳 Add a New Recipe</h2>
        <input type="text" name="name" placeholder="Recipe Name" required />
        <select name="type" required>
            <option value="">-- Select Type --</option>
            <option value="French">French</option>
            <option value="Italian">Italian</option>
            <option value="Chinese">Chinese</option>
            <option value="Indian">Indian</option>
            <option value="Mexican">Mexican</option>
            <option value="Others">Others</option>
        </select>
        <input type="number" name="cookingtime" placeholder="Cooking Time (minutes)" required />
        <textarea name="description" placeholder="Short description..." required></textarea>
        <textarea name="ingredients" placeholder="Ingredients..." required></textarea>
        <textarea name="instructions" placeholder="Cooking Instructions..." required></textarea>
        <button type="submit">Add Recipe</button>
        <p class="msg"><?php echo $msg; ?></p>
        <p><a href="index.php">← Back to recipes</a></p>
    </form>
</body>
</html>
