<?php
session_start();

$username  = '';
$userError = '';
$passError = '';

if (isset($_POST['sub'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Correct credentials
    if ($username === 'admin' && $password === 'Suresh@123') {
        $_SESSION['login'] = true;
        header('Location: login.php'); // go to admin listing
        exit;
    }

    if ($username !== 'admin') {
        $userError = 'Invalid Username';
    }

    // ✅ compare with real password, not the word "password"
    if ($password !== 'Suresh@123') {
        $passError = 'Invalid Password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <title>Login</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.css">

    <style>
        .center {
            margin: 40px auto;
            width: 70%;
            max-width: 600px;
            border: 3px solid #169BD7;
            padding: 20px;
            border-radius: 6px;
        }
        .error {
            color: #d9534f;
            font-size: 0.9rem;
        }
        input[type="text"],
        input[type="password"] {
            max-width: 300px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-right">
            <div class="col-md-12">
                <center>
                    <img src="title.png" alt="Smiley face">
                </center>
                <br>
                <div class="center">
                    <form name="input" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <br>

                        <label for="username"></label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="<?php echo htmlspecialchars($username); ?>"
                        />
                        admin
                        <div class="error"><?php echo $userError; ?></div>

                        <br><br>

                        <label for="password"></label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                        />
                        password
                        <div class="error"><?php echo $passError; ?></div>

                        <br><br>

                        <input type="submit" value="Submit" name="sub" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="common.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" charset="utf-8"></script>
    <script src="js/bootstrap.min.js" charset="utf-8"></script>
</body>

</html>
