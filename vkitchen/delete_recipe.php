<?php
session_start();
include 'db.php';

if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['rid'])) {
    echo "❌ No recipe ID provided.";
    exit();
}

$rid = intval($_GET['rid']);
$uid = $_SESSION['uid'];

// Make sure the user owns this recipe
$stmt = $conn->prepare("SELECT uid FROM recipes WHERE rid = ?");
$stmt->bind_param("i", $rid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1 || $result->fetch_assoc()['uid'] != $uid) {
    echo "❌ You do not have permission to delete this recipe.";
    exit();
}

// Perform delete
$delete = $conn->prepare("DELETE FROM recipes WHERE rid = ? AND uid = ?");
$delete->bind_param("ii", $rid, $uid);

if ($delete->execute()) {
    header("Location: my_recipes.php?deleted=1");
    exit();
} else {
    echo "❌ Failed to delete recipe.";
}
?>
