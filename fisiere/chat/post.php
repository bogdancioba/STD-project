<?php
    session_start();

    $servername = "mysql-apache";
    $port = "3305";
    $username = "root";
    $password = "password";
    $database = "chatDatabase";

    $dsn = "mysql:host=$servername;port=$port;dbname=$database";

    if(isset($_POST['text'])){
        $text = $_POST['text'];

        try {
            $conn = new PDO($dsn, $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $aux = time() + 10800;
            $tmp = date('Y-m-d H:i:s',$aux);

            $sql = 'INSERT INTO Messages VALUES ("'.$text.'","'.$tmp.'")';
            $result = $conn->query($sql);
            
            $conn = null;
        } catch(PDOException $e) {
            die('Conexiunea la MySQL a eșuat: ' . $e->getMessage());
        }
    }
?>