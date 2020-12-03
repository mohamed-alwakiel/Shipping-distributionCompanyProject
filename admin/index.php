<?php 
    session_start();
    $pageTitle = 'Log in';
    // check if session exist or not
    if(isset($_SESSION['Username'])) :
      
      if($_SESSION['UserStatus'] == 1) :
        header('Location: dashboard.php');

      elseif($_SESSION['UserStatus'] == 2) :
        if($_SESSION['UserApprove'] == 1) :
          header('Location: members.php');
        elseif($_SESSION['UserApprove'] == 0) :
          header('Location: logout.php');
        endif;

      elseif($_SESSION['UserStatus'] == 3) :
        if($_SESSION['UserApprove'] == 1) :
          header('Location: distrbution.php');
        elseif($_SESSION['UserApprove'] == 0) :
          header('Location: logout.php');
        endif;
  
      elseif($_SESSION['UserStatus'] == 4) :
        if($_SESSION['UserApprove'] == 1) :
          header('Location: orders.php');
        elseif($_SESSION['UserApprove'] == 0) :
          header('Location: logout.php');
        endif;
      
      endif;
    
    endif;
    
    include("init.php");
   
    // check if user coming from http post request
    if($_SERVER['REQUEST_METHOD'] == 'POST') :
        $username = $_POST['UserName'];
        $password = $_POST['Password'];
        $hashedPass = sha1($password);
        
        // check if the user exist in database
        $check = $con->prepare("SELECT  * 
                                From    users 
                                WHERE   UserName = ? 
                                AND     Password = ?
                                LIMIT 1" ); 
        
        $check->execute(array($username, $hashedPass));
        $user = $check->fetch();              // return data in array
        $count = $check->rowcount();

        // if count > 0 ,this mean that database contain record about this username
        if($count > 0) :
            $_SESSION['Username']    = $username;          // register session name
            $_SESSION['Userid']      = $user['ID'];        // register session ID
            $_SESSION['UserStatus']  = $user['Status'];    // register session status
            $_SESSION['UserApprove'] = $user['Approve'];   // register session Approve
            $_SESSION['UserCity']    = $user['City'];      // register session City
          
            header('Location: dashboard.php');             // redirect to dashboard page
            exit();
        endif;
    endif;
?>

<!-- start header -->
<?php  include $tpl . 'header.php'; ?>
<!-- end header -->

<!-- start content  -->
<div class="container loginconatiner d-flex align-items-center">
      
      <div class="w-100 d-flex align-items-center justify-content-center">
        
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class=" w-75">
          
          <div class="input-group w-50 mx-auto my-3 ">
            <div class="input-group-prepend">
              <div class="input-group-text bg-primary icon text-light"> 
                <i class="fas fa-envelope-square"></i>
              </div>
            </div>
            <input type="text" name="UserName" value="" placeholder="اسم المستخدم" autocomplete="off" class="form-control text-center" id="InputUserName">
          </div>

          <div class="input-group w-50 mx-auto my-3">
            <div class="input-group-prepend">
              <div class="input-group-text bg-primary icon text-light"> 
                <i class="fas fa-unlock"></i>
              </div>
            </div>
            <input type="password" name="Password" value="" placeholder="كلمة السر" autocomplete="new-password" class="password form-control text-center" id="InputPassword">
            <i class="showpass fa fa-eye fa-1x"></i>
          </div>
          
          <div class="w-50 mx-auto my-3 text-center">
            <button type="submit" class="btn btn-dark py-2 w-100" id="ButtonLogIn">
              تسجيل الدخول
            </button>          
          </div>

          <div class="w-50 mx-auto my-3 text-center">
            <a href="register.php?do=Add">
              <button type="button" class="btn btn-primary py-2 w-100" data-toggle="tooltip" title="التسجيل كعضو جديد">
                مستخدم جديد
              </button>
            </a>          
          </div>

        </form>
      </div>
   
</div>
<!-- end content  -->

<!-- start footer -->
<?php  include $tpl . 'footer.php'; ?>
<!-- end footer -->
