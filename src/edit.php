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
$ans = "";
$del = "";
$id = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_driver = $_POST['driver'];

    if (isset($_POST['driver'])) {
        $like = '%' . $search_driver . '%';

        $sql = " SELECT DISTINCT name, img FROM user WHERE name LIKE :name ";

        $query = $connection->prepare($sql);

        $query->bindParam(":name", $like, PDO::PARAM_STR);

        $query->execute();

        if ($query->rowCount() > 0) {
            $results = $query->fetchAll(PDO::FETCH_OBJ);
            $ans = $search_driver;
        }
    }
}

if (isset($_GET['delete'])) {
    $name = $_GET['delete'];

    $sql = " DELETE FROM location WHERE driver_name=:name ";

    $query = $connection->prepare($sql);

    $query->bindParam(":name", $name, PDO::PARAM_STR);

    $query->execute();

    //Selecting id from user table to delete from driver table
    $sql = " SELECT id FROM user WHERE name=:name ";

    $query = $connection->prepare($sql);

    $query->bindParam(":name", $name, PDO::PARAM_STR);

    $query->execute();

    if ($query->rowCount() > 0) {
        $ids = $query->fetchAll(PDO::FETCH_OBJ);
        foreach ($ids as $item) {
            $id = $item->id;
        }

        $sql = " DELETE FROM driver WHERE user_id=:uid ";

        $query = $connection->prepare($sql);

        $query->bindParam(":uid", $id, PDO::PARAM_STR);

        $query->execute();

        if ($query->rowCount() > 0) {

            $sqldel = " DELETE FROM user WHERE name=:no ";

            $query1 = $connection->prepare($sqldel);

            $query1->bindParam(":no", $name, PDO::PARAM_STR);

            $query1->execute();

            $del = "" . $name . "" . " has been successfully deleted";
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
    <link rel="stylesheet" href="../css/admin.css" />
    <title>Edit</title>
</head>

<body>
    <style>
        body {
            display: flex;
            justify-content: center;
            /* margin-top: 8rem; */
            background-image: url(../img/truck.jpeg);
            background-size: 10%;
            background-attachment: fixed;
            z-index: 1000;
        }

        h1 {
            color: white;
        }

        .actions-update {
            display: flex;
            flex-direction: row;
        }

        a {
            text-decoration: none;
            color: black;
        }

        i {
            color: white;
        }

        h3 {
            color: white;
        }

        .driver-container {
            flex-direction: column;
        }

        .assets:hover {
            box-shadow: none;
        }

        h3.delete {
            margin-bottom: 2rem;
            color: white;
            background: red;
            height: 2.5rem;
            text-align: center;
            padding-top: 0.5rem;
            width: 100%;
        }
    </style>
    <div class="backdrop-index"></div>
    <div class="backdrop"></div>
    <!-- <div class="confirm">
        <form method="#" action="post">
            <p>Are you sure you want to delete this asset?</p>
            <button class="update asset-yes">Yes</button>
            <button class="delete asset-no">No</button>
        </form>
    </div> -->
    <header>
        <a href="admin.php">
            <div class="company-logo">
                <img class="logo" src="../img/images (35).jpeg" alt="Company logo" />
                <h3>Truckers</h3>
            </div>
        </a>
        <!-- <a href="admin.html">&lt;-Back</a> -->
        </div>
        <ul>
            <li><a href="add.php">Add driver</a></li>
            <li><a href="#">Edit driver</a></li>
            <li>
                <form action="../php/logout_admin.php" method="post">
                    <button class="logout">Log out</button>
                </form>

            </li>
        </ul>
    </header>

    <div class="main-container-search">
        <?php if ($del != '') { ?>
            <h3 class="delete">
                <?php echo $del; ?>
            </h3>
        <?php } ?>
        <form class="search" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
            <input type="text" name="driver" placeholder="Search driver" required />
            <button>Search</button>
        </form>
        <!-- <h1>Edit driver <i>"alex"</i></h1> -->
        <div class="assets-container driver-container">
            <?php if (isset($results)) {

            ?>
                <h1>Edit driver <i>"<?php echo $ans; ?>"</i></h1>

                <?php
            }

            if (isset($results)) {
                foreach ($results as $result) {
                ?>
                    <div class="assets">
                        <div class="asset">
                            <div class="asset-img">
                                <img class="driver-img" src="../img/<?php echo $result->img; ?>" alt="Driver image" />
                            </div>
                            <div class="asset-details">
                                <p class="asset-serial_no">Driver name:
                                    <?php echo $result->name; ?>
                                </p>
                                <div class="actions actions-update">
                                    <a href="update.php?edit=<?php echo $result->name; ?>">
                                        <div class="update">Update</div>
                                    </a>
                                    <a href="edit.php?delete=<?php echo $result->name; ?>">
                                        <div class="delete">Delete</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
    <!-- <script src="../scripts/edit.js"></script> -->
    <script>
        var remove = document.querySelector('h3.delete')
        setTimeout(() => {
            remove.style.display = 'none'
        }, 3000)
    </script>
</body>

</html>