<?php

    $dsn = 'mysql:host=localhost;dbname=sherif';
    $user = 'root';
    $pass = '';
    $option = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    );

    try {
        $con = new PDO($dsn, $user, $pass, $option); // connection
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // for errors
    }
    catch(PDOException $e) {
        echo 'Faild to Connect' . $e->getMessage();
    }










?>