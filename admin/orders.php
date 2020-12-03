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
            $City   = $_POST['city'];
            $Date   = $_POST['SearchDate'];
            $Phone  = $_POST['SearchPhone'];
        else :
            $City   = 'all';
            $Date   = '';
            $Phone  = '';
        endif;
             
        // Manage page
        if($_SESSION['UserStatus'] == 1) :

            // select all orders to admin
            if(!empty ($Phone)) : 
                $check = $con->prepare("SELECT      orders.*,
                                                    products.*,  
                                                    users.UserName  As UserName
                                        FROM        orders
                                        INNER JOIN  users 
                                                ON  users.ID = orders.UserID
                                        INNER JOIN  products
                                                ON  products.ProductID = orders.OrderModel
                                        WHERE       orders.ClientPhone = '$Phone'   
                                        ORDER BY    OrderID DESC ");
            else :
                if($City != 'all') :
                    if(!empty ($Date)) :
                        $check = $con->prepare("SELECT      orders.*,  
                                                            products.*,  
                                                            users.UserName  As UserName
                                                FROM        orders
                                                INNER JOIN  users 
                                                        ON  users.ID = orders.UserID
                                                INNER JOIN  products
                                                        ON  products.ProductID = orders.OrderModel
                                                WHERE       orders.City = '$City'
                                                AND         orders.OrderDate = '$Date'
                                                ORDER BY    OrderID DESC ");
                    else:
                        $check = $con->prepare("SELECT      orders.*,  
                                                            users.UserName  As UserName
                                                FROM        orders
                                                INNER JOIN  users 
                                                        ON  users.ID = orders.UserID
                                                WHERE       orders.City = '$City'
                                                ORDER BY    OrderID DESC ");
                    endif;
                else:
                    if(!empty($Date)) :
                        $check = $con->prepare("SELECT      orders.*,  
                                                            products.*,  
                                                            users.UserName  As UserName
                                                FROM        orders
                                                INNER JOIN  users 
                                                        ON  users.ID = orders.UserID
                                                INNER JOIN  products
                                                        ON  products.ProductID = orders.OrderModel
                                                WHERE       orders.OrderDate = '$Date'
                                                ORDER BY    OrderID DESC ");
                    else:
                        $check = $con->prepare("SELECT      orders.*,  
                                                            products.*,  
                                                            users.UserName  As UserName
                                                FROM        orders
                                                INNER JOIN  users 
                                                        ON  users.ID = orders.UserID
                                                INNER JOIN  products
                                                        ON  products.ProductID = orders.OrderModel
                                                ORDER BY    OrderID DESC ");
                    endif;
                endif;  
            endif;

        elseif($_SESSION['UserStatus'] == 4) :
            $userid = $_SESSION['Userid'];
            // select all orders for a specific user
            $check = $con->prepare("SELECT      orders.*,
                                                products.*,  
                                                users.UserName  As UserName
                                    FROM        orders
                                    INNER JOIN  users 
                                            ON  users.ID = orders.UserID
                                    INNER JOIN  products
                                            ON  products.ProductID = orders.OrderModel
                                         WHERE  UserID = $userid
                                    ORDER BY    OrderID DESC");
        endif;
        
        $check->execute();
        
        // fetech all data from data base
        $orders = $check->fetchAll();
        

    ?>
        <!-- start manage page -->
        <h1 class="text-center font-weight-bold mt-3 mb-4"> ادارة الطلبات </h1>

        <div class="mx-5 pb-5 min-h">
        
            <div class="d-flex justify-content-between mb-3">
                
                <div class="">
                    <a href="orders.php?do=Add" class="btn btn-primary shadow">
                        <i class="fa fa-plus mr-1"></i>
                        طلب جديد
                    </a>
                </div>

                <div class="d-flex">

                    <div>
                        <button type="submit" formaction="orders.php?do=Delete" form="formorders" class="btn btn-danger px-3 mr-3 shadow" data-toggle="tooltip" title="ازالة">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </div>

                <?php   if($_SESSION['UserStatus'] == 1) : ?> 
                    <div>
                        <button type="submit" formaction="orders.php?do=Print" form="formorders" class="btn btn-dark px-3 shadow" data-toggle="tooltip" title="طباعة">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                <?php   endif; ?>

                </div>

                
                <form method="POST" action="" class="">
                    <div class="d-flex">
                        <?php
                            $cities = ['القاهره', 'الاسكندرية','البحيرة','كفر الشيخ','السويس','الشرقية','الفيوم' ,'المنوفية', 'الجيزة '];
                            CreateSelectBox('city', $cities, 'Filter');
                        ?>
                    
                        <input type="date" class="form-control ml-2 text-center" name="SearchDate">
                        
                        <input type="text" name="SearchPhone" placeholder="الهاتف" class="form-control ml-2 text-center">
                                    
                        <input type="submit" value="تصفية" class="btn btn-primary ml-2 py-1 px-3">
                    </div>
     
                </form>

            </div>

            <form method="POST" action="" id="formorders" >
            
            <?php   if($_SESSION['UserStatus'] == 1) : ?> 
                <div class="d-flex justify-content-end mb-2">
                    <div class="d-flex w-25 justify-content-between">
                        <div class="w-75">
                            <select name="status" class="form-control">
                                <option value="-1" selected> -- اختر الحالة -- </option>
                                <option value="0"> طباعة </option>
                                <option value="1"> جارى التجهيز </option>
                                <option value="2"> توزيع </option>
                                <option value="3"> استلام </option>
                                <option value="4"> جارى المحاسبة </option>
                                <option value="5"> تم التسليم </option>
                                <option value="6"> تم التحصيل </option>
                                <option value="7"> مرتجع </option>
                                <option value="8"> تهرب </option>
                                <option value="9"> ملغى </option>
                            </select>
                        </div>
                        <div>  
                            <button type="submit" formaction="orders.php?do=UpdateStatus" form="formorders" class="btn btn-success shadow">
                                تعديل
                            </button>
                        </div>
                    </div>
                </div>
            <?php   endif; ?>

                <table class="table table-bordered shadow main-table table-hover mb-4 text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>اسم العميل</th>
                            <th>الهاتف</th>
                            <th>العنوان</th>
                            <th>الموديل</th>
                            <th>السعر</th>
                            <th>التاريخ</th>
                            <th>المستخدم</th>

                        <?php   if($_SESSION['UserStatus'] == 1) : ?> 
                            <th>الحالة</th>
                        <?php   endif; ?>
                            
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

                                    $arrayStatus = ["طباعة", "جارى-التجهيز" ,"توزيع", "استلام", "جارى-المحاسبة", "تم-التسليم", "تم-التحصيل", "مرتجع", "تهرب", "ملغى"];
                                    $statusNum = $order['Status'];
                                    $status = $arrayStatus[$statusNum];

                                    $arraySize = ['0', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
                                    $SizeNum = $order['ProductSize'];
                                    $size = $arraySize[$SizeNum];

                                    echo    
                                        '<tr>
                                            
                                            <td>' . $order['ClientName'] . '</td>
                                            <td>' . $order['ClientPhone'] . '</td>
                                            <td>' . $order['City'] . '</td>
                                            <td>' 
                                                . $size . ' - ' 
                                                . $order['ProductName'] . ' - ' 
                                                . $order['ProductColor'] .
                                            '</td>
                                            <td>' . $order['OrderPrice'] . '</td>
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
        <h1 class="text-center font-weight-bold mt-5 my-4">انشاء طلب جديد</h1>

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

                <!-- model of the order -->
                <div class="input-group w-75 mx-auto my-4 ">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary icon text-light"> 
                            <i class="fas fa-tshirt"></i>
                        </div>
                    </div>
                    <select name="ordermodel" class="form-control">
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

                                echo    '<option value="' . $product['ProductID'] . '"> 
                                            ' . $product['ProductName'] . ' - 
                                            ' . $product['ProductColor'] . ' -
                                            ' . $size . '  
                                        </option>';
                            endforeach;
                        ?>
                    </select>
                </div>

                <!-- price of the order -->
                <div class="input-group w-75 mx-auto my-4 ">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary icon text-light"> 
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                    <input type="text" name="orderprice" placeholder="السعر" required="required" class="form-control text-center">
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
            $userid   = $_SESSION['Userid'];
            $name     = $_POST['clientname'];
            $city     = $_POST['City'];
            $address  = $_POST['clientaddress'];
            $model    = $_POST['ordermodel'];
            $price    = $_POST['orderprice'];

            // convert phone number from arabic to english
            $number   = $_POST['clientphone'];
            $phone    = convertArabicNumToEnglish($number);

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
             
            if(empty($model)) :
                $formErrors[] = '<strong> الموديل </strong> لا يجب ان يكون فارغ';
            endif;

            if(empty($price)) :
                $formErrors[] = '<strong> السعر </strong> لا يجب ان يكون فارغ';
            endif;
             
            if(count($formErrors) != 0) :
                foreach($formErrors as $error) :
                    echo  '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                endforeach;
            else :
                
                // 
                if($_SESSION['UserStatus'] == 1) :
                    $approve = 1;
                elseif($_SESSION['UserStatus'] == 4) :
                    $approve = 0;
                endif;

                // now insert the new data into the data base
                $check = $con->prepare("INSERT INTO 
                                        orders  (ClientName, City, ClientAddress, ClientPhone, OrderPrice,
                                                OrderModel, UserID, Approve, OrderDate) 
                                        VALUES  (?, ?, ?, ?, ?, ?, ?, ?, now())");
                
                $check->execute(array($name, $city, $address, $phone, $price, $model, $userid, $approve));

                // print success message
                $themsg = ' <div class="alert alert-primary" role="alert">
                                ' . $check->rowcount() . ' طلب جديد تم اضافته بنجاح
                            </div>' ;
                RedirectFun($themsg, 'orders', 'insert');
            endif;
        else :
            $themsg = ' <div class="alert alert-danger" role="alert">
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
        <h1 class="text-center font-weight-bold mt-5 my-4">تعديل طلب</h1>
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

                    <!-- model of the order -->
                    <div class="input-group w-75 mx-auto my-4 ">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-primary icon text-light"> 
                                <i class="fas fa-tshirt"></i>
                            </div>
                        </div>
                        <select name="ordermodel" class="form-control">
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

                                    echo    '<option value="' . $product['ProductID'] . '"';
                                            if($order['OrderModel'] == $product['ProductID']) :
                                                echo 'selected';
                                            endif;
                                                
                                    echo    '> 
                                                ' . $product['ProductName'] . ' - 
                                                ' . $product['ProductColor'] . ' -
                                                ' . $size . '  
                                            </option>';
                                endforeach;
                            ?>
                        </select>
                    </div>

                    <!-- price of the order -->
                    <div class="input-group w-75 mx-auto my-4 ">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-primary icon text-light"> 
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                        <input type="text" name="orderprice" placeholder="السعر" required="required" value="<?php echo $order['OrderPrice']; ?>" class="form-control text-center">
                    </div>

                    <!-- allow to print -->
                    <?php if($_SESSION['UserStatus'] == 1) : ?>
                        <div class="input-group w-75 mx-auto my-4 text-center">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-primary icon text-light"> 
                                    <i class="far fa-comment-dots"></i>
                                </div>
                            </div>

                            <div class="w-75 d-flex justify-content-around pl-5 radiobord">
                                <div class="w-50">
                                    <input id="aprint" type="radio" name="status" value="0" 
                                        <?php if($order['Status'] == 0): echo 'checked'; endif; ?>
                                    >
                                    <label class="mb-0 my-1" for="aprint">طباعة</label>
                                </div>

                                <div class="w-50">
                                    <input id="pprint" type="radio" name="status" value="1"
                                        <?php if($order['Status'] == 1): echo 'checked'; endif; ?>
                                    >
                                    <label class="mb-0 my-1" for="pprint">تم الطباعة</label>
                                </div>

                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="w-75 mx-auto my-4 text-right">
                        <input type="submit" value="حفظ" class="btn btn-dark px-4">
                    </div>


                    </form>
                </div>
   
            </div>
            <!-- end form to update -->
        
            <?php
        
        else : 
            $themsg = ' <div class="alert alert-danger" role="alert">
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
            $model    = $_POST['ordermodel'];
            $price    = $_POST['orderprice'];
            $status   = $_POST['status']; 

            // convert phone number from arabic to english
            $number   = $_POST['clientphone'];
            $phone    = convertArabicNumToEnglish($number);
                
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
             
            if(empty($model)) :
                $formErrors[] = '<strong> الموديل </strong> لا يجب ان يكون فارغ';
            endif;

            if(empty($price)) :
                $formErrors[] = '<strong> السعر </strong> لا يجب ان يكون فارغ';
            endif;
                
            if(count($formErrors) != 0) :
                foreach($formErrors as $error) :
                    echo  '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                endforeach;
            else :
                // now update the data base with this new data
                $check = $con->prepare("UPDATE  orders 
                                        SET     ClientName = ?,
                                                City = ?,
                                                ClientAddress = ?,
                                                ClientPhone = ?,
                                                OrderModel = ?,
                                                OrderPrice = ?,
                                                Status = ?,
                                                UserID = ?
                                        WHERE   OrderID = ?");
                $check->execute(array   ($name, $city, $address, $phone, $model, $price, $status,
                                        $userid, $orderid));

                // print success message
                $themsg = ' <div class="alert alert-primary" role="alert">
                                ' . $check->rowcount() . ' طلب تم تعديله بنجاح
                            </div>' ;
                RedirectFun($themsg, 'orders', 'update');
            endif;
        else :
            $themsg = ' <div class="alert alert-danger" role="alert">
                            لا يمكنك الدخول على هذه الصفحة مباشرة 
                        </div>';
            RedirectFun($themsg);  // any word [back] to mean that url not null
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
                $themsg = ' <div class="alert alert-primary" role="alert">
                                ' . $count . ' طلب تم تعديل حالته بنجاح !
                            </div>' ;
                RedirectFun($themsg, 'orders', 'update');
                
            else :
                $themsg = ' <div class="alert alert-danger" role="alert"> 
                                اسف ، يجب تحدد بعض الطلبات و تحديد حالة 
                            </div>';
                RedirectFun($themsg, 'orders', 'update');
            endif;
        else :
            $themsg = ' <div class="alert alert-danger" role="alert">
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
                    $check = $con->prepare("DELETE FROM orders
                                            WHERE       OrderID=?");
                    $check->execute(array($rowID));
                    $count += 1;
                endforeach;
            
                // print success message
                $themsg = ' <div class="alert alert-danger" role="alert">
                            ' . $count . ' طلب تم حذفه بنجاح
                            </div>' ;
                RedirectFun($themsg, 'orders', 'delete');
            else:
                $themsg = ' <div class="alert alert-danger" role="alert"> 
                                اسف ، يجب تحديد بعض الطلبات
                            </div>';
                RedirectFun($themsg);
            endif;
        else:
            // check if get request orderid is numeric & get the integer value of it 
            $orderid =  isset($_GET['orderid']) && is_numeric($_GET['orderid']) ? 
                        intval($_GET['orderid']) : 0 ;
            
            // check if the order exist in database & select all data depend on this ID
            $check = CheckItem('OrderID', 'orders', $orderid); // return row count
            
            // check if this ID is exist or not
            if($check > 0) :
                $check = $con->prepare('DELETE FROM orders 
                                        WHERE       OrderID = ?'); // delete the record from data base
                $check->execute(array($orderid));

                // print success message
                $themsg = ' <div class="alert alert-danger" role="alert">
                            ' . $check->rowcount() . ' طلب تم حذفه بنجاح
                            </div>' ;
                RedirectFun($themsg, 'orders', 'delete');

            else:
                $themsg = ' <div class="alert alert-danger" role="alert"> 
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
                    $themsg = ' <div class="alert alert-primary" role="alert">
                                ' . $check->rowcount() . ' طلب تم الموافقة عليه بنجاح
                                </div>' ;
                    RedirectFun($themsg, 'orders', 'Approve');

                else:
                    $themsg = ' <div class="alert alert-success" role="alert"> 
                                    اسف هذا الطلب غير موجود
                                </div>';
                    RedirectFun($themsg);
                endif;
            elseif($_SESSION['UserStatus'] == 0) :
                
                // print warning message
                $themsg =  '<div class="alert alert-danger" role="alert">
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
                        $themsg = ' <div class="alert alert-danger" role="alert">
                                    ' . $count . ' طلب تم اضافة للطباعة بنجاح 
                                    </div>' ;
                        RedirectFun($themsg, 'orders', 'print');
                    else:
                        $themsg = ' <div class="alert alert-danger" role="alert"> 
                                        اسف ، يجب تحديد بعض الطلبات
                                    </div>';
                        RedirectFun($themsg);
                    endif;
                else:
                    $themsg = ' <div class="alert alert-danger" role="alert"> 
                                        اسف يجب تحديد بعض الطلبات
                                    </div>';
                    RedirectFun($themsg);
                endif;
            elseif($_SESSION['UserStatus'] == 0) :
                
                // print warning message
                $themsg =  '<div class="alert alert-danger" role="alert">
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