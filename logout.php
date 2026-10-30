<?php
require_once __DIR__.'/includes/bootstrap.php';

if (is_logged_in() && $pdo) audit($pdo,(int)$_SESSION['user_id'],'logout','User logged out');


$_SESSION=[]; if (ini_get('session.use_cookies')) 
{
     $p=session_get_cookie_params(); 
     setcookie(session_name(),'',time()-42000,$p['path'],$p['domain'],$p['secure'],$p['httponly']); 
     }

session_destroy(); 
header('Location: index.php'); 
exit;
