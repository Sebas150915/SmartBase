<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();

if($_POST['action'] == 'listarCompraCab')
{
       $idventa = $_POST['id'];
       //echo $idventa;
        $query_cab = "SELECT * FROM tbl_compra_cab WHERE id ='$idventa'";
        $resultado_cab = $connect->prepare($query_cab);
        $resultado_cab->execute();
        $row_cab = $resultado_cab->fetch(PDO::FETCH_ASSOC);
        echo json_encode($row_cab,JSON_UNESCAPED_UNICODE);


        exit();
}






        ////////////buscar series
if($_POST['action'] == 'searchDet')
{
  $det=$_POST['detraccion'];
  $cod = $_POST['cod'];
 

$query_detraccion = "SELECT * FROM tbl_por_det WHERE id ='$cod'";
        
$resultado = $connect->prepare($query_detraccion);
$resultado->execute();
$row_detraccion = $resultado->fetch(PDO::FETCH_ASSOC);

echo json_encode($row_detraccion);

exit;
}






//buscar persona

if($_POST['action'] == 'buscarPersona')
{
        if(!empty($_POST['cliente']))
    {
        $ndd=$_POST['cliente'];
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql="SELECT * FROM tbl_contribuyente WHERE num_doc like '$ndd'";
        $resultado=$connect->prepare($sql);
        $resultado->execute();
        $num_reg=$resultado->rowCount();
         $data='';

         if($num_reg>0)
         {
             $data=$resultado->fetch(PDO::FETCH_ASSOC);
         }


         else
         {
            $data =0;
         }

         echo json_encode($data,JSON_UNESCAPED_UNICODE);

    }
    exit;
}

//crear cliente
//####################################CREAR CLIENTE####################################////

if($_POST['action'] == 'addCliente')
{
    
    $dni         = $_POST['dni'];
    $tipo_doc    = $_POST['tipo_doc'];
    $razon       = $_POST['razon'];
    $direccion   = $_POST['direccion'];
    $distrito    = $_POST['distrito']; 
    $provincia   = $_POST['provincia']; 
    $departamento   = $_POST['departamento']; 
    $correo   = $_POST['correo'];
    $empresa  = $_POST['empresa'];

        $query_select = "SELECT * FROM tbl_contribuyente WHERE num_doc = '$dni'";
        $resultado_select=$connect->prepare($query_select);
        $resultado_select->execute();
        $num_reg_select=$resultado_select->rowCount();
        
        if($num_reg_select >= 1)
        {
          $msg = 'existe';
          echo $msg;
          exit;
        }

        else
        {
            $query=$connect->prepare("INSERT INTO tbl_contribuyente(nombre_persona,direccion_persona,distrito,provincia,departamento,tipo_doc,num_doc,correo,empresa) VALUES (?,?,?,?,?,?,?,?,?);");
            $resultado=$query->execute([$razon,$direccion,$distrito,$provincia,$departamento,$tipo_doc,$dni,$correo,$empresa]);

        $sql="SELECT * FROM tbl_contribuyente WHERE num_doc like '$dni'";
        $resultado=$connect->prepare($sql);
        $resultado->execute();
        $num_reg=$resultado->rowCount();
        $data=$resultado->fetch(PDO::FETCH_ASSOC);
        }
    


    if($resultado)
    {
        $msg='ok';
    }
    else
    {
        $msg='error1';
    }
     echo json_encode($data,JSON_UNESCAPED_UNICODE);


    exit;
}


?>
