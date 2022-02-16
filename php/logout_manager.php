<?php

session_start();
unset($_SESSION['managerlogin']);
session_destroy();

header('location: ../src/login.php');
