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
$stmt = $conn->prepare("SELECT rid, name, type, description FROM recipes WHERE uid = ?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Recipes</title>
    <style>
        body { font-family: Arial; padding: 40px; background: #f5f5f5; }
        .topbar {
            margin-bottom: 30px;
            background: #fff;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .topbar a {
            margin-right: 15px;
            text-decoration: none;
            color: #007bff;
        }
        .topbar a:hover {
            text-decoration: underline;
        }
        h2 { margin-bottom: 20px; }
        .recipe {
            background: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .recipe h3 { margin: 0 0 10px 0; }
        .btn {
            margin-top: 10px;
            display: inline-block;
            background: #007BFF;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            margin-right: 10px;
        }
        .btn-danger {
            background: #dc3545;
        }
    </style>
</head>
<body>

<div class="topbar">
    👤 Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> |
    <a href="add_recipe.php">➕ Add Recipe</a>
    <a href="index.php">🏠 All Recipes</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<h2>📋 My Recipes</h2>

<?php while ($row = $result->fetch_assoc()): ?>
    <div class="recipe">
        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
        <p><strong>Type:</strong> <?php echo htmlspecialchars($row['type']); ?></p>
        <p><?php echo htmlspecialchars($row['description']); ?></p>
        <a class="btn" href="update_recipe.php?rid=<?php echo $row['rid']; ?>">✏️ Update</a>
        <a class="btn btn-danger" href="delete_recipe.php?rid=<?php echo $row['rid']; ?>" onclick="return confirm('Are you sure you want to delete this recipe?');">🗑️ Delete</a>
    </div>
<?php endwhile; ?>

<?php if ($result->num_rows == 0): ?>
    <p>You haven't posted any recipes yet.</p>
<?php endif; ?>

</body>
</html>
