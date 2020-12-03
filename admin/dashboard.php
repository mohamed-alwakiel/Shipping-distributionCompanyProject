<?php
    session_start();
    $pageTitle = 'الرئيسية';

    // check if session exist or not
    if(isset($_SESSION['Username']) && $_SESSION['UserStatus'] == 1) :
        include("init.php");
    else :
        header('Location: index.php');   // redirect to login page
        exit();
    endif;

?>

<!-- start header -->
<?php  include $tpl . 'header.php'; ?>
<!-- end header -->

<!-- start navbar -->
<?php 
    include('navbar.php'); 
    creatNavBar();
?>
<!-- end navbar -->

<!-- start content  -->
<div class="container text-center home-stat">
    <h1 class=" font-weight-bold my-5 my-4">الرئيسية</h1>
</div>
<!-- end content  -->

<!-- start footer -->
<?php  include $tpl . 'footer.php'; ?>
<!-- end footer -->
