<?php
require_once __DIR__.'/includes/bootstrap.php'; $pageTitle='Contact | StudentHub'; $errors=[];$success='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$name=trim($_POST['name']??'');$email=trim($_POST['email']??'');$subject=trim($_POST['subject']??'');$message=trim($_POST['message']??'');
 if(mb_strlen($name)<2)$errors[]='Enter your name.';if(!filter_var($email,FILTER_VALIDATE_EMAIL))$errors[]='Enter a valid email.';if(mb_strlen($subject)<3)$errors[]='Enter a subject.';if(mb_strlen($message)<10||mb_strlen($message)>3000)$errors[]='Message must be 10–3000 characters.';
 if(!$errors){$file=__DIR__.'/data/contact_messages.json';$rows=json_decode(@file_get_contents($file)?:'[]',true);if(!is_array($rows))$rows=[];$rows[]=['name'=>$name,'email'=>$email,'subject'=>$subject,'message'=>$message,'created_at'=>date(DATE_ATOM)];if(file_put_contents($file,json_encode($rows,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES),LOCK_EX)===false)$errors[]='Could not save your message. Check folder permissions.';else $success='Thank you. Your message has been saved.';}
}
include __DIR__.'/includes/header.php';
?>
<main id="main-content"><section class="page-title"><h1>Contact us</h1><p>Send a question or feedback to the StudentHub team.</p></section><form class="form-card" method="post"><h2>Send a message</h2><?php foreach($errors as $er):?><p class="alert"><?=e($er)?></p><?php endforeach;?><?php if($success):?><p class="success"><?=e($success)?></p><?php endif;?>
<input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><div class="field"><label for="name">Name</label><input id="name" name="name" required maxlength="100"></div><div class="field"><label for="email">Email</label><input id="email" name="email" type="email" required maxlength="190"></div><div class="field"><label for="subject">Subject</label><input id="subject" name="subject" required maxlength="150"></div><div class="field"><label for="message">Message</label><textarea id="message" name="message" required minlength="10" maxlength="3000"></textarea></div><button class="btn">Submit message</button></form></main><?php include __DIR__.'/includes/footer.php'; ?>
