<?php
require_once('.env');
require_once('org.php');
// Define database info
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];
$dbname = $_ENV['DB_NAME'];
// Make connection with PDO object
$conn = new PDO("mysql:host=localhost;dbname=$dbname", $user, $pass);

// Create holder array
$sorted = array();
// Get productID for each sku out of database, then add it to the $sorted array to use later
foreach ($products as $key=>$value)
{
	$sql= "
	SELECT products_id
	FROM products
	WHERE products_model = :key
	";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':key', $key);
	$stmt->execute();
	while ($row = $stmt->fetch()) 
	{
    	array_push($sorted, ['products_id'=>$row['products_id'], 'products_model' => $key, 'products_price' => $value]);
  	}

}

// Change the price of the product in the "specials" table in the DB [don't know why zencart has prices there]
// column `specials_new_product_price` is what will need to be changed
foreach ($sorted as $product)
{
	$sql = "
	UPDATE specials
	SET specials_new_products_price = :product_price 
	WHERE products_id = :product_id
	";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':product_price', $product['products_price']);
	$stmt->bindParam(':product_id', $product['products_id']);
	if ($stmt->execute())
	{
		echo $product['products_model'] . " updated sucessfully <br>";
	}
}





?>