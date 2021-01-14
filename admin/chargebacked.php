<?php
    /*
        orders page
        you can Edit orders from here
    */
    ob_start();

    session_start();
    $pageTitle = 'المرتجعات';
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
            $City     = $_POST['city'];
            $Date     = $_POST['SearchDate'];
            $Phone    = $_POST['SearchPhone'];
            $Status   = $_POST['status'];
        else :
            $City     = 'all';
            $Status   = '-1';
            $Date     = '';
            $Phone    = '';
        endif;

        if($_SESSION['UserStatus'] == 1 || $_SESSION['UserStatus'] == 2 ) :
            
            if(!empty ($Phone)) : 
                $check = $con->prepare("SELECT      orders.* 
                                        FROM        orders
                                        WHERE       orders.Status IN(7, 8, 9)
                                        AND         orders.ClientPhone = '$Phone'   
                                        ORDER BY    OrderID DESC ");
            else :
                if($City != 'all') :
                    if($Status != '-1') :
                        if(!empty ($Date)) :
                            $check = $con->prepare("SELECT      orders.* 
                                                    FROM        orders
                                                    WHERE       orders.City = '$City'
                                                    AND         orders.Status = '$Status'
                                                    AND         orders.OrderDate = '$Date'
                                                    ORDER BY    OrderID DESC ");
                        else:
                            $check = $con->prepare("SELECT      orders.* 
                                                    FROM        orders
                                                    WHERE       orders.City = '$City'
                                                    AND         orders.Status = '$Status'
                                                    ORDER BY    OrderID DESC ");
                        endif;
                    else :
                        if(!empty ($Date)) :
                            $check = $con->prepare("SELECT      orders.*  
                                                    FROM        orders
                                                    WHERE       orders.Status IN(7, 8, 9)
                                                    AND         orders.City = '$City'
                                                    AND         orders.OrderDate = '$Date'
                                                    ORDER BY    OrderID DESC ");
                        else:
                            $check = $con->prepare("SELECT      orders.*
                                                    FROM        orders
                                                    WHERE       orders.Status IN(7, 8, 9)
                                                    AND         orders.City = '$City'
                                                    ORDER BY    OrderID DESC ");
                        endif;
                    endif;
                else:
                    if($Status != '-1') :
                        if(!empty ($Date)) :
                            $check = $con->prepare("SELECT      orders.*
                                                    FROM        orders
                                                    WHERE       orders.Status = '$Status'
                                                    AND         orders.OrderDate = '$Date'
                                                    ORDER BY    OrderID DESC ");
                        else:
                            $check = $con->prepare("SELECT      orders.*
                                                    FROM        orders
                                                    WHERE       orders.Status = '$Status'
                                                    ORDER BY    OrderID DESC ");
                        endif;
                    else :
                        if(!empty ($Date)) :
                            $check = $con->prepare("SELECT      orders.*
                                                    FROM        orders
                                                    WHERE       orders.Status IN(7, 8, 9)
                                                    AND         orders.OrderDate = '$Date'
                                                    ORDER BY    OrderID DESC ");
                        else:
                            $check = $con->prepare("SELECT      orders.*
                                                    FROM        orders
                                                    WHERE       orders.Status IN(7, 8, 9)
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
        <h1 class="text-center font-weight-bold mt-3 mb-4"> المرتجعات</h1>

        <div class="mx-5 pb-5 min-h">

            <div class="d-flex justify-content-end mb-3">
                
                    <form method="POST" action="" class="">
                        <div class="d-flex">
                            <?php
                                    $cities = ['القاهره', 'الاسكندرية','البحيرة','كفر الشيخ','السويس','الشرقية','الفيوم' ,'المنوفية', 'الجيزة '];
                                    CreateSelectBox('city', $cities, 'Filter');
                            ?>

                            <select name="status" class="form-control ml-2">        
                                <option value="-1" selected> -- اختر الحالة -- </option>
                                <option value="7"> مرتجع </option>
                                <option value="8"> تهرب </option>
                                <option value="9"> ملغى </option>
                            </select>
                                            
                            <input type="date" class="form-control ml-2 text-center" name="SearchDate">
                            
                            <input type="text" name="SearchPhone" placeholder="الهاتف" class="form-control ml-2 text-center">

                            <input type="submit" value="تصفية" class="btn btn-primary ml-2 py-1 px-3">
                        </div>
            
                    </form>

            </div>


            <form method="POST">

                <table class="table table-bordered shadow main-table table-hover mb-4 text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>اسم العميل</th>
                            <th>الهاتف</th>
                            <th>العنوان</th>
                            <th>الطلبات</th>
                            <th>السعر</th>
                            <th>التاريخ</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>

                    <tbody>

                        <!-- print in the table from data base -->
                        <?php
                                foreach($orders as $order) :
                                    
                                    $arrayStatus = ["طباعة", "جارى-التجهيز" ,"توزيع", "استلام", "جارى-المحاسبة", "تم-التسليم", "تم-التحصيل", "مرتجع", "تهرب", "ملغى"];
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


                                    echo    
                                        '<tr>
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
        
    endif;
?>
 
<!-- end content  -->

<!-- start footer -->
<?php  include $tpl . 'footer.php'; ?>
<!-- end footer -->

<?php ob_end_flush(); ?>