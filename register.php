    <?php
        require_once "config.php";

        function h($str){
            return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
        }

        $mess = [];
        $errors =[];

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

        $uname = trim($_POST['uname'] ?? '');
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';

        if ($password === $password2) {

            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

            if ($stmt) {
                $stmt->bind_param('ss', $uname, $password);

                if ($stmt->execute()) {
                $mess[] = "Registered Successfully.\nPlease wait for admin approval of your account.";
                } else {
                    $errors[] = "Something went wrong. Please try again later.";
                }

                $stmt->close();

            } else {
                $errors[] = "Database error. Please try again later.";
            }

        } else {
            $errors[] = "Passwords do not match.";
        }
    }

    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
    </head>
    <body>
        <h1>Welcome!</h1>
        <h2>Create your Account</h2>
        <br><br>
        <?php foreach ($errors as $e): ?>
        <p style="color: red;"><?= h($e)?></p>
        <?php endforeach;?>
        <br><br>
        <?php foreach ($mess as $m): ?>
            <p style="color: green; text-align: center;">
                <?= nl2br(h($m)) ?>
            </p>
        <?php endforeach;?>
        <form action ="" method = "post">
            <label for= "">Username: </label>
            <input type="text" name="uname" id="" placeholder="Enter username" required focus>
            <br><br>
            <label for= "">Password: </label>
            <input type="password" name="password" id="" placeholder="Enter pasword" required>
            <br><br>
            <label for= "">Confirm password: </label>
            <input type="password" name="password2" id="" placeholder="Confirm pasword" required>
            <br><br>
            <input type="submit" value="Register">
        </form>
        <br><br>
        <p>Alredy have an Account? <a href="index.php">Sign In</p>
    </body>
    </html>

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* BODY */
    body {
        min-height: 100vh;
        width: 100%;

        background: white;
        font-family: Arial, sans-serif;
        color: #222;

        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;

        position: relative;
        isolation: isolate;
    }

    /* GRAY LOGIN CONTAINER */
    body::before {
        content: "";
        position: fixed;

        width: 316px;
        height: 390px;

        background-color: #f1f1f1;

        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);

        z-index: -1;
    }

    /* KEEP CONTENT ABOVE BACKGROUND */
    body > * {
        position: relative;
    }

    /* MAIN HEADING */
    h1 {
        font-family: Georgia, serif;
        font-size: 25px;
        font-weight: bold;
        text-align: center;

        margin-bottom: 12px;
    }

    /* SECOND HEADING */
    h2 {
        font-size: 16px;
        font-weight: bold;
        text-align: center;
    }

    /* HIDE EXTRA BREAKS */
    body > br {
        display: none;
    }

    /* FORM */
    form {
        width: 236px;

        display: flex;
        flex-direction: column;
        align-items: center;

        margin-top: 42px;
    }

    /* HIDE LABELS */
    form label {
        display: none;
    }

    /* HIDE BREAKS INSIDE FORM */
    form br {
        display: none;
    }

    /* TEXT AND PASSWORD INPUTS */
    form input[type="text"],
    form input[type="password"] {
        width: 100%;
        height: 31px;

        padding: 0 13px;
        margin-bottom: 11px;

        border: 1px solid #999;
        border-radius: 25px;

        background: white;
        font-size: 11px;

        outline: none;
    }

    form input[type="text"]:focus,
    form input[type="password"]:focus {
        border-color: #596459;
    }

    /* LOGIN BUTTON */
    form input[type="submit"] {
        width: 100%;
        height: 32px;

        margin-top: 1px;

        background-color: #596459;
        color: white;

        border: none;
        cursor: pointer;

        font-size: 11px;
        transition: background 0.3s;
    }

    form input[type="submit"]:hover {
        background-color: #414b41;
    }

    /* ERROR MESSAGES */
    body > p:not(:last-of-type) {
        color: red;
        font-size: 11px;
        text-align: center;
        margin-top: 5px;
    }

    /* REGISTER TEXT */
    body > p:last-of-type {
        color: #777;
        font-size: 10px;
        text-align: center;
        margin-top: 12px;
    }

    body > p:last-of-type a {
        color: #596459;
        text-decoration: none;
        margin-left: 3px;
    }

    body > p:last-of-type a:hover {
        text-decoration: underline;
    }
    </style>