<?php

try{
$db_name= 'mysql:dbname=blog_db;host=localhost';
$user_name= 'root';
$password = 'HTMLCSS1728';

$conn = new PDO($db_name, $user_name, $password);


}catch(PDOException $e){
    echo 'Connection failed!' . $e->getMessage();
}

?>