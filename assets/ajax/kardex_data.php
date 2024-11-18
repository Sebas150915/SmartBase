<?php

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 



// Habilitar depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conectar a la base de datos
$empresa = $_GET["id_empresa"];
$fecha_ini = $_GET['fecha_ini'];
$fecha_fin = $_GET['fecha_fin'];
$action = $_GET['action'];

/*if($action == 'kardexv')
{
        // Consulta para obtener los movimientos entre las fechas indicadas
    $query = "
    SELECT codigo_producto, nombre_producto, fecha, tipo_doc, serie_doc, num_doc, tipo_movimiento, cantidad_entrada, cantidad_salida, costo_unitario_entrada, costo_unitario_salida
    FROM vw_tbl_alm
    WHERE empresa = :empresa
    AND fecha BETWEEN :fecha_ini AND :fecha_fin
    ORDER BY nombre_producto, fecha, tipo_movimiento,empresa
    ";

    $statement = $connect->prepare($query);
    $statement->execute([
    ':empresa' => $empresa,
    ':fecha_ini' => $fecha_ini,
    ':fecha_fin' => $fecha_fin
    ]);

    $totalRecords=$statement->rowCount();

    $movimientos = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Inicializar variables para el cálculo de Kardex
    $saldo_pro = 0;
    $costo_promedio = 0;
    $kardex_data = Array();

    foreach ($movimientos as $movimiento) {
    $codigo_producto = $movimiento['codigo_producto'];
    $nombre_producto = $movimiento['nombre_producto'];
    $cantidad_entrada = $movimiento['cantidad_entrada'];
    $cantidad_salida = $movimiento['cantidad_salida'];
    $costo_unitario_entrada = $movimiento['costo_unitario_entrada'];

    if ($movimiento['tipo_movimiento'] == '01') { // Entrada (compra)
    // Calcular el nuevo costo promedio ponderado
    $valor_inventario = ($saldo_pro * $costo_promedio) + ($cantidad_entrada * $costo_unitario_entrada);
    $saldo_pro += $cantidad_entrada;

    if ($saldo_pro > 0) {
    $costo_promedio = $valor_inventario / $saldo_pro;
    }
    } else if ($movimiento['tipo_movimiento'] == '02') { // Salida (venta)
    $saldo_pro -= $cantidad_salida;
    }

    // Registrar el movimiento en el Kardex
    $kardex_data[] = array(
    'nombre_producto' => $nombre_producto,
    'fecha' => $movimiento['fecha'],
    'tipo_doc' => $movimiento['tipo_doc'],
    'serie_doc' => $movimiento['serie_doc'],
    'num_doc' => $movimiento['num_doc'],
    'tipo_movimiento' => $movimiento['tipo_movimiento'],
    'cantidad_entrada' => round($cantidad_entrada,2),
    'costo_unitario_entrada' => round($costo_unitario_entrada,3),
    'total_entrada' => round($cantidad_entrada * $costo_unitario_entrada,2),
    'cantidad_salida' => round($cantidad_salida,2),
    'costo_unitario_salida' => round($movimiento['costo_unitario_salida'],2),
    'total_salida' => round($cantidad_salida * $movimiento['costo_unitario_salida'],2),
    'saldo_final' => round($saldo_pro,2),
    'costo_promedio' => round($costo_promedio,3),
    'total_final' => round($saldo_pro * $costo_promedio,2)
    );
    }

    // Devolver los datos en formato JSON
    header('Content-Type: application/json');

    $json_data = array(
    "data"  => $kardex_data
    );


    echo json_encode($json_data);

    exit(); 
}*/

/*if($action == 'kardexu')
{

    $almacen = $_GET['local'];
        // Consulta para obtener los movimientos entre las fechas indicadas
    $query = "
    SELECT codigo_producto, nombre_producto, fecha, tipo_doc, serie_doc, num_doc, tipo_movimiento, cantidad_entrada, cantidad_salida, costo_unitario_entrada, costo_unitario_salida
    FROM vw_tbl_alm
    WHERE 
    local like :almacen  AND
    empresa = :empresa AND 
    fecha BETWEEN :fecha_ini AND :fecha_fin
    ORDER BY nombre_producto, fecha, tipo_movimiento,empresa
    ";

    $statement = $connect->prepare($query);
    $statement->execute([
    ':almacen' => $almacen,
    ':empresa' => $empresa,
    ':fecha_ini' => $fecha_ini,
    ':fecha_fin' => $fecha_fin
    ]);

    $totalRecords=$statement->rowCount();

    $movimientos = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Inicializar variables para el cálculo de Kardex
    $saldo_pro = 0;
    $costo_promedio = 0;
    $kardex_data = Array();
    //$saldopro = 0;
    foreach ($movimientos as $movimiento) {
    $codigo_producto = $movimiento['codigo_producto'];
    $nombre_producto = $movimiento['nombre_producto'];
    $cantidad_entrada = $movimiento['cantidad_entrada'];
    $cantidad_salida = $movimiento['cantidad_salida'];
    $costo_unitario_entrada = $movimiento['costo_unitario_entrada'];

    if ($movimiento['tipo_movimiento'] == '02') 
    { // Entrada (compra)
    // Calcular el nuevo costo promedio ponderado
    $valor_inventario = ($saldo_pro * $costo_promedio) + ($cantidad_entrada * $costo_unitario_entrada);
    $saldo_pro += $cantidad_entrada;

    if ($saldo_pro > 0) {
    $costo_promedio = $valor_inventario / $saldo_pro;
    }
    } 
    else if ($movimiento['tipo_movimiento'] == '01') { // Salida (venta)
    $saldo_pro -= $cantidad_salida;
    }

    // Registrar el movimiento en el Kardex
    $kardex_data[] = array(
    'nombre_producto' => $nombre_producto,
    'fecha' => $movimiento['fecha'],
    'tipo_doc' => $movimiento['tipo_doc'],
    'serie_doc' => $movimiento['serie_doc'],
    'num_doc' => $movimiento['num_doc'],
    'tipo_movimiento' => $movimiento['tipo_movimiento'],
    'cantidad_entrada' => round($cantidad_entrada,2),
   
    'cantidad_salida' => round($cantidad_salida,2),
   
    'saldo_final' => round($saldo_pro,2)
    
    );
    }

    // Devolver los datos en formato JSON
    header('Content-Type: application/json');

    $json_data = array(
    "data"  => $kardex_data
    );


    echo json_encode($json_data);

    exit(); 
}*/

if ($action == 'kardexv') {
    // Consulta para obtener los movimientos entre las fechas indicadas
    $query = "
    SELECT codigo_producto, nombre_producto, fecha, tipo_doc, serie_doc, num_doc, tipo_movimiento, cantidad_entrada, cantidad_salida, costo_unitario_entrada, costo_unitario_salida
    FROM vw_tbl_alm
    WHERE empresa = :empresa
    AND fecha BETWEEN :fecha_ini AND :fecha_fin
    ORDER BY codigo_producto, fecha, tipo_movimiento, empresa
    ";

    $statement = $connect->prepare($query);
    $statement->execute([
        ':empresa' => $empresa,
        ':fecha_ini' => $fecha_ini,
        ':fecha_fin' => $fecha_fin
    ]);

    $movimientos = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Inicializar variables para el cálculo de Kardex
    $kardex_data = [];
    $saldo_pro = 0;
    $costo_promedio = 0;
    $producto_anterior = null;

    foreach ($movimientos as $movimiento) {
        $codigo_producto = $movimiento['codigo_producto'];
        $nombre_producto = $movimiento['nombre_producto'];
        $cantidad_entrada = $movimiento['cantidad_entrada'];
        $cantidad_salida = $movimiento['cantidad_salida'];
        $costo_unitario_entrada = $movimiento['costo_unitario_entrada'];
        $costo_unitario_salida = $movimiento['costo_unitario_salida'];

        // Reiniciar cálculos si cambia el producto
        if ($codigo_producto !== $producto_anterior) {
            $saldo_pro = 0;
            $costo_promedio = 0;
        }

        // Calcular costo promedio ponderado
        if ($movimiento['tipo_movimiento'] == '02') { // Entrada (compra)
            $valor_inventario = ($saldo_pro * $costo_promedio) + ($cantidad_entrada * $costo_unitario_entrada);
            $saldo_pro += $cantidad_entrada;

            if ($saldo_pro > 0) {
                $costo_promedio = $valor_inventario / $saldo_pro;
            }
        } elseif ($movimiento['tipo_movimiento'] == '01') { // Salida (venta)
            $saldo_pro -= $cantidad_salida;
        }

        // Registrar el movimiento en el Kardex
        $kardex_data[] = [
            'codigo_producto' => $codigo_producto,
            'nombre_producto' => $nombre_producto,
            'fecha' => $movimiento['fecha'],
            'tipo_doc' => $movimiento['tipo_doc'],
            'serie_doc' => $movimiento['serie_doc'],
            'num_doc' => $movimiento['num_doc'],
            'tipo_movimiento' => $movimiento['tipo_movimiento'],
            'cantidad_entrada' => round($cantidad_entrada, 2),
            'costo_unitario_entrada' => round($costo_unitario_entrada, 3),
            'total_entrada' => round($cantidad_entrada * $costo_unitario_entrada, 2),
            'cantidad_salida' => round($cantidad_salida, 2),
            'costo_unitario_salida' => round($costo_unitario_salida, 2),
            'total_salida' => round($cantidad_salida * $costo_unitario_salida, 2),
            'saldo_final' => round($saldo_pro, 2),
            'costo_promedio' => round($costo_promedio, 3),
            'total_final' => round($saldo_pro * $costo_promedio, 2)
        ];

        // Actualizar el producto anterior
        $producto_anterior = $codigo_producto;
    }

    // Devolver los datos en formato JSON
    header('Content-Type: application/json');
    $json_data = ['data' => $kardex_data];
    echo json_encode($json_data);
    exit();
}


if ($action == 'kardexu') {
    $almacen = $_GET['local'];

    // Consulta para obtener los movimientos entre las fechas indicadas
    $query = "
    SELECT codigo_producto, nombre_producto, fecha, tipo_doc, serie_doc, num_doc, tipo_movimiento, cantidad_entrada, cantidad_salida, costo_unitario_entrada, costo_unitario_salida
    FROM vw_tbl_alm
    WHERE 
    local LIKE :almacen AND
    empresa = :empresa AND 
    fecha BETWEEN :fecha_ini AND :fecha_fin
    ORDER BY codigo_producto, fecha, tipo_movimiento, empresa
    ";

    $statement = $connect->prepare($query);
    $statement->execute([
        ':almacen' => $almacen,
        ':empresa' => $empresa,
        ':fecha_ini' => $fecha_ini,
        ':fecha_fin' => $fecha_fin
    ]);

    $movimientos = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Inicializar variables para el cálculo de Kardex
    $kardex_data = [];
    $saldo_pro = 0;
    $costo_promedio = 0;
    $producto_anterior = null;

    foreach ($movimientos as $movimiento) {
        $codigo_producto = $movimiento['codigo_producto'];
        $nombre_producto = $movimiento['nombre_producto'];
        $cantidad_entrada = $movimiento['cantidad_entrada'];
        $cantidad_salida = $movimiento['cantidad_salida'];
        $costo_unitario_entrada = $movimiento['costo_unitario_entrada'];

        // Reiniciar cálculos si cambia el producto
        if ($codigo_producto !== $producto_anterior) {
            $saldo_pro = 0;
            $costo_promedio = 0;
        }

        // Calcular costo promedio ponderado
        if ($movimiento['tipo_movimiento'] == '02') { // Entrada (compra)
            $valor_inventario = ($saldo_pro * $costo_promedio) + ($cantidad_entrada * $costo_unitario_entrada);
            $saldo_pro += $cantidad_entrada;

            if ($saldo_pro > 0) {
                $costo_promedio = $valor_inventario / $saldo_pro;
            }
        } elseif ($movimiento['tipo_movimiento'] == '01') { // Salida (venta)
            $saldo_pro -= $cantidad_salida;
        }

        // Registrar el movimiento en el Kardex
        $kardex_data[] = [
            'codigo_producto' => $codigo_producto,
            'nombre_producto' => $nombre_producto,
            'fecha' => $movimiento['fecha'],
            'tipo_doc' => $movimiento['tipo_doc'],
            'serie_doc' => $movimiento['serie_doc'],
            'num_doc' => $movimiento['num_doc'],
            'tipo_movimiento' => $movimiento['tipo_movimiento'],
            'cantidad_entrada' => round($cantidad_entrada, 2),
            'cantidad_salida' => round($cantidad_salida, 2),
            'saldo_final' => round($saldo_pro, 2),
            'costo_promedio' => round($costo_promedio, 2)
        ];

        // Actualizar el producto anterior
        $producto_anterior = $codigo_producto;
    }

    // Devolver los datos en formato JSON
    header('Content-Type: application/json');
    $json_data = ['data' => $kardex_data];
    echo json_encode($json_data);
    exit();
}
