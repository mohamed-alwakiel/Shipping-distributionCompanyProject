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
        if($_SERVER['REQUEST_METHOD'] == 'POST') :
            $OrderPhone     = $_POST['SearchPhone'];
            $MainStatus     = $_POST['status'];
            $OrderNum       = $_POST['SearchOrderNum'];
        else :
            $OrderPhone   = '';
            $MainStatus  = '-1';
            $OrderNum = '';
        endif;

        if($_SESSION['UserStatus'] == 3) :

            
            if(!empty ($OrderPhone)) : 
                $check = $con->prepare("SELECT      orders.* 
                                        FROM        orders
                                        WHERE       orders.MandopID = '$mandopid'
                                        AND         orders.MandopStatus  IN(0, 2, 3, 4, 5, 6, 7)
                                        AND         orders.ClientPhone = '$OrderPhone';
                                        ORDER BY    OrderID DESC ");
            elseif(!empty ($OrderNum)) : 
                $check = $con->prepare("SELECT      orders.*  
                                        FROM        orders
                                        WHERE       orders.MandopID = '$mandopid'
                                        AND         orders.MandopStatus  IN(0, 2, 3, 4, 5, 6, 7)
                                        AND         orders.OrderID = '$OrderNum';
                                        ORDER BY    OrderID DESC ");
            else :
                if($MainStatus != '-1') :
                    $check = $con->prepare("SELECT      orders.* 
                                            FROM        orders
                                            WHERE       orders.MandopID = '$mandopid'
                                            AND         orders.MandopStatus = '$MainStatus'
                                            ORDER BY    OrderID DESC ");
                else :
                    $check = $con->prepare("SELECT      orders.* 
                                            FROM        orders
                                            WHERE       orders.MandopID = '$mandopid'
                                            AND         orders.MandopStatus IN(0, 2, 3, 4, 5, 6, 7)
                                            ORDER BY    OrderID DESC ");
                endif;
            endif;            

        else :
            $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                            لا يمكنك الدخول على هذه الصفحه مباشرة 
                        </div>';
            RedirectFun($themsg);
        endif;
        
        $check->execute();
        
        // fetech all data from data base
        $orders = $check->fetchAll();

    // Manage page
    ?>
        <!-- start manage page -->
        <h1 class="text-center font-weight-bold mt-3 mb-4"> ادارة الطلبات </h1>

            <div class="mx-5 pb-5 min-h">
            
                <div class="d-flex justify-content-end mb-3">
                    
                    <div class="d-flex w-75 justify-content-between">

                        <div>
                            <button type="submit" formaction="distrbution.php?do=Delay" form="formorders" class="btn btn-dark px-3 shadow" data-toggle="tooltip" title="تأجيل الطلب">
                                <i class="fas fa-compass"></i>
                                تأجيل
                            </button>
                        </div>

                        <div>
                            <button type="submit" formaction="distrbution.php?do=Closed" form="formorders" class="btn btn-danger px-3 shadow" data-toggle="tooltip" title="الهاتف مغلق">
                                <i class="fas fa-phone-slash"></i>
                                مغلق
                            </button>
                        </div>

                        <div>
                            <button type="submit" formaction="distrbution.php?do=NoReplay" form="formorders" class="btn btn-danger px-3 shadow" data-toggle="tooltip" title="لم يرد على الهاتف">
                                <i class="fas fa-phone-volume"></i>
                                لم يرد
                            </button>
                        </div>

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

                <div class="d-flex justify-content-end mb-3">
                
                    <div class="">
                        <form method="POST" action="" class="">
                            <div class="d-flex">

                                <select name="status" class="form-control ml-2">        
                                    <option value="-1" selected> -- حالة التوزيع -- </option>
                                    <option value="0"> لم يحدد بعد </option>
                                    <option value="1"> تم التسليم </option>
                                    <option value="2"> مرتجع </option>
                                    <option value="3"> تهرب </option>
                                    <option value="4"> ملغى </option>
                                    <option value="5"> لم يرد </option>
                                    <option value="6"> مغلق </option>
                                    <option value="7"> تأجيل </option>
                                </select>

                                <input type="text" name="SearchPhone" placeholder="الهاتف" class="form-control ml-2 text-center">

                                <input type="text" name="SearchOrderNum" placeholder="رقم الاوردر" class="form-control ml-2 text-center">

                                <input type="submit" value="تصفية" class="btn btn-primary ml-2 py-1 px-3">
                            </div>
            
                        </form>
                    </div>


            </div>

            <form method="POST" action="" id="formorders" >
            
              <table class="table table-bordered shadow main-table table-hover mb-4 text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>رقم الطلب</th>
                            <th>اسم العميل</th>
                            <th>الهاتف</th>
                            <th>العنوان</th>
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
                                    
                                    if($order['MandopStatus'] == 0) :
                                        $orderStatus = 'لم يحدد بعد';
                                    else:
                                        $arrayStatus = ["", "تم-التسليم", "مرتجع", "تهرب", "ملغى" , "لم-يرد" , "مغلق" , "تأجيل"];
                                        $statusNum = $order['MandopStatus'];
                                        $orderStatus = $arrayStatus[$statusNum];
                                    endif; 
                                    
                                    echo    
                                        '<tr>
                                            <td>' . $order['OrderID'] . '</td>
                                            <td>' . $order['ClientName'] . '</td>
                                            <td>' . $order['ClientPhone'] ;
                                    if($order['ClientPhone2'] != 0) :
                                        echo '<br>' . $order['ClientPhone2'] ;
                                    endif;
                                        echo'</td>
                                            <td>' . $order['ClientAddress'] . '</td>
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
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                ' . $count . ' طلب تم تسليمه بنجاح
                                </div>' ;
                    RedirectFun($themsg, 'distrbution', 'delivered');
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
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                ' . $count . ' طلب مرتجع
                                </div>' ;
                    RedirectFun($themsg, 'distrbution', 'backed');
                else:
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert"> 
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
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                ' . $count . ' طلب تم التهرب من استلامه
                                </div>' ;
                    RedirectFun($themsg, 'distrbution', 'evade');
                else:
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert"> 
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
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                ' . $count . ' طلب تم الغاءه
                                </div>' ;
                    RedirectFun($themsg, 'distrbution', 'canceled');
                else:
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert"> 
                                    اسف ، يجب تحديد بعض الطلبات
                                </div>';
                    RedirectFun($themsg);
                endif;          
            endif;
        ?>

            </div>
            <!-- end Canceled page -->
    <?php
        ///=======================================================================//
        /// ===================== NoReplay Page ==================================//
        ///=======================================================================//

    elseif($do == 'NoReplay') :
        // NoReplay page
    ?>
            <!-- start NoReplay page -->
            <h1 class="text-center font-weight-bold mt-5 my-4"></h1>

            <div class="container">
            <?php    
            if($_SERVER['REQUEST_METHOD'] == 'POST') :
                if(isset($_POST['check'])) :
                    $Rows = $_POST['check'];
                    $count = 0;
                    foreach($Rows as $rowID):
                        $check = $con->prepare("UPDATE  orders
                                                SET     MandopStatus = 5
                                                WHERE   OrderID = ?");
                        $check->execute(array($rowID));
                        $count += 1;
                    endforeach;
                
                    // print success message
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                    العميل لم يقم بالرد
                                </div>' ;
                    RedirectFun($themsg, 'distrbution', 'noreplay');
                else:
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert"> 
                                    اسف ، يجب تحديد بعض الطلبات
                                </div>';
                    RedirectFun($themsg);
                endif;          
            endif;
        ?>

            </div>
            <!-- end Canceled page -->
    <?php
        ///=======================================================================//
        /// ===================== Closed Page ==================================//
        ///=======================================================================//

    elseif($do == 'Closed') :
        // Closed page
    ?>
            <!-- start Closed page -->
            <h1 class="text-center font-weight-bold mt-5 my-4"></h1>

            <div class="container">
            <?php    
            if($_SERVER['REQUEST_METHOD'] == 'POST') :
                if(isset($_POST['check'])) :
                    $Rows = $_POST['check'];
                    $count = 0;
                    foreach($Rows as $rowID):
                        $check = $con->prepare("UPDATE  orders
                                                SET     MandopStatus = 6
                                                WHERE   OrderID = ?");
                        $check->execute(array($rowID));
                        $count += 1;
                    endforeach;
                
                    // print success message
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                    الهاتف كان مغلق
                                </div>' ;
                    RedirectFun($themsg, 'distrbution', 'Closed');
                else:
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert"> 
                                    اسف ، يجب تحديد بعض الطلبات
                                </div>';
                    RedirectFun($themsg);
                endif;          
            endif;
        ?>

            </div>
            <!-- end Canceled page -->
    <?php
        ///=======================================================================//
        /// ===================== Delay Page ==================================//
        ///=======================================================================//

    elseif($do == 'Delay') :
        // Delay page
    ?>
            <!-- start Delay page -->
            <h1 class="text-center font-weight-bold mt-5 my-4"></h1>

            <div class="container">
            <?php    
            if($_SERVER['REQUEST_METHOD'] == 'POST') :
                if(isset($_POST['check'])) :
                    $Rows = $_POST['check'];
                    $count = 0;
                    foreach($Rows as $rowID):
                        $check = $con->prepare("UPDATE  orders
                                                SET     MandopStatus = 7
                                                WHERE   OrderID = ?");
                        $check->execute(array($rowID));
                        $count += 1;
                    endforeach;
                
                    // print success message
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                    العميل طلب التاجيل
                                </div>' ;
                    RedirectFun($themsg, 'distrbution', 'delay');
                else:
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert"> 
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