<?php
    /*
        Print orders page
        you can Print orders from here
    */  
    ob_start();

    session_start();
    $pageTitle = 'الطابعه';

    // check if session exist or not
    if(isset($_SESSION['Username']) && $_SESSION['UserStatus'] == 2) :
        include("init.php");
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
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
        /// ========================== Manage Page ===============================//
        ///=======================================================================//
    if($do == 'Manage') :
        if($_SERVER['REQUEST_METHOD'] == 'POST') :
            $Date = $_POST['SearchDate'];
        else :
            $Date = '';
        endif;
        
        if(!empty ($Date)) :
            $check = $con->prepare("SELECT      orders.*
                                    FROM        orders
                                    WHERE       orders.ChargePrint = 1
                                    AND         orders.OrderDate = '$Date'
                                    ORDER BY    orders.OrderID DESC ");
        else:
            $check = $con->prepare("SELECT      orders.*
                                    FROM        orders
                                    WHERE       orders.ChargePrint = 1
                                    ORDER BY    orders.OrderID DESC ");
        endif;
        

        $check->execute();

        // fetech all data from data base
        $orders = $check->fetchAll();

    ?>
        <!-- start manage page -->
        <h1 class="text-center font-weight-bold my-3"> طباعة الطلبات </h1>

        <div class="mx-5 pb-5 min-h">

                
            <div class="d-flex justify-content-end mb-3">    
                
                <form method="POST" action="">
                    <div class="d-flex">
                        <input type="date" class="form-control ml-2" name="SearchDate">
                                    
                        <input type="submit" value="تصفية" class="btn btn-primary ml-2 py-1 px-3">
                    </div>
                </form>

            </div>

            <div class="d-flex justify-content-end mb-3">    
                <button type="submit" formaction="chargeprinter.php?do=PrintMandop" form="formchargeorders" class="btn btn-success px-3 shadow" data-toggle="tooltip" title="طباعة كشف الطلبات">
                    <i class="fas fa-print mr-1"></i>
                    طباعة كشف
                </button>
            </div>

            <form method="POST" action="" id="formchargeorders">

                <table class="table table-bordered shadow main-table table-hover text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>رقم الطلب</th>
                            <th>اسم العميل</th>
                            <th>العنوان</th>
                            <th>الهاتف</th>
                            <th>التاريخ</th>
                            <th>
                                <input class="form-check-input p" type="checkbox" id="selectAll">
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- print in the table from data base -->
                        <?php
                            foreach($orders as $order) :
                                
                                echo    
                                    '<tr>
                                        <td>' . $order['OrderID'] . '</td>
                                        <td>' . $order['ClientName'] . '</td>
                                        <td>' . $order['City'] . ' - ' . $order['ClientAddress'] . '
                                        </td>
                                        <td>' . $order['ClientPhone'] . '</td>
                                        <td>' . $order['OrderDate'] . '</td>                              
                                        <td>
                                            <input class="form-check-input" type="checkbox" id="checkItem" name="check[]" value="' . $order['OrderID'] . '">
                                        </td>
                                    </tr>';
                            endforeach;
                        ?>

                    </tbody>
                </table>
            </form>

            <div>

                <!-- Load more Rows -->
                <div class="text-center">
                    <a href="#" class="btn btn-dark px-5 shadow" id="Rows-load">
                        اظهار المزيد 
                        <i class="fa fa-plus ml-3"></i>            
                    </a>
                </div>
                
                <!-- Return to Top -->
                <div class="">
                    <a href="#" id="return-to-top">
                        <i class="icon-chevron-up"></i>
                    </a>
                </div>

            </div>

        </div>
        <!-- end manage page -->

    
    <?php

        ///=======================================================================//
        /// ======================== PrintMandop Page ============================//
        ///=======================================================================//
    elseif($do == 'PrintMandop') :
        // PrintMandop page
    ?>
        <!-- start print page -->

        <?php
            if($_SERVER['REQUEST_METHOD'] == 'POST') :
                if(isset($_POST['check'])) :
                    $PrintedOrders = $_POST['check'];
                    $mandopid = $PrintedOrders[0];

                    $check = $con->prepare("SELECT      orders.OrderID, users.FullName AS MandopName
                                            FROM        orders 
                                            INNER JOIN  users
                                            ON          orders.MandopID = users.ID
                                            WHERE       orders.OrderID = '$mandopid'");
                    $check->execute();
                    $row = $check->fetchall();
                    $mandopname = $row[0]['MandopName'];

                else:
                    $themsg = ' <div class="alert alert-danger mt-5 w-75 mx-auto text-center" dir="rtl" role="alert">
                                    للاسف لا يمكنك الدخول الى هذه الصفحة بدون طلبات محددة للطباعة  
                                </div>';
                    RedirectFun($themsg, 'chargeprinter', 'print');
                endif;  
            endif;
        ?>
        
        <div class="text-center px-3 page">

            <div class="row px-3 py-4">

                <div class="w-100 mx-auto my-3">
                    <div class="w-100 d-flex justify-content-around" dir="rtl">
                        <div class="d-flex d-flex justify-content-around">
                            <div class="font-weight-bolder"> اسم المندوب : </div>
                            <div class="mr-2">
                                <?php
                                    echo $mandopname;
                                ?>
                            </div>    
                        </div>

                        <div class="d-flex justify-content-around">
                            <div class="font-weight-bolder"> تاريخ التوزيع : </div>
                            <div class="mr-2">
                                <?php
                                    $today = date("Y/m/d");
                                    echo $today;
                                ?>
                            </div>    
                        </div>
                    </div>
                </div>
                
                <table class="table table-bordered table-charge">
                    <thead class="thead-inverse">
                        <tr>
                            <th>ملاحظات</th>
                            <th>العنوان</th>
                            <th>الحساب</th>
                            <th>الهاتف</th>
                            <th>الاسم</th>
                            <th>رقم الطلب</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php


                foreach($PrintedOrders as $orderid) :

                        $check2 = $con->prepare("   SELECT  COUNT(Pieces.PieceModel) AS NumberOfOrders,
                                                            SUM(Pieces.PiecePrice) AS TotalPrice 
                                                    FROM    Pieces
                                                    WHERE   OrderNumber = '$orderid'");
                        $check2->execute();
                        $final = $check2->fetch();

                        

                        // select all orders
                        $check = $con->prepare("SELECT      orders.*
                                                FROM        orders
                                                WHERE       orders.OrderID = ? "); 

                        $check->execute(array($orderid));

                        // fetech all data from data base
                        $order = $check->fetch();
                        
                        // now update print col to set order printed
                        $check2 = $con->prepare("   UPDATE  orders 
                                                    SET     ChargePrint = 0
                                                    WHERE   OrderID = ? "); 

                        $check2->execute(array($orderid));
                        ?>
                    
                        <tr>
                            <th></th>
                            <th>
                                <?php 
                                    echo $order['ClientAddress'];
                                ?>
                            </th>
                            <th>
                                <?php 
                                    echo $final['TotalPrice'];
                                ?>
                            </th>
                            <th>
                                <?php 
                                    echo $order['ClientPhone'];
                                    if($order['ClientPhone2'] != 0) :
                                        echo '<br>' . $order['ClientPhone2'] ;
                                    endif; 
                                ?>
                            </th>
                            <th>
                                <?php 
                                    echo $order['ClientName'];
                                ?>
                            </th>
                            <th>
                                <?php 
                                    echo $order['OrderID'];
                                ?>
                            </th>
                        </tr>
                    
                    <?php
                
                endforeach;

                ?>
                    </tbody>
                </table>
            </div>
            
            <!-- print the page -->
            <script>
                window.print();
            </script>

        </div>
        <!-- end Ptint page -->
    <?php
    

    endif;
    ?>



<!-- end content  -->

<!-- start footer -->
<?php  include $tpl . 'footer.php'; ?>
<!-- end footer -->
