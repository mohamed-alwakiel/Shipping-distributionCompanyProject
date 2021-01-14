<?php
    /*
        Print orders page
        you can Print orders from here
    */  
    ob_start();

    session_start();
    $pageTitle = 'الطابعه';

    // check if session exist or not
    if(isset($_SESSION['Username'])) :
        include("init.php");
        if($_SESSION['UserStatus'] == 1) :
            $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        elseif($_SESSION['UserStatus'] == 2) :
            $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
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
        
        if($_SESSION['UserStatus'] == 1) :
            if($filter != 'all') :
                if(!empty ($Date)) :
                    $check = $con->prepare("SELECT      orders.*  
                                            FROM        orders
                                            WHERE       orders.Status = 0
                                            AND         orders.Approve = 1
                                            AND         orders.City = '$filter'
                                            AND         orders.OrderDate = '$Date'
                                            ORDER BY    orders.OrderID DESC ");
                else:
                    $check = $con->prepare("SELECT      orders.*
                                            FROM        orders
                                            WHERE       orders.Status = 0
                                            AND         orders.Approve = 1
                                            AND         orders.City = '$filter'
                                            ORDER BY    orders.OrderID DESC ");
                endif;
            else:
                if(!empty($Date)) :
                    $check = $con->prepare("SELECT      orders.* 
                                            FROM        orders
                                            WHERE       orders.Status = 0
                                            AND         orders.Approve = 1
                                            AND         orders.OrderDate = '$Date'
                                            ORDER BY    orders.OrderID DESC ");
                else:
                    $check = $con->prepare("SELECT      orders.*  
                                            FROM        orders
                                            WHERE       orders.Status = 0
                                            AND         orders.Approve = 1
                                            ORDER BY    orders.OrderID DESC ");
                endif;
            endif;
        elseif($_SESSION['UserStatus'] == 2) :
            if(!empty($Date)) :
                $check = $con->prepare("SELECT      orders.* 
                                        FROM        orders
                                        WHERE       orders.Status = 0
                                        AND         orders.Approve = 1
                                        AND         orders.City = '$city'
                                        AND         orders.OrderDate = '$Date'
                                        ORDER BY    orders.OrderID DESC ");
            else:
                $check = $con->prepare("SELECT      orders.* 
                                        FROM        orders
                                        WHERE       orders.Status = 0
                                        AND         orders.Approve = 1
                                        AND         orders.City = '$city'
                                        ORDER BY    orders.OrderID DESC ");
            endif;
        endif;
        $check->execute();

        // fetech all data from data base
        $orders = $check->fetchAll();

    // Manage Page
    ?>
        <!-- start manage page -->
        <h1 class="text-center font-weight-bold my-3"> طباعة الطلبات </h1>

        <div class="mx-5 pb-5 min-h">

                
            <div class="d-flex justify-content-end mb-3">    
                <form method="POST" action="">
                    <div class="d-flex">
                        <?php
                            if($_SESSION['UserStatus'] == 1) :
                                $cities = ['القاهره', 'الاسكندرية','البحيرة','كفر الشيخ','السويس','الشرقية','الفيوم' ,'المنوفية', 'الجيزة '];
                                CreateSelectBox('city', $cities, 'Filter');
                            endif;
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
                            <th>رقم الطلب</th>
                            <th>اسم العميل</th>
                            <th>العنوان</th>
                            <th>الهاتف</th>
                            <th>الطلبات</th>
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
                            
                            // fetch orders from database
                            $check = $con->prepare("SELECT      pieces.*,
                                                                products.*
                                                    FROM        pieces
                                                    INNER JOIN  products 
                                                            ON  pieces.PieceModel = products.ProductID
                                                    WHERE       pieces.OrderNumber = ?");
                            $check->execute(array($order['OrderID']));
                            $pieces = $check->fetchAll();

                            $arraySize = ['0', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
                            
                            echo    
                                '<tr>
                                    <td>' . $order['OrderID'] . '</td>
                                    <td>' . $order['ClientName'] . '</td>
                                    <td>' . $order['City'] . ' - ' . $order['ClientAddress'] . '</td>
                                    <td>' . $order['ClientPhone'] . '</td>
                                    
                                    <td class="bord">
                                        <table class="w-100">';
                                        $totalprice = 0;

                                        foreach($pieces as $piece) :
                                            $SizeNum = $piece['ProductSize'];
                                            $size = $arraySize[$SizeNum];
                                            
                                            echo' <tr>
                                                    <td>'   . $size . ' - ' 
                                                            . $piece['ProductName'] . ' - ' 
                                                            . $piece['ProductColor'] . '
                                                    </td>
                                                    <td dir="rtl">'
                                                            . $piece['NumberOfPieces'] . '
                                                            قطعة
                                                    </td>
                                                </tr>';
                                            $totalprice += $piece['PiecePrice'];
                                        endforeach;

                                        echo'</table>
                                    </td>
                                    <td dir=rtl>' . $totalprice . ' جنيهاً</td>
                                    
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

            <div class="row d-flex justify-content-center">

                <?php

                $arraySize = ['0', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

                foreach($PrintedOrders as $orderid) :
                    
                    // select all orders
                    $check = $con->prepare("SELECT      orders.*  
                                            FROM        orders
                                            WHERE       orders.OrderID = ? "); 

                    $check->execute(array($orderid));
                    $order = $check->fetch();

                    // fetch orders from database
                    $check1 = $con->prepare("SELECT      pieces.*,
                                                        products.*
                                            FROM        pieces
                                            INNER JOIN  products 
                                                    ON  pieces.PieceModel = products.ProductID
                                            WHERE       pieces.OrderNumber = ?");
                    $check1->execute(array($orderid));
                    $pieces = $check1->fetchAll();           

                    $arraySize = ['0', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
             
                    // now update print col to set order printed
                    $check2 = $con->prepare("   UPDATE  orders 
                                                SET     Status = 1
                                                WHERE   OrderID = ? "); 

                    $check2->execute(array($orderid));
                  
                    ?>
                                    
      
                    <div class="col-12 card text-right d-flex px-3" dir="rtl">
                        
                        <div class="w-100 h-100 d-flex flex-column justify-content-around px-2 bord">

                            <div class="row w-100 mx-0">

                                <div class="col-4 d-flex px-0">
                                    <div class="col-5 font-weight-bolder px-0">
                                        رقم الطلب : 
                                    </div>
                                    <div class ="col-7 px-0">
                                        <?php 
                                           echo $order['OrderID'];
                                        ?>
                                    </div>
                                </div>

                                <div class="col-4 d-flex px-0">
                                    <div class="col-3 px-0 font-weight-bolder">
                                        التاريخ : 
                                    </div>
                                    <div class="col-9">
                                        <?php 
                                            echo $order['OrderDate'];
                                        ?>
                                    </div>
                                    
                                </div>

                                <div class="col-4 d-flex px-0">
                                    <div class="col-5 px-0 font-weight-bolder">
                                        السعر : 
                                    </div>
                                    <div class="col-7 px-0" dir="rtl">
                                        <?php
                                            $totalprice = 0;
                                            foreach($pieces as $pi) :
                                                $totalprice += $pi['PiecePrice'];
                                            endforeach;
                                            echo $totalprice .' جنيهاً';
                                        ?>
                                    </div>
                                    
                                </div>

                            </div>

                            <div class="row w-100 mx-0">

                                <div class="col-7 d-flex px-0">
                                    <div class="col-2 font-weight-bolder px-0">
                                        الاسم : 
                                    </div>
                                    <div class="col-10 px-0">
                                        <?php 
                                            echo $order['ClientName'];
                                        ?>
                                    </div>
                                    
                                </div>

                                <div class="col-5 d-flex px-0">

                                    <div class="col-3 font-weight-bolder px-0">
                                        الهاتف : 
                                    </div>
                                    <div class="col-9 px-0">
                                        <?php 
                                            echo $order['ClientPhone'];
                                            if($order['ClientPhone2'] != 0) :
                                                echo '--' . $order['ClientPhone2'] ;
                                            endif; 
                                        ?>
                                    </div>
                                    
                                </div>

                            </div>

                            <div class="row w-100 mx-0">

                                <div class="col-12 d-flex px-0">
                                    <div class="col-2 px-0 font-weight-bolder">
                                        العنوان :
                                    </div>
                                    <div class="col-10 px-0" dir="rtl">
                                        <?php 
                                            echo $order['City'] . ' - ' . $order['ClientAddress'];
                                        ?>
                                    </div>
                                    
                                </div>

                            </div>

                            <div class="row w-100 mx-0">

                                <div class="col-12 d-flex px-0">

                                    <div class="col-2 font-weight-bolder px-0">
                                        الموديلات : 
                                    </div>
                                    <div class="col-10 px-0" dir="rtl">
                                        <?php
                                            foreach($pieces as $piece) :
                                                $SizeNum = $piece['ProductSize'];
                                                $size = $arraySize[$SizeNum];
                                                
                                                echo' 
                                                    <span dir="rtl">('   
                                                        . $piece['NumberOfPieces'] . ')'
                                                        . $piece['ProductName'] . ' -' 
                                                        . $piece['ProductColor'] . '-' 
                                                        . $size . ' /
                                                    </span>
                                                    ';

                                            endforeach;
                                        ?>
                                    </div>
                                    
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
