<?php
// Run manually only for local demo: php database/seed_students.php
require_once __DIR__.'/../config/db.php';
if(!$pdo)exit("Database connection failed.\n");
$names=['Aarav Patel','Diya Shah','Rohan Mehta','Anaya Desai','Vivaan Joshi','Isha Trivedi','Arjun Parmar','Sara Khan','Krish Patel','Mira Shah','Dev Thakkar','Aditi Mehta','Kabir Desai','Riya Joshi','Yash Trivedi','Neel Shah','Myra Patel','Reyansh Mehta','Kiara Desai','Advik Joshi'];
$courses=['B.Tech IT','B.Tech CSE','BCA','B.Sc IT'];
$s=$pdo->prepare("INSERT IGNORE INTO users(name,email,mobile,course,year_of_study,gender,password_hash,role) VALUES(?,?,?,?,?,?,?,'student')");
foreach($names as $i=>$name){$email='demo.student'.($i+1).'@example.edu';$s->execute([$name,$email,'900000'.str_pad((string)($i+1),3,'0',STR_PAD_LEFT),$courses[$i%4],($i%4)+1,'Prefer not to say',password_hash('DemoPass123',PASSWORD_DEFAULT)]);}
echo "Inserted demo users if not already present. Shared demo password: DemoPass123\n";
