<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

$search = $_GET['search'] ?? '';
$sql = "SELECT rid, name, type, description FROM recipes";

if (!empty($search)) {
    $searchTerm = "%" . $conn->real_escape_string($search) . "%";
    $stmt = $conn->prepare("SELECT rid, name, type, description FROM recipes WHERE name LIKE ? OR type LIKE ?");
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Virtual Kitchen - Recipes</title>
    <style>
        body {
            font-family: Arial;
            margin: 40px;
            background: #f9f9f9;
        }
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
        form {
            margin-bottom: 30px;
        }
        input[type="text"] {
            padding: 10px;
            width: 300px;
        }
        input[type="submit"] {
            padding: 10px 20px;
        }
        .recipe {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .recipe h2 {
            margin-top: 0;
        }
        .type {
            font-style: italic;
            color: #777;
        }
        .view-link {
            display: inline-block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .view-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="topbar">
    <?php if (isset($_SESSION['uid'])): ?>
        <strong>👤 Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</strong> |
        <a href="my_recipes.php">📋 My Recipes</a>
        <a href="add_recipe.php">➕ Add Recipe</a>
        <a href="logout.php">🚪 Logout</a>
    <?php else: ?>
        <a href="login.php">🔐 Login</a>
        <a href="register.php">📝 Register</a>
    <?php endif; ?>
</div>

<h1>🍽️ All Recipes</h1>

<form method="GET" action="index.php">
    <input type="text" name="search" placeholder="Search by name or type..." value="<?php echo htmlspecialchars($search); ?>" />
    <input type="submit" value="Search" />
</form>

<?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div class='recipe'>";
        echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
        echo "<p class='type'>Type: " . htmlspecialchars($row['type']) . "</p>";
        echo "<p>" . htmlspecialchars($row['description']) . "</p>";
        echo "<a class='view-link' href='view_recipe.php?rid=" . $row['rid'] . "'>🔍 View Details</a>";
        echo "</div>";
    }
} else {
    echo "<p>No recipes found.</p>";
}
?>

</body>
</html>
