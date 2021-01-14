<?php
    /*
        orders page
        you can Add | Delete | Edit orders from here
    */
    ob_start();

    session_start();
    $pageTitle = 'الطلبات';
    // check if session exist or not
    if(isset($_SESSION['Username'])) :
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
            $City     = $_POST['city'];
            $Date     = $_POST['SearchDate'];
            $Phone    = $_POST['SearchPhone'];
            $OrderNum = $_POST['SearchOrderNum'];
            
            if($_SESSION['UserStatus'] == 1) :
                $Status   = $_POST['status'];
            endif;

        else :
            $City     = 'all';
            $Date     = '';
            $Phone    = '';
            $OrderNum = '';

            if($_SESSION['UserStatus'] == 1) :
                $Status   = '-1';
            endif;

        endif;
             
        if($_SESSION['UserStatus'] == 1) :

            // select all orders to admin
            if(!empty ($Phone)) : 
                $check = $con->prepare("SELECT      orders.*,
                                                    users.UserName  As UserName
                                        FROM        orders
                                        INNER JOIN  users 
                                                ON  users.ID = orders.UserID
                                        WHERE       orders.Status  
                                                IN  (0, 1, 2, 3, 4, 5, 6, 10, 11 ,12)
                                        AND         orders.ClientPhone = '$Phone'   
                                        ORDER BY    OrderID DESC ");
            elseif(!empty ($OrderNum)) :
                $check = $con->prepare("SELECT      orders.*,
                                                    users.UserName  As UserName
                                        FROM        orders
                                        INNER JOIN  users 
                                                ON  users.ID = orders.UserID
                                        WHERE       orders.Status  
                                                IN  (0, 1, 2, 3, 4, 5, 6, 10, 11 ,12)
                                        AND         orders.OrderID = '$OrderNum'   
                                        ORDER BY    OrderID DESC ");
            else :
                if($City != 'all') :
                    if($Status != '-1') :
                        if(!empty ($Date)) :
                            $check = $con->prepare("SELECT      orders.*,  
                                                                users.UserName  As UserName
                                                    FROM        orders
                                                    INNER JOIN  users 
                                                            ON  users.ID = orders.UserID
                                                    WHERE       orders.City = '$City'
                                                    AND         orders.Status = '$Status'
                                                    AND         orders.OrderDate = '$Date'
                                                    ORDER BY    OrderID DESC ");
                        else:
                            $check = $con->prepare("SELECT      orders.*,  
                                                                users.UserName  As UserName
                                                    FROM        orders
                                                    INNER JOIN  users 
                                                            ON  users.ID = orders.UserID
                                                    WHERE       orders.City = '$City'
                                                    AND         orders.Status = '$Status'
                                                    ORDER BY    OrderID DESC ");
                        endif;
                    else :
                        if(!empty ($Date)) :
                            $check = $con->prepare("SELECT      orders.*,  
                                                                users.UserName  As UserName
                                                    FROM        orders
                                                    INNER JOIN  users 
                                                            ON  users.ID = orders.UserID
                                                    WHERE       orders.Status  
                                                            IN  (0, 1, 2, 3, 4, 5, 6, 10, 11 ,12)
                                                    AND         orders.City = '$City'
                                                    AND         orders.OrderDate = '$Date'
                                                    ORDER BY    OrderID DESC ");
                        else:
                            $check = $con->prepare("SELECT      orders.*,  
                                                                users.UserName  As UserName
                                                    FROM        orders
                                                    INNER JOIN  users 
                                                            ON  users.ID = orders.UserID
                                                    WHERE       orders.Status  
                                                            IN  (0, 1, 2, 3, 4, 5, 6, 10, 11 ,12)
                                                    AND         orders.City = '$City'
                                                    ORDER BY    OrderID DESC ");
                        endif;
                    endif;
                else:
                    if($Status != '-1') :
                        if(!empty ($Date)) :
                            $check = $con->prepare("SELECT      orders.*,  
                                                                users.UserName  As UserName
                                                    FROM        orders
                                                    INNER JOIN  users 
                                                            ON  users.ID = orders.UserID
                                                    WHERE       orders.Status = '$Status'
                                                    AND         orders.OrderDate = '$Date'
                                                    ORDER BY    OrderID DESC ");
                        else:
                            $check = $con->prepare("SELECT      orders.*,  
                                                                users.UserName  As UserName
                                                    FROM        orders
                                                    INNER JOIN  users 
                                                            ON  users.ID = orders.UserID
                                                    WHERE       orders.Status = '$Status'
                                                    ORDER BY    OrderID DESC ");
                        endif;
                    else :
                        if(!empty ($Date)) :
                            $check = $con->prepare("SELECT      orders.*,  
                                                                users.UserName  As UserName
                                                    FROM        orders
                                                    INNER JOIN  users 
                                                            ON  users.ID = orders.UserID
                                                    WHERE       orders.Status  
                                                            IN  (0, 1, 2, 3, 4, 5, 6, 10, 11 ,12)
                                                    AND         orders.OrderDate = '$Date'
                                                    ORDER BY    OrderID DESC ");
                        else:
                            $check = $con->prepare("SELECT      orders.*,  
                                                                users.UserName  As UserName
                                                    FROM        orders
                                                    INNER JOIN  users 
                                                            ON  users.ID = orders.UserID
                                                    WHERE       orders.Status  
                                                            IN  (0, 1, 2, 3, 4, 5, 6, 10, 11 ,12)
                                                    ORDER BY    OrderID DESC ");
                        endif;
                    endif;
                endif;  
            endif;

        elseif($_SESSION['UserStatus'] == 4) :
            $userid = $_SESSION['Userid'];

            // select all orders for a specific user
            if(!empty ($Phone)) : 
                $check = $con->prepare("SELECT      orders.*,
                                                    users.UserName  As UserName
                                        FROM        orders
                                        INNER JOIN  users 
                                                ON  users.ID = orders.UserID
                                        WHERE       UserID = $userid
                                        AND         orders.ClientPhone = '$Phone'   
                                        ORDER BY    OrderID DESC ");
            elseif(!empty ($OrderNum)) :
                $check = $con->prepare("SELECT      orders.*,
                                                    users.UserName  As UserName
                                        FROM        orders
                                        INNER JOIN  users 
                                                ON  users.ID = orders.UserID
                                        WHERE       UserID = $userid
                                        AND         orders.OrderID = '$OrderNum'   
                                        ORDER BY    OrderID DESC ");
            else :
                if($City != 'all') :
                    if(!empty ($Date)) :
                        $check = $con->prepare("SELECT      orders.*,  
                                                            users.UserName  As UserName
                                                FROM        orders
                                                INNER JOIN  users 
                                                        ON  users.ID = orders.UserID
                                                WHERE       orders.City = '$City'
                                                AND         orders.OrderDate = '$Date'
                                                AND         UserID = $userid
                                                ORDER BY    OrderID DESC ");
                    else:
                        $check = $con->prepare("SELECT      orders.*,  
                                                            users.UserName  As UserName
                                                FROM        orders
                                                INNER JOIN  users 
                                                        ON  users.ID = orders.UserID
                                                WHERE       orders.City = '$City'
                                                AND         UserID = $userid
                                                ORDER BY    OrderID DESC ");
                    endif;
                else:
                    if(!empty ($Date)) :
                        $check = $con->prepare("SELECT      orders.*,  
                                                            users.UserName  As UserName
                                                FROM        orders
                                                INNER JOIN  users 
                                                        ON  users.ID = orders.UserID
                                                WHERE       orders.OrderDate = '$Date'
                                                AND         UserID = $userid
                                                ORDER BY    OrderID DESC ");
                    else:
                        $check = $con->prepare("SELECT      orders.*,  
                                                            users.UserName  As UserName
                                                FROM        orders
                                                INNER JOIN  users 
                                                        ON  users.ID = orders.UserID
                                                WHERE       UserID = $userid
                                                ORDER BY    OrderID DESC ");
                    endif;
                endif;  
            endif;

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
                
                <form method="POST" action="" class="">
                    <div class="d-flex">
                        <?php
                            $cities = ['القاهره', 'الاسكندرية','البحيرة','كفر الشيخ','السويس','الشرقية','الفيوم' ,'المنوفية', 'الجيزة '];
                            CreateSelectBox('city', $cities, 'Filter');
                        ?>

                        <?php
                            if($_SESSION['UserStatus'] == 1) :
                        ?>
                            <select name="status" class="form-control ml-2">        
                                <option value="-1" selected> -- اختر الحالة -- </option>
                                <option value="0"> طباعة </option>
                                <option value="1"> جارى التجهيز </option>
                                <option value="2"> توزيع </option>
                                <option value="3"> استلام </option>
                                <option value="4"> جارى المحاسبة </option>
                                <option value="5"> تم التسليم </option>
                                <option value="6"> تم التحصيل </option>
                                <option value="10"> لم يرد </option>
                                <option value="11"> مغلق </option>
                                <option value="12"> تأجيل </option>

                            </select>
                        <?php 
                            endif;
                        ?>


                    
                        <input type="date" class="form-control ml-2 text-center" name="SearchDate">
                        
                        <input type="text" name="SearchPhone" placeholder="الهاتف" class="form-control ml-2 text-center">

                        <input type="text" name="SearchOrderNum" placeholder="رقم الاوردر" class="form-control ml-2 text-center">

                        <input type="submit" value="تصفية" class="btn btn-primary ml-2 py-1 px-3">
                    </div>
     
                </form>

            </div>


            <form method="POST" action="" id="formorders" >
            
            
                <div class="d-flex justify-content-between mb-3">
                    
                    <div class="d-flex justify-content-between w-50 ">
                        
                        <div class="">
                            <a href="orders.php?do=Add" class="btn btn-primary shadow">
                                <i class="fa fa-plus mr-1"></i>
                                طلب جديد
                            </a>
                        </div>

                        <div class="d-flex ">

                            
                            <div>
                                <button type="submit" formaction="orders.php?do=Delete" form="formorders" class="btn btn-danger px-3 mr-3 shadow" data-toggle="tooltip" title="ازالة">
                                    <i class="far fa-trash-alt"></i>
                                        ازالة طلبات
                                </button>
                            </div>
                            
                            <?php
                                if($_SESSION['UserStatus'] == 1) :
                            ?>
                                <div>
                                    <button type="submit" formaction="orders.php?do=Print" form="formorders" class="btn btn-dark px-3 shadow" data-toggle="tooltip" title="طباعة">
                                        <i class="fas fa-print"></i>
                                        طباعة طلبات
                                    </button>
                                </div>
                            <?php
                                endif;
                            ?>
                        </div>

                    </div>
                    
                    <?php
                        if($_SESSION['UserStatus'] == 1) :
                    ?>
                        <div class="d-flex w-25 justify-content-between">
                            <div class="w-75">
                                <select name="status" class="form-control">        
                                    <option value="-1" selected> -- اختر الحالة -- </option>
                                    <option value="0"> طباعة </option>
                                    <option value="1"> جارى التجهيز </option>
                                    <option value="2"> توزيع </option>
                                </select>
                            </div>
                            <div>  
                                <button type="submit" formaction="orders.php?do=UpdateStatus" form="formorders" class="btn btn-success shadow">
                                    تعديل
                                </button>
                            </div>
                        </div>
                    <?php   
                        endif; 
                    ?>
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
                            <th>المستخدم</th>
                        <?php   
                            if($_SESSION['UserStatus'] == 1) : 
                        ?> 
                            <th>الحالة</th>
                        <?php   
                            endif; 
                        ?>
                            
                            <th>الادوات</th>
                            <th>
                                <input class="form-check-input p" type="checkbox" id="selectAll" data-toggle="tooltip" title="تحديد الكل">
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        <!-- print in the table from data base -->
                        <?php
                                foreach($orders as $order) :
                                    if($order['Approve'] == 0) :
                                        $Approve = '
                                                    <a href="orders.php?do=Approve&orderid=' . $order['OrderID'] .    '"class="btn btn-info px-2 my-1" data-toggle="tooltip" title="قبول">
                                                        <i class="far fa-check-circle"></i>
                                                    </a>';
                                    elseif($order['Approve'] == 1) :
                                        $Approve = '';
                                    endif;

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

                                    echo    
                                        '<tr>
                                            <td>' . $order['OrderID'] . '</td>
                                            <td>' . $order['ClientName'] . '</td>
                                            <td>' . $order['ClientPhone'] ;
                                    if($order['ClientPhone2'] != 0) :
                                        echo '<br>' . $order['ClientPhone2'] ;
                                    endif;
                                        echo'</td>
                                            <td>' . $order['City'] . '</td>
                                            
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
                                            <td>' . $order['UserName'] . '</td>';

                                if($_SESSION['UserStatus'] == 1) : 
                                    echo    '<td>' . $status . '</td>' ;
                                endif;        
                                
                                echo    '   <td>
                                                <a href="orders.php?do=Edit&orderid=' . $order['OrderID'] . '" class="btn btn-success px-2  my-1" data-toggle="tooltip" title="تعديل">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="orders.php?do=Delete&orderid=' . $order['OrderID'] . '" class="btn btn-danger px-2 my-1 confirm" data-toggle="tooltip" title="ازالة">
                                                    <i class="far fa-trash-alt"></i>
                                                </a>';
                                if($_SESSION['UserStatus'] == 1) : 
                                        echo    $Approve ;
                                endif;
                                    echo  ' </td>
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
        /// ========================== Add Page ==================================//
        ///=======================================================================//

    elseif($do == 'Add') :
        // Add page
    ?>
        <!-- start add page -->
        <h1 class="text-center font-weight-bold mt-4">انشاء طلب جديد</h1>

        <div class="container  d-flex align-items-center">

            <div class="w-100 h-50 d-flex align-items-center justify-content-center">
                <form action="?do=Insert" method="POST" class="w-75">

                <!-- name of the client -->
                <div class="input-group w-75 mx-auto my-4 ">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary icon text-light"> 
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                    <input type="text" name="clientname" placeholder="اسم العميل" required="required" class="form-control text-center">
                </div>

                <!-- City of the client -->
                <div class="input-group w-75 mx-auto my-4 ">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary icon text-light"> 
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                    </div>
                    <?php
                        $cities = ['القاهره', 'الاسكندرية','البحيرة','كفر الشيخ','السويس','الشرقية','الفيوم' ,'المنوفية', 'الجيزة '];
                        CreateSelectBox('City', $cities, 'Add');
                    ?>
                </div>

                <!-- address of the client -->
                <div class="input-group w-75 mx-auto my-4 ">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary icon text-light"> 
                            <i class="far fa-star"></i>
                        </div>
                    </div>
                    <input type="text" name="clientaddress" placeholder="العنوان بالكامل" required="required" class="form-control text-center">
                </div>

                <!-- phone of the client -->
                <div class="input-group w-75 mx-auto my-4 ">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary icon text-light"> 
                            <i class="fas fa-phone"></i>
                        </div>
                    </div>
                    <input type="text" name="clientphone" placeholder="الهاتف" required="required" class="form-control text-center">
                </div>

                <!-- phone2 of the client -->
                <div class="input-group w-75 mx-auto my-4 ">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary icon text-light"> 
                            <i class="fas fa-phone"></i>
                        </div>
                    </div>
                    <input type="text" name="clientphone2" placeholder="الهاتف الاضافى" class="form-control text-center">
                </div>

                <!-- number of pieces -->
                <div class="input-group w-75 mx-auto my-4 ">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary icon text-light"> 
                            <i class="fas fa-list-ol"></i>
                        </div>
                    </div>
                    <input type="number" name="piecesnumber" placeholder="عدد المنتجات" value="" class="form-control text-center">
                </div>

                <div class="w-75 mx-auto my-4 text-right">
                    <input type="submit" value="انشاء" class="btn btn-dark px-4">
                </div>

                </form>
            </div>
                
        </div>
        <!-- end add page -->    
    

    <?php
        ///=======================================================================//
        /// ========================= Insert Page ================================//
        ///=======================================================================//

    elseif($do == 'Insert') :
        // Insert page
    ?>
        <!-- start insert page -->
        <h1 class="text-center font-weight-bold mt-5 my-4"></h1>
        <div class="container">
            
            <?php
            if($_SERVER['REQUEST_METHOD'] == 'POST') :
                // get variable from the form
                $userid         = $_SESSION['Userid'];
                $name           = $_POST['clientname'];
                $city           = $_POST['City'];
                $address        = $_POST['clientaddress'];
                
                // for number of product
                $numofProduct   = $_POST['piecesnumber'];
                if(empty($numofProduct)) :
                    $numofProduct = 1;
                endif;
                
                // convert phone number from arabic to english
                $number   = $_POST['clientphone'];
                $phone    = convertArabicNumToEnglish($number);
                
                if(isset($_POST['clientphone2'])) :
                    $number2  = $_POST['clientphone2'];
                    $phone2   = convertArabicNumToEnglish($number2);
                else :
                    $phone2   = 0;
                endif;

                // Validate the form
                $formErrors = [];
                            
                if(empty($name)) :
                    $formErrors[] = '<strong> اسم العميل </strong> لا يجب ان يكون فارغ';
                endif;

                if(empty($city)) :
                    $formErrors[] = '<strong> المحافظة </strong> يجب ان تحدد';
                endif;
                    
                if(empty($address)) :
                    $formErrors[] = '<strong> العنوان </strong> لا يجب ان يكون فارغ';
                endif;
                
                if(empty($phone)) :
                    $formErrors[] = '<strong> الهاتف </strong> لا يجب ان يكون فارغ';
                endif;

                if(count($formErrors) != 0) :
                    foreach($formErrors as $error) :
                        $themssage = '<div class="alert alert-danger text center" dir="rtl" role="alert">' . $error . '</div>';
                    endforeach;
                    RedirectFun($themssage, 'orders', 'insert', 2);
                else :
                    
                    // 
                    if($_SESSION['UserStatus'] == 1) :
                        $approve = 1;
                    elseif($_SESSION['UserStatus'] == 4) :
                        $approve = 0;
                    endif;

                    // now insert the new data into the data base
                    $check = $con->prepare("INSERT INTO 
                                            orders  (ClientName, City, ClientAddress, ClientPhone, ClientPhone2, NumOfPieces, UserID, Approve, OrderDate) 
                                            VALUES  (?, ?, ?, ?, ?, ?, ?, ?, now())");
                    
                    $check->execute(array($name, $city, $address, $phone, $phone2, $numofProduct, $userid, $approve));

                    // print success message
                    echo '  <div class="alert alert-primary text-center" role="alert">
                                جارى اضافة المنتجات وعدد القطع 
                            </div>' ;
                endif;
                ?>

                <div class="w-100 h-50 d-flex align-items-center justify-content-center">
                    <form action="?do=InsertProducts" method="POST" class="w-100">
                        
                        <?php 
                            $check = $con->prepare("SELECT      OrderID
                                                    FROM        orders
                                                    ORDER BY    OrderID DESC
                                                    LIMIT   1");
                            $check->execute();
                            $lastorder = $check->fetch();
                            $orderid = $lastorder['OrderID'];
                        ?>
                        
                        <input type="hidden" name="orderid" value="<?php echo $orderid; ?>"> 
                        <input type="hidden" name="piecesnumber" value="<?php echo $numofProduct; ?>"> 
                        
                        <?php 
                            for($i = 1 ; $i <= $numofProduct ; $i++) :
                        ?>
                            <div class="d-flex">
                                <!-- order model -->
                                <div class="input-group w-25 mx-auto my-4 ">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-primary icon text-light"> 
                                            <i class="fas fa-tshirt"></i>
                                        </div>
                                    </div>
                                    <select name="<?php echo 'ordermdoel' . $i ; ?>" class="form-control">
                                        <option value="0"> -- اختر الموديل -- </option>
                                        <?php
                                            $check = $con->prepare("SELECT      *
                                                                    FROM        products
                                                                    ORDER BY    ProductName");
                                            $check->execute();
                                            $products = $check->fetchAll();

                                            $arraySize = ['0', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

                                            foreach($products as $product) :

                                                $SizeNum = $product['ProductSize'];
                                                $size = $arraySize[$SizeNum];   
                                                if($product['NumOfPieces'] > 0) :
                                                    echo    '<option value="' . $product['ProductID'] . '"> 
                                                                ' . $product['ProductName'] . ' - 
                                                                ' . $product['ProductColor'] . ' -
                                                                ' . $size . '  
                                                            </option>';
                                                endif;
                                            endforeach;
                                        ?>
                                    </select>
                                </div>

                                <!-- number of piece -->
                                <div class="input-group w-25 mx-auto my-4 ">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-primary icon text-light"> 
                                            <i class="fas fa-list-ol"></i>
                                        </div>
                                    </div>
                                    <input type="number" name="<?php echo 'piecesnumber' . $i ; ?>" placeholder="عدد القطع" value="" class="form-control text-center">
                                </div>

                                <!-- Price of piece -->
                                <div class="input-group w-25 mx-auto my-4 ">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-primary icon text-light"> 
                                            <i class="far fa-money-bill-alt"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="<?php echo 'piecesprice' . $i ; ?>" placeholder="سعر المنتج" value="" class="form-control text-center">
                                </div>
                            </div>
                        <?php 
                            endfor;
                        ?>

                        <div class="w-100 mx-auto my-4 text-center">
                            <input type="submit" value="اضافة المنتجات" class="btn btn-dark px-4">
                        </div>

                    </form>
                </div>


            <?php
            else :
                $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                لا يمكنك الدخول على هذه الصفحه مباشرة 
                            </div>';
                RedirectFun($themsg); 
            endif; 
            ?>

        </div>
        <!-- end insert page -->

    <?php
        ///=======================================================================//
        /// ===================== InsertProducts Page ============================//
        ///=======================================================================//

    elseif($do == 'InsertProducts') :
        // Insert page
    ?>
        <!-- start insert page -->
        <h1 class="text-center font-weight-bold mt-5 my-4"></h1>
        <div class="container">
            
            <?php
            if($_SERVER['REQUEST_METHOD'] == 'POST') :
                // get variable from the form
                $orderid        = $_POST['orderid'];
                $numofProduct   = $_POST['piecesnumber'];
                
                for($i = 1; $i <= $numofProduct; $i++ ) :
                    $model       = $_POST['ordermdoel'.$i];
                    $numpieces   = $_POST['piecesnumber'.$i];

                    // check if the product exists in store or not
                    $checkpieces = $con->prepare("  SELECT  *
                                                    FROM    products
                                                    WHERE   ProductID = '$model'");
                    $checkpieces->execute();
                    $checkfetch = $checkpieces->fetch();
                    $numofpieces = $checkfetch['NumOfPieces'];
                    $afterpieces = $numofpieces - $numpieces;

                    $arraySize = ['0', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
                    $SizeNum = $checkfetch['ProductSize'];
                    $size = $arraySize[$SizeNum];   

                    if($afterpieces < 0) :
                        $themssage = '
                                    <div class="alert alert-danger text-center" dir="rtl"  role="alert">
                                        للاسف لا يوجد كمية كافية 
                                            <br> 
                                                الباقى فى المخزن فقط :  
                                            ' . $numofpieces . ' قطعة  
                                            <br> 
                                                من المنتج :  ' . $checkfetch['ProductName'] . ' - '
                                                . $checkfetch['ProductColor'] . ' - '  . $size . ' 
                                    </div>';
                        RedirectFun($themssage, 'orders', 'insert', 2);
                    endif;
                endfor;

                for($i = 1; $i <= $numofProduct; $i++ ) :
                    $model       = $_POST['ordermdoel'.$i];
                    $numpieces   = $_POST['piecesnumber'.$i];
                    $pieceprice  = $_POST['piecesprice'.$i];

                    // check if the product exists in store or not
                    $checkpieces = $con->prepare("  SELECT  *
                                                    FROM    products
                                                    WHERE   ProductID = '$model'");
                    $checkpieces->execute();
                    $checkfetch = $checkpieces->fetch();
                    $numofpieces = $checkfetch['NumOfPieces'];
                    $afterpieces = $numofpieces - $numpieces;

                    // now change the number of pices
                    $checkupdate = $con->prepare("  UPDATE  products
                                                    SET     NumOfPieces = ?
                                                    WHERE   ProductID = ?");
                    $checkupdate->execute(array($afterpieces, $model));

                    // now insert the new data into the data base
                    $check = $con->prepare("INSERT INTO pieces  (OrderNumber, PieceModel,
                                                                NumberOfPieces, PiecePrice) 
                                            VALUES  (?, ?, ?, ?)");
                    $check->execute(array($orderid, $model, $numpieces, $pieceprice));

                endfor;
                // print success message
                $themssage = '
                            <div class="alert alert-danger text-center" role="alert">
                                تمت اكمال الطلب واضافة المنتجات بنجاح  
                            </div>';
                RedirectFun($themssage, 'orders', 'insert', 2);

            ?>

            <?php
            else :
                $themsg = ' <div class="alert alert-danger text-center" role="alert">
                                لا يمكنك الدخول على هذه الصفحه مباشرة 
                            </div>';
                RedirectFun($themsg); 
            endif; 
            ?>

            </div>
            <!-- end insert page -->
    
    <?php
        ///=======================================================================//
        /// ========================== Edit Page =================================//
        ///=======================================================================//

    elseif($do == 'Edit') :
        // Edit page
    ?>
        <!-- start edit page -->
        <h1 class="text-center font-weight-bold mt-4">تعديل طلب</h1>
        <div class="container">
            
        <?php
       
        // check if get request orderid is numeric & get the integer value of it 
        $orderid = isset($_GET['orderid']) && is_numeric($_GET['orderid']) ? intval($_GET['orderid']) : 0 ;
        
        // check if the category exist in database & select all data depend on this ID
        $check = $con->prepare("SELECT * 
                                FROM    orders       
                                WHERE   OrderID = ?"); 
        
        $check->execute(array($orderid));    // execute query
        $count = $check->rowcount();
        if($count > 0) :
            $order = $check->fetch();       // fetch data in array
            ?>

            <!-- start form to update -->
            
            <div class="container  d-flex align-items-center">

                <div class="w-100 h-50 d-flex align-items-center justify-content-center">
                    <form action="?do=Update" method="POST" class="w-75">

                    <input type="hidden" name="orderid" value="<?php echo $orderid; ?>"> 
                   
                    <!-- name of the client -->
                    <div class="input-group w-75 mx-auto my-4 ">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-primary icon text-light"> 
                                <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                        <input type="text" name="clientname" placeholder="اسم العميل" required="required" value="<?php echo $order['ClientName']; ?>" class="form-control text-center">
                    </div>

                    <!-- City of the client -->
                    <div class="input-group w-75 mx-auto my-4 ">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-primary icon text-light"> 
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                        <?php
                            $cities = ['القاهره', 'الاسكندرية','البحيرة','كفر الشيخ','السويس','الشرقية','الفيوم' ,'المنوفية', 'الجيزة '];
                            CreateSelectBox('City', $cities, 'Edit', $order);
                        ?>
                    </div>

                    <!-- address of the client -->
                    <div class="input-group w-75 mx-auto my-4 ">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-primary icon text-light"> 
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                        </div>
                        <input type="text" name="clientaddress" placeholder="العنوان" required="required" value="<?php echo $order['ClientAddress']; ?>" class="form-control text-center">
                    </div>

                    <!-- phone of the client -->
                    <div class="input-group w-75 mx-auto my-4 ">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-primary icon text-light"> 
                                <i class="fas fa-phone"></i>
                            </div>
                        </div>
                        <input type="text" name="clientphone" placeholder="الهاتف" required="required" value="<?php echo $order['ClientPhone']; ?>" class="form-control text-center">
                    </div>

                    <!-- phone2 of the client -->
                    <div class="input-group w-75 mx-auto my-4 ">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-primary icon text-light"> 
                                <i class="fas fa-phone"></i>
                            </div>
                        </div>
                        <input type="text" name="clientphone2" placeholder="الهاتف الاضافى" value="<?php echo $order['ClientPhone2']; ?>" class="form-control text-center">
                    </div>

                   
                    <input type="hidden" name="piecesnumber" value="<?php echo $order['NumOfPieces']; ?>">
                    
                    <div class="w-75 mx-auto my-4 text-right">
                        <input type="submit" value="حفظ" class="btn btn-dark px-4">
                    </div>


                    </form>
                </div>
   
            </div>
            <!-- end form to update -->
        
            <?php
        
        else : 
            $themsg = ' <div class="alert alert-danger text-center" role="alert">
                            هذا الطلب غير موجود 
                        </div>';
            RedirectFun($themsg);
        endif;
        ?>
        
        </div>
        <!-- end edit page -->

    <?php
        ///=======================================================================//
        /// ========================== Update Page ===============================//
        ///=======================================================================//

    elseif($do == 'Update') :
        // Update page
    ?>
        <!-- start update page -->
        <h1 class="text-center font-weight-bold mt-5 my-4"></h1>
        <div class="container">
            
        <?php
        if($_SERVER['REQUEST_METHOD'] == 'POST') :

            // get variable from the form
            $userid   = $_SESSION['Userid'];
            $orderid  = $_POST['orderid'];
            $name     = $_POST['clientname'];
            $city     = $_POST['City'];
            $address  = $_POST['clientaddress'];

            // for number of product
            $numofProduct   = $_POST['piecesnumber'];
            if(empty($numofProduct)) :
                $numofProduct = 1;
            endif;

            // convert phone number from arabic to english
            $number   = $_POST['clientphone'];
            $phone    = convertArabicNumToEnglish($number);

            if(isset($_POST['clientphone2'])) :
                $number2  = $_POST['clientphone2'];
                $phone2   = convertArabicNumToEnglish($number2);
            else :
                $phone2   = 0;
            endif;
                
            // Validate the form
            $formErrors = [];
                                    
            if(empty($name)) :
                $formErrors[] = '<strong> اسم العميل </strong> لا يجب ان يكون فارغ';
            endif;

            if(empty($city)) :
                $formErrors[] = '<strong> المحافظة </strong> يجب ان تحدد';
            endif;
                
            if(empty($address)) :
                $formErrors[] = '<strong> العنوان </strong> لا يجب ان يكون فارغ';
            endif;
             
            if(empty($phone)) :
                $formErrors[] = '<strong> الهاتف </strong> لا يجب ان يكون فارغ';
            endif;
            
            if(count($formErrors) != 0) :
                foreach($formErrors as $error) :
                    $themssage = '<div class="alert alert-danger text-center" dir="rtl" role="alert">' . $error . '</div>';
                endforeach;
                RedirectFun($themssage, 'orders', 'update');
            else :
                
                // now update the data base with this new data
                $check = $con->prepare("UPDATE  orders 
                                        SET     ClientName = ?,
                                                City = ?,
                                                ClientAddress = ?,
                                                ClientPhone = ?,
                                                ClientPhone2 = ?,
                                                NumOfPieces = ?,
                                                UserID = ?
                                        WHERE   OrderID = ?");
                $check->execute(array   ($name, $city, $address, $phone, $phone2, $numofProduct, $userid,
                                         $orderid));

                // print success message
                    echo'   <div class="alert alert-primary text-center" dir="rtl" role="alert">
                                جارى تعديل المنتجات وعدد القطع  
                            </div>' ;
            endif;
            ?>

            <div class="w-100 h-50 d-flex align-items-center justify-content-center">
                <form action="?do=UpdateProducts" method="POST" class="w-100">
                    
                    <input type="hidden" name="orderid" value="<?php echo $orderid; ?>"> 
                    <input type="hidden" name="piecesnumber" value="<?php echo $numofProduct; ?>"> 
                    
                    <?php 

                        $ppcheck = $con->prepare("  SELECT  orders.OrderID AS orderid, 
                                                            pieces.*, 
                                                            products.*
                                                    FROM    pieces 
                                                    INNER JOIN products ON pieces.PieceModel = products.ProductID
                                                    INNER JOIN orders	ON pieces.OrderNumber = orders.OrderID
                                                    WHERE pieces.OrderNumber = '$orderid'");
                        $ppcheck->execute();
                        $productsPieces = $ppcheck->fetchAll();
                        $i = 1;
                        
                        foreach($productsPieces as $productpiece) :

                    ?>
                        <div class="d-flex">

                            <!-- order model -->
                            <div class="input-group w-25 mx-auto my-4 ">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-primary icon text-light"> 
                                        <i class="fas fa-tshirt"></i>
                                    </div>
                                </div>
                                <select name="<?php echo 'ordermdoel' . $i ; ?>" class="form-control">
                                    <?php

                                        $check = $con->prepare("SELECT      *
                                                                FROM        products
                                                                ORDER BY    ProductName");
                                        $check->execute();
                                        $products = $check->fetchAll();

                                        $arraySize = ['0', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

                                        foreach($products as $product) :
                                            
                                            $SizeNum = $product['ProductSize'];
                                            $size = $arraySize[$SizeNum];   
                                            if($product['NumOfPieces'] > 0) :
                                                echo    '<option value="' . $product['ProductID'] . '"';
                                                        if($productpiece['PieceModel'] == $product['ProductID']) :
                                                            echo 'selected';
                                                        endif;
                                                            
                                                echo    '> 
                                                            ' . $size . ' -
                                                            ' . $product['ProductName'] . ' - 
                                                            ' . $product['ProductColor'] . '
                                                        </option>';
                                            endif;
                                        endforeach;
                                    ?>
                                </select>
                            </div>


                            <!-- number of piece -->
                            <div class="input-group w-25 mx-auto my-4 ">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-primary icon text-light"> 
                                        <i class="fas fa-list-ol"></i>
                                    </div>
                                </div>
                                <input type="number" name="<?php echo 'piecesnumber' . $i ; ?>" placeholder="عدد القطع" value="<?php echo $productpiece['NumberOfPieces'] ?>" class="form-control text-center">
                            </div>

                            <!-- Price of piece -->
                            <div class="input-group w-25 mx-auto my-4 ">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-primary icon text-light"> 
                                        <i class="far fa-money-bill-alt"></i>
                                    </div>
                                </div>
                                <input type="text" name="<?php echo 'piecesprice' . $i ; ?>" placeholder="سعر المنتج" value="<?php echo $productpiece['PiecePrice'] ?>" class="form-control text-center">
                            </div>
                        </div>
                    <?php 
                            $i++;
                        endforeach;
                    ?>

                    <div class="w-100 mx-auto my-4 text-center">
                        <input type="submit" value="اضافة المنتجات" class="btn btn-dark px-4">
                    </div>

                </form>
            </div>
        
        <?php
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
        /// ====================== UpdateProducts Page ===========================//
        ///=======================================================================//

    elseif($do == 'UpdateProducts') :
        // Update page
    ?>
        <!-- start update page -->
        <h1 class="text-center font-weight-bold mt-5 my-4"></h1>
        <div class="container">
            
        <?php
        if($_SERVER['REQUEST_METHOD'] == 'POST') :
             
            // get variable from the form   
            $orderid  = $_POST['orderid'];                                // 103
            $numofProduct = $_POST['piecesnumber'];

            $checkchange =  $con->prepare(" SELECT  *
                                            FROM    pieces
                                            WHERE   OrderNumber = '$orderid'"); // id = 12 & id = 13
            $checkchange->execute();
            $checkchangefetch = $checkchange->fetchALL();

            $arraySize = ['0', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

            $i = 1;
            
            foreach($checkchangefetch as $orderproduct) :
                $oldpieceid     = $orderproduct['PieceID'];                 //  12
                $oldpiecemodel  = $orderproduct['PieceModel'];              //  15
                $oldnumofpiece  = $orderproduct['NumberOfPieces'];          //  5

                // data updated from inputs
                $updatedpiecemodel  = $_POST['ordermdoel'.$i];              //  15        
                $updatednumofpiece  = $_POST['piecesnumber'.$i];            //  5
                $updatedpieceprice  = $_POST['piecesprice'.$i];             //  100

                if($oldpiecemodel == $updatedpiecemodel) :
                    // check if the product exists in store or not
                    $checkproduct   = $con->prepare("   SELECT  *
                                                        FROM    products
                                                        WHERE   ProductID = '$oldpiecemodel'");
                    $checkproduct->execute();
                    $productfetch = $checkproduct->fetch();
                    $SizeNum = $productfetch['ProductSize'];
                    $size = $arraySize[$SizeNum];   
                    $numofpiecesproduct = $productfetch['NumOfPieces'];     //  15
                    
                    if($oldnumofpiece == $updatednumofpiece) :              //  5 ==  5
                        $afternumofpieces = $numofpiecesproduct;            //  15
                    else:
                        $afternumofpieces = ($numofpiecesproduct + $oldnumofpiece) - $updatednumofpiece;    //  (1 + 2) - 2 = 1
                    endif;

                    if($afternumofpieces < 0) :                             //  1 < 0 
                        $themssage = '
                                    <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                        للاسف لا يوجد كمية كافية 
                                            <br> 
                                                الباقى فى المخزن فقط 
                                            ' . $numofpiecesproduct . ' قطعة  
                                            <br>
                                                من المنتج :  ' . $productfetch['ProductName'] . ' - '
                                                . $productfetch['ProductColor'] . ' - '  . $size . ' 
                                    </div>';
                        RedirectFun($themssage, 'orders', 'update', 2);
                    else:
                        // now change the number of pices
                        $checkupdate = $con->prepare("  UPDATE  products
                                                        SET     NumOfPieces = ?
                                                        WHERE   ProductID = ?");
                        $checkupdate->execute(array($afternumofpieces, $oldpiecemodel));
    
                        // now update the data base with this new data
                        $check  = $con->prepare("   UPDATE  pieces
                                                    SET     PieceModel = ?,
                                                            NumberOfPieces = ?,
                                                            PiecePrice = ?
                                                    WHERE   OrderNumber = ?
                                                    AND     PieceID = ?");
                        $check->execute(array($updatedpiecemodel,$updatednumofpiece, $updatedpieceprice, $orderid, $oldpieceid));
                    endif;
                else :
                    // check if the product exists in store or not
                    $updateproduct  = $con->prepare("   SELECT  *
                                                        FROM    products
                                                        WHERE   ProductID = '$oldpiecemodel'");
                    $updateproduct->execute();
                    $updateproductfetch = $updateproduct->fetch();
                    $updatenumofpiecesproduct = $updateproductfetch['NumOfPieces'];             //  3
                    $afternumofpiecesproduct = $updatenumofpiecesproduct + $oldnumofpiece ;     // 3 + 1 = 4

                    // now change the number of pices
                    $checkupdate = $con->prepare("  UPDATE  products
                                                    SET     NumOfPieces = ?
                                                    WHERE   ProductID = ?");
                    $checkupdate->execute(array($afternumofpiecesproduct, $oldpiecemodel));


                    // check if the product exists in store or not
                    $checkproduct   = $con->prepare("   SELECT  *
                                                        FROM    products
                                                        WHERE   ProductID = '$updatedpiecemodel'");
                    $checkproduct->execute();
                    $productfetch = $checkproduct->fetch();
                    $SizeNum = $productfetch['ProductSize'];
                    $size = $arraySize[$SizeNum];   
                    $numofpiecesproduct = $productfetch['NumOfPieces'];     //  85

                    $afternumofpieces = $numofpiecesproduct - $updatednumofpiece;               //  85 - 10 = 75
                   
                    if($afternumofpieces < 0) :                             //  1 < 0 
                        $themssage = '
                                    <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                        للاسف لا يوجد كمية كافية 
                                            <br> 
                                                الباقى فى المخزن فقط 
                                            ' . $numofpiecesproduct . ' قطعة  
                                            <br>
                                                من المنتج :  ' . $productfetch['ProductName'] . ' - '
                                                . $productfetch['ProductColor'] . ' - '  . $size . ' 
                                    </div>';
                        RedirectFun($themssage, 'orders', 'update', 2);
                    else:
                        // now change the number of pices
                        $checkupdate = $con->prepare("  UPDATE  products
                                                        SET     NumOfPieces = ?
                                                        WHERE   ProductID = ?");
                        $checkupdate->execute(array($afternumofpieces, $updatedpiecemodel));
    
                        // now update the data base with this new data
                        $check  = $con->prepare("   UPDATE  pieces
                                                    SET     PieceModel = ?,
                                                            NumberOfPieces = ?,
                                                            PiecePrice = ?
                                                    WHERE   OrderNumber = ?
                                                    AND     PieceID = ?");
                        $check->execute(array($updatedpiecemodel,$updatednumofpiece, $updatedpieceprice, $orderid, $oldpieceid));
                    endif;

                endif;

                $i++;
            endforeach;
            // print success message
            $themssage = '
                            <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                تمت اكمال الطلب واضافة المنتجات بنجاح  
                            </div>';
            RedirectFun($themssage, 'orders', 'update', 2);

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
        /// ======================= Update Status Page ===========================//
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
                $status     = $_POST['status'];
                $EditRows   = $_POST['check'];

                $count = 0;

                foreach($EditRows as $rowID):
                    // now update the data base with this new data
                    $check = $con->prepare("UPDATE  orders 
                                            SET     Status = ?
                                            WHERE   OrderID = ?");
                    $check->execute(array($status, $rowID));
                    $count += 1;
                endforeach;

                // print success message
                $themsg = ' <div class="alert alert-primary text-center" dir="rtl" role="alert">
                                ' . $count . ' طلب تم تعديل حالته بنجاح !
                            </div>' ;
                RedirectFun($themsg, 'orders', 'update');
                
            else :
                $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert"> 
                                اسف ، يجب تحدد بعض الطلبات و تحديد حالة 
                            </div>';
                RedirectFun($themsg, 'orders', 'update');
            endif;
        else :
            $themsg = ' <div class="alert alert-danger text" dir="rtl" role="alert">
                            لا يمكنك الدخول على هذه الصفحة مباشرة 
                        </div>';
            RedirectFun($themsg);  // any word [back] to mean that url not null
        endif; 
        ?>

        </div>
        <!-- end update page -->

    <?php
        ///=======================================================================//
        /// ========================== Delete Page ===============================//
        ///=======================================================================//
    
    elseif($do == 'Delete') :
        // Delete page
    ?>

        <!-- start delete page -->
        <h1 class="text-center font-weight-bold mt-5 my-4"></h1>

        <div class="container">
            <?php    
            if($_SERVER['REQUEST_METHOD'] == 'POST') :
                if(isset($_POST['check'])) :
                    $DeleteRows = $_POST['check'];
                    $count = 0;
                    foreach($DeleteRows as $rowID):

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

                        $check = $con->prepare("DELETE FROM orders
                                                WHERE       OrderID=?");
                        $check->execute(array($rowID));

                        $count += 1;
                    endforeach;
                
                    // print success message
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                ' . $count . ' طلب تم حذفه بنجاح
                                </div>' ;
                    RedirectFun($themsg, 'orders', 'delete');
                else:
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert"> 
                                    اسف ، يجب تحديد بعض الطلبات
                                </div>';
                    RedirectFun($themsg);
                endif;
            
            
            /////
            else:
                // check if get request orderid is numeric & get the integer value of it 
                $orderid =  isset($_GET['orderid']) && is_numeric($_GET['orderid']) ? 
                            intval($_GET['orderid']) : 0 ;

                
                $checkchange =  $con->prepare(" SELECT  *
                                                FROM    pieces
                                                WHERE   OrderNumber = '$orderid'"); // id = 12 & id = 13
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
                
                // check if the order exist in database & select all data depend on this ID
                $check = CheckItem('OrderID', 'orders', $orderid); // return row count
                
                // check if this ID is exist or not
                if($check > 0) :
                    $check = $con->prepare('DELETE FROM orders 
                                            WHERE       OrderID = ?'); // delete the record from data base
                    $check->execute(array($orderid));

                    // print success message
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                ' . $check->rowcount() . ' طلب تم حذفه بنجاح
                                </div>' ;
                    RedirectFun($themsg, 'orders', 'delete');

                else:
                    $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert"> 
                                    اسف هذا الطلب غير موجود
                                </div>';
                    RedirectFun($themsg);
                endif;
            endif;
            ?>

        </div>

        <!-- end delete page -->
    <?php
        ///=======================================================================//
        /// ========================== Approve Page ===============================//
        ///=======================================================================//

    elseif($do == 'Approve') :
        // Approve page
    ?>

        <!-- start Approve page -->
        <h1 class="text-center font-weight-bold mt-5 my-4"></h1>

        <div class="container">
        <?php 
        
            if($_SESSION['UserStatus'] == 1) :
                // check if get request orderid is numeric & get the integer value of it 
                $orderid =  isset($_GET['orderid']) && is_numeric($_GET['orderid']) ? 
                            intval($_GET['orderid']) : 0 ;
                
                // check if the order exist in database & select all data depend on this ID
                $check = CheckItem('OrderID', 'orders', $orderid); // return row count
                
                // check if this ID is exist or not
                if($check > 0) :
                    $check = $con->prepare('UPDATE  orders
                                            SET     Approve = 1 
                                            WHERE   OrderID = ?'); 
                    $check->execute(array($orderid));

                    // print success message
                    $themsg = ' <div class="alert alert-primary text-center" dir="rtl" role="alert">
                                ' . $check->rowcount() . ' طلب تم الموافقة عليه بنجاح
                                </div>' ;
                    RedirectFun($themsg, 'orders', 'Approve');

                else:
                    $themsg = ' <div class="alert alert-success text-center" dir="rtl" role="alert"> 
                                    اسف هذا الطلب غير موجود
                                </div>';
                    RedirectFun($themsg);
                endif;
            elseif($_SESSION['UserStatus'] == 0) :
                
                // print warning message
                $themsg =  '<div class="alert alert-danger text-center" dir="rtl" role="alert">
                                لا يمكنك الدخول على هذه الصفحة لانك لست المسؤل
                            </div>' ;
                RedirectFun($themsg, 'orders', 'warning');
            endif;
                ?>

        </div>
        <!-- end approve page -->
    <?php
        ///=======================================================================//
        /// ========================== Print Page ===============================//
        ///=======================================================================//

    elseif($do == 'Print') :
        // Print page
    ?>

        <!-- start Print page -->
        <h1 class="text-center font-weight-bold mt-5 my-4"></h1>

        <div class="container">
        <?php 
        
            if($_SESSION['UserStatus'] == 1) :
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
                        RedirectFun($themsg, 'orders', 'print');
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
                                لا يمكنك الدخول على هذه الصفحه رنك لست المسؤل
                            </div>' ;
                RedirectFun($themsg, 'orders', 'warning');
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