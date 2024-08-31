<?php
include('./include/header.php');
include("./connection.php");
session_start();

if (!isset($_SESSION['is_login'])) {
    if (isset($_REQUEST['login'])) {
        $email = mysqli_real_escape_string($con, trim($_REQUEST['email']));
        $pass = mysqli_real_escape_string($con, trim($_REQUEST['pass']));
        $sql = "SELECT `a_email`, `a_password` FROM `admin` WHERE `a_email`='" . $email . "' AND `a_password`='" . $pass . "' LIMIT 1";
        $res = $con->query($sql);
        if ($res->num_rows == true) {
            $_SESSION['is_login'] = true;
            $_SESSION['email'] = $email;
            if (isset($_REQUEST['remember'])) {
                setcookie('email', $email, time() + (86400 * 30), "/");
                setcookie('pass', $pass, time() + (86400 * 30), "/");
            } else {
                setcookie('email', '', time() - 3600, "/");
                setcookie('pass', '', time() - 3600, "/");
            }
            echo "<script>location.href='admin/dash.php';</script>";
        } else {
            $lmsg = "<div class='alert alert-danger mt-3'>Unable to Login!!!</div>";
        }
    }
} else {
    echo "<script>location.href='adminlogin.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Login</title>
</head>

<body>
    <div class="text-center mt-5" style="font-size: 30px;">
        <span>Admin Login</span>
        <p class="text-center mt-2" style="font-size: 20px;"><i class="fas fa-user-secret text-danger"></i>Requester Area(Demo)</p>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center mt-2">
            <div class="col-sm-6 col-md-4 mt-2 shadow p-4">
                <form action="" method="POST" class="item-center" style="justify-content:center;">
                    <div class="form-group">
                        <i class="fas fa-user me-2"></i><label for="name" class="font-weight-bold pl-2 my-2"><b>Email</b></label>
                        <input type="email" class="form-control" name="email" placeholder="" value="<?php echo isset($_COOKIE['email']) ? $_COOKIE['email'] : ''; ?>">
                        <small>We will never share your email with anyone...</small>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-key me-2"></i><label for="name" class="font-weight-bold pl-2 my-2"><b>Password</b></label>
                        <div class="input-group">
                            <input type="password" id="password" name="pass" class="form-control" placeholder="Enter password" value="<?php echo isset($_COOKIE['pass']) ? $_COOKIE['pass'] : ''; ?>">
                            <span class="input-group-text" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                    <button type="submit" class="btn btn-outline-danger mt-4 btn-block" name="login">Login</button>
                    <?php if (isset($lmsg)) {
                        echo $lmsg;
                    } ?>
                </form>
            </div>
        </div>
    </div>

    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/pooper.min.js"></script>
    <script src="../js/all.min.js"></script>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function(e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>