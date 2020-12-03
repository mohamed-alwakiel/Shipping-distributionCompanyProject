<?php
    /*
        Manage members page
        you can Add | Delete | Edit Members from here
    */
    ob_start();

    session_start();
    $pageTitle = 'الاعضاء';
    // check if session exist or not
    if(isset($_SESSION['Username'])) :
        include("init.php");         
        if($_SESSION['UserStatus'] == 1) :
            $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
            $userstats = 1;
            $managerid = $_SESSION['Userid'];
        elseif($_SESSION['UserStatus'] == 2) :
            $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
            $userstats = 2;
            $managerid = $_SESSION['Userid'];
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
        // Manage page
        if($_SERVER['REQUEST_METHOD'] == 'POST') :
            $phone   = $_POST['SearchPhone'];
        else :
            $phone  = '';
        endif;

        if(!empty ($phone)) : 
            $check = $con->prepare("SELECT      *  
                                    FROM        users
                                    WHERE       Phone = '$phone'
                                    AND         ManagerID = '$managerid'
                                    AND         ID != 1
                                    ORDER BY    ID DESC ");
        else :
            $check = $con->prepare("SELECT      * 
                                    FROM        users
                                    WHERE       ManagerID = '$managerid'
                                    AND         ID != 1
                                    ORDER BY    ID DESC ");
        endif;

        $check->execute();
        // fetech all data from data base
        $users = $check->fetchAll();

    ?>
        <!-- start manage page -->
        <h1 class="text-center font-weight-bold mt-3 mb-4"> ادارة الاعضاء </h1>

        <div class="mx-5 pb-5 min-h">

            <div class="d-flex justify-content-between mb-3">
                
                <div class="">
                    <a href="members.php?do=Add&userstatus=<?php echo $userstats; ?>&managerid=<?php echo $managerid; ?>" class="btn btn-primary shadow">
                        <i class="fa fa-plus mr-1"></i>
                        عضو جديد
                    </a>
                </div>

                <div class="d-flex">

                    <div>
                        <button type="submit" formaction="members.php?do=Delete" form="formusers" class="btn btn-danger px-3 mr-3 shadow" data-toggle="tooltip" title="ازالة">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </div>

                </div>

                
                <form method="POST" action="" class="">
                    <div class="d-flex">
                            
                        <input type="text" name="SearchPhone" placeholder="الهاتف" class="form-control ml-2 text-center">
                                    
                        <input type="submit" value="تصفية" class="btn btn-primary ml-2 py-1 px-3">
                    </div>
     
                </form>

            </div>

            <form method="POST" action="" id="formusers" >


                <table class="table table-bordered shadow main-table table-hover text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>اسم المستخدم</th>
                            <th>البريد الالكترونى</th>
                            <th>الاسم بالكامل</th>
                            <th>العنوان</th>
                            <th>الهاتف</th>
                            <th>الوظيفة</th>
                            <th>التاريخ</th>
                            <th>الادوات</th>
                            <th>
                                <input class="form-check-input p" type="checkbox" id="selectAll" data-toggle="tooltip" title="تحديد الكل">
                            </th>
                        </tr>
                    </thead>
                    
                    <tbody>

                        <!-- print in the table from data base -->
                        <?php
                                foreach($users as $user) :
                                    if($user['Approve'] == 0) :
                                        $approve = '
                                                    <a href="members.php?do=Approve&userid=' . $user['ID'] .    '"class="btn btn-info px-2 my-1" data-toggle="tooltip" title="قبول">
                                                        <i class="far fa-check-circle"></i>
                                                    </a>';
                                    else:
                                        $approve = '';
                                    endif;
                                    
                                    $arrayStatus = ["", "مسؤول", "مسؤول-شحن", "مندوب-توزيع", "مستخدم"];
                                    $statusNum = $user['Status'];
                                    $status = $arrayStatus[$statusNum];

                                    echo    
                                        '<tr>
                                            <td>' . $user['UserName'] . '</td>
                                            <td>' . $user['Email'] . '</td>
                                            <td>' . $user['FullName'] . '</td>
                                            <td>' . $user['City'] . ' - ' . $user['Address'] . '</td>
                                            <td>' . $user['Phone'] . '</td>
                                            <td>' . $status . '</td>
                                            <td>' . $user['RegDate'] . '</td>
                                            <td>
                                                <a href="members.php?do=Edit&userid=' . $user['ID'] . '" class="btn btn-success px-2 my-1" data-toggle="tooltip" title="تعديل">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="members.php?do=Delete&userid=' . $user['ID'] . '" class="btn btn-danger px-2 my-1 confirm" data-toggle="tooltip" title="ازالة">
                                                    <i class="far fa-trash-alt"></i>
                                                </a>
                                                ' . $approve . '
                                            </td>
                                            <td>
                                                <input class="form-check-input" type="checkbox" id="checkItem" name="check[]" value="' . $user['ID'] . '" data-toggle="tooltip" title="تحديد">
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
        <h1 class="text-center font-weight-bold mt-5 my-4"> اضافة عضو جديد </h1>

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
                <?php 
                    if($userstats == 1) :
                ?>
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
                <?php
                    elseif($userstats == 2) :
                        $check = $con->prepare("SELECT  *
                                                FROM    users
                                                WHERE   ID = ?");
                        $check->execute(array($managerid));
                        $row = $check->fetch();
                        $managercity = $row['City'];
                ?>
                    <input type="hidden" name="City" value="<?php echo $managercity; ?>"> 
                <?php 
                    endif;
                ?>

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
                <?php 
                    if($userstats == 1) :
                ?>
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
                        </select>
                    </div>
                <?php
                    elseif($userstats == 2) :
                ?>
                    <input type="hidden" name="userstatus" value="3"> 
                <?php 
                    endif;
                ?>

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
            $user    = $_POST['username'];
            $pass    = $_POST['password'];
            $email   = $_POST['email'];
            $name    = $_POST['full'];
            $city    = $_POST['City'];
            $address = $_POST['address'];
            $status  = $_POST['userstatus'];
            $approve = 1;
            
            // convert phone number from arabic to english
            $number   = $_POST['phone'];
            $phone    = convertArabicNumToEnglish($number);

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
            
            if(count($formErrors) != 0) :
                foreach($formErrors as $error) :
                    echo  '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                endforeach;
                RedirectFun();  
            else :
                // check if user Exist in database
                $checkUser = CheckItem('UserName', 'users', $user);
                if($checkUser == 1) :
                    $themsg ='  <div class="alert alert-danger" role="alert">
                                    للاسف هذا المستخدم موجود بالفلعل
                                </div>';
                    RedirectFun( $themsg,'back');    
                else:
                    // now insert the new data into the data base
                    $check = $con->prepare("INSERT INTO 
                                                    users   (UserName, Password, Email, FullName, City,
                                                            Address, Phone, Status, ManagerID, Approve, RegDate) 
                                                    VALUES  (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())");
                    
                    $check->execute(array($user, $hashpass, $email, $name, $city, $address, $phone, $status, $managerid, $approve));

                    // print success message
                    $themsg = ' <div class="alert alert-primary" role="alert">
                                    ' . $check->rowcount() . ' عضو تمت اضافته بنجاح
                                </div>' ;
                    RedirectFun($themsg, 'members', 'insert');
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
        <h1 class="text-center font-weight-bold mt-5 my-4"> تعديل عضو </h1>
        <div class="container">
            
        <?php
        
        // check if get request userid is numeric & get the integer value of it 
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;
        
        // check if the user exist in database & select all data depend on this ID
        $check = $con->prepare("SELECT  *
                                FROM    users
                                WHERE   ID = ? 
                                LIMIT   1" ); 
        
        $check->execute(array($userid));    // execute query
        $count = $check->rowcount();
        if($count > 0) :
            $user = $check->fetch();       // fetch data in array
            ?>

            <!-- start form to update -->
            
            <div class="container  d-flex align-items-center">

                <div class="w-100 h-50 d-flex align-items-center justify-content-center">
                    <form action="?do=Update" method="POST" class="w-75">

                        <input type="hidden" name="userid" value="<?php echo $userid; ?>"> 

                        <!-- user name field -->
                        <div class="input-group w-75 mx-auto my-4 ">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-primary icon text-light"> 
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <input type="text" name="username" value="<?php echo $user['UserName']; ?>" placeholder="اسم المستخدم" autocomplete="off" required="required" class="form-control text-center">
                        </div>

                        <!-- user password field -->
                        <div class="input-group w-75 mx-auto my-4 ">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-primary icon text-light"> 
                                    <i class="fas fa-unlock"></i>
                                </div>
                            </div>
                            <input type="hidden" name="oldpassword" value="<?php echo $user['Password']; ?>">
                            <input type="password" name="newpassword" value="" placeholder="(كلمة السر (اتركها فارغة اذا لم تريد تغيره " autocomplete="new-password" class="form-control text-center">
                        </div>

                        <!-- user email field -->
                        <div class="input-group w-75 mx-auto my-4 ">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-primary icon text-light"> 
                                <i class="fas fa-envelope-square"></i>
                                </div>
                            </div>
                            <input type="email" name="email" value="<?php echo $user['Email']; ?>" placeholder="البريد الالكترونى" autocomplete="off" required="required" class="form-control text-center">
                        </div>

                        <!-- full name field -->
                        <div class="input-group w-75 mx-auto my-4 ">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-primary icon text-light"> 
                                    <i class="fas fa-user-edit"></i>
                                </div>
                            </div>
                            <input type="text" name="full" value="<?php echo $user['FullName']; ?>" placeholder="اسم المستخدم بالكامل" autocomplete="off" required="required" class="form-control text-center">
                        </div>

                        <?php 
                        if($userstats == 1) :
                        ?>
                            <div class="input-group w-75 mx-auto my-4 ">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-primary icon text-light"> 
                                        <i class="far fa-star"></i>
                                    </div>
                                </div>
                                <?php
                                    $cities = ['القاهره', 'الاسكندرية','البحيرة','كفر الشيخ','السويس','الشرقية','الفيوم' ,'المنوفية', 'الجيزة '];
                                    CreateSelectBox('City', $cities, 'Edit', $user);
                                ?>
                            </div>
                        <?php
                        elseif($userstats == 2) :
                        ?>
                            <input type="hidden" name="City" value="<?php echo $user['City']; ?>"> 
                        <?php 
                            endif;
                        ?>

                        <!-- address of the user -->
                        <div class="input-group w-75 mx-auto my-4 ">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-primary icon text-light"> 
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                            </div>
                            <input type="text" name="address" placeholder="العنوان" required="required" value="<?php echo $user['Address']; ?>" class="form-control text-center">
                        </div>

                        <!-- user phone field -->
                        <div class="input-group w-75 mx-auto my-4 ">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-primary icon text-light"> 
                                    <i class="fas fa-phone"></i>
                                </div>
                            </div>
                            <input type="text" name="phone" placeholder="الهاتف" autocomplete="off" required="required" value="<?php echo $user['Phone']; ?>" class="form-control text-center">
                        </div>

                        <!-- user status field -->
                        <?php 
                            if($userstats == 1) :
                        ?>
                            <div class="input-group w-75 mx-auto my-4 ">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-primary icon text-light"> 
                                        <i class="fas fa-compress"></i>
                                    </div>
                                </div>
                                <select name="userstatus" class="form-control">
                                    <option value="4" 
                                            <?php 
                                                if($user['Status'] == 4) : 
                                                    echo 'selected'; 
                                                endif; 
                                            ?>> 
                                            مستخدم 
                                    </option>
                                    <option value="2" 
                                            <?php 
                                                if($user['Status'] == 2) : 
                                                    echo 'selected'; 
                                                endif; 
                                            ?>> 
                                            مسؤول شحن 
                                    </option>
                                </select>
                            </div>
                        <?php
                            elseif($userstats == 2) :
                        ?>
                            <input type="hidden" name="userstatus" value="3"> 
                        <?php 
                            endif;
                        ?>
       
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
                           للاسف هذا المنتج غير موجود 
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
            $userid  = $_POST['userid'];
            $user    = $_POST['username'];
            $pass    = $_POST['newpassword'];
            $city     = $_POST['City'];
            $email   = $_POST['email'];
            $name    = $_POST['full']; 
            $address = $_POST['address'];
            $status  = $_POST['userstatus']; 
            
            // convert phone number from arabic to english
            $number   = $_POST['phone'];
            $phone    = convertArabicNumToEnglish($number);

            // password trick 
            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
            

            // Validate the form
            $formErrors = [];
            
            if(empty($user)) :
                $formErrors[] = '<strong> اسم المستخدم </strong> لا يجب ان يكون فارغ';
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
            
            if(empty($city)) :
                $formErrors[] = '<strong> المحافظة </strong> يجب ان تحدد';
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
            
            if(count($formErrors) != 0) :
                foreach($formErrors as $error) :
                    echo  '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                endforeach;
            else :
                // now update the data base with this new data
                $check = $con->prepare("UPDATE users 
                                        SET UserName = ?,
                                            Password = ?,
                                            Email = ?,
                                            FullName = ?,
                                            City = ?,
                                            Address = ?,
                                            Phone = ?,
                                            Status = ?
                                        WHERE ID = ?");
                $check->execute(array($user, $pass, $email, $name, $city, $address, $phone, $status, $userid));

                // print success message
                $themsg ='  <div class="alert alert-success" role="alert">
                                ' . $check->rowcount() . ' عضو تم تعديله بنجاح
                            </div>' ;
                RedirectFun($themsg, 'members', 'update');  

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
                    $check = $con->prepare("DELETE FROM users
                                            WHERE       ID=?");
                    $check->execute(array($rowID));
                    $count += 1;
                endforeach;
                // print success message
                $themsg = ' <div class="alert alert-danger" role="alert">
                            ' . $count . ' عضو تم حذفه بنجاح
                            </div>' ;
                RedirectFun($themsg, 'members', 'delete');
            else:
                $themsg = ' <div class="alert alert-danger" role="alert"> 
                                اسف ، يجب تحديد بعض الاعضاء
                            </div>';
                RedirectFun($themsg);
            endif;
        
        else:    
            // check if get request userid is numeric & get the integer value of it 
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;
            
            // check if the user exist in database & select all data depend on this ID
            $check = CheckItem('ID', 'users', $userid); // return row count
            
            // check if this ID is exist or not
            if($check > 0) :
                $check = $con->prepare('DELETE FROM users 
                                        WHERE ID = ?'); // delete the record from data base
                $check->execute(array($userid));

                // print success message
                $themsg = ' <div class="alert alert-danger" role="alert">
                            ' . $check->rowcount() . ' عضو تم حذفه بنجاح
                            </div>' ;
                RedirectFun($themsg, 'members', 'delete');

            else:
                $themsg = ' <div class="alert alert-danger" role="alert"> 
                                للاسف هذا المسخدم غير موجود
                            </div>';
                RedirectFun($themsg);
            endif;
        endif;       
            ?>

        </div>
        <!-- end delete page -->
    <?php
        ///=======================================================================//
        /// ========================== Approve Page ==============================//
        ///=======================================================================//

    elseif($do == 'Approve') :
        // Approve page
    ?>

        <!-- start Approve page -->
        <h1 class="text-center font-weight-bold mt-5 my-4"></h1>

        <div class="container">
        <?php    
            
            // check if get request userid is numeric & get the integer value of it 
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;
            
            // check if the user exist in database & select all data depend on this ID
            $check = CheckItem('ID', 'users', $userid); // return row count
            
            // check if this ID is exist or not
            if($check > 0) :
                $check = $con->prepare("UPDATE  users 
                                        SET     Approve = 1  
                                        WHERE   ID = ?"); 
                $check->execute(array($userid));

                // print success message
                $themsg = ' <div class="alert alert-success" role="alert">
                            ' . $check->rowcount() . ' عضو جديد تم الموافقة عليه
                            </div>' ;
                RedirectFun($themsg, 'members', 'Approve');

            else:
                $themsg = ' <div class="alert alert-danger" role="alert"> 
                                للاسف هذا المستخدم غير موجود
                            </div>';
                RedirectFun($themsg);
            endif;       
            ?>

        </div>
        <!-- end Approve page -->
    <?php
    endif;

?>
 
<!-- end content  -->

<!-- start footer -->
<?php  include $tpl . 'footer.php'; ?>
<!-- end footer -->

<?php ob_end_flush(); ?>