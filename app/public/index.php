<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP</title>
</head>

<body>
    <?php
    $user = "root";
    $password = "123123";
    $database = "ROOT";
    $table = "user";

    try {
        $db = new PDO("mysql:host=192.168.209.131;dbname=$database", $user, $password);
        echo "<h2>TODO</h2><ol>";
        foreach ($db->query("SELECT name FROM $table") as $row) {
            echo "<li>" . $row['name'] . "</li>";
        }
        echo "</ol>";
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    } ?>
</body>

</html>