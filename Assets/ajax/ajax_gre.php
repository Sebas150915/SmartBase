<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
require_once("../../sunat/api/xml.php");
require_once("../../sunat/api/ApiFacturacion.php");
session_start();

//listar detalle de nota de credito

if($_POST['action'] == 'listarDetalle')
{
       $idventa = $_POST['id'];
       //echo $idventa;
       $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql="SELECT * FROM vw_tbl_venta_det WHERE idventa = $idventa";
        $resultado=$connect->prepare($sql);
        $resultado->execute();
        $num_reg=$resultado->rowCount();
        $detalletabla = '';
        $cont =1;
        foreach($resultado as $serie )
        {
            
            $descripcion = $serie['descripcion'];
            $codigo      = $serie['codigo'];
            $descripcion = $serie['descripcion'];
            $cantidad    = $serie['cantidad'];
            $cantidadf   = $serie['cantidad_factor'];
            $cantidadu   = $serie['cantidad_unitario'];
            $valor_unitario = $serie['valor_unitario'];
            $precio_unitario = $serie['precio_unitario'];

            $precio_compra   = $serie['costo'];
            $factor   = $serie['factor'];

            $igv_unitario_total    = $serie['igv'];
            $importe_total   = $serie['importe_total'];
            $codigo_afectacion_alt = $serie['codigo_afectacion_alt'];

            $detalletabla .='<tr id="fila'.$cont.'">
             <td><button type="button" class="btn btn-danger" onclick="eliminar('.$cont.')" disabled><i class="fe fe-trash"></i></button></td>
             <td>'.$cont.'</td>
            <td><input type="hidden" name="itemarticulo[]" value="'.$cont.'"><input type="hidden" name="idarticulo[]" value="'.$codigo.'"><input type="hidden" name="nomarticulo[]" value="'.$descripcion.'">'.$descripcion.'</td>
            <td>
            <input type="hidden" name="precio_compra[]" value="'.$precio_compra.'">
            <input type="hidden" name="factor[]" value="'.$factor.'">
            <input type="text" min="1" class="form-control text-right" name="cantidad[]" id="cantidad[]" value="'.$cantidadf.'" onkeyup="modificarSubtotales()" >
            </td>

            <td><input type="text" min="1" class="form-control text-right" name="cantidadu[]" id="cantidadu[]" value="'.$cantidadu.'" onkeyup="modificarSubtotales()" ></td>

            

            <td>
            <input type="hidden" class="form-control input-sm" name="valor_unitario[]" id="valor_unitario[]" value='.$valor_unitario.'" readonly="">
            <input type="hidden" id="cantidada'.$cont.'" name="cantidada[]" class="form-control input-sm">
            <input type="hidden" id="cantidadua'.$cont.'" name="cantidadua[]" class="form-control input-sm">
            <input type="hidden" class="form-control input-sm" name="igv_unitario[]" id="igv_unitario[]" value="'.$igv_unitario_total.'" readonly="">
            <input type="text" class="form-control input-sm" name="precio_venta[]" id="precio_venta[]" value="'.$precio_unitario.'" readonly="">
            </td>

            <td><span id="subtotal'.$cont.'" name="subtotal">'.$importe_total.'</span><input type="hidden" id="afectacion'.$cont.'" name="afectacion[]" class="form-control" value="'.$codigo_afectacion_alt.'"></td>
            </tr>';
             $cont++;
        }

        $arrayData['detalle'] = $detalletabla;

        echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);


        exit();
}


if($_POST['action'] == 'listarGreDet')
{
       $idventa = $_POST['id'];
       //echo $idventa;
       $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql="SELECT 
idempresa, idguia,  sum(peso) AS peso, idproducto, codigoproducto, nro_orden,
unidadmedida,nombreproducto AS nomproducto, sum(cantidad) AS cantidad, sum(precio) AS precio, sum(subtotal) AS subtotal, sum(igv) AS igv, sum(total) AS total, precio_compra, factor, afectacion , sum(preciounitario_igv) AS preciounitario_igv

FROM vw_tbl_gre_det WHERE idguia = $idventa
GROUP BY 
idempresa, idguia, idproducto, codigoproducto, nro_orden,
unidadmedida,nombreproducto, precio_compra, factor, afectacion, preciounitario_igv";
        $resultado=$connect->prepare($sql);
        $resultado->execute();
        $num_reg=$resultado->rowCount();
        $detalletabla = '';
        $cont =1;
        foreach($resultado as $serie )
        {
                        
            $detalletabla .='
            <tr id="fila'.$cont.'">
	         <td><button type="button" class="btn btn-danger" onclick="eliminar('.$cont.')"><i class="fe fe-trash-2"></i></button></td>
	         <td>cont</td>
	          <td><input type="hidden" name="itemarticulo[]" value="'.$cont.'"><input type="hidden" name="idarticulo[]" value="'.$serie['idproducto'].'"><input type="text" class="form-control w-100" name="nomarticulo[]" value="'.$serie['nomproducto'].'" ></td>
	          <td><input type="hidden" name="precio_compra[]" value="'.$serie['precio_compra'].'"><input type="hidden" name="factor[]" value="'.$serie['factor'].'"><input type="text" min="1" class="form-control input-sm" name="cantidad[]" id="cantidad[]" value="0" onkeyup="modificarSubtotales()" required ></td>
	          <td><input type="text" min="1" class="form-control input-sm" name="cantidadu[]" id="cantidadu[]" value="'.$serie['cantidad'].'" required onkeyup="modificarSubtotales()"></td>
	          <td><input type="hidden" class="form-control input-sm" name="valor_unitario[]" id="valor_unitario[]" value="'.$serie['precio'].'" readonly>
	          <input type="hidden" class="form-control input-sm" name="igv_unitario[]" id="igv_unitario[]" value="'.$serie['preciounitario_igv'].'" readonly>
	          <input type="text" class="form-control input-sm" name="precio_venta[]" id="precio_venta[]" value="'.$serie['precio'].'" onkeyup="modificarSubtotales()"></td>
	         <td><span id="subtotal'.$cont.'" name="subtotal">'.$serie['total'].'</span><input type="hidden" id="afectacion'.$cont.'" name="afectacion[]" class="form-control input-sm" value="'.$serie['afectacion'].'"></td>
	         </tr>';
             $cont++;
        }

        $arrayData['detalle'] = $detalletabla;

        echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);


        exit();
}


///////////////////////////////////////////////////////////////////////////////////buscar destino
if($_POST['action'] == 'buscar_destino')
{
        $doc=$_POST['id'];
        $html ="";
        $html= '<option value="">-SELECCIONE-</option>';


        $query = "SELECT * FROM tbl_contribuyente_direccion WHERE id_contribuyente ='$doc'";
        $resultado=$connect->prepare($query);
        $resultado->execute(); 
        $num_reg=$resultado->rowCount();


        while($row = $resultado->fetch(PDO::FETCH_ASSOC) )
        {
        $html.='<option value="'.$row['id'].'">'.$row["direccion"].'</option>';
        }

        echo $html;
        exit;
}

if($_POST['action'] == 'buscar_vehiculo')
{
        $empresa =$_SESSION['id_empresa'];
        $html ="";
        //$html= '<option value="">-SELECCIONE-</option>';


        $query = "SELECT * FROM tbl_gre_vehiculo WHERE idempresa ='$empresa' ORDER BY id DESC";
        $resultado=$connect->prepare($query);
        $resultado->execute(); 
        $num_reg=$resultado->rowCount();


        while($row = $resultado->fetch(PDO::FETCH_ASSOC) )
        {
        $html.='<option value="'.$row['id'].'">'.$row["placa"].'</option>';
        }

        echo $html;
        exit;
}



if($_POST['action'] == 'buscar_chofer')
{
        $empresa =$_SESSION['id_empresa'];
        $html ="";
        //$html= '<option value="">-SELECCIONE-</option>';


        $query = "SELECT * FROM tbl_gre_conductor WHERE empresa ='$empresa' ORDER BY id DESC";
        $resultado=$connect->prepare($query);
        $resultado->execute(); 
        $num_reg=$resultado->rowCount();


        while($row = $resultado->fetch(PDO::FETCH_ASSOC) )
        {
        $html.='<option value="'.$row['id'].'">'.$row["nombre"].'</option>';
        }

        echo $html;
        exit;
}



// guardar nueva venta

if($_POST['action'] == 'nueva_gre')
{
    //var_dump($_POST);

        $t = explode("-",$_POST['tip_cpe']);
        $cod = $t[0];
        $tdoc = $t[1];


        if($_POST['transportista']== "")
        {
                $_POST['transportista'] = 0;
        }
        $doc_ref_gre = $_POST['serie_ref'].'-'.$_POST['num_ref'];
        $hora = date('h:i:s');
        $query=$connect->prepare("INSERT INTO tbl_gre_cab(idempresa,fecha_emision,fecha_traslado,hora_emision,tipo_doc,serie_doc,correlativo,tipo_transportista,motivo,vehiculo,chofer,transportista,peso,nro_cajas,nro_carga,tip_doc_ref,num_doc_ref,op_gravadas,op_exoneradas,op_inafectas,igv,total,idpartida,idcliente,idllegada,dam,carreta,contenedor,precinto,booking,obs) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
        $resultado=$query->execute([$_POST['empresa'],$_POST['fecha_emision'],$_POST['fecha_traslado'],$hora,$tdoc,$_POST['serie'],$_POST['numero'],$_POST['ttransporte'],$_POST['motivo'],$_POST['vehiculo'],$_POST['chofer'],$_POST['transportista'],$_POST['peso'],$_POST['ncajas'],$_POST['ncarga'],$_POST['tip_ref'],$doc_ref_gre,$_POST['op_g'],$_POST['op_e'],$_POST['op_i'],$_POST['igv'],$_POST['total'],$_POST['ppartida'],$_POST['id_ruc'],$_POST['pllegada'],$_POST['dam'],$_POST['carreta'],$_POST['contenedor'],$_POST['precinto'],$_POST['booking'],$_POST['obs']]);

        $lastInsertId = $connect->lastInsertId();

        //registro detalle venta

        for($i = 0; $i< count($_POST['idarticulo']); $i++)
        {
                //var_dump($_POST);
                $item                  = $_POST['itemarticulo'][$i];
                $idarticulo            = $_POST['idarticulo'][$i];
                $nomarticulo           = $_POST["nomproducto"][$i];
                //echo $nomarticulo;
                $cantidad              = $_POST['cantidad'][$i];

                $afectacion            = $_POST['afectacion'][$i];
                $tipo_precio           = '01';
                $unidad                = 'NIU';
                $costo                 = $_POST['precio_compra'][$i];
                $factor                = $_POST['factor'][$i];
                $cantidadu             = $_POST['cantidadu'][$i];
                $mxmn                  = $_POST['mxmn'][$i];
                $cantidad_total        = $factor*$cantidad + $cantidadu;

                if($afectacion == '10')
                {
                $igv_unitario          = 18;
                }
                else
                {
                $igv_unitario          = 0;
                }



                $precio_venta          = $_POST['precio_venta'][$i]/$factor;
                $precio_unitario       = ($precio_venta - ($igv_unitario/$cantidad_total));
                $precio_venta_total    = $precio_venta*$cantidad_total;



                if($afectacion == 10)
                {
                $precio_venta_unitario = $precio_venta/1.18;
                $valor_unitario_total  = ($_POST['valor_unitario'][$i]/$factor)/1.18;

                $importe_total = ($cantidad_total*$precio_venta);

                $valor_total = $cantidad_total*$precio_venta_unitario;




                }
                else
                {
                $precio_venta_unitario = $precio_venta;
                $valor_unitario_total  = ($_POST['valor_unitario'][$i]/$factor);

                $importe_total = ($cantidad_total*$precio_venta);

                $valor_total = $cantidad_total*$precio_venta_unitario;

                }

                $igv_total                = $importe_total - $valor_total;
                $precio_compra            = $_POST['precio_compra'][$i];



        
                $insert_query_detalle =$connect->prepare("INSERT INTO tbl_gre_det(idventa,item,idproducto,nomproducto,cantidad,valor_unitario,precio_unitario,igv,porcentaje_igv,valor_total,importe_total,costo,cantidad_factor,factor,cantidad_unitario,mxmn) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $resultado_detalle = $insert_query_detalle->execute([$lastInsertId,$item,$idarticulo,$nomarticulo,$cantidad_total,$precio_venta_unitario,$precio_venta,$igv_total,18,$valor_total,$importe_total,$costo,$cantidad,$factor,$cantidadu,$mxmn]);

                // actualizar serie + correlativo
                $update_query_serie = $connect->prepare("UPDATE tbl_series SET correlativo = correlativo + ? WHERE serie = ? and correlativo = ? and id_empresa = ?");
                $resultado_serie   = $update_query_serie->execute([1,$_POST['serie'],$_POST['numero'],$_POST['empresa']]);


        }

        
        //envio cpe a SUNAT///////////////////////////////////////////////
        require_once("../../sunat/api/xml.php");



        $xml = new GeneradorXML();
        //buscar ruc emisor

        $query_empresa = "SELECT * FROM vw_tbl_empresas WHERE id_empresa = $_POST[empresa]";
        $resultado_empresa = $connect->prepare($query_empresa);
        $resultado_empresa->execute();
        $row_empresa = $resultado_empresa->fetch(PDO::FETCH_ASSOC);


        //RUC DEL EMISOR - TIPO DE COMPROBANTE - SERIE DEL DOCUMENTO - CORRELATIVO
        //01-> FACTURA, 03-> BOLETA, 07-> NOTA DE CREDITO, 08-> NOTA DE DEBITO, 09->GUIA DE REMISION
        $nombrexml = $row_empresa['ruc'].'-'.$tdoc.'-'.$_POST['serie'].'-'.$_POST['numero'];

        $ruta = "../../sunat/".$row_empresa['ruc']."/xml/".$nombrexml;
        $emisor =   array(
        'tipodoc'           => '6',
        'ruc'               => $row_empresa['ruc'], 
        'razon_social'      => $row_empresa['razon_social'], 
        'nombre_comercial'  => $row_empresa['nombre_comercial'], 
        'direccion'         => $row_empresa['direccion'], 
        'pais'              => 'PE', 
        'departamento'      => $row_empresa['departamento'],//LAMBAYEQUE 
        'provincia'         => $row_empresa['provincia'],//CHICLAYO 
        'distrito'          => $row_empresa['distrito'], //CHICLAYO
        'ubigeo'            => $row_empresa['ubigeo'], //CHICLAYO
        'usuario_sol'       => $row_empresa['usuario_sol'], //USUARIO SECUNDARIO EMISOR ELECTRONICO
        'clave_sol'         => $row_empresa['clave_sol'], //CLAVE DE USUARIO SECUNDARIO EMISOR ELECTRONICO
        'certificado'       => $row_empresa['certificado'],
        'clave_certificado' =>$row_empresa['clave_certificado'],
        'cta_detraccion'    => $row_empresa['cta_detracciones'],
        'servidor_sunat'    =>$row_empresa['servidor_cpe'],
        'servidor_gre'    =>$row_empresa['servidor_gre'],
        'servidor_nombre'   =>$row_empresa['nombre_server'],
        'servidor_link'     =>$row_empresa['link'],
        'secretgre'         =>$row_empresa['secretgre'],
        'idgre'             =>$row_empresa['idgre'],
        'usuariosol'        =>$row_empresa['usuariosol'],
        'clavesol'          =>$row_empresa['clavesol']
        );
        //buscar datos cliente
        $ruc_persona=$_POST['ruc_persona'];
        $query_cliente = "SELECT * FROM tbl_contribuyente WHERE num_doc = '$ruc_persona'";
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

        $cliente = array(
        'tipodoc'       => $row_cliente['tipo_doc'],//6->ruc, 1-> dni 
        'ruc'           => $row_cliente['num_doc'], 
        'razon_social'  => $row_cliente['nombre_persona'], 
        'direccion'     => $row_cliente['direccion_persona'],
        'pais'          => 'PE',
        'correo'        => $row_cliente['correo']
        );  
        $numero = $_POST['total'];
        include 'numeros.php';
        $texto=convertir($numero);
        $texto = ltrim($texto);


        $lista_cpe_cab = "SELECT * FROM vw_tbl_gre_cab WHERE id=$lastInsertId";
        $resultado_cpe_cab = $connect->prepare($lista_cpe_cab);
        $resultado_cpe_cab->execute();
        $row_cpe_cab = $resultado_cpe_cab->fetch(PDO::FETCH_ASSOC);

        $cabecera = $row_cpe_cab;

        

        //********************DATOS DE COMPROBANTE - DETALLE*********************//

        //echo 'el id ultimo es '.$lastInsertId;
        $lista_cpe_det = $connect->prepare("SELECT * FROM vw_tbl_gre_det WHERE idguia=$lastInsertId");
        $lista_cpe_det->execute();
        $row_cpe_det=$lista_cpe_det->fetchAll(PDO::FETCH_ASSOC);
        //print_r($row_cpe_det);

        $detalle = $row_cpe_det;

        // var_dump($detalle1);



        $respuesta = $xml->CrearXMLGRE($ruta, $emisor, $cliente, $cabecera, $detalle);


        require_once("../../sunat/api/ApiFacturacion.php");

        $objApi = new ApiFacturacion();

                $respuesta =  $objApi->EnviarComprobanteElectronicoGRE($emisor,$nombrexml,$connect,$lastInsertId);

                        require_once("phpqrcode/qrlib.php");
                        //CREAR QR INICIO
                        //codigo qr
                        /*RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | 
                        MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION |TIPO DE DOCUMENTO ADQUIRENTE |
                        NUMERO DE DOCUMENTO ADQUIRENTE |*/

                        $ruc = $row_empresa['ruc'];
                        $tipo_documento = $tdoc; //factura
                        $serie = $_POST['serie'];
                        $correlativo = $_POST['numero'];
                        $igv = $_POST['igv'];
                        $total = $_POST['total'];
                        $fecha = $_POST['fecha_emision'];
                        $tipodoccliente = $row_cliente['tipo_doc'];
                        $nro_doc_cliente = $row_cliente['num_doc'];

                        $nombrexml = $ruc."-".$tipo_documento."-".$serie."-".$correlativo;
                        $text_qr = $ruc." | ".$tipo_documento." | ".$serie." | ".$correlativo." | ".$igv." | ".$total." | ".$fecha." | ".$tipodoccliente." | ".$nro_doc_cliente;
                        $ruta_qr = '../../sunat/'.$row_empresa['ruc'].'/qr/'.$nombrexml.'.png';

                        QRcode::png($text_qr, $ruta_qr, 'Q',15, 0);
                        
                        $mensaje['hash_cpe']      = $respuesta['hash_cpe'];
                        //$mensaje['ndoc']          = $respuesta['ndoc'];
                        $mensaje['ruc']           = $respuesta['ruc'];
                        $mensaje['numerror1']     = $respuesta['numerror1'];
                        $mensaje['msj_sunat1']    = $respuesta['msj_sunat1'];
                        $mensaje['hash_cdr1']     = $respuesta['hash_cdr1'];
                        $mensaje['token1']        = $respuesta['token1'];
                        
                        $mensaje['ticket2']       = $respuesta['ticket2'];
                        $mensaje['fecRecepcion2'] = $respuesta['fecRecepcion2'];
                        $mensaje['cod_sunat2']    = $respuesta['cod_sunat2'];
                        $mensaje['numerror2']     = $respuesta['numerror2'];
                        $mensaje['msj_sunat2']    = $respuesta['msj_sunat2'];
                        $mensaje['hash_cdr2']     = $respuesta['hash_cdr2'];
                        
                        $mensaje['idgre']         = $lastInsertId;
                        $mensaje['cod_sunat']     = $respuesta['cod_sunat'];
                        $mensaje['numerror']      = $respuesta['numerror'];
                        $mensaje['msj_sunat']     = $respuesta['msj_sunat'];
                        $mensaje['hash_cdr']      = $respuesta['hash_cdr'];
                       /* $mensaje['arcCdr']        = $respuesta['arcCdr'];*/
                       $mensaje['msj_link']       = $respuesta['msj_link'];
                        
                        //$mensaje['response2'] = $respuesta['response2'];
                        $query=$connect->prepare("UPDATE tbl_gre_cab SET hash=?,ticket=? ,mensaje=?,numerror=?,link=? WHERE id=?;");
                        $resultado=$query->execute([$mensaje['hash_cpe'],$mensaje['ticket2'],$mensaje['msj_sunat'],$mensaje['numerror'],$mensaje['msj_link'],$mensaje['idgre']]);

                        echo json_encode($mensaje,true);
                        exit;
                
        
     
}

if($_POST['action'] == 'sunat_gre')
{
    
   // print_r($_POST);exit;

                $id_venta = $_POST['enviar_id'];            

               $tdoc='09';
    
                //insert deuda por cobrar

                        //envio cpe a SUNAT///////////////////////////////////////////////
        require_once("../../sunat/api/xml.php");



        $xml = new GeneradorXML();
        //buscar ruc emisor
        $idempresa=$_SESSION["id_empresa"];
        $query_empresa = "SELECT * FROM vw_tbl_empresas WHERE id_empresa = $idempresa";
        $resultado_empresa = $connect->prepare($query_empresa);
        $resultado_empresa->execute();
        $row_empresa = $resultado_empresa->fetch(PDO::FETCH_ASSOC);
        
        
        $lista_cpe_cab = "SELECT * FROM vw_tbl_gre_cab WHERE id='$id_venta'";
        $resultado_cpe_cab = $connect->prepare($lista_cpe_cab);
        $resultado_cpe_cab->execute();
        $row_cpe_cab = $resultado_cpe_cab->fetch(PDO::FETCH_ASSOC);
        //print_r($row_cpe_cab);
        //exit;
        $cabecera = $row_cpe_cab;
        
        //print_r($row_cpe_cab);exit;
        /*$numero = $cabecera['total'];
        include 'numeros.php';
        $texto=convertir($numero);
        $texto = ltrim($texto);*/
        
        //RUC DEL EMISOR - TIPO DE COMPROBANTE - SERIE DEL DOCUMENTO - CORRELATIVO
        //01-> FACTURA, 03-> BOLETA, 07-> NOTA DE CREDITO, 08-> NOTA DE DEBITO, 09->GUIA DE REMISION
        $nombrexml = $row_empresa['ruc'].'-'.$tdoc.'-'.$cabecera['serie'].'-'.$cabecera['correlativo'];
        


        $ruta = "../../sunat/".$row_empresa['ruc']."/xml/".$nombrexml;
        $emisor =   array(
        'tipodoc'           => '6',
        'ruc'               => $row_empresa['ruc'], 
        'razon_social'      => $row_empresa['razon_social'], 
        'nombre_comercial'  => $row_empresa['nombre_comercial'], 
        'direccion'         => $row_empresa['direccion'], 
        'pais'              => 'PE', 
        'departamento'      => $row_empresa['departamento'],//LAMBAYEQUE 
        'provincia'         => $row_empresa['provincia'],//CHICLAYO 
        'distrito'          => $row_empresa['distrito'], //CHICLAYO
        'ubigeo'            => $row_empresa['ubigeo'], //CHICLAYO
        'usuario_sol'       => $row_empresa['usuario_sol'], //USUARIO SECUNDARIO EMISOR ELECTRONICO
        'clave_sol'         => $row_empresa['clave_sol'], //CLAVE DE USUARIO SECUNDARIO EMISOR ELECTRONICO
        'certificado'       => $row_empresa['certificado'],
        'clave_certificado' =>$row_empresa['clave_certificado'],
        'cta_detraccion'    => $row_empresa['cta_detracciones'],
        'servidor_sunat'    =>$row_empresa['servidor_cpe'],
        'servidor_gre'    =>$row_empresa['servidor_gre'],
        'servidor_nombre'   =>$row_empresa['nombre_server'],
        'servidor_link'     =>$row_empresa['link'],
        'secretgre'         =>$row_empresa['secretgre'],
        'idgre'             =>$row_empresa['idgre'],
        'usuariosol'        =>$row_empresa['usuariosol'],
        'clavesol'          =>$row_empresa['clavesol']
        );
        //buscar datos cliente
        $ruc_persona=$_POST['ruc_id'];
        $query_cliente = "SELECT * FROM tbl_contribuyente WHERE id_persona = '$ruc_persona'";
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

        $cliente = array(
        'tipodoc'       => $row_cliente['tipo_doc'],//6->ruc, 1-> dni 
        'ruc'           => $row_cliente['num_doc'], 
        'razon_social'  => $row_cliente['nombre_persona'], 
        'direccion'     => $row_cliente['direccion_persona'],
        'pais'          => 'PE',
        'correo'        => $row_cliente['correo']
        );  
        


        

        //********************DATOS DE COMPROBANTE - DETALLE*********************//

        //echo 'el id ultimo es '.$lastInsertId;
        $lista_cpe_det = $connect->prepare("SELECT * FROM vw_tbl_gre_det WHERE idguia='$id_venta'");
        $lista_cpe_det->execute();
        $row_cpe_det=$lista_cpe_det->fetchAll(PDO::FETCH_ASSOC);
        //print_r($row_cpe_det);

        $detalle = $row_cpe_det;

        //var_dump($detalle);exit;



        $respuesta = $xml->CrearXMLGRE($ruta, $emisor, $cliente, $cabecera, $detalle);


        require_once("../../sunat/api/ApiFacturacion.php");

        $objApi = new ApiFacturacion();


                $respuesta =  $objApi->EnviarComprobanteElectronicoGRE($emisor,$nombrexml,$connect,$id_venta);

                        require_once("phpqrcode/qrlib.php");
                        //CREAR QR INICIO
                        //codigo qr
                        /*RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | 
                        MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION |TIPO DE DOCUMENTO ADQUIRENTE |
                        NUMERO DE DOCUMENTO ADQUIRENTE |*/

                        $ruc = $row_empresa['ruc'];
                        $tipo_documento = $tdoc; //factura
                        $serie = $cabecera['serie'];
                        $correlativo = $cabecera['correlativo'];
                        $igv = $cabecera['igv'];
                        $total = $cabecera['total'];
                        $fecha = $cabecera['fecha_emision'];
                        $tipodoccliente = $row_cliente['tipo_doc'];
                        $nro_doc_cliente = $row_cliente['num_doc'];

                        $nombrexml = $ruc."-".$tipo_documento."-".$serie."-".$correlativo;
                        $text_qr = $ruc." | ".$tipo_documento." | ".$serie." | ".$correlativo." | ".$igv." | ".$total." | ".$fecha." | ".$tipodoccliente." | ".$nro_doc_cliente;
                        $ruta_qr = '../../sunat/'.$row_empresa['ruc'].'/qr/'.$nombrexml.'.png';

                        QRcode::png($text_qr, $ruta_qr, 'Q',15, 0);
                        
                        $mensaje['hash_cpe']      = $respuesta['hash_cpe'];
                        //$mensaje['ndoc']          = $respuesta['ndoc'];
                        $mensaje['ruc']           = $respuesta['ruc'];
                        $mensaje['numerror1']     = $respuesta['numerror1'];
                        $mensaje['msj_sunat1']    = $respuesta['msj_sunat1'];
                        $mensaje['hash_cdr1']     = $respuesta['hash_cdr1'];
                        $mensaje['token1']        = $respuesta['token1'];
                        
                        $mensaje['ticket2']       = $respuesta['ticket2'];
                        $mensaje['fecRecepcion2'] = $respuesta['fecRecepcion2'];
                        $mensaje['cod_sunat2']    = $respuesta['cod_sunat2'];
                        $mensaje['numerror2']     = $respuesta['numerror2'];
                        $mensaje['msj_sunat2']    = $respuesta['msj_sunat2'];
                        $mensaje['hash_cdr2']     = $respuesta['hash_cdr2'];
                        
                        $mensaje['idgre']         = $id_venta;
                        $mensaje['cod_sunat']     = $respuesta['cod_sunat'];
                        $mensaje['numerror']      = $respuesta['numerror'];
                        $mensaje['msj_sunat']     = $respuesta['msj_sunat'];
                        $mensaje['hash_cdr']      = $respuesta['hash_cdr'];
                       /* $mensaje['arcCdr']        = $respuesta['arcCdr'];*/
                       $mensaje['msj_link']       = $respuesta['msj_link'];
                        
                        //$mensaje['response2'] = $respuesta['response2'];
                        $query=$connect->prepare("UPDATE tbl_gre_cab SET hash=?,ticket=? ,mensaje=?,numerror=?,link=? WHERE id=?;");
                        $resultado=$query->execute([$mensaje['hash_cpe'],$mensaje['ticket2'],$mensaje['msj_sunat'],$mensaje['numerror'],$mensaje['msj_link'],$mensaje['idgre']]);

                        echo json_encode($mensaje,true);
                        exit;
                
   
     

}
// guardar nueva nota de venta

?>
