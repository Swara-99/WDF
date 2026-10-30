<?php
require_once __DIR__.'/includes/bootstrap.php';
$pageTitle='Register | StudentHub'; $errors=[]; $name=$email=$mobile=$course=$year=$gender='';

if ($_SERVER['REQUEST_METHOD']==='POST') 
    {
        verify_csrf();
        $name=trim($_POST['name']??''); $email=strtolower(trim($_POST['email']??'')); $mobile=trim($_POST['mobile']??'');
        $course=trim($_POST['course']??''); $year=(int)($_POST['year']??0); $gender=trim($_POST['gender']??'');
        $password=$_POST['password']??''; $confirm=$_POST['confirm_password']??'';

        if (mb_strlen($name)<2 || mb_strlen($name)>100) $errors[]='Enter a name between 2 and 100 characters.';
                 if (!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]='Enter a valid email address.';
                 
        if (!preg_match('/^[6-9][0-9]{9}$/',$mobile)) $errors[]='Enter a valid 10-digit Indian mobile number.';

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',$password)) $errors[]='Password must have 8+ characters, uppercase, lowercase and a number.';
        if ($password!==$confirm) $errors[]='Passwords do not match.';

        if (!in_array($year,[1,2,3,4],true)) $errors[]='Choose your current year.';

        if (!in_array($gender,['Female','Male','Prefer not to say','Other'],true)) $errors[]='Choose a gender option.';
        if ($course==='') $errors[]='Choose a course.';

        if (empty($_POST['terms'])) $errors[]='Please accept the terms.';
        if (!$pdo) $errors[]='Database connection unavailable. Import database/studenthub.sql and check config/db.php.';
        if (!$errors)
             {


        try {
                $stmt=$pdo->prepare('INSERT INTO users (name,email,mobile,course,year_of_study,gender,password_hash,role,created_at) VALUES (?,?,?,?,?,?,?,\'student\',NOW())');
                $stmt->execute([$name,$email,$mobile,$course,$year,$gender,password_hash($password,PASSWORD_DEFAULT)]);
            header('Location: login.php?registered=1'); exit;

                } catch (PDOException $ex) { $errors[]=$ex->getCode()==='23000'?'That email is already registered.':'Registration could not be completed. Please try again.'; }
          }
        }
include __DIR__.'/includes/header.php';
?>
<main id="main-content">
    <section class="page-title"><h1>Create your account</h1>
    <p>Join StudentHub to access your student dashboard.</p></section>

        
    <form class="form-card" id="registrationForm" method="post" action="register.php" novalidate>
             <h2>Student registration</h2>
             <?php if($errors): ?>
                <div class="alert" role="alert"><ul><?php foreach($errors as $error): ?>
                    <li><?=e($error)?></li><?php endforeach; ?></ul></div><?php endif; ?>

    <input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>">
        <div class="field"><label for="name">Full name</label>
        <input id="name" name="name" type="text" autocomplete="name" required value="<?=e($name)?>">
        <span class="error" data-error-for="name">

        </span>
    </div>

        <div class="field"><label for="email">Email address</label>
        <input id="email" name="email" type="email" autocomplete="email" required value="<?=e($email)?>">
        <span class="error" data-error-for="email"></span></div>
       
       
        <div class="field"><label for="mobile">Mobile number</label>
        <input id="mobile" name="mobile" type="tel" inputmode="numeric" pattern="[6-9][0-9]{9}" required value="<?=e($mobile)?>">
        <span class="error" data-error-for="mobile"></span></div>
        
        <div class="field"><label for="course">Course</label>
        <select id="course" name="course" required>
            <option value="">Select course</option><?php foreach(['B.Tech IT','B.Tech CSE','BCA','B.Sc IT','Other'] as $c): ?>
            <option <?= $course===$c?'selected':'' ?>><?=e($c)?></option><?php endforeach; ?></select></div>
       
       
            <div class="field"><label for="year">Year of study</label>
        <select id="year" name="year" required><option value="">Select year</option><?php for($i=1;$i<=4;$i++): ?>
            <option value="<?=$i?>" <?=$year===$i?'selected':''?>>Year <?=$i?></option><?php endfor; ?></select></div>
       
       
            <div class="field"><label for="gender">Gender</label>
        <select id="gender" name="gender" required>
            <option value="">Choose option</option><?php foreach(['Female','Male','Other','Prefer not to say'] as $g): ?><option <?=$gender===$g?'selected':''?>><?=e($g)?></option><?php endforeach; ?></select></div>
       
       
            <div class="field"><label for="password">Password</label>
        <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
        <span class="error" data-error-for="password"></span>
        <small id="passwordStrength" aria-live="polite">

        </small></div>
        
        
        <div class="field">
       <label for="confirm_password">Confirm password</label>
        <input id="confirm_password" name="confirm_password" type="password" autocomplete="new-password" required>
        <span class="error" data-error-for="confirm_password">

        </span>
    </div>
        
        
        <div class="field"><label>
            <input type="checkbox" name="terms" value="1" required> I accept the portal terms.</label>
            <span class="error" data-error-for="terms"></span></div>
        <button class="btn" type="submit">Create account</button>
        
        <p class="hint">Already registered? <a href="login.php">Log in</a>.</p>
        
    
    </form>
</main>
<?php include __DIR__.'/includes/footer.php'; ?>
