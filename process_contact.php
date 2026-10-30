<?php

require_once __DIR__.'/includes/bootstrap.php';

if($_SERVER['REQUEST_METHOD']!=='POST')
    {header('Location: contact.php');exit;
}

verify_csrf();
    $name=trim(strip_tags($_POST['name']??''));
    $email=trim($_POST['email']??'');$subject=trim(strip_tags($_POST['subject']??''));
    $message=trim(strip_tags($_POST['message']??''));
    $errors=[];

    if(mb_strlen($name)<2)$errors[]='Name is required.';

    if(!filter_var($email,FILTER_VALIDATE_EMAIL))$errors[]='Valid email required.';
    if(mb_strlen($subject)<3)$errors[]='Subject is required.';

    if(mb_strlen($message)<10||mb_strlen($message)>3000)$errors[]='Message must be 10–3000 characters.';

    if($errors)
        {
            http_response_code(422);$pageTitle='Contact error';include __DIR__.'/includes/header.php';
            echo '<main id="main-content" class="section"><h1>Please correct these fields</h1><ul>';
            foreach($errors as $x)echo '<li>'.e($x).'</li>';echo '</ul><a class="btn" href="contact.php">Back to contact</a></main>';
            include __DIR__.'/includes/footer.php';exit;
    }
    $path=__DIR__.'/data/contact_messages.json';$data=json_decode(@file_get_contents($path)?:'[]',true);
    if(!is_array($data))$data=[];$data[]=['name'=>$name,'email'=>$email,'subject'=>$subject,'message'=>$message,'created_at'=>date(DATE_ATOM)];

    if(file_put_contents($path,json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES),LOCK_EX)===false)
        {
            http_response_code(500);exit('Could not save message.');
            }
        header('Location: contact.php?sent=1');
        exit;
