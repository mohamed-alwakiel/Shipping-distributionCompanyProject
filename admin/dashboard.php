<?php
    session_start();
    $pageTitle = 'الرئيسية';

    // check if session exist or not
    if(isset($_SESSION['Username'])) :
        include("init.php");
        if($_SESSION['UserStatus'] == 1) :
            $do = isset($_GET['do']) ? $_GET['do'] : 'Admin';

        elseif($_SESSION['UserStatus'] == 2) :
            $do = isset($_GET['do']) ? $_GET['do'] : 'AdminCharge';
            $city = $_SESSION['UserCity'];
        endif;
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

<?php 
        ///=======================================================================//
        /// ======================== AdminCharge Page ============================//
        ///=======================================================================//
    if($do == 'AdminCharge') :
    // AdminCharge Page
    ?>
            <div class="container text-center home-stat">
                <h1 class=" font-weight-bold my-5 my-4">الرئيسية</h1>
                <div class="row">
                
                    <div class="col-md-2">
                        <div class="stat st-new">
                            طلبات جديدة
                            <span>
                                <?php
                                    $check = $con->prepare("SELECT  COUNT(orders.Status)
                                                            FROM    orders
                                                            WHERE   orders.Status = 0
                                                            AND     orders.City = '$city' ");
                                    $check->execute();
                                    echo $check->fetchColumn();
                                ?>
                                <i class="fa fa-print"></i>
                            </span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat st-prepare">
                            طلبات تحت التجهيز
                            <span>
                                <?php
                                    $check = $con->prepare("SELECT  COUNT(orders.Status)
                                                            FROM    orders
                                                            WHERE   orders.Status = 1
                                                            AND     orders.City = '$city'");
                                    $check->execute();
                                    echo $check->fetchColumn();
                                ?>
                                <i class="fas fa-american-sign-language-interpreting"></i>
                            </span>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="stat st-back">
                            طلبات معلقة
                            <span>
                                <?php
                                    $check = $con->prepare("SELECT  COUNT(orders.Status)
                                                            FROM    orders
                                                            WHERE   orders.MandopID != 0
                                                            AND     orders.MandopStatus = 0
                                                            AND     orders.City = '$city'");
                                    $check->execute();
                                    echo $check->fetchColumn();
                                ?>
                                <i class="far fa-times-circle"></i>
                            </span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat st-distribute">
                            طلبات تحت التوزيع
                            <span>
                                <?php
                                    $check = $con->prepare("SELECT  COUNT(orders.Status)
                                                            FROM    orders
                                                            WHERE   orders.MandopID = 0
                                                            AND     orders.City = '$city'
                                                            AND     orders.Status IN(1, 2, 3)");
                                    $check->execute();
                                    echo $check->fetchColumn();
                                ?>
                                <i class="fas fa-truck"></i>
                            </span>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="stat st-deliverd">
                            طلبات مستلمة
                            <span>
                                <?php
                                    $check = $con->prepare("SELECT  COUNT(orders.Status)
                                                            FROM    orders
                                                            WHERE   orders.Status = 5");
                                    $check->execute();
                                    echo $check->fetchColumn();
                                ?>
                                <i class="fas fa-receipt"></i>
                            </span>
                        </div>
                    </div>

                </div>
            </div>

    <?php
        ///=======================================================================//
        /// =========================== Admin Page ===============================//
        ///=======================================================================//
    elseif($do == 'Admin') :
    // AdminCharge Page
    ?>
        <div class="container text-center home-stat">
            <h1 class=" font-weight-bold my-5 my-4">الرئيسية</h1>
            <div class="row">
            
                <div class="col-md-4">
                    <div class="stat st-new">
                        طلبات جديدة
                        <span>
                            <?php
                                $check = $con->prepare("SELECT  COUNT(orders.Status)
                                                        FROM    orders
                                                        WHERE   orders.Status = 0");
                                $check->execute();
                                echo $check->fetchColumn();
                            ?>
                            <i class="fa fa-print"></i>
                        </span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat st-prepare">
                        طلبات تحت التجهيز
                        <span>
                            <?php
                                $check = $con->prepare("SELECT  COUNT(orders.Status)
                                                        FROM    orders
                                                        WHERE   orders.Status = 1");
                                $check->execute();
                                echo $check->fetchColumn();
                            ?>
                            <i class="fas fa-american-sign-language-interpreting"></i>
                        </span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat st-deliverd">
                        طلبات مستلمة
                        <span>
                            <?php
                                $check = $con->prepare("SELECT  COUNT(orders.Status)
                                                        FROM    orders
                                                        WHERE   orders.Status = 5");
                                $check->execute();
                                echo $check->fetchColumn();
                            ?>
                            <i class="fas fa-receipt"></i>
                        </span>
                    </div>
                </div>

            </div>
        </div>

    <?php

    endif;
?>

<!-- end content  -->

<!-- start footer -->
<?php  include $tpl . 'footer.php'; ?>
<!-- end footer -->
