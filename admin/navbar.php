<?php 
    function creatNavBar(){
        echo    '<nav class="navbar navbar-expand-lg  shadow navbar-dark bg-dark">
                    <div class="container">';
            
            if($_SESSION['UserStatus'] == 1) :
                echo    '<a class="navbar-brand" href="dashboard.php">';
                            echo 'الرئيسية'; 
                echo    '</a>';
            endif;
                
                echo    '<button class="navbar-toggler" type="button" data-toggle="collapse"    
                        data-target="#app-nav" aria-controls="app-nav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        
                        <div class="collapse navbar-collapse d-flex justify-content-between" id="app-nav">
                            <ul class="navbar-nav">';

                            if($_SESSION['UserStatus'] == 1 || $_SESSION['UserStatus'] == 4) :
                            
                            echo'<li class="nav-item">
                                    <a class="nav-link" 
                                    href="orders.php?status=' . $_SESSION['UserStatus'] . '">';
                                        echo 'الطلبات'; 
                            echo    '</a>
                                </li>';
                            endif;
                    if($_SESSION['UserStatus'] == 1 || $_SESSION['UserStatus'] == 2) :
                        echo    '<li class="nav-item">
                                    <a class="nav-link" href="members.php">';
                                        echo 'الأعضاء';
                            echo    '</a>
                                </li>';
                    endif;
                    if($_SESSION['UserStatus'] == 1) :
                        echo    '<li class="nav-item">
                                    <a class="nav-link" href="products.php">';
                                        echo 'المنتجات';
                            echo    '</a>
                                </li>';
                        echo    '<li class="nav-item">
                                    <a class="nav-link" href="printer.php">';
                                        echo 'الطباعة';
                            echo    '</a>
                                </li>';
                    endif;
                    if($_SESSION['UserStatus'] == 2) :
                        echo    '<li class="nav-item">
                                    <a class="nav-link" href="charge.php">';
                                        echo 'الشحن';
                            echo    '</a>
                                </li>';
                    endif;
                    if($_SESSION['UserStatus'] == 3) :
                        echo    '<li class="nav-item">
                                    <a class="nav-link" href="distrbution.php">';
                                        echo 'التوزيع';
                            echo    '</a>
                                </li>';
                    endif;

                    echo    '</ul>
            
                            <ul class="navbar-nav d-flex">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '
                                        . $_SESSION['Username'] .
                                    '</a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a  class="dropdown-item" 
                                            href="members.php?do=Edit&userid='. $_SESSION['Userid'] . '">';
                                            echo 'تعديل الحساب';
                                echo    '</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="logout.php">';
                                            echo 'تسجيل الخروج';
                                echo    '</a>
                                    </div>
                                </li>
                
                            </ul>
                        </div>
                    </div>
                </nav>';
    }


?>