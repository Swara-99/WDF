<?php
require_once __DIR__.'/includes/bootstrap.php';
$pageTitle='Feedback | StudentHub';
$errors=[];$success='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$name=trim($_POST['name']??'');$email=trim($_POST['email']??'');$rating=(int)($_POST['rating']??0);$message=trim($_POST['message']??'');
 if(mb_strlen($name)<2)$errors[]='Please enter your name.';
 if(!filter_var($email,FILTER_VALIDATE_EMAIL))$errors[]='Please enter a valid email.';
 if($rating<1||$rating>5)$errors[]='Please choose a rating.';
 if(mb_strlen($message)<10||mb_strlen($message)>2000)$errors[]='Feedback must be 10–2000 characters.';
 if(!$errors){$file=__DIR__.'/data/feedback.json';$rows=json_decode(@file_get_contents($file)?:'[]',true);if(!is_array($rows))$rows=[];$rows[]=['name'=>$name,'email'=>$email,'rating'=>$rating,'message'=>$message,'created_at'=>date(DATE_ATOM)];if(file_put_contents($file,json_encode($rows,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES),LOCK_EX)===false)$errors[]='Could not save feedback.';else $success='Thank you for your feedback.';}
}
include __DIR__.'/includes/header.php';
?>
<main id="main-content"><section class="page-title"><h1>Student feedback</h1><p>Help us improve the StudentHub experience.</p></section><form class="form-card" method="post"><h2>Share feedback</h2><?php foreach($errors as $er):?><p class="alert"><?=e($er)?></p><?php endforeach;?><?php if($success):?><p class="success"><?=e($success)?></p><?php endif;?><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><div class="field"><label for="name">Name</label><input id="name" name="name" required maxlength="100"></div><div class="field"><label for="email">Email</label><input id="email" name="email" type="email" required></div><div class="field"><label for="rating">Rating</label><select id="rating" name="rating" required><option value="">Choose a rating</option><?php for($i=5;$i>=1;$i--):?><option value="<?=$i?>"><?=$i?> / 5</option><?php endfor;?></select></div><div class="field"><label for="message">Your feedback</label><textarea id="message" name="message" required minlength="10" maxlength="2000"></textarea></div><button class="btn">Submit feedback</button></form></main><?php include __DIR__.'/includes/footer.php';?>
