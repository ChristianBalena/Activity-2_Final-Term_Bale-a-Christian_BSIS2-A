
<?php
require_once "config.php";

// Session validation
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: index.php");
    exit();
}

// Admin access only
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>

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
            font-size: 30px;
            color: #30352F;
            margin-bottom: 8px;
        }

        .page-title p {
            color: #85877F;
            font-size: 14px;
        }

        /* Table Container */
        .table-container {
            background-color: #FFFFFF;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #E3E3DF;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        }

        .table-container h2 {
            color: #596458;
            font-size: 21px;
            margin-bottom: 8px;
        }

        .table-container .subtitle {
            color: #85877F;
            font-size: 14px;
            margin-bottom: 25px;
        }

        /* Table */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        thead {
            background-color: #596458;
            color: #FFFFFF;
        }

        th, td {
            padding: 15px 12px;
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

        /* Status */
        .status {
            display: inline-block;
            padding: 6px 13px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .approved {
            background-color: #E3EBE1;
            color: #4D674E;
        }

        .pending {
            background-color: #EEEAE0;
            color: #8A7650;
        }

        .disabled {
            background-color: #F1E6E2;
            color: #895C50;
        }

        /* Role */
        .role {
            display: inline-block;
            padding: 6px 13px;
            border-radius: 20px;
            background-color: #E8E9E5;
            color: #596458;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        /* Action Buttons */
        .action-btn {
            display: inline-block;
            text-decoration: none;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .approve-btn {
            background-color: #596458;
            color: white;
        }

        .approve-btn:hover {
            background-color: #434D43;
        }

        .disable-btn {
            background-color: #F1E6E2;
            color: #895C50;
        }

        .disable-btn:hover {
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
            <a href="products.php">Products</a>
            <a href="users.php" class="active">User Management</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>

    </div>

    <!-- Main Content -->
    <div class="main">

        <div class="page-title">
            <h1>User Management</h1>
            <p>Manage user accounts, approve registrations, and control access.</p>
        </div>

        <div class="table-container">

            <h2>Registered Users</h2>
            <p class="subtitle">View and manage all registered accounts in your system.</p>

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th>Date Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php

                        $fetchquery = "SELECT * FROM users";
                        $fetch = mysqli_query($conn, $fetchquery);

                        if (mysqli_num_rows($fetch) > 0) {

                            while ($user = mysqli_fetch_assoc($fetch)) {

                        ?>

                            <tr>

                                <td>
                                    <?php echo htmlspecialchars($user['username']); ?>
                                </td>

                                <td>
                                    <span class="status <?php echo htmlspecialchars($user['status']); ?>">
                                        <?php echo htmlspecialchars($user['status']); ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="role">
                                        <?php echo htmlspecialchars($user['role']); ?>
                                    </span>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($user['created_at']); ?>
                                </td>

                                <td>

                                    <?php if ($user['status'] !== 'approved'): ?>

                                        <a
                                            href="approveuser.php?userid=<?php echo (int)$user['id']; ?>"
                                            class="action-btn approve-btn"
                                            onclick="return confirm('Are you sure you want to approve this user?');"
                                        >
                                            Approve
                                        </a>

                                    <?php else: ?>

                                        <a
                                            href="disableuser.php?userid=<?php echo (int)$user['id']; ?>"
                                            class="action-btn disable-btn"
                                            onclick="return confirm('Are you sure you want to disable this user?');"
                                        >
                                            Disable
                                        </a>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php

                            }

                        } else {

                        ?>

                            <tr>
                                <td colspan="5" class="empty">
                                    No users found.
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
