<?php

session_start();

if (strlen($_SESSION['adminlogin']) == 0) {
    if (strlen($_SESSION['managerlogin']) != 0) {
        unset($_SESSION['managerlogin']);
        session_destroy();
    }
    if (strlen($_SESSION['driverlogin']) != 0) {
        unset($_SESSION['driverlogin']);
        session_destroy();
    }
    header('location: login.php');
}

require('../php/server.php');
$same = "";
$success = "";
$id = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $add_fname = $_POST['fname'];
    $add_lname = $_POST['lname'];
    $add_password = md5($_POST['password']);
    $conf_pass = md5($_POST['pass_conf']);
    $img = $_POST['img'];
    $add_name = $add_fname . $add_lname;

    if (isset($_POST['password']) && isset($_POST['pass_conf'])) {
        if ($_POST['password'] != $_POST['pass_conf']) {
            $same = 'Password mismatch!';
        } else {

            //Entry into the users table

            $sql = "INSERT INTO user (name, password, img) VALUES (:name, :password, :img) ";

            $query = $connection->prepare($sql);

            $query->bindParam(":name", $add_name, PDO::PARAM_STR);
            $query->bindParam(":password", $add_password, PDO::PARAM_STR);
            $query->bindParam(":img", $img, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                $success = "Successfully added " . $add_fname . $add_lname;
            }

            //Selecting userid of new user from user table

            $sql_id = "SELECT id FROM user WHERE name=:name ";

            $query_id = $connection->prepare($sql_id);

            $query_id->bindParam(":name", $add_name, PDO::PARAM_STR);

            $query_id->execute();

            $results = $query_id->fetchAll(PDO::FETCH_OBJ);

            foreach ($results as $result) {
                $id = $result->id;
            }

            // Inserting new id into driver table
            $sql_u = "INSERT INTO driver (user_id) VALUES (:uid) ";

            $query_u = $connection->prepare($sql_u);

            $query_u->bindParam(":uid", $id, PDO::PARAM_STR);

            $query_u->execute();

            // //Inserting new image in location table

            // $sql_img = "INSERT INTO location (image) VALUES (:img) ";

            // $query_img = $connection->prepare($sql_img);

            // $query_img->bindParam(":img", $img, PDO::PARAM_STR);

            // $query->execute();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/index.css" />
    <link rel="stylesheet" href="../css/login.css" />
    <link rel="stylesheet" href="../css/apply.css" />
    <title>Add driver</title>
</head>

<body>
    <style>
        .container-apply {
            margin-top: 5rem;
        }

        label>input {
            background: none;
            color: white;
        }

        .pic {
            border: none;
            background: none;
        }

        h1 {
            color: white;
        }

        body {
            display: flex;
            justify-content: center;
            /* margin-top: 8rem; */
            background-image: url(../img/truck.jpeg);
            background-size: 10%;
            background-attachment: fixed;
            z-index: 1000;
        }

        .logout {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            background: black;
            cursor: pointer;
            color: white;
        }

        .logout:hover,
        .logout:active {
            background: red;
            border-color: red;
        }

        h1.new {
            color: red;
        }

        h1.success {
            color: white;
        }
    </style>
    <div class="backdrop-login"></div>
    <header>
        <div class="company-logo">
            <a href="admin.php"> <img class="logo" src="../img/images (35).jpeg" alt="Company logo" /></a>
            <h3>Truckers</h3>
        </div>
        <!-- <a href="admin.html">&lt;-Back</a> -->
        </div>

        <ul>
            <li><a href="#">Add driver</a></li>
            <li><a href="edit.php">Edit driver</a></li>
            <li>
                <form action="../php/logout_admin.php" method="post">
                    <button class="logout">Log out</button>
                </form>

            </li>
        </ul>
    </header>
    <div class="container-login container-apply">
        <?php if ($success != '') { ?>
            <h1 class="success">
                <?php
                echo $success;
                ?>
            </h1>
        <?php }
        if ($same != '') { ?>
            <h1 class="new">
                <?php
                echo $same;
                ?>
            </h1>
        <?php } ?>
        <form class="container-login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <h1>Add Driver</h1>
            <label>
                <p class="white">First name</p>
                <input class="error" type="text" name="fname" required />
                <!-- <small>Errors here</small> -->
            </label>
            <label>
                <p class="white">Last name</p>
                <input class="error" type="text" name="lname" required />
                <!-- <small>Errors here</small> -->
            </label>
            <label>
                <p class="white">New password</p>
                <input class="error" type="password" name="password" required />
                <!-- <small>Errors here</small> -->
            </label>
            <label>
                <p class="white">Password confirm</p>
                <input class="error" type="password" name="pass_conf" required />
                <!-- <small>Errors here</small> -->
            </label>
            <label>
                <p class="white">Upload Driver pic</p>
                <input type="file" name="img" class="pic" required />
            </label>
            <div class="form-decisions">
                <button id="reset" class="reset" type="reset">Clear</button>
                <button id="submit" class="submit" type="submit">Add</button>
            </div>
        </form>
    </div>
    <script src="../scripts/login.js"></script>
    <script>
        var new_driver = document.querySelector("h1.new")
        var success = document.querySelector("h1.success")
        //console.log(success.className)
        setTimeout(() => {
            new_driver.style.display = "none"
            success.style.display = "none"
        }, 3000)
    </script>
</body>

</html>