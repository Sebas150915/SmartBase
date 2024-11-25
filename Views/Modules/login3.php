<?php 
$mensaje = '';

if(!empty($_POST))
{
  $usuario = $_POST['usuario'];
  $clave   = $_POST['clave'];
  $usuario = trim($usuario);
  $clave = trim($clave);
  $ruc   = $_POST['ruc'];

  if(empty($usuario) || empty($clave) || empty($ruc))
      {
    echo '<script>alert(usuario o clave no pueden estar vacios)</script>';
      }
  else
      {

        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="SELECT * FROM vw_tbl_usuarios WHERE usuario= :user AND clave= :pass AND ruc=:ruc";
        $resultado=$connect->prepare($sql);
        $ruc=htmlentities(addslashes($ruc));
        $usuario=htmlentities(addslashes($usuario));
        $clave=htmlentities(addslashes($clave));
        $clave=md5($clave);
        //echo $clave;
        $resultado->bindValue(":user",$usuario);
        $resultado->bindValue(":pass",$clave);
        $resultado->bindValue(":ruc",$ruc);
        $resultado->execute();
        $num_reg=$resultado->rowCount();
        //echo $num_reg;
        $row_resultado=$resultado->fetch(PDO::FETCH_ASSOC);
        if($num_reg!=0)
          {
            $_SESSION['iniciarSesion']          ="cinema";
            $_SESSION["nombre"]                 =$row_resultado['nombre'];
            $_SESSION["perfil"]                 =$row_resultado['perfil'];
            $_SESSION["sucursal"]               =$row_resultado['sucursal'];
            $_SESSION["id"]                     =$row_resultado['id'];
            $_SESSION["id_empresa"]             =$row_resultado['id_empresa'];
            $_SESSION["empresa"]                =$row_resultado['empresa'];
            $_SESSION["ruc"]                    =$row_resultado['ruc'];
            $_SESSION["fecha_vencimiento"]      =$row_resultado['fecha_vencimiento'];
            $_SESSION["nombre_almacen"]         =$row_resultado['nombre_almacen'];
            $_SESSION["almacen"]         =$row_resultado['almacen'];
            $_SESSION["farmacia"]           =$row_resultado['farmacia'];
            $_SESSION["usabarras"]          =$row_resultado['usabarras'];
            $_SESSION["servidor_cpe"]       =$row_resultado['servidor_cpe'];

            $_SESSION["nombre_svr"]       =$row_resultado['nombre_svr'];
            $_SESSION["tipo_svr"]       =$row_resultado['tipo_svr'];
            $_SESSION["venta_por_mayor"]       =$row_resultado['venta_por_mayor'];

            $_SESSION["detalle"]       =$row_resultado['detalle'];
            $_SESSION["precio"]       =$row_resultado['precio'];


            $hoy                            = date('Y-m-d');
                if($row_resultado['fecha_vencimiento'] <= $hoy)
                {
                  echo '<script>

                    alert("Cuenta con fecha de vencimiento");

                  </script>';
                  session_destroy();
                }
                else
                {
                   
                  echo '<script> window.location ="'.base_url().'/inicio"; </script>';
                }

                
          }
          else
          {
             echo '<script>

                alert("Usuario o clave incorrectos o usuario inactivo");

              </script>';
        session_destroy();
          }
      }
}

 ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Base | Facturacion Electronica</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('<?=media()?>/images/bg1.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 30px;
            margin-top: 100px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            border: 4px solid white;
        }
        .login-container img {
            max-width: 70%; /* Ajuste del tamaño del logo */
            height: auto;
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .login-container img {
                max-width: 50%; /* Tamaño más pequeño del logo en pantallas pequeñas */

            }
            .login-container {
                padding: 20px;
                 margin-top: 100px;
            }
        }
        @media (max-width: 576px) {
            .login-container img {
                max-width: 50%; /* Tamaño más pequeño del logo en pantallas pequeñas */

            }
            .login-container {
                padding: 20px;
                margin-top: 120px;
            }
        }
        .login-container h2 {
            color: white;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .input-group {
            position: relative;
            margin-bottom: 15px;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 0px;
            background-color: rgba(255, 255, 255, 0);
             border-bottom: 4px solid white; /* Solo se muestra la línea inferior */
            color: black;
        }
        
        .input-group input:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.6);
        }
        .btn {
            width: 100%;
            padding: 12px;
            background-color: rgba(0, 123, 255, 0.8);
            border: none;
            color: white;
            border-radius: 8px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: rgba(0, 123, 255, 1);
        }
        .register-link {
            display: block;
            margin-top: 10px;
            color: white;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
         <form class="" method="POST">
            <img src="<?=media()?>/images/logo.png" alt="Logo del Sistema"> <!-- Cambia 'logo.png' por la ruta de tu logo -->
            <div class="input-group">
                <input type="text" id="ruc" name="ruc" placeholder="Ingresar RUC" required>
            </div>
            <div class="input-group">
                <input type="text" class="input-line" id="usuario" name="usuario" placeholder="Ingresar Usuario" required>
            </div>
            <div class="input-group">
                <input type="password" class="input-line" id="clave" name="clave" placeholder="Ingresar Clave" required>
            </div>
            <button type="submit" class="btn btn-success">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>



