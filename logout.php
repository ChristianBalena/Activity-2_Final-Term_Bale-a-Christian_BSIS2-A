
<?php
session_start();

session_unset();
session_destroy();

header("Location: index.php");
exit();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <style>
        .nav {
            display: flex;
            flex-direction: row;
            gap: 20px;
        }

        .nav a {
            text-decoration: none;
            color: black;
        }

        .nav a:hover {
            text-decoration: underline;
            color: green;
        }
    </style>
</head>
<body>
    <div class="nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="products.php">Products</a>
        <a href="users.php">User Management</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main">

    </div>
</body>
</html>