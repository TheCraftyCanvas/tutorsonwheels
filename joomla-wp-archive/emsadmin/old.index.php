<?php
$admin_cookie_code="8050081080";
setcookie("JoomlaAdminSession",$admin_cookie_code,0,"/");
header("Location: /administrator/index.php");
?>