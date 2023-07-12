<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<?php
    $invalid = false;
    $nameError = $emailError = $passwordError = $confirmError = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Validate form inputs
        if (empty($_POST["name"])) {
            $invalid = true;
            $nameError = "Name is required";
        }

        if (empty($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $invalid = true;
            $emailError = "Valid email is required";
        }

        if (empty($_POST["password"])) {
            $invalid = true;
            $passwordError = "Password is required";
        } elseif (strlen($_POST["password"]) < 8 || !preg_match("/[a-z]/i", $_POST["password"]) || !preg_match("/[0-9]/", $_POST["password"])) {
            $invalid = true;
            $passwordError = "Password must be at least 8 characters and contain at least one letter and number";
        }

        if ($_POST["password"] !== $_POST["password_confirmation"]) {
            $invalid = true;
            $confirmError = "Passwords must match";
        }

        if (!$invalid) {
            require __DIR__ . "/database.php";
            
            try {
                $existingEmailQuery = "SELECT email FROM user WHERE email = ?";
                $stmt_existingEmail = $mysqli->prepare($existingEmailQuery);
                $stmt_existingEmail->bind_param("s", $_POST["email"]);
                $stmt_existingEmail->execute();
                $stmt_existingEmail->store_result();

                if ($stmt_existingEmail->num_rows > 0) {
                    $invalid = true;
                    $emailError = "Email already taken";
                } else {
                    $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
                    $insertQuery = "INSERT INTO user (name, email, password_hash) VALUES (?, ?, ?)";
                    $stmt_insert = $mysqli->prepare($insertQuery);
                    $stmt_insert->bind_param("sss", $_POST["name"], $_POST["email"], $password_hash);

                    if ($stmt_insert->execute()) {
                        header("Location: signup-success.html");
                        exit;
                    }
                }
            } catch (mysqli_sql_exception $exception) {
                die("Error: " . $exception->getMessage());
            }
        }
    } else {
        // Clear error messages when the form has not been submitted
        $nameError = $emailError = $passwordError = $confirmError = "";
    }
    ?>
    <form action="signup.php" method="post" id="signup" novalidate>
        <div class="d-flex flex-column justify-content-center min-vh-100 align-items-center">  
            <h1 id="h1">Signup</h1>  
            <div>
                <input type="text" id="name" name="name" placeholder="Name">
                <?php if ($invalid && !empty($nameError)): ?>
                    <div class="error-message"><?php echo $nameError; ?></div>
                <?php endif; ?>
            </div>
            
            <div>
                <input type="email" id="email" name="email" placeholder="Email">
                <?php if ($invalid && !empty($emailError)): ?>
                    <div class="error-message"><?php echo $emailError; ?></div>
                <?php endif; ?>
            </div>
        
            <div>
                <input type="password" id="password" name="password" placeholder="Password">
                <?php if ($invalid && !empty($passwordError)): ?>
                    <div class="error-message"><?php echo $passwordError; ?></div>
                <?php endif; ?>
            </div>
        
            <div>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm password">
                <?php if ($invalid && !empty($confirmError)): ?>
                    <div class="error-message"><?php echo $confirmError; ?></div>
                <?php endif; ?>
            </div>
        
            <button id="signupbutton" type="submit">Sign up</button>

            <a href="login.php">Already have an account? Sign in</a>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
