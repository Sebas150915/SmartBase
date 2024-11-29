<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();

if($_POST['action'] == 'listarseparacion')
{
       $idseparacion = $_POST['id'];
       //echo $idventa;
        $query_cab = "SELECT * FROM tbl_alq_separacion WHERE id ='$idseparacion'";
        $resultado_cab = $connect->prepare($query_cab);
        $resultado_cab->execute();
        $row_cab = $resultado_cab->fetch(PDO::FETCH_ASSOC);


        $query_cli = "SELECT * FROM tbl_contribuyente WHERE id_persona ='$row_cab[idcliente]'";
        $resultado_cli = $connect->prepare($query_cli);
        $resultado_cli->execute();
        $row_cli = $resultado_cli->fetch(PDO::FETCH_ASSOC);

        $row_cab['direccionpro'] =$row_cli['direccion_persona'];
        $row_cab['nombrepro'] =$row_cli['nombre_persona'];

        echo json_encode($row_cab,JSON_UNESCAPED_UNICODE);


        exit();
}


if($_POST['action'] == 'listarCompraDet')
{
       $idventa = $_POST['id'];
       //echo $idventa;
       $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql="SELECT * FROM vw_tbl_compra_det WHERE idventa = $idventa";
        $resultado=$connect->prepare($sql);
        $resultado->execute();
        $num_reg=$resultado->rowCount();
        $detalletabla = '';
        $cont =1;
        foreach($resultado as $serie )
        {
                        
            $detalletabla .='
            <tr id="fila'.$cont.'">
             <td><button type="button" class="btn btn-danger" onclick="eliminar('.$cont.')"><i class="fa fa-trash"></i></button></td>
             <td>'.$cont.'</td>
             <td><input type="hidden" name="id_detalle[]" value="'.$serie['id'].'">

                 <input type="hidden" name="fecven[]" value="0000-00-00">
                 <input type="hidden" name="itemarticulo[]" value="'.$cont.'">
                 <input type="hidden" name="idarticulo[]" value="'.$serie['codigo'].'">
                 <input type="hidden" name="nomarticulo[]" value="'.$serie['descripcion'].'">'.$serie['descripcion'].'</td>
              <td><input type="text" min="1" class="form-control text-right" name="cantidad[]" id="cantidad[]" value="'.$serie['cantidad'].'" onkeyup="modificarSubtotales()" ></td>
              <td><input type="text" min="1" class="form-control text-right" name="precio_venta[]" id="precio_venta[]" value="'.$serie['precio_unitario'].'" onkeyup="modificarSubtotales()" ></td>
              <td><input type="text" min="1" class="form-control text-right" name="por1[]" id="por1[]" value="0" onkeyup="modificarPrecioVenta()" ></td>
              <td><input type="text" min="1" class="form-control text-right" name="precio1[]" id="precio1[]" value="'.$serie['precio_unitario'].'"></td>
              <td><input type="text" min="1" class="form-control text-right" name="por2[]" id="por2[]" value="0" onkeyup="modificarPrecioVenta()" ></td>
              <td><input type="text" min="1" class="form-control text-right" name="precio2[]" id="precio2[]" value="'.$serie['precio_unitario'].'" ></td>
              <td><span id="subtotal'.$cont.'" name="subtotal">'.$serie['valor_total'].'</span>
              <input type="hidden" id="afectacion'.$cont.'" name="afectacion[]" class="form-control" value="'.$serie['codigo_afectacion_alt'].'">
              <input type="hidden" id="afectacion'.$cont.'" name="factor[]" class="form-control" value="1"></td>
              </tr>';
             $cont++;
        }

        $arrayData['detalle'] = $detalletabla;

        echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);


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
