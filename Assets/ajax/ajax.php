<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();
////////////carga desde excel/////////////
if($_POST['action']=='cargar_excel_compra')
{
    
    function validateDate($date, $format = 'Y-m-d') 
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    $mensaje['respuesta']='';
    $empresa  = $_POST['empresa'];
    $vendedor = $_SESSION["id"];
    $file = $_FILES['dataVentas']['name'];
    move_uploaded_file($_FILES['dataVentas']['tmp_name'],'excel/compras_smart.xlsx');
    
    require_once('../plugins/phpexcel/Classes/PHPExcel.php');
    $inputFileType = PHPExcel_IOFactory::identify('excel/compras_smart.xlsx');
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load('excel/compras_smart.xlsx');
    $sheet = $objPHPExcel->getSheet(0); 
    $highestRow = $sheet->getHighestRow(); 
    $highestColumn = $sheet->getHighestColumn();
    $n=0;
    
    sleep(3);//retrasamos la petición 3 segundos
   
    $num=0;
    $k = 0;
    $op_gravadas=0;
    $op_exoneradas=0;
    $op_inafectas=0;
    $igv =0;
    
    for ($row = 2; $row <= $highestRow; $row++)
    { 
    $num++; 
    if($num>0)
    {

    //IMPORTAMOS LAS VENTAS
    $tipcomp=$sheet->getCell("A".$row)->getValue();
    if($tipcomp!='')
    {
    	
    $serie=$sheet->getCell("B".$row)->getValue();
    if($serie == '')
    {
        $mensaje['error'] = 'LA SERIE NO PUEDE ESTAR VACIA';
        $data = json_encode($mensaje,true);
       echo $data;
       exit();
    }
    $correlativo=$sheet->getCell("C".$row)->getValue();
    if($correlativo == '')
    {
        $mensaje['error'] = 'EL CORRELATIVO NO PUEDE ESTAR VACIO';
        $data = json_encode($mensaje,true);
       echo $data;
       exit();
    }
    $fecemision=$sheet->getCell("D".$row)->getValue();

    //$fecemision= date("d/m/Y",strtotime($fecemision));
    //echo 'la fecha es: '.$fecemision;
    if($fecemision == '')
    {
        $mensaje['error'] = 'LA FECHA DE EMISION NO PUEDE ESTAR VACIA';
        $data = json_encode($mensaje,true);
       echo $data;
       exit();
    }
    // Validar el formato de la fecha (YYYY-MM-DD)
    //$fecemision=validateDate($fecemision, 'Y-m-d');
    if (validateDate($fecemision, 'Y-m-d')) 
    {

    } 
    else 
    {
    $mensaje['error'] = 'FORMATO DE FECHA EMISION NO VALIDO:'. $fecemision;
        $data = json_encode($mensaje,true);
       echo $data;
       exit();

    }
    $fecvencimiento=$sheet->getCell("E".$row)->getValue();
    if($fecvencimiento == '')
    {
        $mensaje['error'] = 'LA FECHA DE VENCIMIENTO NO PUEDE ESTAR VACIA'.$fecvencimiento;
        $data = json_encode($mensaje,true);
       echo $data;
       exit();
    }
    //$fecvencimiento=date('Y-m-d', strtotime($fecvencimiento));
    // Validar el formato de la fecha (YYYY-MM-DD)
    if (validateDate($fecvencimiento, 'Y-m-d')) 
    {

    } 
    else 
    {
    $mensaje['error'] = 'FORMATO DE FECHA DE VENCIMIENTO NO VALIDO:'. $fecvencimiento;
        $data = json_encode($mensaje,true);
       echo $data;
       exit();

    }

    $tipcambio=$sheet->getCell("F".$row)->getValue();
    if($tipcambio==''){ $tipcambio='1'; }

    $oc=$sheet->getCell("G".$row)->getValue();

    $guiarem=$sheet->getCell("H".$row)->getValue();
    if($guiarem==''){$guiarem=' ';}
    $condicion=$sheet->getCell("I".$row)->getValue();
    if($condicion == '')
    {
        $mensaje['error'] = 'LA CONDICION NO PUEDE ESTAR VACIA';
        $data = json_encode($mensaje,true);
       echo $data;
       exit();
    }

    $moneda=$sheet->getCell("J".$row)->getValue();

    if($moneda == '')
    {
        $mensaje['error'] = 'LA MONEDA NO PUEDE ESTAR VACIA';
        $data = json_encode($mensaje,true);
       echo $data;
       exit();
    }


    if($moneda == 'S'){$moneda = 'PEN';}
    if($moneda == 'D'){$moneda = 'USD';}
    //echo $moneda;
    $ruccliente=$sheet->getCell("K".$row)->getValue();
    if($ruccliente == '')
    {
        $mensaje['error'] = 'EL RUC DEL CLIENTE NO PUEDE ESTAR VACIO';
        $data = json_encode($mensaje,true);
       echo $data;
       exit();
    }

    $query_cliente = "SELECT * FROM tbl_contribuyente WHERE num_doc = $ruccliente AND empresa=$empresa";
    $resultado_cliente = $connect->prepare($query_cliente);
    $resultado_cliente->execute();
    $row_cliente = $resultado_cliente->fetch(PDO::FETCH_ASSOC);
    $id_ruc = $row_cliente['id_persona'];
    if($id_ruc == '')
    {
        $mensaje['error'] = 'EL RUC INGRESADO NO EXISTE, DEBE CARGAR PRIMERO EL RUC DEL PROVEEDOR: '.$ruccliente;
        $data = json_encode($mensaje,true);
       echo $data;
       exit();
    }


    $nomcliente=$sheet->getCell("L".$row)->getValue();
    if($nomcliente == '')
    {
        $mensaje['error'] = 'EL NOMBRE DEL PROVEEDOR NO PUEDE ESTAR VACIO';
        $data = json_encode($mensaje,true);
       echo $data;
       exit();
    }

    $pordet=$sheet->getCell("M".$row)->getValue();
    if($pordet==''){ $pordet='0'; }

    $coddet=$sheet->getCell("N".$row)->getValue();
    if($coddet==''){ $coddet=' '; }

    $impdet=$sheet->getCell("O".$row)->getValue();
    if($impdet==''){ $impdet=' '; }

    $tipdocref=$sheet->getCell("P".$row)->getValue();
    if($tipdocref==''){ $tipdocref=' '; }

    $serdocref=$sheet->getCell("Q".$row)->getValue();
    if($serdocref==''){ $serdocref=' '; }

    $numdocref=$sheet->getCell("R".$row)->getValue();
    if($numdocref==''){ $numdocref=' '; }

    $codmotivo=$sheet->getCell("S".$row)->getValue();
    if($codmotivo=='')
    { 
        $codmotivo=''; 
        $des_motivo = '';
    }
    else
    {
    $query_ncd = "SELECT * FROM tbl_not_cre_deb WHERE codigo = '$codmotivo'";
    $resultado_ncd = $connect->prepare($query_ncd);
    $resultado_ncd->execute();
    $row_ncd = $resultado_ncd->fetch(PDO::FETCH_ASSOC);
    //echo $query_ncd;
    $des_motivo = $row_ncd['descripcion'];
    }


    $codproducto=$sheet->getCell("T".$row)->getValue();
    /*if($codproducto == '')
    {
        $mensaje['error'] = 'EL CODIGO DEL PRODUCTO NO PUEDE ESTAR VACIO';
        $data = json_encode($mensaje,true);
       echo $data;
       exit();
    }*/

    $nomproducto=$sheet->getCell("U".$row)->getValue();
    if($nomproducto == '')
    {
        $mensaje['error'] = 'EL NOMBRE DEL PRODUCTO NO PUEDE ESTAR VACIO';
        $data = json_encode($mensaje,true);
       echo $data;
       exit();
    }

    $query_prd = "SELECT * FROM tbl_productos WHERE empresa = $empresa AND nombre LIKE '%$nomproducto%'";
    $resultado_prd=$connect->prepare($query_prd);
    $resultado_prd->execute(); 
    $row_prd = $resultado_prd->fetch(PDO::FETCH_ASSOC);
    $num_reg_prd=$resultado_prd->rowCount();

    $codproducto = $row_prd['id'];
    if($codproducto == '')
    {
        $mensaje['error'] = 'EL CODIGO DEL PRODUCTO NO PUEDE ESTAR VACIO '.$nomproducto;
        $data = json_encode($mensaje,true);
       echo $data;
       exit();
    }

    $descripcion=$sheet->getCell("V".$row)->getValue();
    if($descripcion==''){ $descripcion=' '; }

    $cantidad=$sheet->getCell("W".$row)->getValue();
    if($cantidad == '')
    {
        $mensaje['error'] = 'LA CANTIDAD NO PUEDE ESTAR VACIO';
        $data = json_encode($mensaje,true);
       echo $data;
       exit();
    }

    $afectacion=$sheet->getCell("X".$row)->getValue();
    if($afectacion == '')
    {
        $mensaje['error'] = 'LA AFECTACION NO PUEDE ESTAR VACIO';
        $data = json_encode($mensaje,true);
       echo $data;
       exit();
    }

    $porigv=$sheet->getCell("Y".$row)->getValue();
    if($porigv == '')
    {
        $mensaje['error'] = 'EL PORCENTAJE DE IGV NO PUEDE ESTAR VACIO';
        $data = json_encode($mensaje,true);
       echo $data;
       exit();
    }

    $precio=$sheet->getCell("Z".$row)->getValue();
    if($precio == '')
    {
        $mensaje['error'] = 'PRECIO NO PUEDE ESTAR VACIO';
        $data = json_encode($mensaje,true);
       echo $data;
       exit();
    }


    $costo=$sheet->getCell("AA".$row)->getValue();
    if($costo==''){ $costo='0'; }

    $obs=$sheet->getCell("AB".$row)->getValue();
    if($obs==''){ $obs=' '; }

    $feestado=$sheet->getCell("AC".$row)->getValue();
    if($feestado==''){ $costo='0'; }

    $cuotas ='1';


    $codalm=$sheet->getCell("AD".$row)->getValue();
    if($codalm == '')
    {
        $mensaje['error'] = 'EL CODIGO DEL ALMACEN NO PUEDE ESTAR VACIO';
        $data = json_encode($mensaje,true);
       echo $data;
       exit();
    }

    $query_vta = "SELECT * FROM tbl_compra_cab WHERE idempresa = $empresa AND tipocomp = '$tipcomp' AND serie = '$serie' AND correlativo = $correlativo ";
    $resultado_vta=$connect->prepare($query_vta);
    $resultado_vta->execute(); 
    $row_vta = $resultado_vta->fetch(PDO::FETCH_ASSOC);
    $num_reg_vta=$resultado_vta->rowCount();
    //$des_motivo = $row_ncd['descripcion'];
    //echo $query_vta;

    if($impdet==' '){$impdet=0;}
    if($numdocref==' '){$numdocref=0;}

        if($num_reg_vta == 0)
        {
            $hora = date('h:i:s');
            $item =1;
            $query=$connect->prepare("INSERT INTO tbl_compra_cab(idempresa,tipocomp,serie,correlativo,fecha_emision,fecha_vencimiento,condicion_venta,op_gravadas,op_exoneradas,op_inafectas,igv,total,codcliente,vendedor,obs,cuotas_credito,hora_emision,idcliente,por_det,cod_det,imp_det,guia_remision,orden_compra,tipocomp_ref,serie_ref,correlativo_ref,cod_motivo,des_motivo,feestado,idalmacen) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
            $resultado=$query->execute([$empresa,$tipcomp,$serie,$correlativo,$fecemision,$fecvencimiento,$condicion,$op_gravadas,$op_exoneradas,$op_inafectas,$igv_total,$total,$ruccliente, $vendedor,$obs,$cuotas,$hora,$id_ruc,$pordet,$coddet,$impdet,$guiarem,$oc,$tipdocref,$serdocref,$numdocref,$codmotivo,$des_motivo,$feestado,$codalm]);
            
            $lastInsertId = $connect->lastInsertId();
            $k++;
            $op_gravadas=0;
            $op_exoneradas=0;
            $op_inafectas=0;
            $igv =0;
            $total=0;
        }
        else
        {
            $lastInsertId = $row_vta['id'];
            $k++;
            $item++;
        }
        $item                   = $item;
        $idarticulo             = $codproducto;
        $cantidad_total         = $cantidad;
        $porigv                 = $porigv/100;
        $precio_venta_unitario  = $precio/(1+$porigv);
        $precio_venta           = $precio;
        
        
        $valor_total            = $cantidad_total*$precio_venta_unitario;
        $importe_total          = $cantidad_total*$precio_venta;
        $igv_total              = $valor_total*($porigv);
        $costo                  = $costo;
        $cantidad               = $cantidad;
        $factor                 = 1;
        $cantidadu              = 0;
        $mxmn                   = 'MIN';
        $nomarticulo            = $nomproducto;
        
        $insert_query_detalle =$connect->prepare("INSERT INTO tbl_compra_det(idventa,item,idproducto,cantidad,valor_unitario,precio_unitario,igv,porcentaje_igv,valor_total,importe_total,costo,cantidad_factor,factor,cantidad_unitario,mxmn,nombre_producto) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $resultado_detalle = $insert_query_detalle->execute([$lastInsertId,$item,$idarticulo,$cantidad_total,$precio_venta_unitario,$precio_venta,$igv_total,18,$valor_total,$importe_total,$costo,$cantidad,$factor,$cantidadu,$mxmn,$nomarticulo]);
        
       
        if($afectacion == '10')
        {
           $op_gravadas=($valor_total) + $op_gravadas;
           $igv = $op_gravadas*0.18 + $igv;
        
        }
        else if($afectacion == '20')
        {
            $op_exoneradas=$valor_total  + $op_exoneradas;
        
        }
        else if($afectacion == '30')
        {
           $op_inafectas=$valor_total  + $op_inafectas;
         
        }
        $total = $op_gravadas + $igv + $op_inafectas + $op_exoneradas;
        $query=$connect->prepare("UPDATE tbl_compra_cab SET op_gravadas=?,op_exoneradas=?,op_inafectas=?,igv=?,total=? WHERE id = ?");
    	$resultado = $query->execute([$op_gravadas,$op_exoneradas,$op_inafectas,$igv,$total,$lastInsertId]);
    	//sleep(1);
       
    }	

    }
    $mensaje['respuesta'] = 'REGISTROS PROCESADOS : '.$num.' EN TOTAL';
    $mensaje['procesados']  = 'SE CARGARON        : '.$k.'   EN TOTAL';
    $mensaje['noprocesados']  = 'NO SE CARGARON     : '.($num - $k).'   EN TOTAL';
    }
        
     
       $data = json_encode($mensaje,true);

       echo $data;

       exit();

}

////////////carga desde excel/////////////
if($_POST['action']=='cargar_excel_venta')
{
    
    function validateDate($date, $format = 'Y-m-d') 
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    $mensaje['respuesta']='';
    $empresa  = $_POST['empresa'];
    $vendedor = $_SESSION["id"];
    $file = $_FILES['dataVentas']['name'];
    move_uploaded_file($_FILES['dataVentas']['tmp_name'],'excel/ventas_smart.xlsx');
    
    require_once('../plugins/phpexcel/Classes/PHPExcel.php');
    $inputFileType = PHPExcel_IOFactory::identify('excel/ventas_smart.xlsx');
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load('excel/ventas_smart.xlsx');
    $sheet = $objPHPExcel->getSheet(0); 
    $highestRow = $sheet->getHighestRow(); 
    $highestColumn = $sheet->getHighestColumn();
    $n=0;
    
    sleep(3);//retrasamos la petición 3 segundos
   
    $num=0;
    $k = 0;
    $op_gravadas=0;
    $op_exoneradas=0;
    $op_inafectas=0;
    $igv =0;
    
    for ($row = 2; $row <= $highestRow; $row++)
    { 
    $num++; 
    if($num>0)
    {

    //IMPORTAMOS LAS VENTAS
$tipcomp=$sheet->getCell("A".$row)->getValue();
if($tipcomp!='')
{
	
$serie=$sheet->getCell("B".$row)->getValue();
if($serie == '')
{
    $mensaje['error'] = 'LA SERIE NO PUEDE ESTAR VACIA';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}
$correlativo=$sheet->getCell("C".$row)->getValue();
if($correlativo == '')
{
    $mensaje['error'] = 'EL CORRELATIVO NO PUEDE ESTAR VACIO';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}
$fecemision=$sheet->getCell("D".$row)->getValue();

//$fecemision= date("d/m/Y",strtotime($fecemision));
//echo 'la fecha es: '.$fecemision;
if($fecemision == '')
{
    $mensaje['error'] = 'LA FECHA DE EMISION NO PUEDE ESTAR VACIA';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}
// Validar el formato de la fecha (YYYY-MM-DD)
//$fecemision=validateDate($fecemision, 'Y-m-d');
if (validateDate($fecemision, 'Y-m-d')) 
{

} 
else 
{
$mensaje['error'] = 'FORMATO DE FECHA EMISION NO VALIDO:'. $fecemision;
    $data = json_encode($mensaje,true);
   echo $data;
   exit();

}
$fecvencimiento=$sheet->getCell("E".$row)->getValue();
if($fecvencimiento == '')
{
    $mensaje['error'] = 'LA FECHA DE VENCIMIENTO NO PUEDE ESTAR VACIA'.$fecvencimiento;
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}
//$fecvencimiento=date('Y-m-d', strtotime($fecvencimiento));
// Validar el formato de la fecha (YYYY-MM-DD)
if (validateDate($fecvencimiento, 'Y-m-d')) 
{

} 
else 
{
$mensaje['error'] = 'FORMATO DE FECHA DE VENCIMIENTO NO VALIDO:'. $fecvencimiento;
    $data = json_encode($mensaje,true);
   echo $data;
   exit();

}

$tipcambio=$sheet->getCell("F".$row)->getValue();
if($tipcambio==''){ $tipcambio='1'; }

$oc=$sheet->getCell("G".$row)->getValue();

$guiarem=$sheet->getCell("H".$row)->getValue();
if($guiarem==''){$guiarem=' ';}
$condicion=$sheet->getCell("I".$row)->getValue();
if($condicion == '')
{
    $mensaje['error'] = 'LA CONDICION NO PUEDE ESTAR VACIA';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}

$moneda=$sheet->getCell("J".$row)->getValue();

if($moneda == '')
{
    $mensaje['error'] = 'LA MONEDA NO PUEDE ESTAR VACIA';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}


if($moneda == 'S'){$moneda = 'PEN';}
if($moneda == 'D'){$moneda = 'USD';}
//echo $moneda;
$ruccliente=$sheet->getCell("K".$row)->getValue();
if($ruccliente == '')
{
    $mensaje['error'] = 'EL RUC DEL CLIENTE NO PUEDE ESTAR VACIO';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}

$query_cliente = "SELECT * FROM tbl_contribuyente WHERE num_doc = $ruccliente AND empresa=$empresa";
$resultado_cliente = $connect->prepare($query_cliente);
$resultado_cliente->execute();
$row_cliente = $resultado_cliente->fetch(PDO::FETCH_ASSOC);
$id_ruc = $row_cliente['id_persona'];
if($id_ruc == '')
{
    $mensaje['error'] = 'EL RUC INGRESADO NO EXISTE, DEBE CARGAR PRIMERO EL RUC DEL CLIENTE: '.$ruccliente;
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}


$nomcliente=$sheet->getCell("L".$row)->getValue();
if($nomcliente == '')
{
    $mensaje['error'] = 'EL NOMBRE DEL CLIENTE NO PUEDE ESTAR VACIO';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}

$pordet=$sheet->getCell("M".$row)->getValue();
if($pordet==''){ $pordet='0'; }

$coddet=$sheet->getCell("N".$row)->getValue();
if($coddet==''){ $coddet=' '; }

$impdet=$sheet->getCell("O".$row)->getValue();
if($impdet==''){ $impdet=' '; }

$tipdocref=$sheet->getCell("P".$row)->getValue();
if($tipdocref==''){ $tipdocref=' '; }

$serdocref=$sheet->getCell("Q".$row)->getValue();
if($serdocref==''){ $serdocref=' '; }

$numdocref=$sheet->getCell("R".$row)->getValue();
if($numdocref==''){ $numdocref=' '; }

$codmotivo=$sheet->getCell("S".$row)->getValue();
if($codmotivo=='')
{ 
    $codmotivo=''; 
    $des_motivo = '';
}
else
{
$query_ncd = "SELECT * FROM tbl_not_cre_deb WHERE codigo = '$codmotivo'";
$resultado_ncd = $connect->prepare($query_ncd);
$resultado_ncd->execute();
$row_ncd = $resultado_ncd->fetch(PDO::FETCH_ASSOC);
//echo $query_ncd;
$des_motivo = $row_ncd['descripcion'];
}


$codproducto=$sheet->getCell("T".$row)->getValue();
/*if($codproducto == '')
{
    $mensaje['error'] = 'EL CODIGO DEL PRODUCTO NO PUEDE ESTAR VACIO';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}*/

$nomproducto=$sheet->getCell("U".$row)->getValue();
if($nomproducto == '')
{
    $mensaje['error'] = 'EL NOMBRE DEL PRODUCTO NO PUEDE ESTAR VACIO';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}

$query_prd = "SELECT * FROM tbl_productos WHERE empresa = $empresa AND nombre LIKE '$nomproducto%'";
$resultado_prd=$connect->prepare($query_prd);
$resultado_prd->execute(); 
$row_prd = $resultado_prd->fetch(PDO::FETCH_ASSOC);
$num_reg_prd=$resultado_prd->rowCount();

$codproducto = $row_prd['id'];
if($codproducto == '')
{
    $mensaje['error'] = 'EL CODIGO DEL PRODUCTO NO PUEDE ESTAR VACIO '.$nomproducto;
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}

$descripcion=$sheet->getCell("V".$row)->getValue();
if($descripcion==''){ $descripcion=' '; }

$cantidad=$sheet->getCell("W".$row)->getValue();
if($cantidad == '')
{
    $mensaje['error'] = 'LA CANTIDAD NO PUEDE ESTAR VACIO';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}

$afectacion=$sheet->getCell("X".$row)->getValue();
if($afectacion == '')
{
    $mensaje['error'] = 'LA AFECTACION NO PUEDE ESTAR VACIO';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}

$porigv=$sheet->getCell("Y".$row)->getValue();
if($porigv == '')
{
    $mensaje['error'] = 'EL PORCENTAJE DE IGV NO PUEDE ESTAR VACIO';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}

$precio=$sheet->getCell("Z".$row)->getValue();
if($precio == '')
{
    $mensaje['error'] = 'PRECIO NO PUEDE ESTAR VACIO';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}


$costo=$sheet->getCell("AA".$row)->getValue();
if($costo==''){ $costo='0'; }

$obs=$sheet->getCell("AB".$row)->getValue();
if($obs==''){ $obs=' '; }

$feestado=$sheet->getCell("AC".$row)->getValue();
if($feestado==''){ $costo='0'; }

$cuotas ='1';


$query_vta = "SELECT * FROM tbl_venta_cab WHERE idempresa = $empresa AND tipocomp = '$tipcomp' AND serie = '$serie' AND correlativo = $correlativo ";
$resultado_vta=$connect->prepare($query_vta);
$resultado_vta->execute(); 
$row_vta = $resultado_vta->fetch(PDO::FETCH_ASSOC);
$num_reg_vta=$resultado_vta->rowCount();
//$des_motivo = $row_ncd['descripcion'];
//echo $query_vta;

if($impdet==' '){$impdet=0;}
if($numdocref==' '){$numdocref=0;}

    if($num_reg_vta == 0)
    {
        $hora = date('h:i:s');
        $item =1;
        $query=$connect->prepare("INSERT INTO tbl_venta_cab(idempresa,tipocomp,serie,correlativo,fecha_emision,fecha_vencimiento,condicion_venta,op_gravadas,op_exoneradas,op_inafectas,igv,total,codcliente,vendedor,obs,cuotas_credito,hora_emision,idcliente,por_det,cod_det,imp_det,guia_remision,orden_compra,tipocomp_ref,serie_ref,correlativo_ref,cod_motivo,des_motivo,feestado) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
        $resultado=$query->execute([$empresa,$tipcomp,$serie,$correlativo,$fecemision,$fecvencimiento,$condicion,$op_gravadas,$op_exoneradas,$op_inafectas,$igv_total,$total,$ruccliente, $vendedor,$obs,$cuotas,$hora,$id_ruc,$pordet,$coddet,$impdet,$guiarem,$oc,$tipdocref,$serdocref,$numdocref,$codmotivo,$des_motivo,$feestado]);
        
        $lastInsertId = $connect->lastInsertId();
        $k++;
        $op_gravadas=0;
        $op_exoneradas=0;
        $op_inafectas=0;
        $igv =0;
        $total=0;
    }
    else
    {
        $lastInsertId = $row_vta['id'];
        $k++;
        $item++;
    }
    $item                   = $item;
    $idarticulo             = $codproducto;
    $cantidad_total         = $cantidad;
    $porigv                 = $porigv/100;
    $precio_venta_unitario  = $precio/(1+$porigv);
    $precio_venta           = $precio;
    
    
    $valor_total            = $cantidad_total*$precio_venta_unitario;
    $importe_total          = $cantidad_total*$precio_venta;
    $igv_total              = $valor_total*($porigv);
    $costo                  = $costo;
    $cantidad               = $cantidad;
    $factor                 = 1;
    $cantidadu              = 0;
    $mxmn                   = 'MIN';
    $nomarticulo            = $nomproducto;
    
    $insert_query_detalle =$connect->prepare("INSERT INTO tbl_venta_det(idventa,item,idproducto,cantidad,valor_unitario,precio_unitario,igv,porcentaje_igv,valor_total,importe_total,costo,cantidad_factor,factor,cantidad_unitario,mxmn,nombre_producto) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $resultado_detalle = $insert_query_detalle->execute([$lastInsertId,$item,$idarticulo,$cantidad_total,$precio_venta_unitario,$precio_venta,$igv_total,18,$valor_total,$importe_total,$costo,$cantidad,$factor,$cantidadu,$mxmn,$nomarticulo]);
    
   
    if($afectacion == '10')
    {
       $op_gravadas=($valor_total) + $op_gravadas;
       $igv = $op_gravadas*0.18 + $igv;
    
    }
    else if($afectacion == '20')
    {
        $op_exoneradas=$valor_total  + $op_exoneradas;
    
    }
    else if($afectacion == '30')
    {
       $op_inafectas=$valor_total  + $op_inafectas;
     
    }
    $total = $op_gravadas + $igv + $op_inafectas + $op_exoneradas;
    $query=$connect->prepare("UPDATE tbl_venta_cab SET op_gravadas=?,op_exoneradas=?,op_inafectas=?,igv=?,total=? WHERE id = ?");
	$resultado = $query->execute([$op_gravadas,$op_exoneradas,$op_inafectas,$igv,$total,$lastInsertId]);
	//sleep(1);
   
}	

}
$mensaje['respuesta'] = 'REGISTROS PROCESADOS : '.$num.' EN TOTAL';
$mensaje['procesados']  = 'SE CARGARON        : '.$k.'   EN TOTAL';
$mensaje['noprocesados']  = 'NO SE CARGARON     : '.($num - $k).'   EN TOTAL';
}
    
 
   $data = json_encode($mensaje,true);

   echo $data;

   exit();

}
////////////llamar datos mediante un json para editar pelicula/////////////
if($_POST['action']=='buscar_almacenx')
{
  $productos = "SELECT * FROM tbl_almacen WHERE id=$_POST[id]";
   $resultado_productos  = $connect->prepare($productos);
   $resultado_productos->execute();
   $row_productos = $resultado_productos->fetch(PDO::FETCH_ASSOC);

   $data = json_encode($row_productos,true);

   echo $data;

   exit();

}

/////////////////////////datos de empresa //////////////////////////////////
if($_POST['action'] == 'datosEmpresa')
{       
      

            $imagen_nueva=$_FILES['update_logo']['name']; 
            $imagen_antigua = $_POST['last_logo'];

            if(empty($imagen_nueva))
            {
                $imagen = $imagen_antigua;
            }
            else
            {
                 move_uploaded_file($_FILES['update_logo']['tmp_name'],'../images/'.$imagen_nueva);
                 $imagen =$imagen_nueva;
            }

            $certificadon = $_FILES['certificadon']['name']; 
            $certificadon = $certificadon;

            $certificado = $_POST['certificado'];

            if(empty($certificadon))
            {
                $cert = $certificado;
            }
            else
            {
                $cert =$certificadon;

            }          
        
     

      $insert = $connect->prepare("UPDATE tbl_empresas SET ruc=?,razon_social=?,direccion=?,distrito=?,provincia=?,departamento=?,ubigeo=?,correo=?,usuario_sol=?,clave_sol=?,certificado=?,clave_certificado=?,cta_igv_venta=?,cta_igv_compra=?,cta_pagar_soles=?,cta_pagar_dolar=?,cta_cobrar_soles=?,cta_cobrar_dolar=?,origen_venta=?,origen_cobranzas=?,origen_compra=?,origen_pagos=?,por_igv=?,cta_detracciones=?,logo=?,comentario=?,telefono=?, celular=?,venta_por_mayor=?,nombre_comercial=?,top1=?,top2=?,cta_det_ventas=?,cta_det_compras=?,origen_rh=?,origen_pago_rh=?,cta_pagar_soles_rh=?,cta_pagar_dolar_rh=?,cta_retencion_4=?,por_ret_4=?,cta_percepcion=?,por_imp_rta=?,cta_retenciones=?,correo_envio=?,clave_correo=?,smtp=?,puerto=? WHERE id_empresa=?");

    $resultado = $insert->execute([$_POST['ruc'],$_POST['razon'],$_POST['direccion'],$_POST['distrito'],$_POST['provincia'],$_POST['departamento'],$_POST['ubigeo'],$_POST['correo'],$_POST['usuario_sol'],$_POST['clave_sol'],$cert,$_POST['clave_certificado'],$_POST['cta_igv_venta'],$_POST['cta_igv_compra'],$_POST['cta_pagar_soles'],$_POST['cta_pagar_dolar'],$_POST['cta_cobrar_soles'],$_POST['cta_cobrar_dolar'],$_POST['origen_venta'],$_POST['origen_cobranzas'],$_POST['origen_compra'],$_POST['origen_pagos'],$_POST['por_igv'],$_POST['detraccion'],$imagen,$_POST['comentario'],$_POST['update_telefono'],$_POST['update_celular'],$_POST['update_venta_mayor'],$_POST['nombre_comercial'],$_POST['top1'],$_POST['top2'],$_POST['cta_det_ventas'],$_POST['cta_det_compras'],$_POST['origen_rh'],$_POST['origen_pago_rh'],$_POST['cta_pagar_soles_rh'],$_POST['cta_pagar_dolar_rh'],$_POST['cta_retencion_4'],$_POST['por_ret_4'],$_POST['cta_percepcion'],$_POST['por_imp_rta'],$_POST['cta_retenciones'],$_POST['correo_envio'],$_POST['clave_correo'],$_POST['smtp'],$_POST['puerto'],$_POST['id']]);


    if($resultado)
    {
        $mensaje = 'exito';
    }
    else
    {
        $mensaje = 'error';
    }

    echo $mensaje;

    exit;
}




/////////////////////////series //////////////////////////////////
if($_POST['action'] == 'delete_almacen')
{
    $id          = $_POST['delete_id_almacen'];
           
    $delete = $connect->prepare("UPDATE tbl_almacen SET estado = ? WHERE id = ?");
    $resultado = $delete->execute(['0',$id]);


    if($resultado)
    {
        $mensaje = 'exito';
    }
    else
    {
        $mensaje = 'error';
    }

    echo $mensaje;

    exit;
}


if($_POST['action'] == 'editar_almacen')
{
    $id = $_POST['update_id_almacen'];
    $nombre   = $_POST['update_nombre'];
    $direccion   = $_POST['update_direccion'];

     $insert = $connect->prepare("UPDATE tbl_almacen SET nombre=?,direccion=?,ubigeo=?,codlocal=? WHERE id=?");
    $resultado = $insert->execute([$nombre,$direccion,$_POST['update_ubigeo'],$_POST['update_codlocal'],$id]);


    if($resultado)
    {
        $mensaje = 'exito';
    }
    else
    {
        $mensaje = 'error';
    }

    echo $mensaje;

    exit;


}




if($_POST['action'] == 'nuevo_almacen')
{
    $empresa = $_POST['empresa'];
    $nombre   = $_POST['nombre'];
    $direccion   = $_POST['direccion'];

     $insert = $connect->prepare("INSERT INTO tbl_almacen(nombre,direccion,empresa,ubigeo,codlocal) VALUES(?,?,?,?,?)");
    $resultado = $insert->execute([$nombre,$direccion,$empresa,$_POST['ubigeo'],$_POST['codlocal']]);


    if($resultado)
    {
        $mensaje = 'exito';
    }
    else
    {
        $mensaje = 'error';
    }

    echo $mensaje;

    exit;


}



////////////////////////////////////serie - usuario////////////////////

if($_POST['action'] == 'nuevo_serie_usuario')
{
    $id_usuario = $_POST['usuario'];
    $id_serie   = $_POST['serie_nueva'];

     $insert = $connect->prepare("INSERT INTO tbl_series_usuarios(id_usuario,id_serie) VALUES(?,?)");
    $resultado = $insert->execute([$id_usuario,$id_serie]);


    $update = $connect->prepare("UPDATE tbl_series SET flat='1' WHERE id=?");
    $resultado_update = $update->execute([$id_serie]);


    if($resultado)
    {
        $mensaje = 'exito';
    }
    else
    {
        $mensaje = 'error';
    }

    echo $mensaje;

    exit;


}

if($_POST['action'] == 'delete_serie_usuario')
{
    $id = $_POST['delete_id_serie_usuario'];

     $delete = $connect->prepare("DELETE FROM tbl_series_usuarios WHERE id = ?");
    $resultado = $delete->execute([$id]);


    if($resultado)
    {
        $mensaje = 'exito';
    }
    else
    {
        $mensaje = 'error';
    }

    echo $mensaje;

    exit;


}


/////////////////////////series //////////////////////////////////
if($_POST['action'] == 'delete_serie')
{
    $id          = $_POST['delete_id_serie'];
           
    $delete = $connect->prepare("UPDATE tbl_series SET estado = ? WHERE id = ?");
    $resultado = $delete->execute(['0',$id]);


    if($resultado)
    {
        $mensaje = 'exito';
    }
    else
    {
        $mensaje = 'error';
    }

    echo $mensaje;

    exit;
}


if($_POST['action'] == 'update_serie')
{
    $empresa     = $_POST['update_empresa'];
    $id          = $_POST['update_id_serie'];
    $correlativo = $_POST['update_correlativo'];
       
    $insert = $connect->prepare("UPDATE tbl_series SET correlativo = ? WHERE id = ?");
    $resultado = $insert->execute([$correlativo,$id]);


    if($resultado)
    {
        $mensaje = 'exito';
    }
    else
    {
        $mensaje = 'error';
    }

    echo $mensaje;

    exit;
}


if($_POST['action'] == 'nueva_serie')
{
	$empresa     = $_POST['empresa'];
    $tdoc        = $_POST['tdoc'];
    $t           = explode('-',$tdoc);
    $cd          = $t[0];
    $tdoc        = $t[1];
    

    $correlativo = $_POST['correlativo'];
    $serie       = $_POST['serie'];
    
    $insert = $connect->prepare("INSERT INTO tbl_series(id_td,serie,correlativo,id_empresa,id_doc) VALUES(?,?,?,?,?)");
    $resultado = $insert->execute([$tdoc,$serie,$correlativo,$empresa,$cd]);


    if($resultado)
    {
    	$mensaje = 'exito';
    }
    else
    {
    	$mensaje = 'error';
    }

    echo $mensaje;

    exit;
}

//////////////////////usuarios //////////////////////////////////
if($_POST['action'] == 'editar_usuario')
{
	$empresa  = $_POST['empresa_update'];
    $id   	  = $_POST['id_update'];
    $clave    = $_POST['clave_update'];
    $clave    = md5($clave);
    

    $delete = $connect->prepare("UPDATE tbl_usuarios SET clave=?, nombre_personal=?,apellido_personal=? WHERE id_empresa=? and id_usuario=?");
    $resultado = $delete->execute([$clave,$_POST['nombre_update'],$_POST['apellido_update'],$empresa,$id]);


    if($resultado)
    {
    	$mensaje = 'exito';
    }
    else
    {
    	$mensaje = 'error';
    }

    echo $mensaje;

    exit;
}

if($_POST['action'] == 'eliminar_usuario')
{
	$empresa  = $_POST['delete_empresa'];
    $id   	  = $_POST['delete_id'];
    

    $delete = $connect->prepare("UPDATE tbl_usuarios SET estado=? WHERE id_empresa=? and id_usuario=?");
    $resultado = $delete->execute([0,$empresa,$id]);


    if($resultado)
    {
    	$mensaje = 'exito';
    }
    else
    {
    	$mensaje = 'error';
    }

    echo $mensaje;

    exit;
}

if($_POST['action'] == 'nuevo_usuario')
{
	$empresa  = $_POST['empresa'];
    $nombre   = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $usuario  = $_POST['user'];
    $clave    = $_POST['clave'];
    $clave    = md5($clave);
    $perfil   = $_POST['perfil'];
    $almacen  = $_POST['almacen'];

    $insert = $connect->prepare("INSERT INTO tbl_usuarios(usuario,clave,nombre_personal,apellido_personal,id_empresa,almacen,perfil) VALUES(?,?,?,?,?,?,?)");
    $resultado = $insert->execute([$usuario,$clave,$nombre,$apellido,$empresa,$almacen,$perfil]);


    if($resultado)
    {
    	$mensaje = 'exito';
    }
    else
    {
    	$mensaje = 'error';
    }

    echo $mensaje;

    exit;
}


if($_POST['action'] == 'listarclientes')
{
    

$empresa = $_SESSION["id_empresa"];

$data= Array();


$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
	
## Search 
$searchQuery = " ";
		
if($searchValue != ''){
   	$searchQuery .= " and ( num_doc LIKE '%".$_POST['search']['value']."%' ";
   	$searchQuery .=" OR nombre_persona LIKE '%".$_POST['search']['value']."%' ) ";
	//$searchQuery .=" OR txtCOD_ARTICULO IN (SELECT cod_articulo FROM articulo_serie WHERE serie LIKE '%".$_POST['search']['value']."%' OR lote LIKE '%".$_POST['search']['value']."%' ) ";
	
	//txtCOD_ARTICULO IN (SELECT cod_articulo FROM articulo_serie WHERE serie LIKE '%F0011%')
}

## Total number of record with filtering
$query_documento = "select *  from tbl_contribuyente WHERE empresa= $empresa ";
$resultado_documento=$connect->prepare($query_documento);
$resultado_documento->execute(); 
$totalRecords=$resultado_documento->rowCount();

## Total number of record with filtering
$query_documento1 = "select * from tbl_contribuyente WHERE empresa= $empresa ".$searchQuery;
$resultado_documento1=$connect->prepare($query_documento1);
$resultado_documento1->execute(); 
$totalRecordwithFilter=$resultado_documento1->rowCount();

## Fetch records
$lista1=$connect->query("SELECT * FROM tbl_contribuyente WHERE empresa= $empresa ".$searchQuery." limit ".$row.",".$rowperpage);
$resultado1=$lista1->fetchAll(PDO::FETCH_OBJ);		

foreach ($resultado1 as $res2) {

$botones='<a class="btn btn-success" onclick="enviacliente(\''.$res2->id_persona.'\',\''.$res2->num_doc.'\', \''.$res2->nombre_persona.'\', \''.$res2->direccion_persona.'\', \''.$res2->correo.'\');"><i class="fe fe-check"></i></a>';

 			$data[]=array(
				"0"=>$res2->num_doc,
 				"1"=>$res2->nombre_persona,
				"2"=>$botones
 				);
 		}
		
$json_data = array(
        "draw"            =>intval($draw),
        "recordsTotal"    =>$totalRecords,
        "recordsFiltered" =>$totalRecordwithFilter,
        "data"            => $data   // total data array
        );
		
		
echo json_encode($json_data);
    
    
    
}


?>
