<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION['uid'];
$rid = $_GET['rid'] ?? null;
$msg = "";

if (!$rid) {
    die("No recipe ID provided.");
}

// Get the recipe and check ownership
$stmt = $conn->prepare("SELECT * FROM recipes WHERE rid = ? AND uid = ?");
$stmt->bind_param("ii", $rid, $uid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Recipe not found or you do not have permission.");
}

$recipe = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $type = $_POST['type'];
    $description = trim($_POST['description']);
    $cookingtime = intval($_POST['cookingtime']);
    $ingredients = trim($_POST['ingredients']);
    $instructions = trim($_POST['instructions']);

    if ($name && $type && $description && $cookingtime && $ingredients && $instructions) {
        $update = $conn->prepare("UPDATE recipes SET name = ?, type = ?, description = ?, cookingtime = ?, ingredients = ?, instructions = ? WHERE rid = ? AND uid = ?");
        $update->bind_param("sssissii", $name, $type, $description, $cookingtime, $ingredients, $instructions, $rid, $uid);

        if ($update->execute()) {
            $msg = "Recipe updated successfully!";
        } else {
            $msg = "Error: " . $conn->error;
        }
    } else {
        $msg = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Recipe</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; padding: 40px; }
        form { background: white; padding: 20px; max-width: 600px; margin: auto; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        input, textarea, select { width: 100%; padding: 10px; margin: 10px 0; }
        button { padding: 10px 20px; }
        .msg { color: green; margin-top: 10px; }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Update Recipe</h2>
        <input type="text" name="name" value="<?php echo htmlspecialchars($recipe['name']); ?>" required />
        <select name="type" required>
            <?php
            $types = ['French', 'Italian', 'Chinese', 'Indian', 'Mexican', 'others'];
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
        <a style="color: red;" href="delete_recipe.php?rid=<?php echo $row['rid']; ?>" onclick="return confirm('Are you sure you want to delete this recipe?');">Delete</a>
        <p class="msg"><?php echo $msg; ?></p>
        <p><a href="my_recipes.php">← Back to My Recipes</a></p>
    </form>
</body>
</html>
