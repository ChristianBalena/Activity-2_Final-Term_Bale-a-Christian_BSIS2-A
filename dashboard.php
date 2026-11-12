
<?php
require_once "config.php";

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: index.php");
    exit();
}

$totaluser = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$totalpending = $conn->query("SELECT COUNT(*) AS total FROM users WHERE status = 'pending'")->fetch_assoc()['total'];
$totalproduct = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <style>
       
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    background: #F2F2F0;
    color: #30352F;
}

/* Navigation */
.nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    padding: 20px 7%;
    background: #FFFFFF;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.logo {
    font-size: 22px;
    font-weight: bold;
    color: #596458;
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 30px;
}

.nav a {
    text-decoration: none;
    color: #62665F;
    font-size: 15px;
    transition: 0.3s;
}

.nav a:hover,
.nav a.active {
    color: #596458;
    font-weight: bold;
}

.nav .logout {
    background: #596458;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
}

.nav .logout:hover {
    background: #434D43;
}

/* Main */
.main {
    padding: 50px 7%;
}

.welcome {
    margin-bottom: 35px;
}

.welcome h1 {
    font-size: 32px;
    color: #30352F;
    margin-bottom: 8px;
}

.welcome p {
    color: #85877F;
}

/* Cards */
.cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
}

.card {
    background: #FFFFFF;
    padding: 30px;
    border-radius: 8px;
    border: 1px solid #E3E3DF;
    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.card-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 23px;
    margin-bottom: 20px;
}

.users-icon {
    background: #E6EAE4;
}

.pending-icon {
    background: #EEEAE0;
}

.products-icon {
    background: #E4E8E2;
}

.card h3 {
    font-size: 15px;
    color: #85877F;
    font-weight: 500;
    margin-bottom: 12px;
}

.card h2 {
    font-size: 34px;
    color: #596458;
}
    </style>
</head>

<body>

    <div class="nav">
        <div class="logo">☕︎ Coffee Shop Inventory System </div>

        <div class="nav-links">
            <a href="dashboard.php" class="active">Dashboard</a>
            <a href="products.php">Products</a>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="users.php">User Management</a>
            <?php endif; ?>

            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <div class="main">

        <div class="welcome">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! </h1>
            <p>Here's an overview of your system today.</p>
        </div>

        <div class="cards">

            <div class="card">
                <div class="card-icon users-icon"></div>
                <h3>Total Users</h3>
                <h2><?php echo $totaluser; ?></h2>
            </div>

            <div class="card">
                <div class="card-icon pending-icon"></div>
                <h3>Not Approved Users</h3>
                <h2><?php echo $totalpending; ?></h2>
            </div>

            <div class="card">
                <div class="card-icon products-icon"></div>
                <h3>Total Products</h3>
                <h2><?php echo $totalproduct; ?></h2>
            </div>

        </div>
    </div>

</body>
</html>
