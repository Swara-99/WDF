<?php
require_once __DIR__.'/../includes/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
function json_response($data,int $status=200): never {http_response_code($status);echo json_encode($data,JSON_UNESCAPED_UNICODE);exit;}
if(!$pdo)json_response(['error'=>'Database unavailable. Import the SQL schema first.'],503);
$method=$_SERVER['REQUEST_METHOD'];
if($method==='GET'){
 $q=trim($_GET['q']??'');$stmt=$pdo->prepare('SELECT id,title,category,event_date AS date,location,description,status FROM events WHERE title LIKE ? OR category LIKE ? OR location LIKE ? ORDER BY event_date ASC LIMIT 100');
 $term='%'.$q.'%';$stmt->execute([$term,$term,$term]);json_response(['data'=>$stmt->fetchAll()]);
}
if(!is_logged_in())json_response(['error'=>'Sign in required.'],401);
if(!in_array($method,['POST','PUT','DELETE'],true))json_response(['error'=>'Method not allowed.'],405);
$token=$_SERVER['HTTP_X_CSRF_TOKEN']??'';if(!is_string($token)||!hash_equals(csrf_token(),$token))json_response(['error'=>'Invalid CSRF token.'],419);
if(current_role()!=='admin')json_response(['error'=>'Admin access required.'],403);
$body=json_decode(file_get_contents('php://input'),true);if(!is_array($body))$body=$_POST;
try{
 if($method==='POST'){
  $title=trim($body['title']??'');$category=trim($body['category']??'General');$date=$body['date']??'';$location=trim($body['location']??'');$description=trim($body['description']??'');
  if(mb_strlen($title)<3||!preg_match('/^\d{4}-\d{2}-\d{2}$/',$date)||$location===''||mb_strlen($description)<5)json_response(['error'=>'Provide a valid title, date, location and description.'],422);
  $s=$pdo->prepare("INSERT INTO events(title,category,event_date,location,description,created_by) VALUES(?,?,?,?,?,?)");$s->execute([$title,$category,$date,$location,$description,$_SESSION['user_id']]);audit($pdo,(int)$_SESSION['user_id'],'event_create',$title);json_response(['message'=>'Event created.','id'=>(int)$pdo->lastInsertId()],201);
 }
 $id=(int)($body['id']??$_GET['id']??0);if($id<1)json_response(['error'=>'Valid event id required.'],422);
 if($method==='PUT'){
  $title=trim($body['title']??'');$category=trim($body['category']??'General');$date=$body['date']??'';$location=trim($body['location']??'');$description=trim($body['description']??'');
  if(mb_strlen($title)<3||!preg_match('/^\d{4}-\d{2}-\d{2}$/',$date)||$location===''||mb_strlen($description)<5)json_response(['error'=>'Provide valid event details.'],422);
  $s=$pdo->prepare('UPDATE events SET title=?,category=?,event_date=?,location=?,description=? WHERE id=?');$s->execute([$title,$category,$date,$location,$description,$id]);audit($pdo,(int)$_SESSION['user_id'],'event_update',(string)$id);json_response(['message'=>'Event updated.']);
 }
 $s=$pdo->prepare('DELETE FROM events WHERE id=?');$s->execute([$id]);audit($pdo,(int)$_SESSION['user_id'],'event_delete',(string)$id);json_response(['message'=>'Event deleted.']);
}catch(Throwable $ex){json_response(['error'=>'The event operation could not be completed.'],500);}
