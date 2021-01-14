<?php
    
    ob_start();

    session_start();
    $pageTitle = 'التسجيل';
    // check if session exist or not
    include("init.php");        
    $do = isset($_GET['do']) ? $_GET['do'] : 'Add';


?>

<!-- start header -->
<?php  include $tpl . 'header.php'; ?>
<!-- end header -->


<!-- start content  -->

<?php 
        ///=======================================================================//
        /// ========================== Add Page ===============================//
        ///=======================================================================//

    if($do == 'Add') :
    // Add page
    ?>
        <!-- start add page -->
        <h1 class="text-center font-weight-bold mt-5 my-4"> التسجيل </h1>

        <div class="container  d-flex align-items-center">

            <div class="w-100 h-50 d-flex align-items-center justify-content-center">
                <form action="?do=Insert" method="POST" class="w-75">

                <!-- user name field -->
                <div class="input-group w-75 mx-auto my-4 ">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary icon text-light"> 
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <input type="text" name="username" placeholder="اسم المستخدم" autocomplete="off" required="required" class="form-control text-center">
                </div>

                <!-- user password field -->
                <div class="input-group w-75 mx-auto my-4 ">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary icon text-light"> 
                            <i class="fas fa-unlock"></i>
                        </div>
                    </div>
                    <input type="password" name="password" placeholder="كلمة السر" autocomplete="new-password" required class="password form-control text-center">
                    <i class="showpass fa fa-eye fa-1x"></i>
                </div>

                <!-- user email field -->
                <div class="input-group w-75 mx-auto my-4 ">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary icon text-light"> 
                        <i class="fas fa-envelope-square"></i>
                        </div>
                    </div>
                    <input type="email" name="email" placeholder="البريد الالكترونى" autocomplete="off" required="required" class="form-control text-center">
                </div>

                <!-- user fullname field -->
                <div class="input-group w-75 mx-auto my-4 ">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary icon text-light"> 
                            <i class="fas fa-user-edit"></i>
                        </div>
                    </div>
                    <input type="text" name="full" placeholder="اسم المستخدم بالكامل" required="required" class="form-control text-center">
                </div>

                <!-- City of the user -->
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

                <!-- user Address field -->
                <div class="input-group w-75 mx-auto my-4 ">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary icon text-light"> 
                            <i class="far fa-star"></i>
                        </div>
                    </div>
                    <input type="text" name="address" placeholder="العنوان بالكامل" class="form-control text-center">
                </div>

                <!-- user phone field -->
                <div class="input-group w-75 mx-auto my-4 ">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary icon text-light"> 
                            <i class="fas fa-phone"></i>
                        </div>
                    </div>
                    <input type="text" name="phone" placeholder="الهاتف" autocomplete="off" required="required" class="form-control text-center">
                </div>

                <!-- user status field -->
                <div class="input-group w-75 mx-auto my-4 ">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary icon text-light"> 
                            <i class="fas fa-compress"></i>
                        </div>
                    </div>
                    <select name="userstatus" class="form-control">
                        <option > -- اختر الوظيفة -- </option>
                        <option value="4"> مستخدم </option>
                        <option value="2"> مسؤول شحن </option>
                        <option value="3"> مندوب توزيع </option>
                    </select>
                </div>
               

                <div class="w-75 mx-auto my-4 text-right">
                    <input type="submit" value="تسجيل" class="btn btn-dark px-4">          
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
            $user    = $_POST['username'];
            $pass    = $_POST['password'];
            $email   = $_POST['email'];
            $city    = $_POST['City'];
            $status  = $_POST['userstatus'];
            $name    = $_POST['full']; 
            $address = $_POST['address']; 
            $phone   = $_POST['phone']; 
            
            $hashpass = sha1($_POST['password']);
            // Validate the form
            $formErrors = [];
           
            if(empty($user)) :
                $formErrors[] = '<strong> اسم المستخدم </strong> لا يجب ان يكون فارغ';
            endif;
            
            if(empty($city)) :
                $formErrors[] = '<strong> المحافظة </strong> يجب ان تحدد';
            endif;
            
            if(strlen($user) < 4) :
                $formErrors[] = '<strong> اسم المستحدم </strong> يجب ان يكون اكثر من 3 حروف';
            endif;
            
            if(empty($pass)) :
                $formErrors[] = '<strong> كلمة السر </strong> لا يجب ان يكون فارغ';
            endif;
            
            if(empty($email)) :
                $formErrors[] = '<strong> البريد الالكترونى </strong> لا يجب ان يكون فارغ';
            endif;
            
            if(empty($name)) :
                $formErrors[] = '<strong> الاسم بالكامل </strong> لا يجب ان يكون فارغ';
            endif;

            if(empty($address)) :
                $formErrors[] = '<strong> العنوان </strong> لا يجب ان يكون فارغ';
            endif;

            if(empty($phone)) :
                $formErrors[] = '<strong> الهاتف </strong> لا يجب ان يكون فارغ';
            endif;

            if(empty($status)) :
                $formErrors[] = '<strong> الوظيفة </strong> يجب ان تحدد';
            endif;

            // select the manager id 
            if($status == 2 || $status == 4) :
                $managerid = 1;
            elseif($status == 3) :
                $check = $con->prepare("SELECT  *
                                        FROM    users
                                        WHERE   Status = '2'
                                        AND     City = ?
                                        AND     Approve = 1");
                $check->execute(array($city));
                if($check->rowcount() == 1) :
                    $manager = $check->fetch();
                    $managerid = $manager['ID'];
                else:
                    $themsg ='  <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                    للاسف لا يوجد مسؤول شحن فى هذه المحافظة
                                </div>';
                    RedirectFun($themsg,'back');
                endif;
            endif;
            
            if(count($formErrors) != 0) :
                foreach($formErrors as $error) :
                    echo  '<div class="alert alert-danger text-center" dir="rtl" role="alert">' . $error . '</div>';
                endforeach;
                RedirectFun();    
            else :
                
                // check if user Exist in database
                $checkUser = CheckItem('UserName', 'users', $user);
                if($checkUser == 1) :
                    $themsg ='  <div class="alert alert-danger text-center" dir="rtl" role="alert">
                                    للاسف هذا المستخدم موجود بالفلعل
                                </div>';
                    RedirectFun($themsg,'back');    
                else:
                    // now insert the new data into the data base
                    $check = $con->prepare("INSERT INTO 
                                                    users   (UserName, Password, Email, FullName, City,
                                                            Address, Phone, Status, ManagerID, RegDate) 
                                                    VALUES  (?, ?, ?, ?, ?, ?, ?, ?, ?, now())");
                    
                    $check->execute(array($user, $hashpass, $email, $name, $city, $address, $phone,
                                         $status, $managerid));

                    // print success message
                    $themsg = ' <div class="alert alert-primary text-center" dir="rtl" role="alert">
                                    ' . $check->rowcount() . ' عضو تمت اضافته بنجاح
                                </div>' ;
                    RedirectFun($themsg, 'members', 'insert');
                endif;
            endif;
        else :
            $themsg = ' <div class="alert alert-danger text-center" dir="rtl" role="alert">
                            للاسف لا يمكن الدخول على هذه الصفحه مباشرة 
                        </div>';
            RedirectFun($themsg);  
        endif; 
        ?>

        </div>
        <!-- end insert page -->

    <?php
    endif;

?>
 
<!-- end content  -->

<!-- start footer -->
<?php  include $tpl . 'footer.php'; ?>
<!-- end footer -->

<?php ob_end_flush(); ?>