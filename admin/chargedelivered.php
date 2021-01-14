<?php
    /*
        orders page
        you can Edit orders from here
    */
    ob_start();

    session_start();
    $pageTitle = 'الحسابات';
    // check if session exist or not
    if(isset($_SESSION['Username'])) :
        include("init.php");
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        $usercity = $_SESSION['UserCity'];
        $userid   = $_SESSION['Userid'];

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
        
        if($_SESSION['UserStatus'] == 1 || $_SESSION['UserStatus'] == 2) :

            $check = $con->prepare("SELECT      orders.* 
                                    FROM        orders
                                    WHERE       orders.Status IN(4, 5)");
            $check->execute();
            $orders = $check->fetchAll();
            
        else :
            $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                            لا يمكنك الدخول على هذه الصفحه مباشرة 
                        </div>';
            RedirectFun($themsg);
        endif;
    // Manage page
    ?>
        <!-- start manage page -->
        <h1 class="text-center font-weight-bold mt-3 mb-4"> المحاسبة و التحصيل </h1>

        <div class="mx-5 pb-5 min-h">
        

            <form method="POST" action="" id="formordersdelivered">

                <table class="table table-bordered shadow main-table table-hover mb-4 text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>رقم الاوردر</th>
                            <th>اسم العميل</th>
                            <th>الهاتف</th>
                            <th>العنوان</th>
                            <th>عدد المنتجات</th>
                            <th>الحساب</th>
                            <th>الحالة</th>
                            <?php
                                if($_SESSION['UserStatus'] == 1) :
                            ?>
                            <th>
                                <input class="form-check-input p" type="checkbox" id="selectAll" data-toggle="tooltip" title="تحديد الكل">
                            </th>
                            <?php
                                endif;
                            ?>
                        </tr>
                    </thead>

                    <tbody>

                        <!-- print in the table from data base -->
                        <?php
                            $totalPrice = 0;
                            $totalorders = 0;
                            foreach($orders as $order) :

                                $orderid = $order['OrderID'];
                                
                                $arrayStatus = ["طباعة", "جارى-التجهيز" ,"توزيع", "استلام", "جارى-المحاسبة", "تم-التسليم", "تم-التحصيل", "مرتجع", "تهرب", "ملغى"];
                                $statusNum = $order['Status'];
                                $status = $arrayStatus[$statusNum];

                                $check2 = $con->prepare("   SELECT  COUNT(Pieces.PieceModel) AS NumberOfOrders,
                                                                    SUM(Pieces.PiecePrice) AS TotalPrice 
                                                            FROM    Pieces
                                                            WHERE   OrderNumber = '$orderid'");
                                $check2->execute();
                                $final = $check2->fetch();

                                $totalPrice += $final['TotalPrice'];
                                $totalorders +=1 ;

                                echo    
                                    '<tr>
                                        <td>' . $order['OrderID'] . '</td>
                                        <td>' . $order['ClientName'] . '</td>
                                        <td>' . $order['ClientPhone'] ;
                                if($order['ClientPhone2'] != 0) :
                                    echo ' - ' . $order['ClientPhone2'] ;
                                endif;
                                    echo'</td>
                                        <td>' . $order['ClientAddress'] . '</td>
                                        <td>' . $final['NumberOfOrders'] . '</td>
                                        <td>' . $final['TotalPrice'] . '</td>
                                        <td>' . $status . '</td>';
                            
                                if($_SESSION['UserStatus'] == 1) :
                            
                                    echo'<td>
                                            <input class="form-check-input" type="checkbox" id="checkItem" name="check[]" value="' . $order['OrderID'] . '" data-toggle="tooltip" title="تحديد">
                                        </td>                                
                                    </tr>';
                                    
                                endif;
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
        

        <div class="mx-5 pb-5">
            <div class="d-flex justify-content-around w-50 py-3 mx-auto bg-dark shadow-lg rounded">

                <div class="text-light text-center font-weight-bold">
                    <div class=""> عدد الطلبــات </div>
                    <div class="mt-2" dir="rtl"> 
                        <?php echo $totalorders . ' طلب ';?>
                    </div>
                </div>
                
                <div class="text-light text-center font-weight-bold">
                    <div class=""> أجمالى الحساب </div>
                    <div class="mt-2" dir="rtl"> 
                        <?php echo $totalPrice . ' جنيهــاً ';?>
                    </div>
                </div>
                
                    <?php 
                        if($_SESSION['UserStatus'] == 1) :
                    ?>
                    
                    <div class="">

                        <div class="">
                            <button type="submit" formaction="chargedelivered.php?do=Collected" form="formordersdelivered" class="btn btn-success px-3 py-3 shadow" data-toggle="tooltip" title="جارى دفع الحساب بالكامل">
                                <i class="fas fa-money-bill-alt mr-1"></i>
                                تم التحصيل
                            </button>
                        </div>
                    </div>

                    <?php 
                        endif;
                    ?>

            </div>
        </div>

        <!-- end manage page --> 
    
    <?php 
        
        ///=======================================================================//
        /// ========================== Collected Page ============================//
        ///=======================================================================//

    elseif($do == 'Collected') :
        // Collected page
    ?>
        <!-- start Collected page -->
        <h1 class="text-center font-weight-bold mt-5 my-4"></h1>

        <div class="container">
        <?php    
        if($_SERVER['REQUEST_METHOD'] == 'POST') :
            if(isset($_POST['check'])) :
                $Rows = $_POST['check'];
                $count = 0;
                foreach($Rows as $rowID):
                    $check = $con->prepare("UPDATE  orders
                                            SET     Status = 6
                                            WHERE   OrderID = ?");
                    $check->execute(array($rowID));
                    $count += 1;
                endforeach;
            
                // print success message
                $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                            ' . $count . ' طلب تم تحصيل مبلغه بالكامل
                            </div>' ;
                RedirectFun($themsg, 'back');
            else:
                $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert"> 
                                اسف ، يجب تحديد بعض الطلبات
                            </div>';
                RedirectFun($themsg);
            endif;          
        endif;
        ?>

        </div>
        <!-- end Delivered page -->
    <?php
        
    endif;
?>
 
<!-- end content  -->

<!-- start footer -->
<?php  include $tpl . 'footer.php'; ?>
<!-- end footer -->

<?php ob_end_flush(); ?>