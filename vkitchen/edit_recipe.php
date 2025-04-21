<?php
session_start();
include 'db.php';

if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['rid'])) {
    echo "❌ Recipe ID missing.";
    exit();
}

$rid = intval($_GET['rid']);
$uid = $_SESSION['uid'];

// Check that the recipe belongs to this user
$stmt = $conn->prepare("SELECT * FROM recipes WHERE rid = ? AND uid = ?");
$stmt->bind_param("ii", $rid, $uid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "❌ You are not allowed to edit this recipe.";
    exit();
}

$recipe = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Recipe</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; padding: 40px; }
        form { background: white; padding: 20px; max-width: 600px; margin: auto; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        input, textarea, select { width: 100%; padding: 10px; margin: 10px 0; }
        button { padding: 10px 20px; }
    </style>
</head>
<body>
    <form method="POST" action="save_recipe.php">
        <h2>✏️ Edit Recipe</h2>
        <input type="hidden" name="rid" value="<?php echo $recipe['rid']; ?>" />
        <input type="text" name="name" value="<?php echo htmlspecialchars($recipe['name']); ?>" required />
        <select name="type" required>
            <?php
            $types = ["French", "Italian", "Chinese", "Indian", "Mexican", "Others"];
            foreach ($types as $type) {
                $selected = $type == $recipe['type'] ? "selected" : "";
                echo "<option value='$type' $selected>$type</option>";
            }
            ?>
        </select>
        <input type="number" name="cookingtime" value="<?php echo $recipe['cookingtime']; ?>" required />
        <textarea name="description" required><?php echo htmlspecialchars($recipe['description']); ?></textarea>
        <textarea name="ingredients" required><?php echo htmlspecialchars($recipe['ingredients']); ?></textarea>
        <textarea name="instructions" required><?php echo htmlspecialchars($recipe['instructions']); ?></textarea>
        <button type="submit">Save Changes</button>
        <p><a href="index.php">← Back to recipes</a></p>
    </form>
</body>
</html>
