<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = $_POST["email"];
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <form method="post" action="send-password-reset.php">
        <div class="d-flex flex-column justify-content-center min-vh-100 align-items-center">
            <h1 id="h1">Forgot Password</h1>
            
            <input type="email" name="email" id="email" placeholder="Enter your email">
            <?php if (empty($email)): ?>
                <div class="error-message"><?php echo "No email provided" ?></div>
            <?php endif; ?>
            <button type="submit" name="submit" id="signupbutton">Send</button>
        </div>
    </form>
         
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>

