<?php
    $servername = "mysql-apache";
    $port = "3305";
    $username = "root";
    $password = "password";
    $database = "chatDatabase";

    $dsn = "mysql:host=$servername;port=$port;dbname=$database";

    try {
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'SELECT * FROM Messages';
        $result = $conn->query($sql);

        if($result) {
            foreach ($result as $document) {
                $array = json_decode(json_encode($document), true);
                echo '<div class="msg"><p>'.$array['text'].'</p><p>'.$array['date'].'</p></div>';
            }
        }
        
        $conn = null;
    } catch(PDOException $e) {
        die('Conexiunea la MySQL a eșuat: ' . $e->getMessage());
    }
?>



