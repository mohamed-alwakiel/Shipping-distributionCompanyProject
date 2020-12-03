<?php
    /*
        orders page
        you can Edit orders from here
    */
    ob_start();

    session_start();
    $pageTitle = 'التوزيع';
    // check if session exist or not
    if(isset($_SESSION['Username'])) :
        include("init.php");
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        $mandopid   = $_SESSION['Userid'];
    
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
        // Manage page
        if($_SESSION['UserStatus'] == 3) :

            // select all orders to admin
            $check = $con->prepare("SELECT      orders.*,  
                                                products.*  
                                    FROM        orders
                                    INNER JOIN  products
                                            ON  products.ProductID = orders.OrderModel
                                    WHERE       orders.MandopID = '$mandopid'
                                    AND         orders.MandopStatus = 0
                                    ORDER BY    OrderID DESC ");

        else :
            $themsg = ' <div class="alert alert-danger" role="alert">
                            لا يمكنك الدخول على هذه الصفحه مباشرة 
                        </div>';
            RedirectFun($themsg);
        endif;
        
        $check->execute();
        
        // fetech all data from data base
        $orders = $check->fetchAll();
        

    ?>
        <!-- start manage page -->
        <h1 class="text-center font-weight-bold mt-3 mb-4"> ادارة الطلبات </h1>

        <div class="mx-5 pb-5 min-h">
        
            <div class="d-flex justify-content-end mb-3">
                
                <div class="d-flex w-50 justify-content-between">

                    <div>
                        <button type="submit" formaction="distrbution.php?do=Canceled" form="formorders" class="btn btn-danger px-3 shadow" data-toggle="tooltip" title="الغاء الطلب">
                            <i class="fas fa-backspace mr-1"></i>
                            ملغى
                        </button>
                    </div>

                    <div>
                        <button type="submit" formaction="distrbution.php?do=Evade" form="formorders" class="btn btn-dark px-3 shadow" data-toggle="tooltip" title="تهرب من الاستلام">
                            <i class="fab fa-accessible-icon mr-1"></i>
                            تهرب
                        </button>
                    </div>

                    <div>
                        <button type="submit" formaction="distrbution.php?do=Backed" form="formorders" class="btn btn-dark px-3 shadow" data-toggle="tooltip" title="ارجاع الطلب">
                            <i class="fas fa-undo mr-1"></i>                            
                            مرتجع
                        </button>
                    </div>

                    <div>
                        <button type="submit" formaction="distrbution.php?do=Delivered" form="formorders" class="btn btn-success px-3 shadow" data-toggle="tooltip" title="تم تسليم الطلب">
                            <i class="fas fa-receipt mr-1"></i>                            
                            تم التسليم
                        </button>
                    </div>
                
                </div>

            </div>

            <form method="POST" action="" id="formorders" >
            
              <table class="table table-bordered shadow main-table table-hover mb-4 text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>اسم العميل</th>
                            <th>الهاتف</th>
                            <th>العنوان</th>
                            <th>الموديل</th>
                            <th>السعر</th>
                            <th>التاريخ</th>
                            <th>الحالة</th>
                            <th>
                                <input class="form-check-input p" type="checkbox" id="selectAll" data-toggle="tooltip" title="تحديد الكل">
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

                                    if($order['MandopStatus'] == 0) :
                                        $orderStatus = 'لم يحدد بعد';
                                    else:
                                        $arrayStatus = ["", "تم-التسليم", "مرتجع", "تهرب", "ملغى"];
                                        $statusNum = $order['MandopStatus'];
                                        $orderStatus = $arrayStatus[$statusNum];
                                    endif; 
                                    
                                    echo    
                                        '<tr>
                                            
                                            <td>' . $order['ClientName'] . '</td>
                                            <td>' . $order['ClientPhone'] . '</td>
                                            <td>' . $order['ClientAddress'] . '</td>
                                            <td>' 
                                                . $size . ' - ' 
                                                . $order['ProductName'] . ' - ' 
                                                . $order['ProductColor'] .
                                            '</td>
                                            <td>' . $order['OrderPrice'] . '</td>
                                            <td>' . $order['OrderDate'] . '</td>                                
                                            <td>' . $orderStatus . '</td>                                
                                            <td>
                                                <input class="form-check-input" type="checkbox" id="checkItem" name="check[]" value="' . $order['OrderID'] . '" data-toggle="tooltip" title="تحديد">
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
                        Load more 
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
        /// ========================== Delivered Page ============================//
        ///=======================================================================//

    elseif($do == 'Delivered') :
        // Delivered page
    ?>
            <!-- start Delivered page -->
            <h1 class="text-center font-weight-bold mt-5 my-4"></h1>

            <div class="container">
            <?php    
            if($_SERVER['REQUEST_METHOD'] == 'POST') :
                if(isset($_POST['check'])) :
                    $Rows = $_POST['check'];
                    $count = 0;
                    foreach($Rows as $rowID):
                        $check = $con->prepare("UPDATE  orders
                                                SET     MandopStatus = 1
                                                WHERE   OrderID = ?");
                        $check->execute(array($rowID));
                        $count += 1;
                    endforeach;
                
                    // print success message
                    $themsg = ' <div class="alert alert-danger" role="alert">
                                ' . $count . ' طلب تم تسليمه بنجاح
                                </div>' ;
                    RedirectFun($themsg, 'distrbution', 'delivered');
                else:
                    $themsg = ' <div class="alert alert-danger" role="alert"> 
                                    اسف ، يجب تحديد بعض الطلبات
                                </div>';
                    RedirectFun($themsg);
                endif;          
            endif;
        ?>

            </div>
            <!-- end Delivered page -->
    <?php
        ///=======================================================================//
        /// ========================== Backed Page ===============================//
        ///=======================================================================//

    elseif($do == 'Backed') :
        // Backed page
    ?>
            <!-- start Recipt page -->
            <h1 class="text-center font-weight-bold mt-5 my-4"></h1>

            <div class="container">
            <?php    
            if($_SERVER['REQUEST_METHOD'] == 'POST') :
                if(isset($_POST['check'])) :
                    $Rows = $_POST['check'];
                    $count = 0;
                    foreach($Rows as $rowID):
                        $check = $con->prepare("UPDATE  orders
                                                SET     MandopStatus = 2
                                                WHERE   OrderID = ?");
                        $check->execute(array($rowID));
                        $count += 1;
                    endforeach;
                
                    // print success message
                    $themsg = ' <div class="alert alert-danger" role="alert">
                                ' . $count . ' طلب مرتجع
                                </div>' ;
                    RedirectFun($themsg, 'distrbution', 'backed');
                else:
                    $themsg = ' <div class="alert alert-danger" role="alert"> 
                                    اسف ، يجب تحديد بعض الطلبات
                                </div>';
                    RedirectFun($themsg);
                endif;          
            endif;
        ?>

            </div>
            <!-- end Backed page -->
    <?php
        ///=======================================================================//
        /// ========================== Evade Page ================================//
        ///=======================================================================//

    elseif($do == 'Evade') :
        // Evade page
    ?>
            <!-- start Evade page -->
            <h1 class="text-center font-weight-bold mt-5 my-4"></h1>

            <div class="container">
            <?php    
            if($_SERVER['REQUEST_METHOD'] == 'POST') :
                if(isset($_POST['check'])) :
                    $Rows = $_POST['check'];
                    $count = 0;
                    foreach($Rows as $rowID):
                        $check = $con->prepare("UPDATE  orders
                                                SET     MandopStatus = 3
                                                WHERE   OrderID = ?");
                        $check->execute(array($rowID));
                        $count += 1;
                    endforeach;
                
                    // print success message
                    $themsg = ' <div class="alert alert-danger" role="alert">
                                ' . $count . ' طلب تم التهرب من استلامه
                                </div>' ;
                    RedirectFun($themsg, 'distrbution', 'evade');
                else:
                    $themsg = ' <div class="alert alert-danger" role="alert"> 
                                    اسف ، يجب تحديد بعض الطلبات
                                </div>';
                    RedirectFun($themsg);
                endif;          
            endif;
        ?>

            </div>
            <!-- end Evade page -->
    <?php
        ///=======================================================================//
        /// ===================== Canceled Page ==================================//
        ///=======================================================================//

    elseif($do == 'Canceled') :
        // Canceled page
    ?>
            <!-- start Canceled page -->
            <h1 class="text-center font-weight-bold mt-5 my-4"></h1>

            <div class="container">
            <?php    
            if($_SERVER['REQUEST_METHOD'] == 'POST') :
                if(isset($_POST['check'])) :
                    $Rows = $_POST['check'];
                    $count = 0;
                    foreach($Rows as $rowID):
                        $check = $con->prepare("UPDATE  orders
                                                SET     MandopStatus = 4
                                                WHERE   OrderID = ?");
                        $check->execute(array($rowID));
                        $count += 1;
                    endforeach;
                
                    // print success message
                    $themsg = ' <div class="alert alert-danger" role="alert">
                                ' . $count . ' طلب تم الغاءه
                                </div>' ;
                    RedirectFun($themsg, 'distrbution', 'canceled');
                else:
                    $themsg = ' <div class="alert alert-danger" role="alert"> 
                                    اسف ، يجب تحديد بعض الطلبات
                                </div>';
                    RedirectFun($themsg);
                endif;          
            endif;
        ?>

            </div>
            <!-- end Canceled page -->
    <?php
        
    endif;
?>
 
<!-- end content  -->

<!-- start footer -->
<?php  include $tpl . 'footer.php'; ?>
<!-- end footer -->

<?php ob_end_flush(); ?>