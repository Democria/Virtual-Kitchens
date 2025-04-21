<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

// Get the recipe ID from the URL
if (!isset($_GET['rid'])) {
    die("Recipe ID not provided.");
}

$rid = intval($_GET['rid']);

// Prepare and run query to get the recipe + user who owns it
$sql = "SELECT r.*, u.username 
        FROM recipes r 
        JOIN users u ON r.uid = u.uid 
        WHERE r.rid = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $rid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Recipe not found.");
}

$recipe = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($recipe['name']); ?> - Recipe</title>
    <style>
        body { font-family: Arial; margin: 40px; background: #f9f9f9; }
        .container { background: white; padding: 20px; border-radius: 10px; max-width: 800px; margin: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { margin-top: 0; }
        .meta { color: #666; margin-bottom: 20px; }
        .section { margin-top: 20px; }
        a { display: inline-block; margin-top: 20px; color: #007BFF; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($recipe['name']); ?></h1>
        <p class="meta">
            Type: <?php echo htmlspecialchars($recipe['type']); ?> |
            Cooking Time: <?php echo intval($recipe['cookingtime']); ?> minutes |
            Posted by: <?php echo htmlspecialchars($recipe['username']); ?>
        </p>

        <div class="section">
            <h3>Description</h3>
            <p><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
        </div>

        <div class="section">
            <h3>Ingredients</h3>
            <p><?php echo nl2br(htmlspecialchars($recipe['ingredients'])); ?></p>
        </div>

        <div class="section">
            <h3>Instructions</h3>
            <p><?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?></p>
        </div>

        <a href="index.php">← Back to recipes</a>
    </div>
</body>
</html>
