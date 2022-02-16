<?php

session_start();

if (strlen($_SESSION['driverlogin']) == 0) {
    if (strlen($_SESSION['adminlogin']) != 0) {
        unset($_SESSION['adminlogin']);
        session_destroy();
    } elseif (strlen($_SESSION['managerlogin']) != 0) {
        unset($_SESSION['managerlogin']);
        session_destroy();
    }
    header('location: login.php');
}

require('../php/server.php');

$name_error = '';
$location_error = '';
$package_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reg_name = $_POST['name'];
    $reg_location = $_POST['location'];
    $reg_package = $_POST['package'];
    $reg_date = date("Y-m-d");
    $reg_month = date("M");
    $reg_img = '';

    if (empty($_POST['name'])) {
        $name_error = 'Name field is empty!';
    }

    if (empty($_POST['location'])) {
        $location_error = 'Location field is empty!';
    }

    if (empty($_POST['package'])) {
        $package_error = 'Package field is empty!';
    }

    if (isset($_POST['name']) & isset($_POST['location']) & isset($_POST['package'])) {

        $sql_u = " SELECT name, img FROM user WHERE name LIKE :name ";

        $query_u = $connection->prepare($sql_u);

        $query_u->bindParam(":name", $reg_name, PDO::PARAM_STR);

        $query_u->execute();

        $results = $query_u->fetchAll(PDO::FETCH_OBJ);

        foreach ($results as $result) {
            $reg_name = $result->name;
            $reg_img = $result->img;
        }

        $sql = " INSERT INTO location (driver_name, location, image, package, month, date) VALUES (:name, :location, :img, :package, :month, :date) ";

        $query = $connection->prepare($sql);

        $query->bindParam(":name", $reg_name, PDO::PARAM_STR);
        $query->bindParam(":location", $reg_location, PDO::PARAM_STR);
        $query->bindParam(":img", $reg_img, PDO::PARAM_STR);
        $query->bindParam(":package", $reg_package, PDO::PARAM_STR);
        $query->bindParam(":month", $reg_month, PDO::PARAM_STR);
        $query->bindParam(":date", $reg_date, PDO::PARAM_STR);

        $query->execute();

        // $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            $_SESSION['success'] = 'You have successfully registered have a good day';

            header('location: login.php');
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
    <link rel="stylesheet" href="../css/login.css" />
    <title>Application</title>
</head>

<body>
    <div class="backdrop-login"></div>
    <div class="container-login">
        <form class="container-login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <h1>REGISTER</h1>
            <label>
                <p class="white">Name</p>
                <input class="error" type="text" name="name" value="<?php echo $_SESSION['user']; ?>" required />
                <small>
                    <?php
                    echo $name_error;
                    ?>
                </small>
            </label>
            <label>
                <p class="white">Location</p>
                <input class="error" type="text" name="location" required />
                <small>
                    <?php
                    echo $location_error;
                    ?>
                </small>
            </label>
            <label>
                <p class="white">Package you are delivering</p>
                <input class="error" type="text" name="package" required />
                <small>
                    <?php
                    echo $package_error;
                    ?>
                </small>
            </label>
            <div class="form-decisions">
                <button id="reset" class="reset" type="reset">Clear</button>
                <button id="submit" class="submit" type="submit">Register</button>
            </div>

        </form>
    </div>
    <script src="../scripts/login.js"></script>

</body>

</html>