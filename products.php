<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <?php 
        include("./connection/conn.php");
    ?>
</head>
<body>
    <?php
        
        $maxProductsPerPage = 4;
        $productPages = [];

        $NumberFurnitureQuery = "SELECT COUNT(*) AS number_products
        FROM furniture_picture 
        INNER JOIN furniture ON furniture_picture.furniture_id = furniture.furniture_id
        INNER JOIN furniture_type ON furniture_type.type_id = furniture.furniture_type
        INNER JOIN furniture_price ON  furniture_price.furniture_id = furniture.furniture_id
        WHERE furniture_price.end_date IS null
        "
        ;

        $numberProductsResult = $conn->query($NumberFurnitureQuery);
        $fetchNumberProducts =  $numberProductsResult ->fetch_array();
        $numberProducts = $fetchNumberProducts['number_products'];
            

            //number of the page
            $pageNumber = $_GET["p"];
            $offset = ($pageNumber - 1) * $maxProductsPerPage;

            $selectFurnitureQuery = "SELECT furniture.furniture_name, furniture_type.type_name, 
                furniture_picture.picture_path, furniture_price.price
                FROM furniture_picture 
                INNER JOIN furniture ON furniture_picture.furniture_id = furniture.furniture_id
                INNER JOIN furniture_type ON furniture_type.type_id = furniture.furniture_type
                INNER JOIN furniture_price ON  furniture_price.furniture_id = furniture.furniture_id
                WHERE furniture_price.end_date IS null
                LIMIT $offset, $maxProductsPerPage";

            if ($pageNumber == null){
            $selectFurnitureQuery = "SELECT furniture.furniture_name, furniture_type.type_name, 
                furniture_picture.picture_path, furniture_price.price
                FROM furniture_picture 
                INNER JOIN furniture ON furniture_picture.furniture_id = furniture.furniture_id
                INNER JOIN furniture_type ON furniture_type.type_id = furniture.furniture_type
                INNER JOIN furniture_price ON  furniture_price.furniture_id = furniture.furniture_id
                WHERE furniture_price.end_date IS null
                LIMIT $maxProductsPerPage";
            }                

$SelectPicturesResult = $conn->query($selectFurnitureQuery);

echo "<table>";
while ($fetchRow = $SelectPicturesResult->fetch_array()){

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
    <td>
    <img src=$picturePath width='200px' height='200px'> 
    <p>Type:$furnitureType </p>
    <p>Name:$furnitureName </p>
    <p>Price:$furniturePrice</p>
    ";

    $ratingString = "";

    for ($i = 0; $i < $furnitureRating; $i++){
        $ratingString = $ratingString."*";
    }
    
    echo "<p>Rating:$ratingString</p>
    <td>"
    ;
}
echo "</table><br>";

//This cycle populates the array with the number of pages based on the number of products
if ($numberProducts > $maxProductsPerPage){
    while ($numberProducts > 0){
        $numberProducts -= $maxProductsPerPage;
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