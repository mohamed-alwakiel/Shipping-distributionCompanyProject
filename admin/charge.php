<?php
    /*
        orders page
        you can Edit orders from here
    */
    ob_start();

    session_start();
    $pageTitle = 'الشحن';
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
        if($_SERVER['REQUEST_METHOD'] == 'POST') :
            $MandopName     = $_POST['Mandopname'];
            $MandopStatus   = $_POST['Mandopstatus'];
            $OrderPhone     = $_POST['SearchPhone'];
            $MainStatus     = $_POST['Mainstatus'];
            $OrderNum       = $_POST['SearchOrderNum'];
        else :
            $MandopName   = '-1';
            $MandopStatus   = '-1';
            $OrderPhone   = '';
            $MainStatus  = '-1';
            $OrderNum = '';
        endif;

        if($_SESSION['UserStatus'] == 2) :

            if(!empty ($OrderPhone)) : 
                $check = $con->prepare("SELECT      orders.* 
                                        FROM        orders
                                        WHERE       orders.City = '$usercity'
                                        AND         orders.Status  IN(0, 1, 2, 3, 10, 11 ,12)
                                        AND         orders.ClientPhone = '$OrderPhone';
                                        ORDER BY    OrderID DESC ");
            elseif(!empty ($OrderNum)) :
                $check = $con->prepare("SELECT      orders.*
                                        FROM        orders
                                        WHERE       orders.City = '$usercity'
                                        AND         orders.Status  IN(0, 1, 2, 3, 10, 11 ,12)
                                        AND         orders.OrderID = '$OrderNum';   
                                        ORDER BY    OrderID DESC ");
            else :
                if($MainStatus != '-1') :
                    if($MandopStatus != '-1') :
                        if($MandopName != '-1') :
                            $check = $con->prepare("SELECT      orders.* 
                                                    FROM        orders
                                                    WHERE       orders.City = '$usercity'
                                                    AND         orders.Status = '$MainStatus'
                                                    AND         orders.MandopStatus = '$MandopStatus';
                                                    AND         orders.MandopID = '$MandopName';
                                                    ORDER BY    OrderID DESC ");
                        else :
                            $check = $con->prepare("SELECT      orders.* 
                                                    FROM        orders
                                                    WHERE       orders.City = '$usercity'
                                                    AND         orders.Status = '$MainStatus'
                                                    AND         orders.MandopStatus = '$MandopStatus';
                                                    ORDER BY    OrderID DESC ");
                        endif;
                    else :
                        if($MandopName != '-1') :
                            $check = $con->prepare("SELECT      orders.* 
                                                    FROM        orders
                                                    WHERE       orders.City = '$usercity'
                                                    AND         orders.Status = '$MainStatus'
                                                    AND         orders.MandopID = '$MandopName';
                                                    ORDER BY    OrderID DESC ");
                        else :
                            $check = $con->prepare("SELECT      orders.*
                                                    FROM        orders
                                                    WHERE       orders.City = '$usercity'
                                                    AND         orders.Status = '$MainStatus'
                                                    ORDER BY    OrderID DESC ");
                        endif;
                    endif;
                else :
                    if($MandopStatus != '-1') :
                        if($MandopName != '-1') :
                            $check = $con->prepare("SELECT      orders.*  
                                                    FROM        orders
                                                    WHERE       orders.City = '$usercity'
                                                    AND         orders.MandopStatus = '$MandopStatus';
                                                    AND         orders.MandopID = '$MandopName';
                                                    ORDER BY    OrderID DESC ");
                        else :
                            $check = $con->prepare("SELECT      orders.*
                                                    FROM        orders
                                                    WHERE       orders.City = '$usercity'
                                                    AND         orders.MandopStatus = '$MandopStatus';
                                                    ORDER BY    OrderID DESC ");
                        endif;
                    else :
                        if($MandopName != '-1') :
                            $check = $con->prepare("SELECT      orders.* 
                                                    FROM        orders
                                                    WHERE       orders.City = '$usercity'
                                                    AND         orders.MandopID = '$MandopName';
                                                    ORDER BY    OrderID DESC ");
                        else :
                            $check = $con->prepare("SELECT      orders.* 
                                                    FROM        orders
                                                    WHERE       orders.City = '$usercity'
                                                    AND         orders.Status IN(0 ,1, 2, 3, 10, 11 ,12)
                                                    ORDER BY    OrderID DESC ");
                        endif;
                    endif;
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
                        <button type="submit" formaction="charge.php?do=Delay" form="formorders" class="btn btn-dark px-3 shadow" data-toggle="tooltip" title="تأجيل الطلب">
                            <i class="fas fa-compass mr-1"></i>
                            تأجيل
                        </button>
                    </div>

                    <div>
                        <button type="submit" formaction="charge.php?do=Closed" form="formorders" class="btn btn-danger px-3 shadow" data-toggle="tooltip" title="الهاتف مغلق">
                            <i class="fas fa-phone-slash mr-1"></i>
                            مغلق
                        </button>
                    </div>

                    <div>
                        <button type="submit" formaction="charge.php?do=NoReplay" form="formorders" class="btn btn-danger px-3 shadow" data-toggle="tooltip" title="لم يرد على الهاتف">
                            <i class="fas fa-phone-volume mr-1"></i>
                            لم يرد
                        </button>
                    </div>

                    <div>
                        <button type="submit" formaction="charge.php?do=Canceled" form="formorders" class="btn btn-danger px-3 shadow" data-toggle="tooltip" title="الغاء الطلب">
                            <i class="fas fa-backspace mr-1"></i>
                            ملغى
                        </button>
                    </div>

                    <div>
                        <button type="submit" formaction="charge.php?do=Evade" form="formorders" class="btn btn-dark px-3 shadow" data-toggle="tooltip" title="تهرب من الاستلام">
                            <i class="fab fa-accessible-icon mr-1"></i>
                            تهرب
                        </button>
                    </div>

                    <div>
                        <button type="submit" formaction="charge.php?do=Backed" form="formorders" class="btn btn-dark px-3 shadow" data-toggle="tooltip" title="رجاع الطلب">
                            <i class="fas fa-undo mr-1"></i>                            
                            مرتجع
                        </button>
                    </div>

                    <div>
                        <button type="submit" formaction="charge.php?do=Delivered" form="formorders" class="btn btn-success px-3 shadow" data-toggle="tooltip" title="تم تسليم الطلب">
                            <i class="fas fa-receipt mr-1"></i>                            
                            تم التسليم
                        </button>
                    </div>

                    <div>
                        <button type="submit" formaction="charge.php?do=Recipt" form="formorders" class="btn btn-primary px-3 shadow" data-toggle="tooltip" title="استلام الطلبات">
                            <i class="fas fa-truck mr-1"></i>
                            استلام
                        </button>
                    </div>
                
                </div>

            </div>

            <div class="d-flex justify-content-end mb-3">
                
                <div class="">
                    <form method="POST" action="" class="">
                        <div class="d-flex">
                            <select name="Mainstatus" class="form-control ml-2">        
                                <option value="-1" selected> -- اختر الحالة -- </option>
                                <option value="0"> طباعة </option>
                                <option value="1"> جارى التجهيز </option>
                                <option value="3"> استلام </option>
                                <option value="10"> لم يرد </option>
                                <option value="11"> مغلق </option>
                                <option value="12"> تأجيل </option>
                            </select>

                            <select name="Mandopstatus" class="form-control ml-2">        
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

                            <select name="Mandopname" class="form-control ml-2">
                                <option value="-1" selected> -- اختر المندوب -- </option>
                                <option value="0"> لم يحدد بعد </option>
                                <?php 
                                    $check = $con->prepare("SELECT  *
                                                            FROM    users
                                                            WHERE   ManagerID = '$userid'");
                                    $check->execute();
                                    $mandops = $check->fetchAll();
                                    foreach($mandops as $mandop) :
                                ?>
                                    <option value="<?php echo $mandop['ID'];?>"> 
                                        <?php echo $mandop['UserName'] . ' - ' . $mandop['Address']; ?>
                                    </option>
                                <?php
                                    endforeach;
                                ?>
                            </select>

                            <input type="text" name="SearchPhone" placeholder="الهاتف" class="form-control ml-2 text-center">

                            <input type="text" name="SearchOrderNum" placeholder="رقم الاوردر" class="form-control ml-2 text-center">
                                        
                            <input type="submit" value="تصفية" class="btn btn-primary ml-2 py-1 px-3">
                        </div>
        
                    </form>
                </div>

            </div>


            <form method="POST" action="" id="formorders" >
            
                <div class="d-flex justify-content-between mb-3">

                    <div class="d-flex justify-content-between">
                        
                        <div class="d-flex justify-content-end">    
                            <div class="d-flex">
                                <div class="mr-3">
                                    <button type="submit" formaction="charge.php?do=PrintMandop" form="formorders" class="btn btn-success px-3 shadow" data-toggle="tooltip" title="طباعة كشف الطلبات">
                                        <i class="fas fa-print mr-1"></i>
                                        طباعة كشف
                                    </button>
                                </div>

                                <div>
                                    <button type="submit" formaction="charge.php?do=PrintOrders" form="formorders" class="btn btn-primary px-3 shadow" data-toggle="tooltip" title="طباعة كروت الطلبات">
                                        <i class="fas fa-print mr-1"></i>
                                        طباعة طلبات
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex w-25 justify-content-between">
                        <div class="w-75">
                            <select name="status" class="form-control">
                                <option value="-1" selected> -- اختر المندوب -- </option>
                                <option value="0"> الغاء تعين مندوب </option>
                                <?php 
                                    $check = $con->prepare("SELECT  *
                                                            FROM    users
                                                            WHERE   ManagerID = '$userid'");
                                    $check->execute();
                                    $mandops = $check->fetchAll();
                                    foreach($mandops as $mandop) :
                                ?>
                                    <option value="<?php echo $mandop['ID'];?>"> 
                                        <?php echo $mandop['UserName'] . ' - ' . $mandop['Address']; ?>
                                    </option>
                                <?php
                                    endforeach;
                                ?>
                            </select>
                        </div>
                        <div>  
                            <button type="submit" formaction="charge.php?do=UpdateStatus" form="formorders" class="btn btn-success shadow">
                                اضافة
                            </button>
                        </div>
                    </div>
                    
                </div>

                <table class="table table-bordered shadow main-table table-hover mb-4 text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>رقم الطلب</th>
                            <th>اسم العميل</th>
                            <th>الهاتف</th>
                            <th>العنوان</th>
                            <th>الطلبات</th>
                            <th>السعر</th>
                            <th>التاريخ</th>
                            <th>الحالة</th>
                            <th>اسم المندوب</th>
                            <th>حالة التوزيع</th>
                            <th>
                                <input class="form-check-input p" type="checkbox" id="selectAll" data-toggle="tooltip" title="تحديد الكل">
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        <!-- print in the table from data base -->
                        <?php
                                foreach($orders as $order) :
                                    
                                    $arrayStatus = ["طباعة", "جارى-التجهيز" ,"توزيع", "استلام", "جارى-المحاسبة", "تم-التسليم", "تم-التحصيل", "مرتجع", "تهرب", "ملغى", "لم-يرد", "مغلق", "تأجيل"];
                                    $statusNum = $order['Status'];
                                    $status = $arrayStatus[$statusNum];

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
                                    

                                    if($order['MandopID'] == 0) :
                                        $mandopname = 'لم يحدد بعد';
                                    else:
                                        $mandopID = $order['MandopID'];
                                        $check = $con->prepare("SELECT  *
                                                                FROM    users
                                                                WHERE   ID = '$mandopID'");
                                        $check->execute();
                                        $row = $check->fetch();
                                        $mandopname = $row['UserName'];
                                    endif; 
                                    
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
                                            <td>' . $status . '</td>                                
                                            <td>' . $mandopname . '</td>                                
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
        /// ======================= Update Mandop Page ===========================//
        ///=======================================================================//
        
    elseif($do == 'UpdateStatus') :
        // Update Status page
    ?>
        <!-- start update page -->
        <h1 class="text-center font-weight-bold mt-5 my-4"></h1>
        <div class="container">
            
        <?php
        if($_SERVER['REQUEST_METHOD'] == 'POST') :
            if(isset($_POST['check'])) :
                $mandopstatus   = $_POST['status'];
                $EditRows       = $_POST['check'];

                $count = 0;

                if($mandopstatus == 0) :
                    foreach($EditRows as $rowID):
                        // now update the data base with this new data
                        $check = $con->prepare("UPDATE  orders 
                                                SET     MandopID = ?,
                                                        MandopStatus = ?
                                                WHERE   OrderID = ?");
                        $check->execute(array($mandopstatus, $mandopstatus, $rowID));
                        $count += 1;
                    endforeach;
                else :
                    foreach($EditRows as $rowID):
                        // now update the data base with this new data
                        $check = $con->prepare("UPDATE  orders 
                                                SET     MandopID = ?
                                                WHERE   OrderID = ?");
                        $check->execute(array($mandopstatus, $rowID));
                        $count += 1;
                    endforeach;
                endif;
                // print success message
                $themsg = ' <div class="alert alert-primary text-center" dir="rtl" role="alert">
                                ' . $count . ' طلب تم تعينه للمندوب بنجاح !
                            </div>' ;
                RedirectFun($themsg, 'charge', 'update');
                
            else :
                $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert"> 
                                اسف ، يجب تحدد بعض الطلبات و تحديد حالة 
                            </div>';
                RedirectFun($themsg, 'charge', 'update');
            endif;
        else :
            $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                            لا يمكنك الدخول على هذه الصفحة مباشرة 
                        </div>';
            RedirectFun($themsg);  
        endif; 
        ?>

        </div>
        <!-- end update page -->

    <?php 
        ///=======================================================================//
        /// ========================== Recipt Page ===============================//
        ///=======================================================================//

    elseif($do == 'Recipt') :
        // Recipt page
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
                                                SET     Status = 3
                                                WHERE   OrderID = ?");
                        $check->execute(array($rowID));
                        $count += 1;
                    endforeach;
                
                    // print success message
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                ' . $count . ' طلب تم استلامه بنجاح
                                </div>' ;
                    RedirectFun($themsg, 'charge', 'recipt');
                else:
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert"> 
                                    اسف ، يجب تحديد بعض الطلبات
                                </div>';
                    RedirectFun($themsg);
                endif;          
            endif;
        ?>

            </div>
            <!-- end Recipt page -->
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
                                                SET     Status = 4
                                                WHERE   OrderID = ?");
                        $check->execute(array($rowID));
                        $count += 1;
                    endforeach;
                
                    // print success message
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                ' . $count . ' طلب تم تسليمه بنجاح
                                </div>' ;
                    RedirectFun($themsg, 'charge', 'delivered');
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
                                                SET     Status = 7
                                                WHERE   OrderID = ?");
                        $check->execute(array($rowID));

                        $checkchange =  $con->prepare(" SELECT  *
                                                        FROM    pieces
                                                        WHERE   OrderNumber = '$rowID'");
                        $checkchange->execute();
                        $checkchangefetch = $checkchange->fetchALL();

                        $i = 1;

                        foreach($checkchangefetch as $orderproduct) :
                            $oldpieceid     = $orderproduct['PieceID'];                 
                            $oldpiecemodel  = $orderproduct['PieceModel'];              
                            $oldnumofpiece  = $orderproduct['NumberOfPieces'];          

                            // check if the product exists in store or not
                            $updateproduct  = $con->prepare("   SELECT  *
                                                                FROM    products
                                                                WHERE   ProductID = '$oldpiecemodel'");
                            $updateproduct->execute();
                            $updateproductfetch = $updateproduct->fetch();
                            $updatenumofpiecesproduct = $updateproductfetch['NumOfPieces'];             
                            $afternumofpiecesproduct = $updatenumofpiecesproduct + $oldnumofpiece ;     

                            // now change the number of pices
                            $checkupdate = $con->prepare("  UPDATE  products
                                                            SET     NumOfPieces = ?
                                                            WHERE   ProductID = ?");
                            $checkupdate->execute(array($afternumofpiecesproduct, $oldpiecemodel));

                            $i++;
                        endforeach;

                        $count += 1;
                    endforeach;
                
                    // print success message
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                ' . $count . ' طلب مرتجع
                                </div>' ;
                    RedirectFun($themsg, 'charge', 'backed');
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
                                                SET     Status = 8
                                                WHERE   OrderID = ?");
                        $check->execute(array($rowID));

                        $checkchange =  $con->prepare(" SELECT  *
                                                        FROM    pieces
                                                        WHERE   OrderNumber = '$rowID'");
                        $checkchange->execute();
                        $checkchangefetch = $checkchange->fetchALL();

                        $i = 1;

                        foreach($checkchangefetch as $orderproduct) :
                        $oldpieceid     = $orderproduct['PieceID'];                 
                        $oldpiecemodel  = $orderproduct['PieceModel'];              
                        $oldnumofpiece  = $orderproduct['NumberOfPieces'];          

                        // check if the product exists in store or not
                        $updateproduct  = $con->prepare("   SELECT  *
                                                        FROM    products
                                                        WHERE   ProductID = '$oldpiecemodel'");
                        $updateproduct->execute();
                        $updateproductfetch = $updateproduct->fetch();
                        $updatenumofpiecesproduct = $updateproductfetch['NumOfPieces'];             
                        $afternumofpiecesproduct = $updatenumofpiecesproduct + $oldnumofpiece ;     

                        // now change the number of pices
                        $checkupdate = $con->prepare("  UPDATE  products
                                                    SET     NumOfPieces = ?
                                                    WHERE   ProductID = ?");
                        $checkupdate->execute(array($afternumofpiecesproduct, $oldpiecemodel));

                        $i++;
                        endforeach;

                        $count += 1;
                    endforeach;
                
                    // print success message
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                ' . $count . ' طلب تم التهرب من استلامه
                                </div>' ;
                    RedirectFun($themsg, 'charge', 'evade');
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
                                                SET     Status = 9
                                                WHERE   OrderID = ?");
                        $check->execute(array($rowID));

                        $checkchange =  $con->prepare(" SELECT  *
                                                        FROM    pieces
                                                        WHERE   OrderNumber = '$rowID'");
                        $checkchange->execute();
                        $checkchangefetch = $checkchange->fetchALL();

                        $i = 1;

                        foreach($checkchangefetch as $orderproduct) :
                        $oldpieceid     = $orderproduct['PieceID'];                 
                        $oldpiecemodel  = $orderproduct['PieceModel'];              
                        $oldnumofpiece  = $orderproduct['NumberOfPieces'];          

                        // check if the product exists in store or not
                        $updateproduct  = $con->prepare("   SELECT  *
                                                        FROM    products
                                                        WHERE   ProductID = '$oldpiecemodel'");
                        $updateproduct->execute();
                        $updateproductfetch = $updateproduct->fetch();
                        $updatenumofpiecesproduct = $updateproductfetch['NumOfPieces'];             
                        $afternumofpiecesproduct = $updatenumofpiecesproduct + $oldnumofpiece ;     

                        // now change the number of pices
                        $checkupdate = $con->prepare("  UPDATE  products
                                                    SET     NumOfPieces = ?
                                                    WHERE   ProductID = ?");
                        $checkupdate->execute(array($afternumofpiecesproduct, $oldpiecemodel));

                        $i++;
                        endforeach;
                        $count += 1;
                    endforeach;
                
                    // print success message
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                ' . $count . ' طلب تم الغاءه
                                </div>' ;
                    RedirectFun($themsg, 'charge', 'canceled');
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
                                                SET     Status = 10
                                                WHERE   OrderID = ?");
                        $check->execute(array($rowID));
                        $count += 1;
                    endforeach;
                
                    // print success message
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                    العميل لم يقم بالرد
                                </div>' ;
                    RedirectFun($themsg, 'charge', 'noreplay');
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
                                                SET     Status = 11
                                                WHERE   OrderID = ?");
                        $check->execute(array($rowID));
                        $count += 1;
                    endforeach;
                
                    // print success message
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                    الهاتف كان مغلق
                                </div>' ;
                    RedirectFun($themsg, 'charge', 'Closed');
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
                                                SET     Status = 12
                                                WHERE   OrderID = ?");
                        $check->execute(array($rowID));
                        $count += 1;
                    endforeach;
                
                    // print success message
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                    العميل طلب التاجيل
                                </div>' ;
                    RedirectFun($themsg, 'charge', 'delay');
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
        /// ========================= PrintOrders Page ===========================//
        ///=======================================================================//

    elseif($do == 'PrintOrders') :
        // Print page
    ?>

        <!-- start Print page -->
        <h1 class="text-center font-weight-bold mt-5 my-4"></h1>

        <div class="container">
        <?php 
        
            if($_SESSION['UserStatus'] == 2) :
                if($_SERVER['REQUEST_METHOD'] == 'POST') :
                    if(isset($_POST['check'])) :
                        $PrintRows = $_POST['check'];
                        $count = 0;
                        foreach($PrintRows as $rowID):
                            $check = $con->prepare("UPDATE  orders
                                                    SET     Status = 0
                                                    WHERE   OrderID=?");
                            $check->execute(array($rowID));
                            $count += 1;
                        endforeach;
                    
                        // print success message
                        $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                    ' . $count . ' طلب تم اضافة للطباعة بنجاح 
                                    </div>' ;
                        RedirectFun($themsg, 'charge', 'print');
                    else:
                        $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert"> 
                                        اسف ، يجب تحديد بعض الطلبات
                                    </div>';
                        RedirectFun($themsg);
                    endif;
                else:
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert"> 
                                        اسف يجب تحديد بعض الطلبات
                                    </div>';
                    RedirectFun($themsg);
                endif;
            elseif($_SESSION['UserStatus'] == 0) :
                
                // print warning message
                $themsg =  '<div class="alert alert-danger text-center" dir="rtl" role="alert">
                                لا يمكنك الدخول على هذه الصفحه رنك لست مسؤول شحن
                            </div>' ;
                RedirectFun($themsg, 'charge', 'warning');
            endif;
                ?>

        </div>
        <!-- end approve page -->
    <?php
        ///=======================================================================//
        /// ========================= PrintMandop Page ===========================//
        ///=======================================================================//

    elseif($do == 'PrintMandop') :
        // Print page
    ?>
    
        <!-- start Print page -->
        <h1 class="text-center font-weight-bold mt-5 my-4"></h1>

        <div class="container">
        <?php 
        
            if($_SESSION['UserStatus'] == 2) :
                if($_SERVER['REQUEST_METHOD'] == 'POST') :
                    if(isset($_POST['check'])) :
                        $PrintRows = $_POST['check'];
                        $count = 0;
                        foreach($PrintRows as $rowID):
                            $check = $con->prepare("UPDATE  orders
                                                    SET     ChargePrint = 1
                                                    WHERE   OrderID=?");
                            $check->execute(array($rowID));
                            $count += 1;
                        endforeach;
                    
                        // print success message
                        $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                    ' . $count . ' طلب تم اضافة للطباعة بنجاح 
                                    </div>' ;
                        RedirectFun($themsg, 'charge', 'print');
                    else:
                        $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert"> 
                                        اسف ، يجب تحديد بعض الطلبات
                                    </div>';
                        RedirectFun($themsg);
                    endif;
                else:
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert"> 
                                        اسف يجب تحديد بعض الطلبات
                                    </div>';
                    RedirectFun($themsg);
                endif;
            elseif($_SESSION['UserStatus'] == 0) :
                
                // print warning message
                $themsg =  '<div class="alert alert-danger text-center" dir="rtl" role="alert">
                                لا يمكنك الدخول على هذه الصفحه رنك لست مسؤول شحن
                            </div>' ;
                RedirectFun($themsg, 'charge', 'warning');
            endif;
                ?>

        </div>
        <!-- end approve page -->
    <?php

        
    endif;
?>
 
<!-- end content  -->

<!-- start footer -->
<?php  include $tpl . 'footer.php'; ?>
<!-- end footer -->

<?php ob_end_flush(); ?>