<?php

// Start session
session_start();

require('../php/server.php');

$username_error = "";
$password_error = "";
$wrong = "";
$username = "";

function Validate($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = Validate($_POST['username']);
    $password = Validate($_POST['password']);

    if (empty($_POST['username'])) {
        $username_error = "Username is empty!";
    }

    if (empty($_POST['password'])) {
        $password_error = "Password is empty!";
    }

    if (isset($_POST['username']) & isset($_POST['password'])) {

        // Getting username and password


        $password = md5($password);
        $id;
        // Fetch data from the database basing from of username and email

        $sql = " SELECT id, name, password FROM user WHERE name=:name AND password=:password ";

        // $sql = " SELECT * FROM user ";

        $query = $connection->prepare($sql);

        $query->bindParam(':name', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);

        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_OBJ);

        foreach ($results as $result) {
            $id = $result->id;
            $username = $result->name;
        }
        // Check if user belongs to admin, staff or manager


        if ($query->rowCount() > 0) {


            // admin

            $sql_admin = " SELECT user_id FROM admin WHERE user_id=:u_id ";

            $query_admin = $connection->prepare($sql_admin);

            $query_admin->bindParam(':u_id', $id, PDO::PARAM_STR);

            $query_admin->execute();

            $admins = $query_admin->fetchAll(PDO::FETCH_OBJ);

            if ($query_admin->rowCount() > 0) {
                $_SESSION['adminlogin'] = $_POST['username'] . "admin";
                foreach ($admins as $admin) {
                    if ($admin->user_id == $id) {
                        echo "<script type='text/javascript'> document.location = 'admin.php'; </script>";
                    }
                }
            }

            // Drivers

            $sql_driver = " SELECT user_id FROM driver WHERE user_id=:u_id ";

            $query_driver = $connection->prepare($sql_driver);

            $query_driver->bindParam(':u_id', $id, PDO::PARAM_STR);

            $query_driver->execute();

            $drivers = $query_driver->fetchAll(PDO::FETCH_OBJ);

            if ($query_driver->rowCount() > 0) {
                $_SESSION['driverlogin'] = $_POST['username'] . "driver";
                foreach ($drivers as $driver) {
                    if ($driver->user_id == $id) {
                        echo "<script type='text/javascript'> document.location = 'register.php'; </script>";
                    }
                }
            }

            // manager

            $sql_man = " SELECT user_id FROM manager WHERE user_id=:u_id ";

            $query_man = $connection->prepare($sql_man);

            $query_man->bindParam(':u_id', $id, PDO::PARAM_STR);

            $query_man->execute();

            $manager = $query_man->fetchAll(PDO::FETCH_OBJ);

            if ($query_man->rowCount() > 0) {
                $_SESSION['managerlogin'] = $_POST['username'] . "manager";
                foreach ($manager as $item) {
                    if ($item->user_id == $id) {
                        echo "<script type='text/javascript'> document.location = '../index.php'; </script>";
                    }
                }
            }
        } else {
            $wrong = 'Invalid credentials';
            // echo "<h3 id=`center`>" . $wrong . "</h3>";
        }
        $_SESSION['user'] = $username;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css" />
    <title>Login</title>
</head>

<body>
    <style>
        .invalid {
            color: red;
            text-align: center;
        }

        label>small {
            background: none;
        }

        h3.register {
            background: green;
            color: white;
            height: 2.5rem;
            text-align: center;
            width: 100%;
        }
    </style>
    <div class="backdrop-login"></div>
    <?php if (isset($_SESSION['success'])) { ?>
        <h3 class="register">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </h3>
    <?php } ?>
    <h1 class="invalid">
        <?php
        echo $wrong;
        ?>
    </h1>
    <div class="container-login">
        <form class="container-login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <h1>Login</h1>
            <label>
                <p class="white">Username</p>
                <input class="error" type="text" name="username" />
                <small><?php echo $username_error; ?></small>
            </label>
            <label>
                <p class="white">Password</p>
                <input class="error" type="password" name="password" />
                <small><?php echo $password_error; ?></small>
            </label>
            <div class="form-decisions">
                <button id="reset" class="reset" type="reset">Clear</button>
                <button id="submit" class="submit" type="submit">Login</button>
            </div>
            <a class="forgot" href="#">Forgot password</a>
        </form>
    </div>
    <script src="../scripts/login.js"></script>
    <script>
        var register = document.querySelector("h3.register")
        setTimeout(() => {
            register.style.display = "none"
        }, 3000)
    </script>
</body>

</html>