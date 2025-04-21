<?php
session_start();
include 'db.php';

if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = $_SESSION['uid'];
    $rid = intval($_POST['rid']);
    $name = trim($_POST['name']);
    $type = trim($_POST['type']);
    $description = trim($_POST['description']);
    $cookingtime = intval($_POST['cookingtime']);
    $ingredients = trim($_POST['ingredients']);
    $instructions = trim($_POST['instructions']);

    // Validate
    if (
        empty($name) || empty($type) || empty($description) ||
        empty($ingredients) || empty($instructions) || $cookingtime <= 0
    ) {
        echo "❌ All fields are required.";
        exit();
    }

    // Check ownership
    $check = $conn->prepare("SELECT uid FROM recipes WHERE rid = ?");
    $check->bind_param("i", $rid);
    $check->execute();
    $result = $check->get_result();
    if ($result->num_rows !== 1 || $result->fetch_assoc()['uid'] !== $uid) {
        echo "❌ You do not have permission to edit this recipe.";
        exit();
    }

    // Update
    $stmt = $conn->prepare("UPDATE recipes SET name=?, type=?, description=?, cookingtime=?, ingredients=?, instructions=? WHERE rid=? AND uid=?");
    $stmt->bind_param("sssissii", $name, $type, $description, $cookingtime, $ingredients, $instructions, $rid, $uid);

    if ($stmt->execute()) {
        header("Location: my_recipes.php");
        exit();
    } else {
        echo "❌ Update failed: " . $conn->error;
    }
}
?>
