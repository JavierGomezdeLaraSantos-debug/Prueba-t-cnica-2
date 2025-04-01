<?php

//conexion del código con la base de datos
// Al igual que en el cliente, para mayor sencillez en la conexión he obviado el uso del .env
//ATENCION: debe cambiar usuario, contraseña y host con sus propios datos. La tabla la adjunto en el github
$db_name = "citas_psicologo";
$db_user = "root";
$db_pass = "";
$db_host = "localhost";

    try {
    
        $pdo = new PDO("mysql:host=" . $db_host . ";dbname=".$db_name, $db_user, $db_pass);
    
    } catch (PDOException $error) {
    
        echo "Error: " . $err->getMessage();
    
    }

?>