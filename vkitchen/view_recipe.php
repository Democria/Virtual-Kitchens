<?php
include 'db.php';
session_start();

$rid = $_GET['rid'] ?? null;

if (!$rid) {
    die("No recipe selected.");
}

$stmt = $conn->prepare("SELECT r.*, u.username FROM recipes r JOIN users u ON r.uid = u.uid WHERE r.rid = ?");
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
    <title><?php echo htmlspecialchars($recipe['name']); ?></title>
    <style>
        body { font-family: Arial; padding: 40px; background: #f9f9f9; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 700px; margin: auto; box-shadow: 0 3px 8px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; }
        p { margin: 10px 0; }
        .back { margin-top: 20px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo htmlspecialchars($recipe['name']); ?></h2>
        <p><strong>Type:</strong> <?php echo htmlspecialchars($recipe['type']); ?></p>
        <p><strong>Cooking Time:</strong> <?php echo $recipe['cookingtime']; ?> mins</p>
        <p><strong>Description:</strong><br><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
        <p><strong>Ingredients:</strong><br><?php echo nl2br(htmlspecialchars($recipe['ingredients'])); ?></p>
        <p><strong>Instructions:</strong><br><?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?></p>
        <p><strong>Posted by:</strong> <?php echo htmlspecialchars($recipe['username']); ?></p>

        <a class="back" href="index.php">← Back to All Recipes</a>
    </div>
</body>
</html>
