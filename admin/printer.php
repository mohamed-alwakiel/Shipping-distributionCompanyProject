<?php
    /*
        Print orders page
        you can Print orders from here
    */  
    ob_start();

    session_start();
    $pageTitle = 'الطابعه';

    // check if session exist or not
    if(isset($_SESSION['Username']) && $_SESSION['UserStatus'] == 1) :
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
            $filter = $_POST['city'];
            $Date = $_POST['SearchDate'];
        else :
            $filter = 'all';
            $Date = '';
        endif;
        
        if($filter != 'all') :
            if(!empty ($Date)) :
                $check = $con->prepare("SELECT      orders.*,
                                                    products.*  
                                        FROM        orders
                                        INNER JOIN  products
                                                ON  products.ProductID = orders.OrderModel
                                        WHERE       orders.Status = 0
                                        AND         orders.Approve = 1
                                        AND         orders.City = '$filter'
                                        AND         orders.OrderDate = '$Date'
                                        ORDER BY    orders.OrderID DESC ");
            else:
                $check = $con->prepare("SELECT      orders.*,
                                                    products.*  
                                        FROM        orders
                                        INNER JOIN  products
                                                ON  products.ProductID = orders.OrderModel
                                        WHERE       orders.Status = 0
                                        AND         orders.Approve = 1
                                        AND         orders.City = '$filter'
                                        ORDER BY    orders.OrderID DESC ");
            endif;
        else:
            if(!empty($Date)) :
                $check = $con->prepare("SELECT      orders.*,
                                                    products.*  
                                        FROM        orders
                                        INNER JOIN  products
                                                ON  products.ProductID = orders.OrderModel
                                        WHERE       orders.Status = 0
                                        AND         orders.Approve = 1
                                        AND         orders.OrderDate = '$Date'
                                        ORDER BY    orders.OrderID DESC ");
            else:
                $check = $con->prepare("SELECT      orders.*,
                                                    products.*  
                                        FROM        orders
                                        INNER JOIN  products
                                                ON  products.ProductID = orders.OrderModel
                                        WHERE       orders.Status = 0
                                        AND         orders.Approve = 1
                                        ORDER BY    orders.OrderID DESC ");
            endif;
        endif;  

        $check->execute();

        // fetech all data from data base
        $orders = $check->fetchAll();

    ?>
        <!-- start manage page -->
        <h1 class="text-center font-weight-bold my-3"> طباعة الطلبات </h1>

        <div class="mx-5 pb-5 min-h">

                
            <div class="d-flex justify-content-between mb-3">    
                <form method="POST" action="">
                    <div class="d-flex">
                        <?php
                            $cities = ['القاهره', 'الاسكندرية','البحيرة','كفر الشيخ','السويس','الشرقية','الفيوم' ,'المنوفية', 'الجيزة '];
                            CreateSelectBox('city', $cities, 'Filter');
                        ?>
                    
                        <input type="date" class="form-control ml-2" name="SearchDate">
                                    
                        <input type="submit" value="تصفية" class="btn btn-primary ml-2 py-1 px-3">
                    </div>

                </form>
            </div>

            <form method="POST" action="printer.php?do=Print">

                <div class="text-right">
                    <input type="submit" value="طباعة" class="btn btn-success px-4 mb-3 shadow">
                </div>

               
                <table class="table table-bordered shadow main-table table-hover text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>اسم العميل</th>
                            <th>العنوان</th>
                            <th>الهاتف</th>
                            <th>الموديل</th>
                            <th>السعر</th>
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
                            
                            $arraySize = ['0', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
                            $SizeNum = $order['ProductSize'];
                            $size = $arraySize[$SizeNum];
                        
                            echo    
                                '<tr>
                                    <td>' . $order['ClientName'] . '</td>
                                    <td>' . $order['City'] . ' - ' . $order['ClientAddress'] . '
                                    </td>
                                    <td>' . $order['ClientPhone'] . '</td>
                                    <td>' 
                                        . $size . ' - ' 
                                        . $order['ProductName'] . ' - ' 
                                        . $order['ProductColor'] .
                                    '</td>
                                    <td>' . $order['OrderPrice'] . '</td>                              
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
        /// ========================== Print Page ===============================//
        ///=======================================================================//
    elseif($do == 'Print') :
        // Manage page
    ?>
        <!-- start print page -->

        <?php
            if($_SERVER['REQUEST_METHOD'] == 'POST') :
                if(isset($_POST['check'])) :
                    $PrintedOrders = $_POST['check'];
                else:
                    $themsg = ' <div class="alert alert-danger mt-5 w-75 mx-auto" role="alert">
                                    للاسف لا يمكنك الدخول الى هذه الصفحة بدون طلبات محددة للطباعة  
                                </div>';
                    RedirectFun($themsg, 'printer', 'print');
                endif;  
            endif;
            ?>
        
        <div class="text-center px-3 page">

            <div class="row">

                <?php

                $arraySize = ['0', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

                foreach($PrintedOrders as $orderid) :
                    
                    // select all orders
                    $check = $con->prepare("SELECT      orders.*,
                                                        products.*  
                                            FROM        orders
                                            INNER JOIN  products
                                                    ON  products.ProductID = orders.OrderModel
                                            WHERE       orders.OrderID = ? "); 

                    $check->execute(array($orderid));

                    // fetech all data from data base
                    $order = $check->fetch();
                    
                    $SizeNum = $order['ProductSize'];
                    $size = $arraySize[$SizeNum];
                    
                    // now update print col to set order printed
                    $check2 = $con->prepare("   UPDATE  orders 
                                                SET     Status = 1
                                                WHERE   OrderID = ? "); 

                    $check2->execute(array($orderid));
                    ?>
                                    
      
                    <div class="col-6 text-left my-3 text-break card">
                        <div class="bord layer d-flex flex-column justify-content-space-between text-right font-weight-bolder">

                            <div class="row w-100">
                                <div class="col-9">
                                    <?php 
                                        echo $order['ClientName'];
                                    ?>
                                </div>
                                <div class="col-3">
                                    الاسم 
                                </div>
                            </div>

                            <div class="row w-100">
                                <div class="col-9">
                                    <?php 
                                        echo $order['ClientPhone']; 
                                    ?>
                                </div>
                                <div class="col-3">
                                    الهاتف 
                                </div>
                            </div>

                            <div class="row w-100">
                                <div class="col-9">
                                    <?php 
                                        echo    $size . ' - ' . 
                                                $order['ProductName'] . ' - ' . 
                                                $order['ProductColor'];
                                    ?>
                                </div>
                                <div class="col-3">
                                    الموديل 
                                </div>
                            </div>

                            <div class="row w-100">
                                <div class="col-9">
                                    <?php 
                                        echo $order['OrderPrice'];
                                    ?>
                                </div>
                                <div class="col-3">
                                    السعر 
                                </div>
                            </div>

                            <div class="row w-100">
                                <div class="col-9">
                                    <?php 
                                        echo $order['OrderDate'];
                                    ?>
                                </div>
                                <div class="col-3">
                                    التاريخ 
                                </div>
                            </div>

                            <div class="row w-100">
                                <div class="col-9">
                                    <?php 
                                        echo $order['City'] . ' - ' . $order['ClientAddress'];
                                    ?>
                                </div>
                                <div class="col-3">
                                    العنوان
                                </div>
                            </div>

                        </div>
                    </div>

                    <?php
                
                endforeach;

                ?>

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
