<?php 

 //   $connect = new PDO("mysql:host=".BD_HOST.";dbname=".BD_NAME,BD_USER,BD_PASSWORD);
 //   $connect -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


try {
    $connect = new PDO("mysql:host=" . BD_HOST . ";dbname=" . BD_NAME, BD_USER, BD_PASSWORD, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Modo de manejo de errores
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retorna los resultados como arreglos asociativos por defecto
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    // En producción, podrías registrar este error en lugar de mostrarlo
    error_log($e->getMessage());
    die("Error al conectar a la base de datos");
}


 ?>