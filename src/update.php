<?php

session_start();

require('../php/server.php');

// Validating Session.
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

$uname = "";
$ulocation = "";
$upackages = "";
$uimg = "";
$udate = "";

// Insert
if (isset($_GET['edit'])) {
    $uname = $_GET['edit'];

    $sql = " SELECT * FROM location WHERE driver_name=:name ";

    $query = $connection->prepare($sql);

    $query->bindParam(":name", $uname, PDO::PARAM_STR);

    $query->execute();

    $results = $query->fetchAll(PDO::FETCH_OBJ);

    foreach ($results as $result) {
        $uname = $result->driver_name;
        $uimg = $result->image;
    }
}

$serial_error = "";
$name_error = "";
$updated = "";
$wrong = "";
$unknown = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['uname']) && isset($_POST['ulocation']) && isset($_POST['upackage']) && isset($_POST['udate'])) {

        $uname = $_POST['uname'];
        $ulocation = $_POST['ulocation'];
        $udate = $_POST['udate'];
        $upackage = $_POST['upackage'];
        $month = date('M', strtotime($_POST['udate']));
        // Update the location

        $sql = " UPDATE location SET location=:loc, package=:pac WHERE date=:date AND driver_name=:name ";

        $query = $connection->prepare($sql);

        $query->bindParam(":date", $udate, PDO::PARAM_STR);
        $query->bindParam(":name", $uname, PDO::PARAM_STR);
        $query->bindParam(":loc", $ulocation, PDO::PARAM_STR);
        $query->bindParam(":pac", $upackage, PDO::PARAM_STR);
        //$query->bindParam(":month", $month, PDO::PARAM_STR);

        $query->execute();

        if ($query->rowCount() > 0) {
            $updated = "Updated driver " . $uname . " delivery details";
            $_SESSION['driver_details'] = $updated;
            header("location: admin.php");
        }
    }

    if (isset($_POST['uname']) && isset($_POST['uimg'])) {
        $uname = $_POST['uname'];
        $uimg = $_POST['uimg'];
        //Update name and image in user and location table
        $sql = "UPDATE location SET image=:img WHERE driver_name=:name";

        $query = $connection->prepare($sql);

        $query->bindParam(":name", $uname, PDO::PARAM_STR);
        $query->bindParam(":img", $uimg, PDO::PARAM_STR);

        $query->execute();

        //Update user table as well

        $sql = " UPDATE user SET img=:img WHERE name=:name ";

        $query = $connection->prepare($sql);

        $query->bindParam(":name", $uname, PDO::PARAM_STR);
        $query->bindParam(":img", $uimg, PDO::PARAM_STR);

        $query->execute();

        if ($query->rowCount() > 0) {
            $updated = "Updated " . $uname . " profile pic";
            $_SESSION['update'] = $updated;
            header('location: admin.php');
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
    <title>Update driver</title>
</head>

<body>
    <style>
        .container-apply {
            margin-top: 1rem;
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
            flex-direction: column;
            align-items: center;
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

        input[type="date"] {
            background: white;
            color: black;
        }

        .toggle {
            margin-top: 5rem;
            z-index: 1000;
            background: white;
        }

        .container-login-form {
            margin: 0rem;

            /* padding: 0rem; */
        }

        .container-login {
            height: auto;
        }

        .toggle>button {
            padding: 0.5rem 0.8rem;
            border: none;
            background: lightgreen;
            border-radius: 10rem;
            cursor: pointer;
        }

        .toggle>button[class="Driving-details"] {
            background: white;
        }

        form:nth-child(3) {
            display: none;

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
    <div class="toggle">
        <button class="personal-details">Personal details</button>
        <button class="Driving-details">Driving details</button>
    </div>
    <div class="container-login container-apply">
        <h1>
            <?php
            if (isset($wrong)) {
                echo $wrong;
            }
            if (isset($updated)) {
                echo $updated;
            }
            ?>
        </h1>
        <form class="container-login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <h1>Personal details</h1>
            <label>
                <p class="white">Name</p>
                <input class="error" type="text" name="uname" value="<?php echo $uname; ?>" />
            </label>
            <label>
                <p class="white">Edit Driver pic</p>
                <input type="file" name="uimg" class="pic" />
            </label>
            <div class="form-decisions">
                <button id="reset" class="reset" type="reset">Clear</button>
                <button id="submit" class="submit" type="submit">Add</button>
            </div>
        </form>
        <?php require('update_more.php');
        ?>
    </div>
    <script src="../scripts/login.js"></script>
    <script src="../scripts/update.js">
    </script>
</body>

</html>