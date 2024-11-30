<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();
header('Content-Type: application/json');


try 
{
    if (!empty($_POST['ruc']) && !empty($_POST['usuario']) && !empty($_POST['clave'])) 
    {
        $ruc = trim($_POST['ruc']);
        $usuario = trim($_POST['usuario']);
        $clave = trim($_POST['clave']);

        // Consulta segura usando password_verify()
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="SELECT * FROM vw_tbl_usuarios WHERE usuario= :user AND clave= :pass AND ruc=:ruc";
        $resultado=$connect->prepare($sql);


        // Sanitizar entradas
        $ruc = trim($ruc);
        $usuario = trim($usuario);
        $clave = md5(trim($clave)); // Hash MD5 para la clave

        $resultado->bindValue(":user",$usuario);
        $resultado->bindValue(":pass",$clave);
        $resultado->bindValue(":ruc",$ruc);
        $resultado->execute();
        $num_reg = $resultado->rowCount();
        //echo $num_reg;
        if($num_reg!=0)
        {
            $user = $resultado->fetch(PDO::FETCH_ASSOC);         
                // Configurar variables de sesión
                $_SESSION['iniciarSesion']     = "cinema";
                $_SESSION["nombre"]            = $user['nombre'];
                $_SESSION["perfil"]            =$user['perfil'];
                $_SESSION["sucursal"]          =$user['sucursal'];
                $_SESSION["id"]                =$user['id'];
                $_SESSION["id_empresa"]        =$user['id_empresa'];
                $_SESSION["empresa"]           =$user['empresa'];
                $_SESSION["ruc"]               =$user['ruc'];
                $_SESSION["fecha_vencimiento"] =$user['fecha_vencimiento'];
                $_SESSION["nombre_almacen"]  =$user['nombre_almacen'];
                $_SESSION["almacen"]         =$user['almacen'];
                $_SESSION["farmacia"]        =$user['farmacia'];
                $_SESSION["usabarras"]       =$user['usabarras'];
                $_SESSION["servidor_cpe"]    =$user['servidor_cpe'];
                $_SESSION["nombre_svr"]      =$user['nombre_svr'];
                $_SESSION["tipo_svr"]        =$user['tipo_svr'];
                $_SESSION["venta_por_mayor"] =$user['venta_por_mayor'];
                $_SESSION["detalle"]         =$user['detalle'];
                $_SESSION["precio"]          =$user['precio'];
                $_SESSION["usaexportacion"]  =$user['usaexportacion'];
                // Agrega más variables de sesión según sea necesario
                
                echo json_encode([
                    'success' => true,
                    'redirect_url' => 'inicio'
                ]);
             
        } 
        else 
        {
            echo json_encode([
                'success' => false,
                'message' => 'Credenciales inválidas al iniciar sesion.'
            ]);
        }
    } 
    else 
    {
        echo json_encode([
            'success' => false,
            'message' => 'Todos los campos son obligatorios.'
        ]);
    }
} 
catch (Exception $e) 
{
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor.'
    ]);
}




 ?>