<?php
include_once("conexion.php");
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

// Parte del código para checkear si el usuario ingresa una cita por primera vez

if (isset($_GET['checkDNI'])) {

    $variable = [];
    // Miro si existe alguien con el dni.
    foreach ($pdo->query("SELECT * FROM citas WHERE DNI = '" . $_GET['checkDNI'] ."'") as $value) {

        $variable[] = $value;

    }

    if (count($variable) < 1) {
        echo json_encode('none');
    }else{
        echo json_encode($variable);
    }


}

// Parte del código para insertar la nueva cita

if ($data) {

    $table = [];

    //miro a ver si hay ya citas en ese dia

   try {
    foreach ($pdo->query("SELECT * FROM citas WHERE fecha_cita ='". $data["fecha"] ."'") as $value) {

        $table[] = $value;

    }

    // Si hay 11 citas preciamente, ya que cierra a las 22 la consulta, y dura 1 hora la consulta.
    // La última cita será a las 21
    
    if (count($table) < 12) {
        
        // Preparo la consulta de inserción

        $sql = "INSERT INTO citas (nombre, DNI, telefono, email, tipo, fecha_cita) VALUES (:nombre, :dni, :telefono, :email, :tipo, :fecha)";
        $stmt = $pdo->prepare($sql);

        $execute = $stmt->execute([
            ':nombre'   => $data['nombre'],
            ':dni'      => $data['DNI'],
            ':telefono' => $data['telefono'],
            ':email'    => $data['email'],
            ':tipo'     => $data['tipo'],
            ':fecha'    => $data['fecha']
        ]);

        // La ejecuto
        
        if ($execute) {
            echo json_encode(['message' => 'Cita reservada a las '.(10 +count($table))]);
        } else {
            echo json_encode(['error' => 'No se pudo registrar la cita']);
        }
        
    }else{
        echo json_encode(['error' => '¡Todo el día está ocupado! Intente otro día']);

    }

      
    } catch (PDOException $e) {
        
        echo json_encode(['error' => 'Error al insertar en la base de datos: ' . $e->getMessage()]);
    }
}
?>