<?php
require_once('.env');
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];
$dbname = $_ENV['DB_NAME'];

// Connect to Database
$conn = new PDO("mysql:host=localhost;dbname=$dbname", $user, $pass);

$query = 'SELECT products_model FROM products';
$results = $conn->query($query);
foreach ($results as $row)
{
$string = $row['products_model'] . "\r\n";
file_put_contents('all_of_them.txt',$string,FILE_APPEND);
}

$test = file('all_of_them.txt');
$compare = file('compare.txt');

// Returns the values of all the array indices that intersect between the two
$results = array_intersect($test, $compare);

echo "<pre>";
print_r($results);
echo "</pre>"


?>