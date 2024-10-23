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

    <table>
    <?php
        $selectFurnitureQuery = "SELECT furniture.furniture_name, furniture_type.type_name, 
        furniture_picture.picture_path, furniture_price.price
        FROM furniture_picture 
        INNER JOIN furniture ON furniture_picture.furniture_id = furniture.furniture_id
        INNER JOIN furniture_type ON furniture_type.type_id = furniture.furniture_type
        INNER JOIN furniture_price ON  furniture_price.furniture_id = furniture.furniture_id
        WHERE furniture_price.end_date IS null
        LIMIT 3"
        ;
    

        // Per mostrare solo due o n prodotti per pagina:

        // innanzitutto vediamo quante pagine avremo in base al numero totale dei prodotti restituiti dalla query
        // crieamo un array contenente il numero delle pagine che ha solo all'inizio il numero 1
        // prendere il numero totale degli items
        // se il numero totale dei prodotti è maggiore al limite per pagina: 
        // ciclo while (condizione: numero delle pagine > 0)
        // in cui ad ogni iterazione totale prodotti - numero prodotti per pagina,
        //  numero della pagina += 1
        //  numero prodotti -= numero prodotti per pagina

        // se la dimensione dell'array delle pagine è maggiore:
        // stampare i numeri delle pagine sotto 
        // se la variabile pagina non è null --- > (ad esempio è 1, vedere sotto)
        // limit della query da (limite per pagina * numero della pagina + 1), prendere 2
        // ciclo for (per il numero di pagine nell'array)
        // stampa un link con numero della pagina, sia come testo che nella variabile pagina dell'href ad ogni iterazione

        // altrimenti, se la dimensione dell'array è solo 1 di uno, semplicemente stampare i prodotti
        // senza particolari seghe mentali

        $SelectPicturesResult = $conn->query($selectFurnitureQuery);

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
            <td>";
        }
    ?>
    </table>
</body>
</html>