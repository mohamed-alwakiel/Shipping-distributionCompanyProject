<?php

    /*
        ** Title function v 1.0 
        ** that echo the title page in case the page has the variable $pageTitle
        ** and echo defult title for other pages
    */
    function getTitle()
    {
        global $pageTitle;  
        if(isset($pageTitle)) :
            echo $pageTitle;
        else :
            echo 'Defult';
        endif;
    }


/*
    ** Home redirect function v 2.0
    ** [ this function takes parameters ]
    ** $themsg = echo the error message [ Error | Success | Warning ]
    ** $url = the like you want to redirect to it 
    ** $seconds = seconds before redirecting
*/
    function RedirectFun($themsg = null, $page = null, $url = null, $seconds = 1)
    {
        if($url === null) :
            $url = 'index.php';
            $pageRediret = 'Home Page';
        else:
            // ['HTTP_REFERER'] --- > the page you come from
            if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') :
                if($url === 'back') :
                    $url = $_SERVER['HTTP_REFERER']; // the page you come from
                    $pageRediret = 'Previous Page';
                else :
                    if($page == 'members') :
                        $url = 'members.php';
                        $pageRediret = 'Members Page';
                    elseif($page == 'orders') :
                        $url = 'orders.php';
                        $pageRediret = 'Orders Page';
                    elseif($page == 'products') :
                        $url = 'products.php';
                        $pageRediret = 'Products Page';
                    elseif($page == 'printer') :
                        $url = 'printer.php';
                        $pageRediret = 'Printer Page';
                    elseif($page == 'charge') :
                        $url = 'charge.php';
                        $pageRediret = 'Charge Page';
                    elseif($page == 'distrbution') :
                        $url = 'distrbution.php';
                        $pageRediret = 'Distrbution Page';
                    endif;
                endif;
            else:
                $url = 'index.php';
                $pageRediret = 'Home Page';
            endif;
        endif;
        echo $themsg;
        header('refresh:' . $seconds . ';url=' . $url);
        exit();
    }

    
/*
    ** Check items function v 1.0
    ** Function to check item in Database [ function accept parameters ]
    ** $col = the item to select 
    ** $table = the table to select from
    ** $val = the value of select 
*/
    function CheckItem($col, $table, $val)
    {
        global $con;
        
        // in queuries must use ("") not ('') when we use variables
        $check = $con->prepare("SELECT  $col 
                                FROM    $table
                                WHERE   $col = ?");  

        $check->execute(array($val));

        $count = $check->rowCount();

        return $count;
    }


/*
    ** Count items function v 1.0
    ** Function to count number of item in Database [ function accept parameters ]
    ** $item = the item to count 
    ** $table = the table to select from
*/
    function CountRow($item, $table)
    {
        global $con;
        $check = $con->prepare("SELECT COUNT($item) 
                                FROM $table");
        $check->execute();
        return $check->fetchColumn();
    }


/*
    ** Create Select Box function v 2.0
    ** Function to print options
    ** $name = the field of select 
    ** $array = the options
    ** $page = add or edit 
    ** $rows = items from data base

*/
    function CreateSelectBox($name, $array, $page, $rows = null)
    {
        if($page == 'Add') :

            echo'
                <select name="' . $name . '" class="form-control" required>
                    <option value="0"> -- اختر المحافظة -- </option>';
            foreach($array as $option):
                echo'
                    <option value="' . $option . '"> ' . $option . ' </option>';
            endforeach;
            echo'
                </select>';
        
        elseif($page == 'Edit') :
            echo'
                <select name="' . $name . '" class="form-control" required>';
            foreach($array as $option):
                echo'
                    <option value="' . $option . '"'; 
                            if($rows[$name] == $option) : 
                                echo 'selected'; 
                            endif; 
                echo'>'    
                        . $option . ' 
                    </option>';
            endforeach;
            echo'
                </select>';

        elseif($page == 'Filter') :
            echo'
                <select name="' . $name . '" class="form-control">
                    <option value="all" selected> -- اختر المحافظة -- </option>';
            foreach($array as $option):
                echo'
                    <option value="' . $option . '"> ' . $option . ' </option>';
            endforeach;
            echo'
                </select>';
        endif;
            
    } 
    
/*
    ** convert arabic num to english function v 1.0
    ** $number = the unmber need to convert
*/
    function convertArabicNumToEnglish($number)
    {

        $western_arabic = array('0','1','2','3','4','5','6','7','8','9');
        $eastern_arabic = array('٠','١','٢','٣','٤','٥','٦','٧','٨','٩');
        
        $str = str_replace($eastern_arabic, $western_arabic, $number);
    
        return $str;
    }
?>