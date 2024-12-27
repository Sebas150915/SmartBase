<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();

// Permite la conexion desde cualquier origen
header("Access-Control-Allow-Origin: *");
// Permite la ejecucion de los metodos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
date_default_timezone_set('America/Lima');
if (strlen(session_id()) < 1) 

header("HTTP/1.1");
header("Content-Type: application/json; charset=UTF-8");
$jsondata = array();
$jsondata['estado'] = '0';
$jsondata['mensaje'] = 'ERROR';
$jsondata['numero'] = '00000000';

//$array = explode("/", $_SERVER['REQUEST_URI']);
$bodyRequest = file_get_contents("php://input");
// Decodifica el cuerpo de la solicitud y lo guarda en un array de PHP
$cab = json_decode($bodyRequest, true);
$detalle = $cab['detalle'];
$hoy = date("Y-m-d");


$idcliente=(isset($cab['idcliente'])) ? $cab['idcliente'] : "0";
$fecha=(isset($cab['fecha'])) ? $cab['fecha'] : "0000-00-00";
$percibido=(isset($cab['percibido'])) ? $cab['percibido'] : "0";
$tasa=(isset($cab['tasa'])) ? $cab['tasa'] : "3";
$total=(isset($cab['total'])) ? $cab['total'] : "0";
$txtSERIE=(isset($cab['serie'])) ? $cab['serie'] : "P001";
$txtNUMERO=(isset($cab['numero'])) ? $cab['numero'] : "";
$tipodocumento=(isset($cab['tipodocumento'])) ? $cab['tipodocumento'] : "20";
$pago=(isset($cab['pago'])) ? $cab['pago'] : "CONTADO";
$regular=(isset($cab['regular'])) ? $cab['regular'] : "00";
$moneda=(isset($cab['moneda'])) ? $cab['moneda'] : "PEN";

$tcambio=(isset($cab['tcambio'])) ? $cab['moneda'] : 1;


$idlocal=$_SESSION['almacen'];
$idusuario=$_SESSION['id'];
$idempresa = $_SESSION["id_empresa"];
$hora = date('h:i:s');
$query=$connect->prepare("INSERT INTO tbl_ret_cab(idempresa,idcliente,idusuario,idlocal,tipodocumento,serie,numero,fecha,regular,tasa,percibido,total,tipo_pago,hash_cpe,hash_cdr,mensaje,estado,moneda) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
$resultado=$query->execute([$idempresa,$idcliente,$idusuario,$idlocal,$tipodocumento,$txtSERIE,$txtNUMERO,$fecha,$regular,$tasa,$percibido,$total,$pago,'','','','0',$moneda]);

$lastInsertId = $connect->lastInsertId();



/*insertar detalle*/

for ($i = 0; $i < count($detalle); $i++) 
{
		
	$id=(isset($detalle[$i]["txtID"]))?$detalle[$i]["txtID"]:"0";	
	$tipodoc=(isset($detalle[$i]["tipodoc"]))?$detalle[$i]["tipodoc"]:"0";
	$seriedet=(isset($detalle[$i]["seriedet"]))?$detalle[$i]["seriedet"]: "0";
	$numerodet=(isset($detalle[$i]["numerodet"]))?$detalle[$i]["numerodet"]: "";
	$fechadet=(isset($detalle[$i]["fechadet"]))?$detalle[$i]["fechadet"]: "00000-00-00";
	$monedadet=(isset($detalle[$i]["monedadet"]))?$detalle[$i]["monedadet"]: "PEN";	
	$importe=(isset($detalle[$i]["importe"]))?$detalle[$i]["importe"]:"0";
	$percepcion=(isset($detalle[$i]["percepcion"]))?$detalle[$i]["percepcion"]: "0";
	$neto=(isset($detalle[$i]["neto"]))?$detalle[$i]["neto"]: "0";
	$porcentaje=(isset($detalle[$i]["porcentaje"]))?$detalle[$i]["porcentaje"]: "0";

	$insert_query_detalle =$connect->prepare("INSERT INTO tbl_ret_det(idretencion,idventa,tipdoc,seriedoc,numdoc,fechadoc,moneda,importe,retencion,neto,porcentaje,tc) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");
	$resultado_detalle = $insert_query_detalle->execute([$lastInsertId,$lastInsertId,$tipodoc,$seriedet,$numerodet,$fechadet,$monedadet,$importe,$percepcion,$neto,$porcentaje,$tcambio]);

}	

    // actualizar serie + correlativo
    $update_query_serie = $connect->prepare("UPDATE tbl_series SET correlativo = correlativo + ? WHERE serie = ? and correlativo = ? and id_empresa = ?");
    $resultado_serie   = $update_query_serie->execute([1,$txtSERIE,$txtNUMERO,$idempresa]);

/*generando XML de retenciones*/
 require_once("../../sunat/api/xml.php");
 $xml = new GeneradorXML();
$query_empresa = "SELECT * FROM vw_tbl_empresas WHERE id_empresa = $idempresa";
$resultado_empresa = $connect->prepare($query_empresa);
$resultado_empresa->execute();
$row_empresa = $resultado_empresa->fetch(PDO::FETCH_ASSOC);


        //RUC DEL EMISOR - TIPO DE COMPROBANTE - SERIE DEL DOCUMENTO - CORRELATIVO
        //01-> FACTURA, 03-> BOLETA, 07-> NOTA DE CREDITO, 08-> NOTA DE DEBITO, 09->GUIA DE REMISION
        $numdocumento = str_pad($txtNUMERO, 8, "0", STR_PAD_LEFT);
        $nombrexml = $row_empresa['ruc'].'-'.$tipodocumento.'-'.$txtSERIE.'-'.$numdocumento;

        $ruta = "../../sunat/".$row_empresa['ruc']."/xml/".$nombrexml;

        /*DATOS DEL EMISOR*/
        $emisor =   array(
        'tipodoc'       => '6',
        'ruc'           => $row_empresa['ruc'], 
        'razon_social'  => $row_empresa['razon_social'], 
        'nombre_comercial'  => $row_empresa['nombre_comercial'], 
        'direccion'     => $row_empresa['direccion'], 
        'pais'          => 'PE', 
        'departamento'  => $row_empresa['departamento'],//LAMBAYEQUE 
        'provincia'     => $row_empresa['provincia'],//CHICLAYO 
        'distrito'      => $row_empresa['distrito'], //CHICLAYO
        'ubigeo'        => $row_empresa['ubigeo'], //CHICLAYO
        'usuario_sol'   => $row_empresa['usuario_sol'], //USUARIO SECUNDARIO EMISOR ELECTRONICO
        'clave_sol'     => $row_empresa['clave_sol'], //CLAVE DE USUARIO SECUNDARIO EMISOR ELECTRONICO
        'certificado'  => $row_empresa['certificado'],
        'clave_certificado'  =>$row_empresa['clave_certificado'],
        'cta_detraccion'  => $row_empresa['cta_detracciones'],
        'servidor_sunat'     =>$row_empresa['servidor_cpe'],
        'servidor_nombre'     =>$row_empresa['nombre_server'],
        'servidor_link'     =>$row_empresa['link'],
        'servidor_cpe'      =>$row_empresa['servidor_cpe']
        );

   //buscar datos cliente
        
        $rucpersona = $idcliente;
        $idempresa = $idempresa;
        $query_cliente = "SELECT * FROM tbl_contribuyente WHERE id_persona = '$rucpersona'  AND empresa = $idempresa";
        $resultado_cliente = $connect->prepare($query_cliente);
        $resultado_cliente->execute();
        $row_cliente = $resultado_cliente->fetch(PDO::FETCH_ASSOC);
        //********************CREAR CLAVE CLIENTE SI EN CASO NO TIENE*********************//


        $clave = $row_cliente['clave'];
        $ruc_persona1 = $row_cliente['num_doc'];


        if(empty($clave))
        {
        $query_ctr = $connect->prepare("UPDATE tbl_contribuyente SET clave = md5(?) WHERE num_doc = ?");
        $resultado_ctr = $query_ctr->execute([$ruc_persona1,$ruc_persona1]);

        }
       /*DATOS DEL CLIENTE*/
       $cliente = array(
        'tipodoc'       => $row_cliente['tipo_doc'],//6->ruc, 1-> dni 
        'ruc'           => $row_cliente['num_doc'], 
        'razon_social'  => $row_cliente['nombre_persona'], 
        'direccion'     => $row_cliente['direccion_persona'],
        'pais'          => 'PE',
        'correo'        => $row_cliente['correo']
        );  
        /*$numero = $_POST['total'];
        include 'numeros.php';
        $texto=convertir($numero);
        $texto = ltrim($texto);*/


        $lista_cpe_cab = "SELECT * FROM vw_tbl_ret_cab WHERE id=$lastInsertId";
        $resultado_cpe_cab = $connect->prepare($lista_cpe_cab);
        $resultado_cpe_cab->execute();
        $row_cpe_cab = $resultado_cpe_cab->fetch(PDO::FETCH_ASSOC);

        /*DATOS DE CABECERA*/
        $cabecera = $row_cpe_cab; 


          $lista_cpe_det = $connect->prepare("SELECT * FROM vw_tbl_ret_det WHERE idventa=$lastInsertId");
          $lista_cpe_det->execute();
          $row_cpe_det=$lista_cpe_det->fetchAll(PDO::FETCH_ASSOC);
          
          /*DATOS DEL DETALLE*/
          $detalle = $row_cpe_det;

          $xml->CrearXMLRetenciones($ruta, $emisor, $cliente, $cabecera, $detalle);

          require_once("../../sunat/api/ApiFacturacion.php");
          $objApi = new ApiFacturacion();
          
          $respuesta = $objApi->EnviarRetencionPercepcion($emisor,$nombrexml,$connect,$lastInsertId);

          require_once("phpqrcode/qrlib.php");
            //CREAR QR INICIO
            //codigo qr
            /*RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | 
            MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION |TIPO DE DOCUMENTO ADQUIRENTE |
            NUMERO DE DOCUMENTO ADQUIRENTE |*/
    
            $ruc = $row_empresa['ruc'];
            $tipo_documento = $tipodocumento; //factura
            $serie = $txtSERIE;
            $correlativo = $txtNUMERO;
            $igv = 0.00;
            $total = $total;
            $fecha = $fecha;
            $tipodoccliente = $row_cliente['tipo_doc'];
            $nro_doc_cliente = $row_cliente['num_doc'];
    
            $nombrexml = $ruc."-".$tipo_documento."-".$serie."-".$correlativo;
            $text_qr = $ruc." | ".$tipo_documento." | ".$serie." | ".$correlativo." | ".$igv." | ".$total." | ".$fecha." | ".$tipodoccliente." | ".$nro_doc_cliente;
            $ruta_qr = '../../sunat/'.$row_empresa['ruc'].'/qr/'.$nombrexml.'.png';
    
            QRcode::png($text_qr, $ruta_qr, 'Q',15, 0);
            
            $cod_sunat = $respuesta['cod_sunat'];
            $msj_sunat = $respuesta['msj_sunat'];
            $hash_cdr  = $respuesta['hash_cdr'];
            $idfactura = $lastInsertId;
            $respuestahash = $respuesta['respuestahash'];
            $estadodoc   = $respuesta['cod_sunat1'];
            
         
            
            if($cod_sunat == '0')
            {
               $estadofe = '1'; 
            }
            else if(intval($cod_sunat)==1033)
            {
                $estadofe = '1';
            }
            else if(intval($cod_sunat)==1032)
            {
                $estadofe = '3';
            }
            else if(intval($cod_sunat)>=2000 || intval($cod_sunat)<=3999)
            {
                $estadofe = '3';
            }
             else if(intval($cod_sunat)>4000 )
            {
                $estadofe = '2';
            }
            else
            {
                $estadofe = '0';
            }
            
            if(isset($hash_cdr))
            {
                $hash_cdr = $respuestahash;
            }

        //var_dump($cod_sunat);

        $query=$connect->prepare("UPDATE tbl_ret_cab SET hash_cpe=?,estado=? ,fecodigoerror=?,mensaje=? WHERE id=?;");
        $resultado=$query->execute([$hash_cdr,$estadofe,$cod_sunat,$msj_sunat,$idfactura]);
            
            $miArray= array
            ("id_emp"      => $row_empresa['ruc'],
             "cod_sunat" => $cod_sunat,
             "msj_sunat" => $msj_sunat,
             "hash_cdr"  => $hash_cdr,
             "lastInsertId"=>$idfactura,
             "estadodoc"=>$estadodoc
             
                );
            echo json_encode($miArray);
            
           // echo $cod_sunat.'-'.$msj_sunat;
            exit;


          $jsondata['estado'] = '1';
          $jsondata['mensaje'] = "Documento Guardado con exito..!";

          $jsondata['idventa'] = $lastInsertId;
          echo json_encode($jsondata);
          exit();	

 ?>




