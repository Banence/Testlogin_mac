<?php
$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $mysqli = require __DIR__ . "/database.php";

    $sql = sprintf("SELECT * FROM user
                    WHERE email = '%s'",
        $mysqli->real_escape_string($_POST["email"]));

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();

    if ($user) {

        if (password_verify($_POST["password"], $user["password_hash"])) {

            session_start();

            session_regenerate_id();

            $_SESSION["user_id"] = $user["id"];

            header("Location: index.php");

            exit;
        }
    }

    $is_invalid = true;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/73fca10ead.js" crossorigin="anonymous"></script>
</head>
<body>
    <form method="post">
        <div class="d-flex flex-column justify-content-center min-vh-100 align-items-center">
            <h1 id="h1">Login</h1>
            <input type="email" name="email" id="email"
                value="<?= htmlspecialchars($_POST["email"] ?? "") ?>" placeholder="Email">
            <div class="pass_input">
                <input type="password" name="password" id="password" placeholder="Password">
                <i id="icon" class="fa-regular fa-eye-slash fa-lg" onclick="togglePasswordVisibility()"></i>
            </div>
            <button id="signupbutton">Log in</button>
            <?php if ($is_invalid): ?>
                <em class="error-message">Invalid login</em>
            <?php endif; ?>
            <a href="forgot-password.html">Forgot password?</a>
            <a href="signup.php">Don't have an account?</a>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="show_password.js"></script>
</body>
</html>
