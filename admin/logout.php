<?php

    session_start();
    session_unset();    // unset the data
    session_destroy();  // destroy the session

    header('Location: index.php');      // go to log in page
    exit();

?>