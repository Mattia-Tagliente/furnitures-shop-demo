<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <?php 
        include("./connection/conn.php");        
    ?>
    <link rel="stylesheet" type="text/css" href="./resources/css/products.css">
</head>
<body>
    <?php
        
        //This constant sets the number of products that can be displayed in a page
        $MAX_PRODUCTS_PER_PAGE = 4;
        //This array will store the page IDs according to the total amount of products
        $productPages = [];
        $furnituresFromJoinWhere = "FROM furniture_picture 
        INNER JOIN furniture ON furniture_picture.furniture_id = furniture.furniture_id
        INNER JOIN furniture_type ON furniture_type.type_id = furniture.furniture_type
        INNER JOIN furniture_price ON  furniture_price.furniture_id = furniture.furniture_id
        WHERE furniture_price.end_date IS null
        "
        ;

        //This query returns the total amount of products from the database 
        $NumberFurnitureQuery = "SELECT COUNT(*) AS number_products ".$furnituresFromJoinWhere;
        ;
        //Fetch of the result of the previous query
        $numberProductsResult = $conn->query($NumberFurnitureQuery);
        $fetchNumberProducts =  $numberProductsResult ->fetch_array();
        $numberProducts = $fetchNumberProducts['number_products'];
            

            //This GET call returns the current number of the page  
            $pageNumber = null;
            if (isset($_GET["p"])){
                $pageNumber = $_GET["p"];
            }
            //this variable sets an offset for the LIMIT of the query that returns the products in a page
            //For example: the max amount of products that can be displayed is 4.
            //Assuming there are 12  products, the first page shows the first four products,
            //while the second one shows the products from fith eighth
            //and the third page shows the products from nineth tto welfth 
            $offset = ($pageNumber - 1) * $MAX_PRODUCTS_PER_PAGE;

            //The query by default selects the furnitures with an offset
            $selectFurnitureQuery = "SELECT furniture.furniture_name, furniture_type.type_name, 
                furniture_picture.picture_path, furniture_price.price ".$furnituresFromJoinWhere."
                    LIMIT $offset, $MAX_PRODUCTS_PER_PAGE";

            //If the page number is null the current page will result as the first one,
            //in this case there is no need for a offset.
            if ($pageNumber == null){
            $selectFurnitureQuery = "SELECT furniture.furniture_name, furniture_type.type_name, 
                furniture_picture.picture_path, furniture_price.price ". $furnituresFromJoinWhere."
                LIMIT $MAX_PRODUCTS_PER_PAGE";
            }                

//Fetch of the previous query
$SelectFornituresResult = $conn->query($selectFurnitureQuery);

//Table that shows the furnitures data
echo "<table>";
while ($fetchRow = $SelectFornituresResult->fetch_array()){

    $picturePath = $fetchRow['picture_path'];
    $furnitureName =$fetchRow['furniture_name'];
    $furnitureType =$fetchRow['type_name'];
    $furniturePrice =$fetchRow['price'];

    $ratingQuery = "SELECT AVG(vote) AS rating_vote
    FROM furniture_rating 
    INNER JOIN furniture ON furniture_rating.furniture = furniture.furniture_id
    WHERE furniture.furniture_name = '$furnitureName'";

    $ratingResult = $conn->query($ratingQuery);
    $fetchRating =  $ratingResult->fetch_array();

    $furnitureRating = ceil($fetchRating['rating_vote']);

    echo "
    <td class='furniture'>
    <img class='furn-picture' src=$picturePath width='200px' height='200px'> 
    <div class='furniture-description'>
    <p class= 'furn-data type'>".strtoupper($furnitureType)."</p>
    <p class='furn-data name'>$furnitureName </p>
    <div class='furn-price-rating-wrapper'>
    <p class='furn-data price'>$furniturePrice</p>
    
    ";

    $ratingString = "";

    for ($i = 0; $i < $furnitureRating; $i++){
        $ratingString = $ratingString."*";
    }
    
    echo "<p class='furn-data rating'>$ratingString</p>
    </div>
    </div>
    <td>"
    ;
}
echo "</table><br>";

//These cycles populate the array with the number of pages based on the total amount of products
//and print the related links when the amount of products is more than the max number that can be displayed 
if ($numberProducts > $MAX_PRODUCTS_PER_PAGE){
    while ($numberProducts > 0){
        $numberProducts -= $MAX_PRODUCTS_PER_PAGE;
        $nextPage = end($productPages) + 1;
        $productPages[] = $nextPage;
    }
    
    for ($i = 0; $i < count($productPages); $i++){
        $pageIndex = $productPages[$i];
        if ($pageIndex == 1){
            echo "
            <a href='./products.php'>$pageIndex</a>
            " 
            ;
        } else {
            echo "
            <a href='./products.php?p=$pageIndex'>$pageIndex</a>
            " 
            ;
        }
        
        
    }
}
        
?>
</body>
</html>