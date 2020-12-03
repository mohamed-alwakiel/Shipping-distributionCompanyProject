<?php
    /*
        Manage products page
        you can Add | Delete | Edit products from here
    */
    ob_start();

    session_start();
    $pageTitle = 'المنتجات';
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
        // Manage page

        if($_SERVER['REQUEST_METHOD'] == 'POST') :
            $productname   = $_POST['SearchProduct'];
        else :
            $productname  = '';
        endif;

        if(!empty ($productname)) : 
            $check = $con->prepare("SELECT      *  
                                    FROM        products
                                    WHERE       ProductName = '$productname'   
                                    ORDER BY    ProductID DESC ");
        else :
            $check = $con->prepare("SELECT      * 
                                    FROM        products 
                                    ORDER BY    ProductID DESC ");
        endif;
            
        $check->execute();
        // fetech all data from data base
        $products = $check->fetchAll();

    ?>
        <!-- start manage page -->
        <h1 class="text-center font-weight-bold mt-3 mb-4"> ادارة المنتجات </h1>

        <div class="mx-5 pb-5 min-h">
       
            

            <div class="d-flex justify-content-between mb-3">
                
                <div class="">
                    <a href="products.php?do=Add" class="btn btn-primary shadow">
                        <i class="fa fa-plus mr-1"></i>
                        منتج جديد
                    </a>
                </div>

                <div class="d-flex">

                    <div>
                        <button type="submit" formaction="products.php?do=Delete" form="formproducts" class="btn btn-danger px-3 mr-3 shadow" data-toggle="tooltip" title="ازالة">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </div>

                </div>

                
                <form method="POST" action="" class="">
                    <div class="d-flex">
                            
                        <input type="text" name="SearchProduct" placeholder="اسم المنتج" class="form-control ml-2 text-center">
                                    
                        <input type="submit" value="تصفية" class="btn btn-primary ml-2 py-1 px-3">
                    </div>
     
                </form>

            </div>
            
            <form method="POST" action="" id="formproducts" >

                <table class="table table-bordered shadow main-table table-hover text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>نوع المنتج</th>
                            <th>اللون</th>
                            <th>المقاس</th>
                            <th>الادوات</th>
                            <th>
                                <input class="form-check-input p" type="checkbox" id="selectAll" data-toggle="tooltip" title="تحديد الكل">
                            </th>
                        </tr>
                    </thead>
                    
                    <tbody>

                    <!-- print in the table from data base -->
                    <?php
                            foreach($products as $product) :
                                
                                $arraySize = ['0', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
                                $SizeNum = $product['ProductSize'];
                                $size = $arraySize[$SizeNum];

                                echo    
                                    '<tr>
                                        <td>' . $product['ProductName'] . '</td>
                                        <td>' . $product['ProductColor'] . '</td>
                                        <td>' . $size . '</td>
                                        <td>
                                            <a href="products.php?do=Edit&productid=' . $product['ProductID'] . '" class="btn btn-success px-2 my-1" data-toggle="tooltip" title="تعديل"> 
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="products.php?do=Delete&productid=' . $product['ProductID'] . '" class="btn btn-danger px-2 my-1 confirm" data-toggle="tooltip" title="ازالة">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <input class="form-check-input" type="checkbox" id="checkItem" name="check[]" value="' . $product['ProductID'] . '" data-toggle="tooltip" title="تحديد">
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
        /// ========================== Add Page ==================================//
        ///=======================================================================//

    elseif($do == 'Add') :
        // Add page
    ?>
        <!-- start add page -->
        <h1 class="text-center font-weight-bold mt-5 my-4"> اضافة منتج جديد </h1>

        <div class="container d-flex align-items-center">

            <div class="w-100 h-50 d-flex align-items-center justify-content-center">
                <form action="?do=Insert" method="POST" class="w-75">

                <!-- product name field -->
                <div class="input-group w-75 mx-auto my-4 ">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary icon text-light"> 
                            <i class="fas fa-tshirt"></i>
                        </div>
                    </div>
                    <input type="text" name="productname" placeholder="اسم المنتج" required="required" class="form-control text-center">
                </div>

                <!-- product color field -->
                <div class="input-group w-75 mx-auto my-4 ">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary icon text-light"> 
                            <i class="fas fa-palette"></i>
                        </div>
                    </div>
                    <input type="text" name="productcolor" placeholder="لون المنتج" required="required" class="form-control text-center">
                </div>

                <!-- product size field -->
                <div class="input-group w-75 mx-auto my-4 ">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary icon text-light"> 
                            <i class="fas fa-compress"></i>
                        </div>
                    </div>
                    <select name="productsize" class="form-control">
                        <option value="0"> -- اختر المقاس -- </option>
                        <option value="1"> S </option>
                        <option value="2"> M </option>
                        <option value="3"> L </option>
                        <option value="4"> XL </option>
                        <option value="5"> XXL </option>
                        <option value="6"> XXXL </option>
                    </select>
                </div>

                <div class="w-75 mx-auto my-4 text-right">
                    <input type="submit" value="اضافة" class="btn btn-dark px-4">          
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
            $name   = $_POST['productname'];
            $color  = $_POST['productcolor'];
            $size   = $_POST['productsize'];
            
            // Validate the form
            $formErrors = [];
                        
            if(empty($name)) :
                $formErrors[] = '<strong> اسم المنتج </strong> لا يجب ان يكون فارغ';
            endif;
            
            if(empty($color)) :
                $formErrors[] = '<strong> لون المنتج </strong> لا يجب ان يكون فارغ';
            endif;
            
            if(empty($size)) :
                $formErrors[] = '<strong> مقياس المنتج </strong> يجب تحديده';
            endif;
            
            if(count($formErrors) != 0) :
                foreach($formErrors as $error) :
                    echo  '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                endforeach;
            else :
                // check if user Exist in database
                $check = $con->prepare("SELECT  * 
                                        FROM    products
                                        WHERE   ProductName = ?
                                        AND     ProductColor = ?
                                        AND     ProductSize = ? ");  

                $check->execute(array($name, $color, $size));

                $checkProduct = $check->rowCount();

                if($checkProduct == 1) :
                    $themsg ='  <div class="alert alert-danger" role="alert">
                                    للاسف هذا المنتج موجود بالفلعل
                                </div>';
                    RedirectFun( $themsg,'back');    
                else:
                    // now insert the new data into the data base
                    $check = $con->prepare("INSERT  INTO 
                                                    products    (ProductName, ProductColor, ProductSize)
                                                    VALUES      (?, ?, ?)");
                    
                    $check->execute(array($name, $color, $size));

                    // print success message
                    $themsg = ' <div class="alert alert-primary" role="alert">
                                    ' . $check->rowcount() . ' منتج تمت اضافته بنجاح
                                </div>' ;
                    RedirectFun($themsg, 'products', 'insert');
                endif;
            endif;
        else :
            $themsg = ' <div class="alert alert-danger" role="alert">
                            للاسف لا يمكن الدخول على هذه الصفحه مباشرة 
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
        <h1 class="text-center font-weight-bold mt-5 my-4"> تعديل منتج </h1>
        <div class="container">
            
        <?php
        
        // check if get request productid is numeric & get the integer value of it 
        $productid =    isset($_GET['productid']) && is_numeric($_GET['productid']) ? 
                        intval($_GET['productid']) : 0 ;
        
        // check if the product exist in database & select all data depend on this ID
        $check = $con->prepare("SELECT  *
                                FROM    products
                                WHERE   ProductID = ? 
                                LIMIT   1" ); 
        
        $check->execute(array($productid));    // execute query
        $count = $check->rowcount();
        if($count > 0) :
            $product = $check->fetch();       // fetch data in array
            ?>

            <!-- start form to update -->
            
            <div class="container  d-flex align-items-center">

                <div class="w-100 h-50 d-flex align-items-center justify-content-center">
                    <form action="?do=Update" method="POST" class="w-75">

                    <input type="hidden" name="productid" value="<?php echo $productid; ?>"> 

                    <!-- product name field -->
                    <div class="input-group w-75 mx-auto my-4 ">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-primary icon text-light"> 
                                <i class="fas fa-tshirt"></i>
                            </div>
                        </div>
                        <input type="text" name="productname" placeholder="اسم المنتج" required="required" class="form-control text-center" value="<?php echo $product['ProductName']; ?>">
                    </div>

                    <!-- product color field -->
                    <div class="input-group w-75 mx-auto my-4 ">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-primary icon text-light"> 
                                <i class="fas fa-palette"></i>
                            </div>
                        </div>
                        <input type="text" name="productcolor" placeholder="لون المنتج" required="required" class="form-control text-center" value="<?php echo $product['ProductColor']; ?>">
                    </div>

                    <!-- product size field -->
                    <div class="input-group w-75 mx-auto my-4 ">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-primary icon text-light"> 
                                <i class="fas fa-compress"></i>
                            </div>
                        </div>
                        <select name="productsize" class="form-control">
                            <option value="1" 
                                    <?php 
                                        if($product['ProductSize'] == 1) : 
                                            echo 'selected'; 
                                        endif; 
                                    ?>> 
                                    S 
                            </option>
                            <option value="2" 
                                    <?php 
                                        if($product['ProductSize'] == 2) : 
                                            echo 'selected'; 
                                        endif; 
                                    ?>> 
                                    M 
                            </option>
                            <option value="3" 
                                    <?php 
                                        if($product['ProductSize'] == 3) : 
                                            echo 'selected'; 
                                        endif; 
                                    ?>> 
                                    L 
                            </option>
                            <option value="4" 
                                    <?php 
                                        if($product['ProductSize'] == 4) : 
                                            echo 'selected'; 
                                        endif; 
                                    ?>> 
                                    XL 
                            </option>
                            <option value="5" 
                                    <?php 
                                        if($product['ProductSize'] == 5) : 
                                            echo 'selected'; 
                                        endif; 
                                    ?>> 
                                    XXL 
                            </option><option value="6" 
                                    <?php 
                                        if($product['ProductSize'] == 6) : 
                                            echo 'selected'; 
                                        endif; 
                                    ?>> 
                                    XXXL 
                            </option>
                        </select>
                    </div>


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
                            للاسف ، هذا المنتج غير موجود
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
            $productid  = $_POST['productid'];
            $name       = $_POST['productname'];
            $color      = $_POST['productcolor'];
            $size       = $_POST['productsize'];
            
            // Validate the form
            $formErrors = [];
                        
            if(empty($name)) :
                $formErrors[] = '<strong> اسم المنتج </strong> لا يجب ان يكون فارغ';
            endif;
            
            if(empty($color)) :
                $formErrors[] = '<strong> لون المنتج </strong> لا يجب ان يكون فارغ';
            endif;
            
            if(empty($size)) :
                $formErrors[] = '<strong> مقياس المنتج </strong> يجب تحديده';
            endif;
            
            if(count($formErrors) != 0) :
                foreach($formErrors as $error) :
                    echo  '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                endforeach;
            else :
                // now update the data base with this new data
                $check = $con->prepare("UPDATE  products 
                                        SET     ProductName = ?,
                                                ProductColor = ?,
                                                ProductSize = ?
                                        WHERE   ProductID = ?");
                $check->execute(array($name, $color, $size, $productid));

                // print success message
                $themsg ='  <div class="alert alert-success" role="alert">
                                ' . $check->rowcount() . ' منتج تم تعديله بنجاح
                            </div>' ;
                RedirectFun($themsg, 'products', 'update');  

            endif;
        else :
            $themsg = ' <div class="alert alert-danger" role="alert">
                            للاسف لا يمكن الدخول على هذه الصفحه مباشرة 
                        </div>';
            RedirectFun($themsg);  
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
                    $check = $con->prepare("DELETE FROM products
                                            WHERE       ProductID=?");
                    $check->execute(array($rowID));
                    $count += 1;
                endforeach;
                // print success message
                $themsg = ' <div class="alert alert-danger" role="alert">
                            ' . $count . ' منتج تم حذفه بنجاح
                            </div>' ;
                RedirectFun($themsg, 'products', 'delete');
            else:
                $themsg = ' <div class="alert alert-danger" role="alert"> 
                                اسف ، يجب تحديد بعض المنتجات
                            </div>';
                RedirectFun($themsg);
            endif;
        
        else:
            // check if get request userid is numeric & get the integer value of it 
            $productid =    isset($_GET['productid']) && is_numeric($_GET['productid']) ? 
                            intval($_GET['productid']) : 0 ;
            
            // check if the user exist in database & select all data depend on this ID
            $check = CheckItem('ProductID', 'products', $productid); // return row count
            
            // check if this ID is exist or not
            if($check > 0) :
                $check = $con->prepare('DELETE FROM products 
                                        WHERE       ProductID = ?'); // delete the record from data base
                $check->execute(array($productid));

                // print success message
                $themsg = ' <div class="alert alert-danger" role="alert">
                            ' . $check->rowcount() . ' منتج تم حذفه بنجاح
                            </div>' ;
                RedirectFun($themsg, 'products', 'delete');

            else:
                $themsg = ' <div class="alert alert-danger" role="alert"> 
                                للاسف هذا المنتج غير موجود
                            </div>';
                RedirectFun($themsg);
            endif; 
        endif;      
            ?>

        </div>
        <!-- end delete page -->
    <?php
    endif;

?>
 
<!-- end content  -->

<!-- start footer -->
<?php  include $tpl . 'footer.php'; ?>
<!-- end footer -->

<?php ob_end_flush(); ?>