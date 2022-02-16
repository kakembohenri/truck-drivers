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

require('../php/server.php');
$ans = '';
$wrong = "";
$missing = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $driver = $_POST['driver'];
    $month = $_POST['month'];

    $sql = " SELECT * FROM user WHERE name=:name ";

    $query = $connection->prepare($sql);

    $query->bindParam(":name", $driver, PDO::PARAM_STR);

    $query->execute();

    if ($query->rowCount() > 0) {
        $sql = "SELECT * FROM location WHERE driver_name LIKE :driver AND month LIKE :month ";

        $query = $connection->prepare($sql);

        $query->bindParam(":driver", $driver, PDO::PARAM_STR);
        $query->bindParam(":month", $month, PDO::PARAM_STR);

        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_OBJ);

        $ans = $driver;
        $mon = $month;
    } else {
        $missing = "Driver doesnt exist";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/apply.css" />
    <title>Report</title>
</head>

<body>
    <style>
        h3.missing {
            width: 100%;
            background: red;
            height: 2.5rem;
            text-align: center;
            padding-top: 0.5rem;
            color: white;
        }

        body {
            background-image: url('../img/truck.jpeg');
        }
    </style>
    <div class="backdrop-report"></div>
    <a href="../index.php">
        &lt;-back</a>
    <main class="report">
        <h1>REPORT</h1>
        <form class="search" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
            <input name="driver" type="text" placeholder="Search driver" required />
            <input name="month" type="text" placeholder="Month in abbreviation" required />
            <button>Search</button>
        </form>
        <?php if ($missing != '') { ?>
            <h3 style="font-size: 1rem;" class="missing">
                <?php
                echo $missing;
                ?>
            </h3>
        <?php } ?>
        <?php
        if (isset($ans) & isset($mon)) {
        ?>
            <h3>Report for driver <i>"<?php echo $ans; ?>"</i> for the month <i>"<?php echo $mon; ?>"</i> </h3>
        <?php
        }
        ?>
        <div class="container-table">
            <?php
            if (isset($results)) {

            ?>
                <table border="4" width="100%">
                    <!-- <caption>Report on unallocated assets</caption> -->
                    <tr>
                        <th colspan="2">Location</th>
                        <th colspan="2">Delivery</th>
                        <th colspan="2">Date</th>
                    </tr>
                    <?php

                    foreach ($results as $result) {

                    ?>
                        <tr>
                            <td colspan="2">
                                <?php
                                echo $result->location;
                                ?>
                            </td>
                            <td colspan="2">
                                <?php
                                echo $result->package;
                                ?>
                            </td>
                            <td colspan="2">
                                <?php
                                echo $result->date;
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="3"><b>Total days worked:</b> <?php echo $query->rowCount(); ?></td>
                    </tr>
                </table>
            <?php
            }
            ?>
        </div>
    </main>
    <script src="/scripts/login.js"></script>
    <script>
        var missing = document.querySelector('h3.missing')
        setTimeout(() => {
            missing.style.display = 'none'
        }, 3000)
    </script>
</body>

</html>