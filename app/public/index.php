<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP</title>
</head>

<body>
    <?php 
        $x = 10;
        $myArray = array();
        // Ví dụ PHP
        function addItemsToMyArray() {
            $x = 20;
            global $myArray;
            $myArray[] = 'apple';
            $myArray[] = 'banana';
            $myArray[] = 'coconut';
            echo $x;
            return $myArray;
        }  
        
        $myArray = addItemsToMyArray();
        echo count($myArray);
    ?>
</body>
</html>