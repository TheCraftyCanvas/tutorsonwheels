<?php
if ($_GET['randomId'] != "zFnh80z7pkN_yVskXyfEFbdDS8KVhxBTYbC8neek1Iym0D5aajpJ2x_teIfHRayp") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
