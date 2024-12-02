<?php

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();

if($_POST['action'] == 'buscagrefact')
{
       $movkey = $_POST['refgre'];
       $idempresa = $_SESSION["id_empresa"];
       //echo $idventa;
        $query_cab = "SELECT * FROM vw_tbl_gre_cab WHERE movkey ='$movkey' AND idempresa= $idempresa";
        $resultado_cab = $connect->prepare($query_cab);
        $resultado_cab->execute();
        $row_cab = $resultado_cab->fetch(PDO::FETCH_ASSOC);

        $codigocliente = $row_cab['codcliente'];
        $query_cli = "SELECT * FROM tbl_contribuyente WHERE id_persona ='$codigocliente'";
        $resultado_cli = $connect->prepare($query_cli);
        $resultado_cli->execute();
        $row_cli = $resultado_cli->fetch(PDO::FETCH_ASSOC);

        $row_cab['direccionpro'] =$row_cli['direccion_persona'];
        $row_cab['nombrepro'] =$row_cli['nombre_persona'];

        echo json_encode($row_cab,JSON_UNESCAPED_UNICODE);


        exit();
}






///////////////////////////////////////////////////////////////////////////////////buscar clientes
if($_POST['action'] == 'searchCliente')
{
  $doc=$_POST['cliente'];

$query_articulos = "SELECT * FROM tbl_persona WHERE num_doc ='$doc'";
$resultado = $connect->prepare($query_articulos);
$resultado->execute();
$row_articulo = $resultado->fetch(PDO::FETCH_ASSOC);

echo json_encode($row_articulo);
exit;
}

///////////////////////////////////////////////////////////////////////////////////buscar series
if($_POST['action'] == 'searchSerie')
{
  $doc=$_POST['cliente'];
  $user = $_POST['usuario'];

$query_articulos = "SELECT * FROM vw_tbl_serie_usuario WHERE cod_doc ='$doc' AND usuario='$user'";
$resultado = $connect->prepare($query_articulos);
$resultado->execute();
$row_articulo = $resultado->fetch(PDO::FETCH_ASSOC);

echo json_encode($row_articulo);
exit;
}


/////////////////////////////////////////////////////////////////////////guardar venta
if($_POST['action']=='addVenta')
{
  $fecha             = $_POST['fecha'];
  $f                 = explode('-',$fecha);

	$vendedor          = $_POST['vendedor'];
  $cliente           = $_POST['cod_cliente'];
  $ruc               = $_POST['nit_cliente'];
  $nombre_cliente    = $_POST['proveedor'];
  $fecha_emision     = $f[2].'-'.$f[1].'-'.$f[0];
  $fecha_vencimiento = $f[2].'-'.$f[1].'-'.$f[0];
  $tipo_cpe          = $_POST['tip_cpe'];
  $serie_cpe         = $_POST['serie'];
  $num_cpe           = $_POST['numero'];
  $documento         = $_POST['tip_cpe'].'-'.$_POST['serie'].'-'.$_POST['numero'];
  $op_gravadas       = $_POST['op_g'];
  $op_exoneradas     = $_POST['op_e'];
  $op_inafectas      = $_POST['op_i'];
  $sub_total         = $_POST['subt'];
  $igv               = $_POST['igv'];
  $total             = $_POST['total'];

  $query=$connect->prepare("INSERT INTO tbl_factura_cab(documento,tipo_cpe,serie_cpe,numero_cpe,fecha_emision,fecha_vencimiento,id_cliente,nombre_cliente,op_gravadas,op_exoneradas,op_inafectas,subtotal,igv,total) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
  $resultado=$query->execute([$documento,$tipo_cpe,$serie_cpe,$num_cpe,$fecha_emision,$fecha_vencimiento,$cliente,$nombre_cliente,$op_gravadas,$op_exoneradas,$op_inafectas,$sub_total,$igv,$total]);
    $lastInsertId = $connect->lastInsertId();
//registro detalle compra


    for($i = 0; $i< count($_POST['idarticulo']); $i++)
    {
      $item                  = $_POST['itemarticulo'][$i];
      $idarticulo            = $_POST['idarticulo'][$i];
      $nomarticulo           = $_POST['nomarticulo'][$i];
      $factor                = $_POST['factor'][$i];
      $cantidad              = $_POST['cantidad'][$i];
      $cantidadu             = $_POST['cantidadu'][$i];
      $cantidad_total        = $factor*$cantidad + $cantidadu;
      $valor_unitario_total  = $_POST['valor_unitario'][$i];
      $igv_unitario          = $_POST['igv_unitario'][$i];
      $precio_venta          = $_POST['precio_venta'][$i];
      $precio_unitario       = $precio_venta - ($igv_unitario/$cantidad_total);
      $precio_venta_total    = $precio_venta*$cantidad_total;
      $afectacion            = $_POST['afectacion'][$i];
      $tipo_precio           ='01';
      $unidad                = 'NIU';
      $igv_total             = $precio_venta_total - $valor_unitario_total;
      $precio_compra            = $_POST['precio_compra'][$i];

          $query_afectacion = "SELECT * FROM tbl_tipo_tributo WHERE cod_tributo ='$afectacion'";
          $resultado_afectacion = $connect->prepare($query_afectacion);
          $resultado_afectacion->execute();
          $row_afectacion = $resultado_afectacion->fetch(PDO::FETCH_ASSOC);
          $ctt = $row_afectacion['codigo_tipo_tributo'];
          $nt  = $row_afectacion['nombre_tributo'];
          $nc  = $row_afectacion['nombre_codigo'];

      $insert_query_detalle =$connect->prepare("INSERT INTO tbl_factura_det(id_factura_cab,item,codigo,descripcion,cantidad_factor,factor,cantidad_unitario,cantidad,valor_unitario,precio_unitario,tipo_precio,igv,valor_total,importe_total,unidad,codigo_afectacion_alt,codigo_afectacion,nombre_afectacion,tipo_afectacion,precio_compra) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
      $resultado_detalle = $insert_query_detalle->execute([$lastInsertId,$item,$idarticulo,$nomarticulo,$cantidad,$factor,$cantidadu,$cantidad_total,$precio_unitario,$precio_venta,$tipo_precio,$igv_total,$valor_unitario_total,$precio_venta_total,$unidad,$afectacion,$ctt,$nt,$nc,$precio_compra]);



          // inserta stock
          $update_query_articulo = $connect->prepare("UPDATE tbl_articulo SET stock = stock - ? WHERE id_articulo = ?");
          $resultado_stock   = $update_query_articulo->execute([$cantidad,$idarticulo]);

          // inserta stock
          $update_query_serie = $connect->prepare("UPDATE tbl_series SET numero_cpe = numero_cpe + ? WHERE serie_cpe = ? and numero_cpe = ? and id_usuario = ?");
          $resultado_serie   = $update_query_serie->execute(['1',$serie_cpe,$num_cpe,$vendedor]);
    }


    if($update_query_articulo)
    {
      $msg = 'ok';
    }
    else
    {
      $msg = 'error';
    }

    echo $msg;

    exit;



}


/////////////////////////////////////////////////////////////////////////editar venta
if($_POST['action']=='editVenta')
{
  $id_factura        = $_POST['id_factura'];
  $fecha             = $_POST['fecha'];
  $f                 = explode('-',$fecha);

	$vendedor          = $_POST['vendedor'];
  $cliente           = $_POST['cod_cliente'];
  $ruc               = $_POST['nit_cliente'];
  $nombre_cliente    = $_POST['proveedor'];
  $fecha_emision     = $f[2].'-'.$f[1].'-'.$f[0];
  $fecha_vencimiento = $f[2].'-'.$f[1].'-'.$f[0];
  $tipo_cpe          = $_POST['tip_cpe'];
  $serie_cpe         = $_POST['serie'];
  $num_cpe           = $_POST['numero'];
  $documento         = $_POST['tip_cpe'].'-'.$_POST['serie'].'-'.$_POST['numero'];
  $op_gravadas       = $_POST['op_g'];
  $op_exoneradas     = $_POST['op_e'];
  $op_inafectas      = $_POST['op_i'];
  $sub_total         = $_POST['subt'];
  $igv               = $_POST['igv'];
  $total             = $_POST['total'];

  $query=$connect->prepare("UPDATE tbl_factura_cab SET op_gravadas=?,op_exoneradas=?,op_inafectas=?,subtotal=?,igv=?,total=? WHERE id_factura =?;");
  $resultado=$query->execute([$op_gravadas,$op_exoneradas,$op_inafectas,$sub_total,$igv,$total,$id_factura]);

$query_stk = "SELECT *  FROM tbl_factura_det WHERE id_factura_cab = '$id_factura'";
$resultado_stk=$connect->prepare($query_stk);
$resultado_stk->execute();
while($stk = $resultado_stk->fetch(PDO::FETCH_ASSOC) )
{
  // UPDATE ARTICULO
  $update_query_articulo = $connect->prepare("UPDATE tbl_articulo SET stock = stock + ? WHERE id_articulo = ?");
  $resultado_stock   = $update_query_articulo->execute([$stk['cantidad'],$stk['codigo']]);
}

$query_delete=$connect->prepare("DELETE FROM tbl_factura_det  WHERE id_factura_cab =?;");
$resultado_delete=$query_delete->execute([$id_factura]);

$j = 1;
    for($i = 0; $i< count($_POST['idarticulo']); $i++)
    {

      $item                  = $_POST['itemarticulo'][$i];
      $idarticulo            = $_POST['idarticulo'][$i];
      $nomarticulo           = $_POST['nomarticulo'][$i];
      $factor                = $_POST['factor'][$i];
      $cantidad              = $_POST['cantidad'][$i];
      $cantidadu             = $_POST['cantidadu'][$i];
      $cantidad_total        = $factor*$cantidad + $cantidadu;
      $valor_unitario_total  = $_POST['valor_unitario'][$i];
      $igv_unitario          = $_POST['igv_unitario'][$i];
      $precio_venta          = $_POST['precio_venta'][$i];
      $precio_unitario       = $precio_venta - ($igv_unitario/$cantidad_total);
      $precio_venta_total    = $precio_venta*$cantidad_total;
      $afectacion            = $_POST['afectacion'][$i];
      $tipo_precio           ='01';
      $unidad                = 'NIU';
      $igv_total             = $precio_venta_total - $valor_unitario_total;
      $precio_compra            = $_POST['precio_compra'][$i];

$query_afectacion = "SELECT * FROM tbl_tipo_tributo WHERE cod_tributo ='$afectacion'";
$resultado_afectacion = $connect->prepare($query_afectacion);
$resultado_afectacion->execute();
$row_afectacion = $resultado_afectacion->fetch(PDO::FETCH_ASSOC);
$ctt = $row_afectacion['codigo_tipo_tributo'];
$nt  = $row_afectacion['nombre_tributo'];
$nc  = $row_afectacion['nombre_codigo'];

      $insert_query_detalle =$connect->prepare("INSERT INTO tbl_factura_det(id_factura_cab,item,codigo,descripcion,cantidad_factor,factor,cantidad_unitario,cantidad,valor_unitario,precio_unitario,tipo_precio,igv,valor_total,importe_total,unidad,codigo_afectacion_alt,codigo_afectacion,nombre_afectacion,tipo_afectacion,precio_compra) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
      $resultado_detalle = $insert_query_detalle->execute([$id_factura,$j,$idarticulo,$nomarticulo,$cantidad,$factor,$cantidadu,$cantidad_total,$precio_unitario,$precio_venta,$tipo_precio,$igv_total,$valor_unitario_total,$precio_venta_total,$unidad,$afectacion,$ctt,$nt,$nc,$precio_compra]);



// inserta stock
$update_query_articulo = $connect->prepare("UPDATE tbl_articulo SET stock = stock - ? WHERE id_articulo = ?");
$resultado_stock   = $update_query_articulo->execute([$cantidad,$idarticulo]);
$j++;

}


    if($update_query_articulo)
    {
      $msg = 'ok';
    }
    else
    {
      $msg = 'error';
    }

    echo $msg;

    exit;



}

if($_POST['action']=='delete_venta')
{
  $id_factura = $_POST['delete_id'];

  $query_delete=$connect->prepare("UPDATE tbl_factura_cab set estado_cpe = ?  WHERE id_factura =?;");
  $resultado_delete=$query_delete->execute(['0',$id_factura]);

  if($resultado_delete)
  {
    $msg = 'ok';
  }
  else
  {
    $msg = 'error';
  }

  echo $msg;

  exit;



}


////////////////////////////aprobar venta

if($_POST['action']=='aprobar_venta')
{
  $id_factura = $_POST['aprobar_id'];

  $query_delete=$connect->prepare("UPDATE tbl_factura_cab set aprobacion = ?  WHERE id_factura =?;");
  $resultado_delete=$query_delete->execute(['1',$id_factura]);

  if($resultado_delete)
  {
    $msg = 'ok';
  }
  else
  {
    $msg = 'error';
  }

  echo $msg;

  exit;



}
//////////////////////////////////////////enviar cpe SUNAT /////////////////////////////////////////


if($_POST['action'] == 'sunat')
{

  $idfactura = $_POST['enviar_id'];
//********************DATOS DE EMISOR*********************//
$lista = $connect->prepare("SELECT * FROM tbl_configuracion WHERE id_empresa='1'");
$lista->execute();
$row=$lista->fetch(PDO::FETCH_ASSOC);
//echo $tipo_doc=$row['tipo_doc'];

$tipodoc         = $row['tipo_doc'];
$ruc             = $row['ruc'];
$razon           = $row['nombre_empresa'];
$comercial       = $row['nombre_comercial'];
$direccion       = $row['direccion'];
$pais            = $row['pais'];
$departamento    = $row['departamento'];
$provincia       = $row['provincia']; 
$distrito        = $row['distrito'];
$ubigeo          = $row['ubigeo'];
$usuario_sol     = $row['usuario_sol'];
$clave_sol       = $row['clave_sol'];



$emisor =   array(
      'tipodoc'   => $tipodoc,
      'ruc'       => $ruc, 
      'razon_social'  => $razon, 
      'nombre_comercial'  => $comercial, 
      'direccion'   => $direccion, 
      'pais'      => $pais, 
      'departamento'  => $departamento,//LAMBAYEQUE 
      'provincia'   => $provincia,//CHICLAYO 
      'distrito'    => $distrito, //CHICLAYO
      'ubigeo'    => $ubigeo, //CHICLAYO
      'usuario_sol' => $usuario_sol, //USUARIO SECUNDARIO EMISOR ELECTRONICO
      'clave_sol'   => $clave_sol //CLAVE DE USUARIO SECUNDARIO EMISOR ELECTRONICO
      );



$cliente = array(
      'tipodoc'   => '6',//6->ruc, 1-> dni 
      'ruc'     => '20480631286', 
      'razon_social'  => 'ASOCIACION CENTRO DE ENTRENAMIENTO EN TECNOLOGIAS DE INFORMACION - CETI', 
      'direccion'   => 'Cal. Francisco Cuneo-Pataz Nro. 270(Frente al Circulo Departamental de Emple)',
      'pais'      => 'PE'
      );  
//********************DATOS DE COMPROBANTE - CABECERA*********************//
$lista_cpe = $connect->prepare("SELECT * FROM tbl_factura_cab WHERE id_factura='$idfactura'");
$lista_cpe->execute();
$row_cpe=$lista_cpe->fetch(PDO::FETCH_ASSOC);

$comprobante =  array(
      'tipodoc'   => $row_cpe['tipo_cpe'], //01->FACTURA, 03->BOLETA, 07->NC, 08->ND
      'serie'     => $row_cpe['serie_cpe'],
      'correlativo' => $row_cpe['numero_cpe'],
      'fecha_emision' => $row_cpe['fecha_emision'],
      'moneda'    => $row_cpe['moneda'], //PEN->SOLES; USD->DOLARES
      'total_opgravadas'=> $row_cpe['op_gravadas'], //OP. GRAVADAS
      'total_opexoneradas'=>$row_cpe['op_exoneradas'],
      'total_opinafectas'=>$row_cpe['op_inafectas'],
      'igv'     => $row_cpe['igv'],
      'total'     => $row_cpe['total'],
      'total_texto' => ''
    );
//********************DATOS DE COMPROBANTE - DETALLE*********************//
$lista_cpe_det = $connect->prepare("SELECT * FROM tbl_factura_det WHERE id_factura_cab='$idfactura'");
$lista_cpe_det->execute();
$row_cpe_det=$lista_cpe_det->fetchAll(PDO::FETCH_ASSOC);


$detalle = $row_cpe_det;
      /*array(
        array(
          'item'        => 1,
          'codigo'      => '11',
          'descripcion'   => 'ACEITE CAPRI',
          'cantidad'      => 1,
          'valor_unitario'  => 50,
          'precio_unitario' => 59,
          'tipo_precio'   => "01", //ya incluye igv
          'igv'       => 9,
          'porcentaje_igv'  => 18, //de 0 a 100
          'valor_total'   => 50, 
          'importe_total'   => 59,
          'unidad'      => 'NIU',//unidad,
          'codigo_afectacion_alt' => '10', // Catálogo No. 07 - Anexo V
          'codigo_afectacion' => 1000,
          'nombre_afectacion' =>'IGV',
          'tipo_afectacion' => 'VAT' //GRAVADAS        
        ),
        array(
          'item'        => 2,
          'codigo'      => '22',
          'descripcion'   => 'LIBRO ABC',
          'cantidad'      => 1,
          'valor_unitario'  => 50,
          'precio_unitario' => 50,
          'tipo_precio'   => "01", //ya incluye igv
          'igv'       => 0,
          'porcentaje_igv'  => 18,
          'valor_total'   => 50,
          'importe_total'   => 50,
          'unidad'      => 'NIU',//unidad,
          'codigo_afectacion_alt' => '20', // Catálogo No. 07 - Anexo V
          'codigo_afectacion' => 9997,
          'nombre_afectacion' =>  'EXO',  //EXONERADOS
          'tipo_afectacion' => 'VAT'       
        ),
        array(
          'item'        => 3,
          'codigo'      => '33',
          'descripcion'   => 'TOMATE ABC',
          'cantidad'      => 1,
          'valor_unitario'  => 50,
          'precio_unitario' => 50,
          'tipo_precio'   => "01", //ya incluye igv
          'igv'       => 0,
          'porcentaje_igv'  => 18,
          'valor_total'   => 50,
          'importe_total'   => 50,
          'unidad'      => 'NIU',//unidad,
          'codigo_afectacion_alt' => '30', // Catálogo No. 07 - Anexo V
          'codigo_afectacion' => 9998,
          'nombre_afectacion' =>  'INA',  // INAFECTAS
          'tipo_afectacion' => 'FRE'  
        )               
      );*/

$op_gravadas = 0;
$op_inafectas = 0;
$op_exoneradas = 0;
$igv = 0;
$total = 0; 

/*foreach ($detalle as $k => $v) {
  if($v['codigo_afectacion_alt']=='10'){
    $op_gravadas = $op_gravadas + $v['valor_total'];
  }

  if($v['codigo_afectacion_alt']=='20'){
    $op_exoneradas = $op_exoneradas + $v['valor_total'];
  }

  if($v['codigo_afectacion_alt']=='30'){
    $op_inafectas = $op_inafectas + $v['valor_total'];
  }

  $igv = $igv + $v['igv'];
  $total = $total + $v['importe_total'];
}*/

$comprobante['total_opgravadas'] = $row_cpe['op_gravadas'];
$comprobante['total_opexoneradas'] = $row_cpe['op_exoneradas'];
$comprobante['total_opinafectas'] = $row_cpe['op_inafectas'];
$comprobante['igv'] = $row_cpe['igv'];
$comprobante['total'] = $row_cpe['total'];
$comprobante['total_texto'] = CantidadEnLetra($row_cpe['total']);

$query_cpe_cab=$connect->prepare("UPDATE  tbl_factura_cab SET total_texto=? WHERE id_factura = ?;");
$resultado_cpe_cab=$query_cpe_cab->execute([$comprobante['total_texto'],$row_cpe['id_factura']]);

$id_cpe = $row_cpe['id_factura'];

require_once("../../sunat/xml.php");

$xml = new GeneradorXML();

//RUC DEL EMISOR - TIPO DE COMPROBANTE - SERIE DEL DOCUMENTO - CORRELATIVO
//01-> FACTURA, 03-> BOLETA, 07-> NOTA DE CREDITO, 08-> NOTA DE DEBITO, 09->GUIA DE REMISION
$nombrexml = $emisor['ruc'].'-'.$comprobante['tipodoc'].'-'.$comprobante['serie'].'-'.$comprobante['correlativo'];

$ruta = "../../sunat/xml/".$nombrexml;
$xml->CrearXMLFactura($ruta, $emisor, $cliente, $comprobante, $detalle);

$xml_sunat ='1';


$query_cpe_xml=$connect->prepare("UPDATE  tbl_factura_cab SET xml_sunat=? WHERE id_factura = ?;");
$resultado_cpe_xml=$query_cpe_xml->execute([$xml_sunat,$id_cpe]);

//ENVIO DE COMPROBANTE A SUNAT

require_once("../../sunat/ApiFacturacion.php");

$objApi = new ApiFacturacion();

$objApi->EnviarComprobanteElectronico($emisor,$nombrexml,$connect,$id_cpe);

}



?>
