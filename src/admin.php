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

// Count drivers

$sql = " SELECT DISTINCT driver_name FROM location ";

$query = $connection->prepare($sql);

$query->execute();

//$drivers = $query->fetchAll(PDO::FETCH_OBJ);

// Count distinct locations

$sql_l = " SELECT location FROM location ";

$query_l = $connection->prepare($sql_l);

$query_l->execute();

//$locations = $query->fetchAll(PDO::FETCH_OBJ);

// Count distinct packages

$sql_p = " SELECT DISTINCT package FROM location ";

$query_p = $connection->prepare($sql_p);

$query_p->execute();

//$packages = $query->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/index.css" />
    <link rel="stylesheet" href="../css/admin.css" />
    <title>Admin</title>
</head>

<body>
    <style>
        body {
            display: flex;
            justify-content: flex-start;
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

        h3#updated-pic {
            color: white;
            background: green;
            width: 100%;
            height: 2.5rem;
            padding-top: 0.5rem;
            text-align: center;
        }
    </style>
    <div class="backdrop-index"></div>
    <header>
        <div class="company-logo">
            <img class="logo" src="../img/images (35).jpeg" alt="Company logo" />
            <h3>Truckers</h3>
        </div>

        <ul>
            <li><a href="add.php">Add driver</a></li>
            <li><a href="edit.php">Edit driver</a></li>
            <li>
                <form action="../php/logout_admin.php" method="post">
                    <button class="logout">Log out</button>
                </form>

            </li>
        </ul>
    </header>
    <div class="main-container-admin">
        <div class="admin-content">
            <?php if (isset($_SESSION['update'])) { ?>
                <h3 id="updated-pic">
                    <?php
                    echo $_SESSION['update'];
                    unset($_SESSION['update']);
                    ?>
                </h3>
            <?php } ?>
            <?php if (isset($_SESSION['driver_details'])) { ?>
                <h3 id="updated-pic">
                    <?php
                    echo $_SESSION['driver_details'];
                    unset($_SESSION['driver_details']);
                    ?>
                </h3>
            <?php } ?>
            <h2>Admin dashboard</h2>
            <div class="categories">
                <h2>Popular</h2>
                <div class="categories-items">
                    <div class="item item-center">
                        <img src="../img/images (37).jpeg" alt="driver" />
                        <p>Drivers</p>
                        <p>
                            <?php

                            echo $query->rowCount();
                            ?>
                        </p>
                    </div>
                    <div class="item item-center">
                        <img src="../img/location.jpeg" alt="location" />
                        <p>Deliveries made</p>
                        <p>
                            <?php
                            echo $query_l->rowCount();
                            ?>
                        </p>
                    </div>
                    <div class="item item-center">
                        <img src="../img/PackageIcon.png" alt="package" />
                        <p>Unique items delivered</p>
                        <p>
                            <?php
                            echo $query_p->rowCount();
                            ?>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        var remove = document.querySelector("h3#updated-pic")
        setTimeout(() => {
            remove.style.display = "none"
        }, 3000)
    </script>
</body>

</html>