<?php
require_once __DIR__.'/../includes/bootstrap.php';require_admin();
$pageTitle='Manage students | StudentHub';$notice='';
if($_SERVER['REQUEST_METHOD']==='POST')
    
    {
        verify_csrf();$action=$_POST['action']??'';$id=(int)($_POST['id']??0);

 if($pdo&&$action==='delete'&&$id){$s=$pdo->prepare("DELETE FROM users WHERE id=? AND role='student'");
 
 $s->execute([$id]);
 audit($pdo,(int)$_SESSION['user_id'],'student_delete',(string)$id);
 $notice='Student record deleted.';
 
}

 if($pdo&&$action==='update'&&$id){$name=trim($_POST['name']??'');
 $course=trim($_POST['course']??'');$email=strtolower(trim($_POST['email']??''));
 
 if(mb_strlen($name)>=2&&filter_var($email,FILTER_VALIDATE_EMAIL)&&$course!=='')
    {
        $s=$pdo->prepare("UPDATE users SET name=?,email=?,course=? WHERE id=? AND role='student'");
        
        $s->execute([$name,$email,$course,$id]);$notice='Student updated.';
        
        }
        
        else $notice='Please provide valid name, email and course.';}
}
$q=trim($_GET['q']??'');$course=trim($_GET['course']??'');$rows=[];$courses=[];


if($pdo)
    {
        $courses=$pdo->query("SELECT DISTINCT course FROM users WHERE role='student' ORDER BY course")->fetchAll(PDO::FETCH_COLUMN);
        
        $sql="SELECT id,name,email,course,year_of_study,created_at FROM users WHERE role='student' AND (name LIKE ? OR email LIKE ?)";
        $args=['%'.$q.'%','%'.$q.'%'];if($course!=='')
        
        {
            $sql.=' AND course=?';$args[]=$course;
            }
            
            $sql.=' ORDER BY name LIMIT 100';
            $s=$pdo->prepare($sql);
            $s->execute($args);$rows=$s->fetchAll();
            }

include __DIR__.'/../includes/header.php';
?>
<main id="main-content">
    
    <section class="page-title">
        <h1>Student management</h1>
        <p>Search, filter, update and remove student records.

        </p>
</section>

<section class="section">
    <?php if($notice):?><p class="success"><?=e($notice)?></p><?php endif;?>


<form class="toolbar" method="get">
    <input name="q" placeholder="Search name or email" value="<?=e($q)?>">
    
    <select name="course"><option value="">All courses
        
    </option><?php foreach($courses as $c):?>
        <option <?=$course===$c?'selected':''?>><?=e($c)?>
        
    </option><?php endforeach;?></select><button class="btn">Search


    </button>
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr><th>Name</th><th>Email</th><th>Course</th><th>Year</th>
            
            <th>Actions</th></tr></thead><tbody><?php foreach($rows as $r):?>
                <tr><td><?=e($r['name'])?></td><td><?=e($r['email'])?>
            
            </td><td>
                <?=e($r['course'])?></td><td>
                    <?=e($r['year_of_study'])?>
                </td><td>
                    
                <details>
                    <summary>Edit</summary>
                    <form method="post" class="form-card">
                        <input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?=$r['id']?>">
                        
                        <div class="field"><label>Name</label>
                        <input name="name" value="<?=e($r['name'])?>" required></div>
                        
                        <div class="field"><label>Email</label>
                        <input name="email" type="email" value="<?=e($r['email'])?>" required>
                    
                    </div>
                    <div class="field">
                        <label>Course</label>
                        <input name="course" value="<?=e($r['course'])?>" required>
                    
                    </div>
    <button class="btn">Save</button>
</form>
</details>
        <form method="post" class="inline-form" onsubmit="return confirm('Delete this student account?')">
            <input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?=$r['id']?>">
            <button class="btn danger small-btn">Delete</button>
        </form></td></tr><?php endforeach;?><?php if(!$rows):?>
            
            <tr><td colspan="5">No student records found.</td></tr>
            
            <?php endif;?></tbody></table></div>
            <p class="hint">This page manages registered user accounts whose role is student. 
                Use fictional data for practical testing.</p
            >
        
        </section>
    </main>
    
    <?php include __DIR__.'/../includes/footer.php';?>
