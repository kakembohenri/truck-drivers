<?php

session_start();

if (strlen($_SESSION['managerlogin']) == 0) {
    if (strlen($_SESSION['adminlogin']) != 0) {
        unset($_SESSION['adminlogin']);
        session_destroy();
    }
    if (strlen($_SESSION['driverlogin']) != 0) {
        unset($_SESSION['driverlogin']);
        session_destroy();
    }
    header('location: src/login.php');
}

require('php/server.php');
$ans = '';
$wrong = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $driver = $_POST['driver'];
    $date = $_POST['date'];

    //First get user image
    $driver_like = "%" . $driver . "%";
    $sql = "SELECT * FROM location WHERE driver_name LIKE :driver AND date=:date ";

    $query = $connection->prepare($sql);

    $query->bindParam(":driver", $driver_like, PDO::PARAM_STR);
    $query->bindParam(":date", $date, PDO::PARAM_STR);

    $query->execute();

    $results = $query->fetchAll(PDO::FETCH_OBJ);

    $ans = $driver;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css" />
    <title>Assets</title>
</head>

<body>
    <style>
        body {
            display: flex;
            justify-content: flex-start;
            /* margin-top: 8rem; */
            background-image: url(img/truck.jpeg);
            background-size: 10%;
            background-attachment: fixed;
        }


        .assets {
            margin-top: 0rem;
        }

        .assets:hover {
            box-shadow: none;
        }

        .main-container {
            display: flex;
            /* flex-direction: column; */
            /* align-items: center; */

            justify-content: flex-start;
            padding: 1rem;
            margin-left: 1rem;

            /* width: 70%; */
            /* box-shadow: 0.1rem 0.1rem 0.1rem 0.1rem gray; */
            /* background-color: rgb(184, 247, 247); */
        }


        .logout {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            /* background: rgb(255, 110, 110); */
            background: black;
            cursor: pointer;
            /* border: 0.1rem solid rgb(255, 110, 110); */
            color: white;
        }

        .logout:hover,
        .logout:active {
            background: red;
            border-color: red;
        }

        .main-container_assets>h3 {
            position: absolute;
            top: 25%;
            left: 5%;
            color: white;
            border-bottom: none;
        }
    </style>
    <div class="backdrop-index"></div>
    <header>
        <div class="company-logo">
            <img class="logo" src="img/images (35).jpeg" alt="Company logo" />
            <h3>Truckers</h3>
        </div>
        <ul>
            <li><a href="src/report.php">Get Report</a></li>
            <li>
                <form class="search" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                    <input name="driver" type="text" placeholder="Search driver" required />
                    <input name="date" type="date" required />
                    <button type="submit">Search</button>
                </form>

            </li>
            <li>
                <form action="php/logout_manager.php" method="post">
                    <button class="logout">Log out</button>
                </form>

            </li>
        </ul>
    </header>
    <main class="index">
        <div class="main-container">
            <div class="main-container_assets">
                <?php if (isset($results)) {

                ?>
                    <h3>Showing results for "<?php echo $ans; ?>" and date "<?php echo $date; ?>"</h3>
                    <?php
                }
                if (isset($results)) {
                    foreach ($results as $result) {
                    ?>
                        <div class="assets">
                            <div class="asset">
                                <div class="asset-img">

                                    <img class="driver-img" src="img/<?php echo $result->image; ?>" alt="Driver image" />

                                </div>
                                <div class="asset-details">
                                    <p class="asset-serial_no">Driver name:
                                        <?php echo $result->driver_name; ?>
                                    </p>
                                    <p class="asset-owner">Location:
                                        <?php echo $result->location; ?>
                                    </p>
                                    <p class="asset-owner">Package:
                                        <?php echo $result->package; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </main>
</body>

</html>