<?php
session_start();
require ("connect-db.php");    // include("connect-db.php");
require ("request-db.php");
?>

<?php
$warning = false;
$redirect = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = getUser($username);

    if ($user && password_verify($password, $user['login_password'])) {
        $_SESSION['username'] = $username;
        $_SESSION['user'] = $user;
        // header("Location: home.php");
        $redirect = "home.php";
    } else {
        $warning = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
</head>

<body>
    <script>
        const redirect = <?= json_encode($redirect) ?>;
        if (redirect) {
            window.location.href = redirect;
        }
    </script>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h3 class="text-center">Login</h3>
                    </div>
                    <div class="card-body">
                        <form action="login.php" method="post">
                            <div class="form-group mb-2">
                                <label for="username">Username/Email</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group mb-4">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <?php if ($warning) { ?>
                                <div class="alert alert-danger mt-4" role="alert">
                                    Invalid username or password.
                                </div>
                            <?php } ?>

                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html