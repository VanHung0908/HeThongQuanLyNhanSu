<?php 
// DB credentials.
define('DB_HOST','localhost');
define('DB_USER','LamVanHung');
define('DB_PASS','0908');
define('DB_NAME','qlns');
// Establish database connection.
try
{
$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
// Thiết lập chế độ lỗi để hiển thị thông báo lỗi khi có lỗi SQL
}
catch (PDOException $e)
{
exit("Error: " . $e->getMessage());
}
?>