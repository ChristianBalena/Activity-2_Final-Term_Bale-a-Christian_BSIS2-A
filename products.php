
<?php
require_once "config.php";

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: index.php");
    exit();
}

// Add Product
if (isset($_POST['addproductbtn'])) {

    $name = trim($_POST['product_name']);
    $desc = trim($_POST['description']);
    $q = (int) $_POST['quantity'];
    $price = (float) $_POST['price'];

    $userid = $_SESSION['user_id'];

    if ($q < 0 || $price < 0 || $name === '' || $desc === '') {
        header("Location: products.php?error=1");
        exit();
    }

    $insertquery = $conn->prepare(
        "INSERT INTO products (name, description, quantity, price, created_by)
         VALUES (?, ?, ?, ?, ?)"
    );

    $insertquery->bind_param("ssidi", $name, $desc, $q, $price, $userid);

    if ($insertquery->execute()) {
        header("Location: products.php?saved=1");
        exit();
    } else {
        header("Location: products.php?error=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background-color: #F2F2F0;
            color: #30352F;
        }

        /* Navigation */
        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            padding: 20px 7%;
            background-color: #FFFFFF;
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
            background-color: #596458;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .nav .logout:hover {
            background-color: #434D43;
        }

        /* Main Content */
        .main {
            padding: 40px 7%;
        }

        .page-title {
            margin-bottom: 30px;
        }

        .page-title h1 {
            color: #30352F;
            font-size: 30px;
            margin-bottom: 8px;
        }

        .page-title p {
            color: #85877F;
            font-size: 14px;
        }

        /* Add Product Form */
        .form-container {
            background-color: #FFFFFF;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #E3E3DF;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
            margin-bottom: 35px;
        }

        .form-container h2 {
            color: #596458;
            margin-bottom: 25px;
            font-size: 21px;
        }

        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            color: #555D53;
            font-size: 14px;
            font-weight: 600;
        }

        input, textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #D8D8D2;
            border-radius: 5px;
            background-color: #FAFAF8;
            color: #30352F;
            font-size: 14px;
            outline: none;
            transition: 0.3s;
        }

        input:focus, textarea:focus {
            border-color: #596458;
            box-shadow: 0 0 0 3px rgba(89,100,88,0.10);
        }

        textarea {
            resize: vertical;
        }

        .full-width {
            grid-column: span 2;
        }

        .add-btn {
            background-color: #596458;
            color: white;
            padding: 13px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: 0.3s;
        }

        .add-btn:hover {
            background-color: #434D43;
        }

        /* Messages */
        .message {
            padding: 13px 16px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .success {
            background-color: #E6EAE4;
            color: #435B45;
            border: 1px solid #CCD9C9;
        }

        .error {
            background-color: #F1E6E2;
            color: #815449;
            border: 1px solid #E2CFC7;
        }

        /* Product Table */
        .table-container {
            background-color: #FFFFFF;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #E3E3DF;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        }

        .table-container h2 {
            color: #596458;
            margin-bottom: 25px;
            font-size: 21px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 750px;
        }

        thead {
            background-color: #596458;
            color: #FFFFFF;
        }

        th, td {
            padding: 14px 12px;
            text-align: center;
            font-size: 14px;
            border-bottom: 1px solid #E8E8E3;
        }

        th {
            font-weight: 600;
        }

        tbody tr {
            transition: 0.2s;
        }

        tbody tr:hover {
            background-color: #F5F6F3;
        }

        td {
            color: #62665F;
        }

        /* Action Buttons */
        .action-btn {
            display: inline-block;
            text-decoration: none;
            padding: 7px 13px;
            border-radius: 4px;
            font-size: 12px;
            margin: 2px;
            transition: 0.3s;
        }

        .edit-btn {
            background-color: #E6EAE4;
            color: #4D614D;
        }

        .edit-btn:hover {
            background-color: #D4DED1;
        }

        .delete-btn {
            background-color: #F1E6E2;
            color: #895C50;
        }

        .delete-btn:hover {
            background-color: #E7D4CE;
        }

        .empty {
            padding: 30px;
            color: #85877F;
        }

    </style>
</head>

<body>

    <!-- Navigation -->
    <div class="nav">

        <div class="logo">☕︎ Coffee Shop Inventory System</div>

        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>

            <a href="products.php" class="active">Products</a>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="users.php">User Management</a>
            <?php endif; ?>

            <a href="logout.php" class="logout">Logout</a>
        </div>

    </div>

    <div class="main">

        <!-- Page Title -->
        <div class="page-title">
            <h1>Products</h1>
            <p>Manage your coffee shop products and inventory.</p>
        </div>

        <!-- Messages -->
        <?php if (isset($_GET['saved'])): ?>
            <div class="message success">
                Product saved successfully!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="message error">
                Unable to save product. Please check your information and try again.
            </div>
        <?php endif; ?>

        <!-- Add Product -->
        <div class="form-container">

            <h2>+ Add New Product</h2>

            <form action="" method="POST">

                <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input
                        type="text"
                        name="product_name"
                        id="product_name"
                        placeholder="Enter product name"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input
                        type="number"
                        name="quantity"
                        id="quantity"
                        placeholder="Enter quantity"
                        min="0"
                        required
                    >
                </div>

                <div class="form-group full-width">
                    <label for="description">Description</label>
                    <textarea
                        name="description"
                        id="description"
                        rows="4"
                        placeholder="Enter product description"
                        required
                    ></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input
                        type="number"
                        name="price"
                        id="price"
                        placeholder="Enter price"
                        min="0"
                        step="0.01"
                        required
                    >
                </div>

                <div class="form-group" style="justify-content: end;">
                    <button type="submit" name="addproductbtn" class="add-btn">
                        Add Product
                    </button>
                </div>

            </form>

        </div>

        <!-- Product List -->
        <div class="table-container">

            <h2>Product List</h2>

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Date Saved</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $fetchproduct = mysqli_query($conn, "SELECT * FROM products");

                        if (mysqli_num_rows($fetchproduct) > 0) {

                            while ($product = mysqli_fetch_assoc($fetchproduct)) {
                        ?>

                            <tr>
                                <td><?php echo (int)$product['id']; ?></td>

                                <td>
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($product['description']); ?>
                                </td>

                                <td>
                                    <?php echo (int)$product['quantity']; ?>
                                </td>

                                <td>
                                    ₱<?php echo number_format((float)$product['price'], 2); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($product['created_at']); ?>
                                </td>

                                <td>
                                    <a
                                        href="editproduct.php?productid=<?php echo (int)$product['id']; ?>"
                                        class="action-btn edit-btn"
                                    >
                                        Edit
                                    </a>

                                    <a
                                        href="deleteproduct.php?productid=<?php echo (int)$product['id']; ?>"
                                        class="action-btn delete-btn"
                                        onclick="return confirm('Are you sure you want to delete this product?');"
                                    >
                                        Delete
                                    </a>
                                </td>
                            </tr>

                        <?php
                            }

                        } else {
                        ?>

                            <tr>
                                <td colspan="7" class="empty">
                                    No products found. Add your first product!
                                </td>
                            </tr>

                        <?php
                        }
                        ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</body>
</html>
