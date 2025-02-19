<?php

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

   // echo $input;

    if (isset($input['fecha'])) {
        $fecha = $input['fecha'];

        try {
          

            // Consultar el tipo de cambio para la fecha
            $stmt = $connect->prepare("SELECT tventa FROM tbl_tipo_cambio WHERE fecha = :fecha");
            $stmt->bindParam(':fecha', $fecha);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode(['success' => true, 'tventa' => $result['tventa']]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Tipo de cambio no encontrado.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Fecha no proporcionada.']);
    }
}
