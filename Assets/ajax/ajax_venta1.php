<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
require_once("../../sunat/api/xml.php");
require_once("../../sunat/api/ApiFacturacion.php");
session_start();




if($_POST['action'] == 'nota_venta_editar')
{

        $t = explode("-",$_POST['tip_cpe']);
        $cod = $t[0];
        $tdoc = $t[1];

        $hora = date('h:i:s');
        $visa = $_POST['visa'];
        $cvisa = $_POST['cvisa'];    
        $efectivo = $_POST['efectivo'];
        $query_insumo = $connect->prepare("UPDATE tbl_venta_cab SET op_gravadas =  ?, op_exoneradas=?,op_inafectas=?,igv=?, total=?  WHERE id = ?");
        $resultado_insumo = $query_insumo->execute([$_POST['op_g'],$_POST['op_e'],$_POST['op_i'],$_POST['igv'],$_POST['total'],$_POST['id_nv']]);


        //registro detalle venta

        for($i = 0; $i< count($_POST['idarticulo']); $i++)
        {
            $item                  = $_POST['itemarticulo'][$i];
            $idarticulo            = $_POST['idarticulo'][$i];
            $nomarticulo           = $_POST['nomarticulo'][$i];
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


            if($cantidad_total>0)
            {
            $precio_venta          = $_POST['precio_venta'][$i]/$factor;
            $precio_unitario       = ($precio_venta - ($igv_unitario/$cantidad_total));
            $precio_venta_total    = $precio_venta*$cantidad_total;
            }
            else
            {
            $precio_venta          = 0;
            $precio_unitario       = 0;
            $precio_venta_total    = 0;
            }



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

        $igv_total             = $importe_total - $valor_total;
        $precio_compra            = $_POST['precio_compra'][$i];



        /*regresar productos al stock*/

        if($tdoc=='01' || $tdoc=='03' || $tdoc  == '99')
        {
              
                /*buscar producto*/
                $query_bpro = "SELECT * FROM tbl_venta_det WHERE idproducto = $idarticulo AND idventa = $_POST[id_nv] ";
                $resultado_bpro = $connect->prepare($query_bpro);
                $resultado_bpro->execute();
                $num_reg_bpro=$resultado_bpro->rowCount();
                /*fin buscar producto*/
                /*producto nuevo*/
                if($num_reg_bpro == 0)
                {
$insert_query_detalle =$connect->prepare("INSERT INTO tbl_venta_det(idventa,item,idproducto,cantidad,valor_unitario,precio_unitario,igv,porcentaje_igv,valor_total,importe_total,costo,cantidad_factor,factor,cantidad_unitario,mxmn) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $resultado_detalle = $insert_query_detalle->execute([$_POST['id_nv'] ,$item,$idarticulo,$cantidad_total,$precio_venta_unitario,$precio_venta,$igv_total,18,$valor_total,$importe_total,$costo,$cantidad,$factor,$cantidadu,$mxmn]);
                /*fin producto nuevo*/
                }

                else
                {
                          //ACTUALIZA STOCK
                        //buscar insumos de tabla recetas
                $query_articulos = "SELECT * FROM tbl_recetas WHERE id_producto = '$idarticulo'";
                $resultado_articulos = $connect->prepare($query_articulos);
                $resultado_articulos->execute();
                $num_reg_articulos=$resultado_articulos->rowCount();

                $cantidad1              = $_POST['cantidada'][$i];
                $cantidadu1             = $_POST['cantidadua'][$i];
                $cantidad_total1        = (float)$factor*(float)$cantidad1 + (float)$cantidadu1;

                /*regresa el producto al kardex*/

                if($num_reg_articulos>=1)
                {
                	foreach($resultado_articulos as $receta_insumo)
                	{
                	$idarticulo = $receta_insumo['id_insumo'];

                	$query_insumo = $connect->prepare("UPDATE tbl_productos SET stock = stock + ?  WHERE id = ?");
                	$resultado_insumo = $query_insumo->execute([$cantidad_total1*$receta_insumo['cantidad'],$idarticulo]);                                                      

                	}



                }
                else
                {
                $query_stock  = $connect->prepare("UPDATE tbl_productos SET stock = stock + ? WHERE id=?");
                $resultado_stock = $query_stock->execute([$cantidad_total1,$idarticulo]);

                }


                }


                	if($cantidad_total>0)
                	{

                		$insert_query_detalle =$connect->prepare("UPDATE tbl_venta_det SET item=?,idproducto=?,cantidad=?,valor_unitario=?,precio_unitario=?,igv=?,porcentaje_igv=?,valor_total=?,importe_total=?,costo=?,cantidad_factor=?,factor=?,cantidad_unitario=? WHERE idventa=? AND item = ? and idproducto = ?");
                		$resultado_detalle = $insert_query_detalle->execute([$item,$idarticulo,$cantidad_total,$precio_venta_unitario,$precio_venta,$igv_total,18,$valor_total,$importe_total,$costo,$cantidad,$factor,$cantidadu,$_POST['id_nv'],$item,$idarticulo]);
                	}
                	else
                	{
                		$del_pro =$connect->prepare("DELETE FROM tbl_venta_det WHERE idventa=? AND item = ? and idproducto = ?");
                		$resultado_del = $del_pro->execute([$_POST['id_nv'],$item,$idarticulo]);
                	}

                }


        if($tdoc=='01' || $tdoc=='03' || $tdoc == '99')
        {
        //ACTUALIZA STOCK
        //buscar insumos de tabla recetas

        $query_articulos = "SELECT * FROM tbl_recetas WHERE id_producto = '$idarticulo'";
        $resultado_articulos = $connect->prepare($query_articulos);
        $resultado_articulos->execute();
        $num_reg_articulos=$resultado_articulos->rowCount();



        if($num_reg_articulos>=1)
        {
        foreach($resultado_articulos as $receta_insumo)
        {
        $idarticulo = $receta_insumo['id_insumo'];

        $query_insumo = $connect->prepare("UPDATE tbl_productos SET stock = stock - ?  WHERE id = ?");
        $resultado_insumo = $query_insumo->execute([$cantidad_total*$receta_insumo['cantidad'],$idarticulo]);                                                       


        }

        }
        else
        {
        $query_stock  = $connect->prepare("UPDATE tbl_productos SET stock = stock - ? WHERE id=?");
        $resultado_stock = $query_stock->execute([$cantidad_total,$idarticulo]);

        }



        }
        else if($tdoc=='07')
        {
        //ACTUALIZA STOCK

        $query_stock  = $connect->prepare("UPDATE tbl_productos SET stock = stock + ? WHERE id=?");
        $resultado_stock = $query_stock->execute([$cantidad,$idarticulo]);
        }


        }



        $insert_ctemov =$connect->prepare("INSERT INTO tbl_cta_cobrar(tipo,persona,tipo_doc,ser_doc,num_doc,monto,fecha,empresa) VALUES(?,?,?,?,?,?,?,?)");
        $resultado_detalle = $insert_ctemov->execute(['1',$_POST['ruc_persona'],$tdoc,$_POST['serie'],$_POST['numero'],$_POST['total'],$_POST['fecha_emision'],$_POST['empresa']]);



        if($_POST['condicion'] == '1')
        {
        if($visa>0)
        {
        $fdp = '2';
        $query_fdp = $connect->prepare("UPDATE tbl_venta_pago set fdp=?,importe_pago=? WHERE  id_venta=?");
        $resultado_fdp = $query_fdp->execute([$cvisa,$visa,$_POST['id_nv']]);

        $insert_ctemov =$connect->prepare("INSERT INTO tbl_cta_cobrar(tipo,persona,tipo_doc,ser_doc,num_doc,monto,fecha,empresa) VALUES(?,?,?,?,?,?,?,?)");
        $resultado_detalle = $insert_ctemov->execute(['2',$_POST['ruc_persona'],$tdoc,$_POST['serie'],$_POST['numero'],$visa,$_POST['fecha_emision'],$_POST['empresa']]);


        }
        if($efectivo>0)
        {
        $fdp = '1';
        $query_fdp = $connect->prepare("UPDATE tbl_venta_pago set fdp=?,importe_pago=? WHERE  id_venta=?");
        $resultado_fdp = $query_fdp->execute([$fdp,$efectivo,$_POST['id_nv']]);

        $insert_ctemov =$connect->prepare("INSERT INTO tbl_cta_cobrar(tipo,persona,tipo_doc,ser_doc,num_doc,monto,fecha,empresa) VALUES(?,?,?,?,?,?,?,?)");
        $resultado_detalle = $insert_ctemov->execute(['2',$_POST['ruc_persona'],$tdoc,$_POST['serie'],$_POST['numero'],$efectivo+$_POST['vuelto'],$_POST['fecha_emision'],$_POST['empresa']]);
        }

        }
        else
        {
        $cuotas = $_POST['cuotas'];
        $importe_pago  = $_POST['importe_pago_cuota'];
        $num_cuota = 1;

        for($i = 0; $i< count($_POST['datepago']); $i++)
        {
        $fecha_cuota                  = $_POST['datepago'][$i];
        $importe_cuota          = $_POST['montocuota'][$i];

        $query_fdp = $connect->prepare("INSERT INTO tbl_venta_cuota(id_venta,num_cuota,importe_cuota,fecha_cuota) VALUES (?,?,?,?)");
        $resultado_fdp = $query_fdp->execute([$_POST['id_nv'],$num_cuota,$importe_cuota,$fecha_cuota]);


        $num_cuota = $num_cuota+1;



        }

        }
        echo json_encode($_POST['id_nv']);
        exit;
}

// guardar nueva venta
if($_POST['action'] == 'nueva_venta'){
    
        $redondeo = 0;

        $t = explode("-",$_POST['tip_cpe']);
        $cod = $t[0];
        $tdoc = $t[1];
        $moneda = $_POST['moneda'];
        $localemp = $_SESSION['almacen'];

         $d = $_POST['det'];

        $query_det = "SELECT * FROM tbl_por_det WHERE id = $d";
        $resultado_det = $connect->prepare($query_det);
        $resultado_det->execute();
        $row_det = $resultado_det->fetch(PDO::FETCH_ASSOC);

        $por_det = $row_det['por'];
        $cod_det = $row_det['codigo'];
        $importe_det = $_POST['imp_det'];


        if($_POST['ven']==0)
        {
                $vendedor = $_SESSION["id"];
        }
        else
        {
                $vendedor = $_POST['ven'];
        }
        
if(isset($_POST['redondeo']))        {
        if($_POST['redondeo']>0)
        {
            $redondeo = $_POST['redondeo'];
        }
}

$estadopagoanticipo=(isset($_POST['estadopagoanticipo'])) ? $_POST['estadopagoanticipo'] : "0";
$relacionado_id=(isset($_POST['relacionado_id'])) ? $_POST['relacionado_id'] : "0";
$relacionado_serie=(isset($_POST['relacionado_serie'])) ? $_POST['relacionado_serie'] : "";


        $query_cli = $connect->prepare("UPDATE tbl_contribuyente SET correo = ? WHERE id_persona = ?");
        $resultado_cli = $query_cli->execute([$_POST['correo_cliente'],$_POST['id_ruc']]);

        $hora = date('h:i:s');
$query=$connect->prepare("INSERT INTO tbl_venta_cab(idempresa,tipocomp,serie,correlativo,fecha_emision,fecha_vencimiento,condicion_venta,op_gravadas,op_exoneradas,op_inafectas,igv,total,codcliente,vendedor,obs,cuotas_credito,hora_emision,idcliente,por_det,cod_det,imp_det,guia_remision,orden_compra,codmoneda,local,redondeo, relacionado_id, relacionado_serie, estadopagoanticipo) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
$resultado=$query->execute([$_POST['empresa'],$tdoc,$_POST['serie'],$_POST['numero'],$_POST['fecha_emision'],$_POST['fecha_vencimiento'],$_POST['condicion'],$_POST['op_g'],$_POST['op_e'],$_POST['op_i'],$_POST['igv'],$_POST['total'],$_POST['ruc_persona'], $vendedor,$_POST['obs'],$_POST['cuotas'],$hora,$_POST['id_ruc'],$por_det,$cod_det,$importe_det,$_POST['nguiar'],$_POST['orden_compra'],$moneda,$localemp,$redondeo, $relacionado_id, $relacionado_serie, $estadopagoanticipo]);

$lastInsertId = $connect->lastInsertId();

$visa = $_POST['visa'];
$cvisa = $_POST['cvisa'];    
$efectivo = $_POST['efectivo'];
$vuelto   = $_POST['vuelto'];


        //registro detalle venta

        for($i = 0; $i< count($_POST['idarticulo']); $i++)
        {
        $item                  = $_POST['itemarticulo'][$i];
        $idarticulo            = $_POST['idarticulo'][$i];
$nomarticulo=(isset($_POST['nomarticulo'][$i])) ? $_POST['nomarticulo'][$i] : "";
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

/**************buscando el detalle del producto***************/
$query_pro = "SELECT * FROM tbl_productos WHERE id = $idarticulo ";
$resultado_pro=$connect->prepare($query_pro);
$resultado_pro->execute(); 
$row_pro = $resultado_pro->fetch(PDO::FETCH_ASSOC);
$num_reg_pro=$resultado_pro->rowCount();
$des = $row_pro['descripcion'];
/**************fin del detalle******************************/


$insert_query_detalle =$connect->prepare("INSERT INTO tbl_venta_det(idventa,item,idproducto,cantidad,valor_unitario,precio_unitario,igv,porcentaje_igv,valor_total,importe_total,costo,cantidad_factor,factor,cantidad_unitario,mxmn,nombre_producto,descripcion_producto) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
$resultado_detalle = $insert_query_detalle->execute([$lastInsertId,$item,$idarticulo,$cantidad_total,$precio_venta_unitario,$precio_venta,$igv_total,18,$valor_total,$importe_total,$costo,$cantidad,$factor,$cantidadu,$mxmn,$nomarticulo,$des]);

        // actualizar serie + correlativo
        $update_query_serie = $connect->prepare("UPDATE tbl_series SET correlativo = correlativo + ? WHERE serie = ? and correlativo = ? and id_empresa = ?");
        $resultado_serie   = $update_query_serie->execute([1,$_POST['serie'],$_POST['numero'],$_POST['empresa']]);





        if($tdoc=='01' || $tdoc=='03')
        {
        //ACTUALIZA STOCK
        //buscar insumos de tabla recetas

        $query_articulos = "SELECT * FROM tbl_recetas WHERE id_producto = '$idarticulo'";
        $resultado_articulos = $connect->prepare($query_articulos);
        $resultado_articulos->execute();
        $num_reg_articulos=$resultado_articulos->rowCount();



        if($num_reg_articulos>=1)
        {
        foreach($resultado_articulos as $receta_insumo)
        {
        $idarticulo = $receta_insumo['id_insumo'];

        $query_insumo = $connect->prepare("UPDATE tbl_productos SET stock = stock - ?  WHERE id = ?");
        $resultado_insumo = $query_insumo->execute([$cantidad_total*$receta_insumo['cantidad'],$idarticulo]);                                                       


        }

        }
        else
        {
        $query_stock  = $connect->prepare("UPDATE tbl_productos SET stock = stock - ? WHERE id=?");
        $resultado_stock = $query_stock->execute([$cantidad_total,$idarticulo]);

        }



        }
        else if($tdoc=='07')
        {
        //ACTUALIZA STOCK

        $query_stock  = $connect->prepare("UPDATE tbl_productos SET stock = stock + ? WHERE id=?");
        $resultado_stock = $query_stock->execute([$cantidad,$idarticulo]);
        }


        }

        //insert deuda por cobrar

        $insert_ctemov =$connect->prepare("INSERT INTO tbl_cta_cobrar(tipo,persona,tipo_doc,ser_doc,num_doc,monto,fecha,empresa) VALUES(?,?,?,?,?,?,?,?)");
        $resultado_detalle = $insert_ctemov->execute(['1',$_POST['ruc_persona'],$tdoc,$_POST['serie'],$_POST['numero'],$_POST['total'],$_POST['fecha_emision'],$_POST['empresa']]);



        if($_POST['condicion'] == '1')
        {
        if($visa>0)
        {
        $fdp = '2';
        $query_fdp = $connect->prepare("INSERT INTO tbl_venta_pago(id_venta,fdp,importe_pago) VALUES (?,?,?)");
        $resultado_fdp = $query_fdp->execute([$lastInsertId,$cvisa,$visa]);

        $insert_ctemov =$connect->prepare("INSERT INTO tbl_cta_cobrar(tipo,persona,tipo_doc,ser_doc,num_doc,monto,fecha,empresa) VALUES(?,?,?,?,?,?,?,?)");
        $resultado_detalle = $insert_ctemov->execute(['2',$_POST['ruc_persona'],$tdoc,$_POST['serie'],$_POST['numero'],$visa,$_POST['fecha_emision'],$_POST['empresa']]);


        }
        if($efectivo>0)
        {
        $fdp = '1';
        $query_fdp = $connect->prepare("INSERT INTO tbl_venta_pago(id_venta,fdp,importe_pago,fecha_pago) VALUES (?,?,?,?)");
        $resultado_fdp = $query_fdp->execute([$lastInsertId,$fdp,$efectivo,$_POST['fecha_emision']]);

        $insert_ctemov =$connect->prepare("INSERT INTO tbl_cta_cobrar(tipo,persona,tipo_doc,ser_doc,num_doc,monto,fecha,empresa) VALUES(?,?,?,?,?,?,?,?)");
        $resultado_detalle = $insert_ctemov->execute(['2',$_POST['ruc_persona'],$tdoc,$_POST['serie'],$_POST['numero'],$efectivo+$_POST['vuelto'],$_POST['fecha_emision'],$_POST['empresa']]);
        }

        }
        else
        {
        $cuotas = $_POST['cuotas'];
        $importe_pago  = $_POST['importe_pago_cuota'];
        $num_cuota = 1;

        for($i = 0; $i< count($_POST['datepago']); $i++)
        {
        $fecha_cuota            = $_POST['datepago'][$i];
        $importe_cuota          = $_POST['montocuota'][$i];

        $query_fdp = $connect->prepare("INSERT INTO tbl_venta_cuota(id_venta,num_cuota,importe_cuota,fecha_cuota,moneda) VALUES (?,?,?,?,?)");
        $resultado_fdp = $query_fdp->execute([$lastInsertId,$num_cuota,$importe_cuota,$fecha_cuota,$moneda]);


        $num_cuota = $num_cuota+1;



        }

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
        'servidor_link'     =>$row_empresa['link']
        );
        //buscar datos cliente
        

        $query_cliente = "SELECT * FROM tbl_contribuyente WHERE num_doc = $_POST[ruc_persona] AND empresa = $_POST[empresa]";
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

        $lista_cpe_cab = "SELECT * FROM vw_tbl_venta_cab WHERE id=$lastInsertId";
        $resultado_cpe_cab = $connect->prepare($lista_cpe_cab);
        $resultado_cpe_cab->execute();
        $row_cpe_cab = $resultado_cpe_cab->fetch(PDO::FETCH_ASSOC);


/*REVISAMOS A VER SI HAY ANTICIPOS*/
/*REVISAMOS A VER SI HAY ANTICIPOS*/
/*REVISAMOS A VER SI HAY ANTICIPOS*/

$totanticipo='0.00';
$subanticipo='0.00';
$pagadoanticipo='0.00';

$lista_cpe_cab = "SELECT * FROM tbl_venta_cab WHERE id=$lastInsertId";
$resultadoventa = $connect->prepare($lista_cpe_cab);
$resultadoventa->execute();
$rowventa= $resultadoventa->fetch(PDO::FETCH_ASSOC);

if($rowventa['estadopagoanticipo']=='2'){

$lista_cpe_cab = " SELECT *FROM tbl_coti_cab WHERE id='$rowventa[relacionado_id]' ";
$sqlm = $connect->prepare($lista_cpe_cab);
$sqlm->execute();
$most= $sqlm->fetch(PDO::FETCH_ASSOC);

$totanticipo=$most['total'];
$subanticipo=$most['total']-$most['igv'];
$subanticipo=round($subanticipo, 2);

$totalventaanticipo=$rowventa['total']-$rowventa['igv'];
$totalventaorden=$most['total']-$most['igv'];

$pagadoanticipo=$totalventaorden-$totalventaanticipo;
$pagadoanticipo=round($pagadoanticipo, 2);

$totalventa=round($totanticipo-$pagadoanticipo, 2);

$n=0;	
$sqla=" SELECT *FROM tbl_venta_cab WHERE relacionado_id='$rowventa[relacionado_id]' AND estadopagoanticipo='1' ";
$stmt = $connect->prepare($sqla);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_OBJ);
$dataArr = is_array($result) ? $result : array($result);
foreach ( $dataArr ?? [] as $reg ) {
$n=$n+1;
if($reg->op_gravadas>0){
$subtotaldetalle=round($reg->op_gravadas, 2);
}else{
$subtotaldetalle=round($reg->total, 2);
}

$tipocomprobante='03';
if($reg->tipocomp=='01'){ $tipocomprobante='02'; }

$jsonant["orden"]=$n;	
$jsonant["monto"]=$subtotaldetalle;
$jsonant["tipodoc"]=$tipocomprobante;
$jsonant["serienumero"]=$reg->serie.'-'.$reg->correlativo;
$cuerpoant[]=$jsonant;	
}

}else{
$cuerpoant='';
$totalventa=round(($row_cpe_cab['total']-$row_cpe_cab['redondeo']), 2);

}
/*REVISAMOS A VER SI HAY ANTICIPOS*/
/*REVISAMOS A VER SI HAY ANTICIPOS*/
/*REVISAMOS A VER SI HAY ANTICIPOS*/




        $comprobante =  array(
        'tipodoc'            => $row_cpe_cab['tipocomp'], //01->FACTURA, 03->BOLETA, 07->NC, 08->ND
        'serie'              => $row_cpe_cab['serie'],
        'correlativo'        => $row_cpe_cab['correlativo'],
        'fecha_emision'      => $row_cpe_cab['fecha_emision'],
        'fecha_vencimiento'  => $row_cpe_cab['fecha_vencimiento'],
        'condicion_venta'    => $row_cpe_cab['condicion_venta'],
        'cuotas_credito'     => $row_cpe_cab['cuotas_credito'],
        'moneda'             => $row_cpe_cab['codmoneda'], //PEN->SOLES; USD->DOLARES
        'total_opgravadas'   => $row_cpe_cab['op_gravadas'], //OP. GRAVADAS
        'total_opexoneradas' => $row_cpe_cab['op_exoneradas'],
        'total_opinafectas'  => $row_cpe_cab['op_inafectas'],
        'igv'                => $row_cpe_cab['igv'],
        'total'              => $totalventa,
        'cod_det'            => $row_cpe_cab['cod_det'],
        'por_det'            => $row_cpe_cab['por_det'],
        'imp_det'            => $row_cpe_cab['imp_det'],
        'tc'                 => $row_cpe_cab['tc'],
        'total_texto'        => $texto,
        'redondeo'           => $row_cpe_cab['redondeo'],
        /*CUERPO DE ANTICIPOS*/
"totalanticipo"=> $totanticipo,
"subanticipo"=> $subanticipo,
"pagadoanticipo"=> $pagadoanticipo,
"NROACTICIPO"=>$cuerpoant,

        );

//var_dump($comprobante);

        //********************DATOS DE COMPROBANTE - DETALLE*********************//
/*REVISAMOS A VER SI HAY ANTICIPOS*/
/*REVISAMOS A VER SI HAY ANTICIPOS*/
/*REVISAMOS A VER SI HAY ANTICIPOS*/
if($rowventa['estadopagoanticipo']=='2'){

    $lista_cpe_cab = "SELECT * FROM tbl_venta_cab WHERE id=$lastInsertId";
    $resultadoventa = $connect->prepare($lista_cpe_cab);
    $resultadoventa->execute();
    $rowventa= $resultadoventa->fetch(PDO::FETCH_ASSOC);

        //echo 'el id ultimo es '.$lastInsertId;
        $lista_cpe_det = $connect->prepare("SELECT * FROM vw_tbl_coti_det WHERE idventa='$rowventa[relacionado_id]' ");

        $lista_cpe_det->execute();
        $row_cpe_det=$lista_cpe_det->fetchAll(PDO::FETCH_ASSOC);
        //print_r($row_cpe_det);

        $detalle = $row_cpe_det;
//var_dump($detalle);
/*REVISAMOS A VER SI HAY ANTICIPOS*/
/*REVISAMOS A VER SI HAY ANTICIPOS*/
/*REVISAMOS A VER SI HAY ANTICIPOS*/
}else{
        //echo 'el id ultimo es '.$lastInsertId;
        $lista_cpe_det = $connect->prepare("SELECT * FROM vw_tbl_venta_det WHERE idventa=$lastInsertId");

        $lista_cpe_det->execute();
        $row_cpe_det=$lista_cpe_det->fetchAll(PDO::FETCH_ASSOC);
        //print_r($row_cpe_det);

        $detalle = $row_cpe_det;
    }
//var_dump($detalle);
          //*******************DATOS DE LAS CUOTAS *********************//
        
$cuota='';

        if($_POST['condicion'] == '2')
        {
            $lista_cpe_cuo = $connect->prepare("SELECT * FROM tbl_venta_cuota WHERE id_venta=$lastInsertId");
            
            $lista_cpe_cuo->execute();
            $row_cpe_cuo=$lista_cpe_cuo->fetchAll(PDO::FETCH_ASSOC);
            //print_r($row_cpe_det);
            
            $cuota = $row_cpe_cuo;
        }

        $xml->CrearXMLFactura($ruta, $emisor, $cliente, $comprobante, $detalle, $cuota);


        require_once("../../sunat/api/ApiFacturacion.php");

        $objApi = new ApiFacturacion();

        if($row_empresa['envio_automatico']=='SI')
        {
            if($tdoc=='03' && $row_empresa['envio_resumen']=='SI')
            {
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
            
            /*procesando documentos para resumen*/
            
            $cod_sunat = '01999999';
            $msj_sunat = 'Documento preparado para envio Resumen';
            $hash_cdr  = '';
            $idfactura = $lastInsertId;
            
            if($cod_sunat == '01999999')
            {
               $estadofe = '0'; 
            }
            
            $query=$connect->prepare("UPDATE tbl_venta_cab SET hash=?,feestado=? ,fecodigoerror=?,femensajesunat=? WHERE id=?;");
		$resultado=$query->execute([$hash_cdr,$estadofe,$cod_sunat,$msj_sunat,$idfactura]);
            
            $miArray= array
            ("id_emp"      => $row_empresa['ruc'],
             "cod_sunat"   => $cod_sunat,
             "msj_sunat"   => $msj_sunat,
             "hash_cdr"    => $hash_cdr,
             "lastInsertId"=>$idfactura
             
                );
            echo json_encode($miArray);
            
            //echo json_encode($lastInsertId);
            exit;
    
            }
    
            else if($tdoc=='01' || $tdoc=='03')
            {
    
            $respuesta = $objApi->EnviarComprobanteElectronico($emisor,$nombrexml,$connect,$lastInsertId);
    
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
            
            $cod_sunat = $respuesta['cod_sunat'];
            $msj_sunat = $respuesta['msj_sunat'];
            $hash_cdr  = $respuesta['hash_cdr'];
            $idfactura = $lastInsertId;
            $respuestahash = $respuesta['respuestahash'];
            
         
            
            
          /*  if(strlen($cod_sunat)>4)
            {
                $codsunat = explode("-",$cod_sunat);
                $cod_sunat = $codsunat[0];//extraemos la respuesta de error
                if(intval($codsunat)>=0 && is_string(intval($codsunat))=='false')
                {
                    $cod_sunat = $cod_sunat;
                    $msj_sunat = trim($codsunat[1]);
                }
                else
                {
                    $cod_sunat = $codsunat[0];
                    $msj_sunat = trim($cod_sunat);
                    
                }
                
                //$respuesta_sunat = $codsunat[0];//extraemos la respuesta de error
                
                $query_sunat = "SELECT * FROM tbl_error WHERE nombre='$msj_sunat'";//buscamos la respuesta en la tabla de errores
                $resultado_sunat = $connect->prepare($query_sunat);
                $resultado_sunat->execute();
                $row_sunat = $resultado_sunat->fetch(PDO::FETCH_ASSOC);
                
                $cod_sunat = $row_sunat['cod']; //asignamos el codigo de error
                
                
            }*/
            /*$csunat = explode("-",$cod_sunat);
            $cod_sunat = trim($csunat[0]);
            $msj_sunat = trim($csunat[1]);*/
            
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

$query=$connect->prepare("UPDATE tbl_venta_cab SET hash=?,feestado=? ,fecodigoerror=?,femensajesunat=? WHERE id=?;");
$resultado=$query->execute([$hash_cdr,$estadofe,$cod_sunat,$msj_sunat,$idfactura]);
            
            $miArray= array
            ("id_emp"      => $row_empresa['ruc'],
             "cod_sunat" => $cod_sunat,
             "msj_sunat" => $msj_sunat,
             "hash_cdr"  => $hash_cdr,
             "lastInsertId"=>$idfactura
             
                );
            echo json_encode($miArray);
            
           // echo $cod_sunat.'-'.$msj_sunat;
            exit;
            }
        }
        // si envio automatico es NO
        else
        {
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
            
            /*Documento Fcatura aviso para enviar mas tarde*/
            
            $cod_sunat = '01999999';
            $msj_sunat = 'Pendiente de envio';
            $hash_cdr  = '';
            $idfactura = $lastInsertId;
            
            if($cod_sunat == '01999999')
            {
               $estadofe = '0'; 
            }
            
            $query=$connect->prepare("UPDATE tbl_venta_cab SET hash=?,feestado=? ,fecodigoerror=?,femensajesunat=? WHERE id=?;");
		$resultado=$query->execute([$hash_cdr,$estadofe,$cod_sunat,$msj_sunat,$idfactura]);
            
            $miArray= array
            ("id_emp"      => $row_empresa['ruc'],
             "cod_sunat" => $cod_sunat,
             "msj_sunat" => $msj_sunat,
             "hash_cdr"  => $hash_cdr,
             "lastInsertId"=>$idfactura
             
                );
            echo json_encode($miArray);
            
            //echo json_encode($lastInsertId);
            exit;
        }
}

// guardar nueva venta nota de credito

if($_POST['action'] == 'nueva_nota_de_credito')
{

        $tdoc = $_POST['tip_cpe'];
        $t = explode("-", $tdoc);
        $cod  = $t[0];
        $tip  = $t[1];    
        $localemp = $_SESSION['almacen'];

        $porciones = explode("-", $_POST['motivo1']);
        $cmotivo= $porciones[0]; // porción1
        $cdescripcion = $porciones[1]; // porción2
        $query=$connect->prepare("INSERT INTO tbl_venta_cab(idempresa,tipocomp,serie,correlativo,fecha_emision,fecha_vencimiento,condicion_venta,op_gravadas,op_exoneradas,op_inafectas,igv,total,codcliente,tipocomp_ref,serie_ref,correlativo_ref,cod_motivo,des_motivo,vendedor,idcliente,codmoneda,local) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
        $resultado=$query->execute([$_POST['empresa'],$tip,$_POST['serie'],$_POST['numero'],$_POST['fecha_emision'],$_POST['fecha_vencimiento'],$_POST['condicion'],$_POST['op_g'],$_POST['op_e'],$_POST['op_i'],$_POST['igv'],$_POST['total'],$_POST['ruc_persona'],$_POST['cod_doc_ref'],$_POST['serie_ref'],$_POST['num_ref'],$cmotivo,$cdescripcion,$_POST['vendedor'],$_POST['id_ruc'],$_POST['moneda'],$localemp]);



        $lastInsertId = $connect->lastInsertId();
        
        if($_POST['condicion']<>'1')
        {
           $j=1;
            for($i = 0; $i< count($_POST['montocuota']); $i++)
            {
                $idventa   = $lastInsertId;
                $numcuota  = $j;
                $impcuota  = $_POST['montocuota'][$i];
                $feccuota  = $_POST['datepago'][$i];
                
                $insert_query_detalle =$connect->prepare("INSERT INTO tbl_venta_cuota(id_venta,num_cuota,importe_cuota,fecha_cuota) VALUES(?,?,?,?)");
        $resultado_detalle = $insert_query_detalle->execute([$idventa,$numcuota,$impcuota,$feccuota]);
            }
        }
        
        
        //registro detalle compra

        $j=1;
        for($i = 0; $i< count($_POST['idarticulo']); $i++)
        {
        $item                  = $_POST['itemarticulo'][$i];/*item articulo*/
        $idarticulo            = $_POST['idarticulo'][$i];/*id articulo*/
        $nomarticulo           = $_POST['nomarticulo'][$i];/*nombre articulo*/
        $cantidadf              = $_POST['cantidad'][$i];/*cantidad articulo*/
        $factor                = $_POST['factor'][$i];
        $cantidadu             = $_POST['cantidadu'][$i];
        $precio_venta          = $_POST['precio_venta'][$i];/*precio venta*/
        $afectacion            = $_POST['afectacion'][$i];/*afetacion*/
        $tipo_precio           ='01';
        $unidad                = 'NIU';
        
        
        $cantidad = floatval($cantidadf)*floatval($factor) + floatval($cantidadu);
       

        if($afectacion == '10')
        {
        $valor_unitario = $precio_venta / 1.18;
        }
        else
        {
        $valor_unitario = $precio_venta;

        }
        $igv = ($cantidad*$precio_venta) -($cantidad*$valor_unitario);

        $insert_query_detalle =$connect->prepare("INSERT INTO tbl_venta_det(idventa,item,idproducto,nombre_producto,cantidad_factor,factor,cantidad_unitario,cantidad,valor_unitario,precio_unitario,igv,porcentaje_igv,valor_total,importe_total) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $resultado_detalle = $insert_query_detalle->execute([$lastInsertId,$j,$idarticulo,$nomarticulo ,$cantidadf,$factor,$cantidadu,$cantidad,$valor_unitario,$precio_venta,$igv,18,($cantidad*$valor_unitario),($cantidad*$precio_venta)]);

        // actualizar serie + correlativo
        $update_query_serie = $connect->prepare("UPDATE tbl_series SET correlativo = correlativo + ? WHERE serie = ? and correlativo = ? and id_empresa = ?");
        $resultado_serie   = $update_query_serie->execute([1,$_POST['serie'],$_POST['numero'],$_POST['empresa']]);

        $j = $j+1;

        if($cmotivo=='01' || $cmotivo=='02'  )
        {
        //buscar insumos de tabla recetas

        $query_articulos = "SELECT * FROM tbl_recetas WHERE id_producto = '$idarticulo'";
        $resultado_articulos = $connect->prepare($query_articulos);
        $resultado_articulos->execute();
        //$row_articulos = $resultado_articulos->fetch(PDO::FETCH_ASSOC);
        foreach($resultado_articulos as $receta_insumo)
        {
        //actualizar stock insumos
        if($tip=='01' || $tip=='03')
        {
        $query_insumo = $connect->prepare("UPDATE tbl_insumos SET stock = stock - ?  WHERE id = ?");
        $resultado_insumo = $query_insumo->execute([$cantidad*$receta_insumo['cantidad'],$receta_insumo['id_insumo']]);
        }
        else if($tip=='07')
        {
        $query_insumo = $connect->prepare("UPDATE tbl_insumos SET stock = stock + ?  WHERE id = ?");
        $resultado_insumo = $query_insumo->execute([$cantidad*$receta_insumo['cantidad'],$receta_insumo['id_insumo']]);
        }


        }
        }

        }

        //insert deuda por cobrar

        $insert_ctemov =$connect->prepare("INSERT INTO tbl_cta_cobrar(tipo,persona,tipo_doc,ser_doc,num_doc,monto,fecha,empresa) VALUES(?,?,?,?,?,?,?,?)");
        $resultado_detalle = $insert_ctemov->execute(['2',$_POST['ruc_persona'],$tip,$_POST['serie'],$_POST['numero'],$_POST['total'],$_POST['fecha_emision'],$_POST['empresa']]);

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
        $nombrexml = $row_empresa['ruc'].'-'.$tip.'-'.$_POST['serie'].'-'.$_POST['numero'];

        $ruta = "../../sunat/".$row_empresa['ruc']."/xml/".$nombrexml;
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
        'servidor_link'     =>$row_empresa['link']
        );
        //buscar datos cliente

        $query_cliente = "SELECT * FROM tbl_contribuyente WHERE num_doc = $_POST[ruc_persona]";
        $resultado_cliente = $connect->prepare($query_cliente);
        $resultado_cliente->execute();
        $row_cliente = $resultado_cliente->fetch(PDO::FETCH_ASSOC);

        $cliente = array(
        'tipodoc'       => $row_cliente['tipo_doc'],//6->ruc, 1-> dni 
        'ruc'           => $row_cliente['num_doc'], 
        'razon_social'  => $row_cliente['nombre_persona'], 
        'direccion'     => $row_cliente['direccion_persona'],
        'pais'          => 'PE'
        );  

        $numero = $_POST['total'];
        include 'numeros.php';
        $texto=convertir($numero);

        $comprobante =  array(
        'tipodoc'       => $tip, //01->FACTURA, 03->BOLETA, 07->NC, 08->ND
        'serie'         => $_POST['serie'],
        'correlativo'   => $_POST['numero'],
        'fecha_emision' => $_POST['fecha_emision'],
        'moneda'        => $_POST['moneda'], //PEN->SOLES; USD->DOLARES
        'total_opgravadas'=> $_POST['op_g'], //OP. GRAVADAS
        'total_opexoneradas'=>$_POST['op_e'],
        'total_opinafectas'=>$_POST['op_i'],
        'igv'           => $_POST['igv'],
        'total'         => $_POST['total'],
        'total_texto'   => $texto,
        'condicion_venta'   =>'1',
        'tipodoc_ref'   => $_POST['cod_doc_ref'], //FACTURA
        'serie_ref'     => $_POST['serie_ref'],
        'correlativo_ref'=> $_POST['num_ref'],
        'codmotivo'     => $cmotivo,
        'descripcion'   => $cdescripcion
        );


        //********************DATOS DE COMPROBANTE - DETALLE*********************//

        //echo 'el id ultimo es '.$lastInsertId;
        $lista_cpe_det = $connect->prepare("SELECT * FROM vw_tbl_venta_det WHERE idventa=$lastInsertId");

        $lista_cpe_det->execute();
        $row_cpe_det=$lista_cpe_det->fetchAll(PDO::FETCH_ASSOC);
        //print_r($row_cpe_det);

        $detalle = $row_cpe_det;

        // var_dump($detalle1);



        $xml->CrearXMLNotaCredito($ruta, $emisor, $cliente, $comprobante, $detalle);


        require_once("../../sunat/api/ApiFacturacion.php");

        $objApi = new ApiFacturacion();

        if($row_empresa['envio_automatico']=='SI')
        {
        if($_POST['tip_cpe']=='07' && $row_empresa['envio_resumen']=='SI')
        {
        require_once("phpqrcode/qrlib.php");
        //CREAR QR INICIO
        //codigo qr
        /*RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | 
        MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION |TIPO DE DOCUMENTO ADQUIRENTE |
        NUMERO DE DOCUMENTO ADQUIRENTE |*/

        $ruc = $row_empresa['ruc'];
        $tipo_documento = $tip; //factura
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

        echo json_encode($lastInsertId);
        exit;

        }

        else
        {

        $objApi->EnviarComprobanteElectronico($emisor,$nombrexml,$connect,$lastInsertId);

        require_once("phpqrcode/qrlib.php");
        //CREAR QR INICIO
        //codigo qr
        /*RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | 
        MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION |TIPO DE DOCUMENTO ADQUIRENTE |
        NUMERO DE DOCUMENTO ADQUIRENTE |*/

        $ruc = $row_empresa['ruc'];
        $tipo_documento = $tip; //factura
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

        echo json_encode($lastInsertId);
        exit;
        }
        }
        // si envio automatico es NO
        else
        {
        require_once("phpqrcode/qrlib.php");
        //CREAR QR INICIO
        //codigo qr
        /*RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | 
        MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION |TIPO DE DOCUMENTO ADQUIRENTE |
        NUMERO DE DOCUMENTO ADQUIRENTE |*/

        $ruc = $row_empresa['ruc'];
        $tipo_documento = $tip; //factura
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

        echo json_encode($lastInsertId);
        exit;
        }
}


// guardar re-envia sunat

if($_POST['action'] == 'sunat')
{

                $id_venta = $_POST['enviar_id'];            


                //insert deuda por cobrar

                require_once("../../sunat/api/xml.php");


                $xml = new GeneradorXML();
                //buscar ruc emisor

                $query_empresa = "SELECT * FROM vw_tbl_empresas WHERE id_empresa = $_POST[empresa_id]";
                $resultado_empresa = $connect->prepare($query_empresa);
                $resultado_empresa->execute();
                $row_empresa = $resultado_empresa->fetch(PDO::FETCH_ASSOC);


                $query_cab = "SELECT * FROM vw_tbl_venta_cab WHERE id = $_POST[enviar_id]";
                $resultado_cab = $connect->prepare($query_cab);
                $resultado_cab->execute();
                $row_cab = $resultado_cab->fetch(PDO::FETCH_ASSOC);


                //RUC DEL EMISOR - TIPO DE COMPROBANTE - SERIE DEL DOCUMENTO - CORRELATIVO
                //01-> FACTURA, 03-> BOLETA, 07-> NOTA DE CREDITO, 08-> NOTA DE DEBITO, 09->GUIA DE REMISION
                $nombrexml = $row_empresa['ruc'].'-'.$row_cab['tipocomp'].'-'.$row_cab['serie'].'-'.$row_cab['correlativo'];

                $ruta = "../../sunat/".$row_empresa['ruc']."/xml/".$nombrexml;
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
                'servidor_link'     =>$row_empresa['link']
                );
                //buscar datos cliente
                $num_doc = $_POST['ruc_id'];
                //echo 'cliente :'.$num_doc;
                $query_cliente = "SELECT * FROM tbl_contribuyente WHERE id_persona = $num_doc ";
                
                //echo $query_cliente.'- este ees el query';
                $resultado_cliente = $connect->prepare($query_cliente);
                $resultado_cliente->execute();
                $row_cliente = $resultado_cliente->fetch(PDO::FETCH_ASSOC);
                //print_r($row_cliente);
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
                'pais'          => 'PE'
                );  


                $numero = $row_cab['total'];
                include 'numeros.php';
                $texto=convertir($numero);

                if($row_cab['tipocomp']=='01' || $row_cab['tipocomp'] =='03')
                {

                        $comprobante =  array(
                        'tipodoc'            => $row_cab['tipocomp'], //01->FACTURA, 03->BOLETA, 07->NC, 08->ND
                        'serie'              => $row_cab['serie'],
                        'correlativo'        => $row_cab['correlativo'],
                        'fecha_emision'      => $row_cab['fecha_emision'],
                        'fecha_vencimiento'  => $row_cab['fecha_vencimiento'],
                        'condicion_venta'    => $row_cab['condicion_venta'],
                        'cuotas_credito'     => $row_cab['cuotas_credito'],
                        'moneda'             => $row_cab['codmoneda'], //PEN->SOLES; USD->DOLARES
                        'total_opgravadas'   => $row_cab['op_gravadas'], //OP. GRAVADAS
                        'total_opexoneradas' => $row_cab['op_exoneradas'],
                        'total_opinafectas'  => $row_cab['op_inafectas'],
                        'igv'                => $row_cab['igv'],
                        'total'              => ($row_cab['total']-$row_cab['redondeo']),
                       'cod_det'            => $row_cpe_cab['cod_det'],
                        'por_det'            => $row_cpe_cab['por_det'],
                        'imp_det'            => $row_cpe_cab['imp_det'],
                        'tc'                 => $row_cpe_cab['tc'],
                        'total_texto'        => $texto,
                        'redondeo'           => $row_cab['redondeo']
                        );
                }
                else if($row_cab['tipocomp'] == '07' || $row_cab['tipocomp'] == '08')
                {
                        $comprobante =  array(
                        'tipodoc'       => $row_cab['tipocomp'],//01->FACTURA, 03->BOLETA, 07->NC, 08->ND
                        'serie'         => $row_cab['serie'],
                        'correlativo'   => $row_cab['correlativo'],
                        'fecha_emision' => $row_cab['fecha_emision'],
                         'fecha_vencimiento' => $row_cab['fecha_emision'],
                        'moneda'        => $row_cab['codmoneda'], //PEN->SOLES; USD->DOLARES
                        'total_opgravadas'=> $row_cab['op_gravadas'], //OP. GRAVADAS
                        'total_opexoneradas'=>$row_cab['op_exoneradas'],
                        'total_opinafectas'=>$row_cab['op_inafectas'],
                        'igv'           => $row_cab['igv'],
                        'total'         => $row_cab['total'],
                        'total_texto'   => $texto,
                        'tipodoc_ref'   => $row_cab['tipocomp_ref'], //FACTURA
                        'serie_ref'     => $row_cab['serie_ref'],
                        'correlativo_ref'=> $row_cab['correlativo_ref'],
                        'codmotivo'     => $row_cab['cod_motivo'],
                        'por_det'            => 0,
                        'condicion_venta'    => '1',
                        'descripcion'   => $row_cab['des_motivo']
                        );
                }




                //********************DATOS DE COMPROBANTE - DETALLE*********************//

                //echo 'el id ultimo es '.$lastInsertId;
                $lista_cpe_det = $connect->prepare("SELECT * FROM vw_tbl_venta_det WHERE idventa=$_POST[enviar_id]");

                $lista_cpe_det->execute();
                $row_cpe_det=$lista_cpe_det->fetchAll(PDO::FETCH_ASSOC);
                //print_r($row_cpe_det);

                $detalle = $row_cpe_det;

                // var_dump($detalle1);
                
                  if($row_cab['condicion_venta'] == '2')
        {
            $lista_cpe_cuo = $connect->prepare("SELECT * FROM tbl_venta_cuota WHERE id_venta=$_POST[enviar_id]");
            
            $lista_cpe_cuo->execute();
            $row_cpe_cuo=$lista_cpe_cuo->fetchAll(PDO::FETCH_ASSOC);
            //print_r($row_cpe_det);
            
            $cuota = $row_cpe_cuo;
        }
 // 'condicion: '.$row_cab['condicion_venta'];

            if($row_cab['tipocomp']=='01' || $row_cab['tipocomp'] =='03')
                {
                $xml->CrearXMLFactura($ruta, $emisor, $cliente, $comprobante, $detalle,$cuota);
                }
                else if($row_cab['tipocomp'] == '07' || $row_cab['tipocomp'] == '08')
                {
                 $xml->CrearXMLNotaCredito($ruta, $emisor, $cliente, $comprobante, $detalle);   
                }



                require_once("../../sunat/api/ApiFacturacion.php");

                $objApi = new ApiFacturacion();

                $enviar_id=$_POST['enviar_id'];
                 $respuesta = $objApi->EnviarComprobanteElectronico($emisor,$nombrexml,$connect,$enviar_id);


                require_once("phpqrcode/qrlib.php");
                //CREAR QR INICIO
                //codigo qr
                /*RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | 
                MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION |TIPO DE DOCUMENTO ADQUIRENTE |
                NUMERO DE DOCUMENTO ADQUIRENTE |*/

                $ruc = $row_empresa['ruc'];
                $tipo_documento = $row_cab['tipocomp']; //factura
                $serie = $row_cab['serie'];
                $correlativo = $row_cab['correlativo'];
                $igv = $row_cab['igv'];
                $total = $row_cab['total'];
                $fecha = $row_cab['fecha_emision'];
                $tipodoccliente = $row_cliente['tipo_doc'];
                $nro_doc_cliente = $row_cliente['num_doc'];

                $nombrexml = $ruc."-".$tipo_documento."-".$serie."-".$correlativo;
                $text_qr = $ruc." | ".$tipo_documento." | ".$serie." | ".$correlativo." | ".$igv." | ".$total." | ".$fecha." | ".$tipodoccliente." | ".$nro_doc_cliente;
                $ruta_qr = '../../sunat/'.$row_empresa['ruc'].'/qr/'.$nombrexml.'.png';

                QRcode::png($text_qr, $ruta_qr, 'Q',15, 0);

            $cod_sunat = $respuesta['cod_sunat'];
            //$cod_sunatx = $respuesta['cod_sunat'];
            $msj_sunat = $respuesta['msj_sunat'];
            $hash_cdr  = $respuesta['hash_cdr'];
            $idfactura = $id_venta;
            $httpcode  = $respuesta['httpcode'];
            $respcurl  = $respuesta['respcurl'];
            $respuestahash = $respuesta['respuestahash'];
            $rutacert     = $respuesta['rutacert'];
            $cod_sunat1 = $mensaje['cod_sunat1'];
            
           /* if(strlen($cod_sunat)>4)
            {
                $codsunat      = explode("-",$cod_sunat);
                $codigo_error  = trim($codsunat[0]);
                $mensaje_error = trim($codsunat[1]); 
                $cod_sunat     = trim($codsunat[0]);//extraemos la respuesta de error
                
                if(intval($cod_sunat)>=0 && is_string(intval($cod_sunat))=='false')
                {
                    $cod_sunat = $cod_sunat;
                    $msj_sunat = trim($codsunat[1]);
                    $msj_if = 'if';
                }
                else
                {
                    $cod_sunat = $codsunat[0];
                    $msj_sunat = trim($cod_sunat);
                    $msj_if = 'else';
                    
                }
                
                //$respuesta_sunat = $codsunat[0];//extraemos la respuesta de error
                
                $query_sunat = "SELECT * FROM tbl_error WHERE nombre='$msj_sunat'";//buscamos la respuesta en la tabla de errores
                $resultado_sunat = $connect->prepare($query_sunat);
                $resultado_sunat->execute();
                $row_sunat = $resultado_sunat->fetch(PDO::FETCH_ASSOC);
                
                $cod_sunat = $row_sunat['cod']; //asignamos el codigo de error
                
                
            }*/
            $csunat = explode("-",$cod_sunat);
            $cod_sunat = trim($csunat[0]);
            $msj_sunat = trim($csunat[1]);
            
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
        
       $query=$connect->prepare("UPDATE tbl_venta_cab SET hash=?,feestado=? ,fecodigoerror=?,femensajesunat=? WHERE id=?;");
		$resultado=$query->execute([$hash_cdr,$estadofe,$cod_sunat,$msj_sunat,$id_venta]);
            $miArray= array
            ("cod_sunat" => $cod_sunat,
             "cod_sunat1" => $cod_sunat1,
             "msj_sunat" => $msj_sunat,
             "hash_cdr"  => $hash_cdr,
             "lastInsertId"=>$id_venta,
             "httpcode"  => $httpcode,
             "codigo_error" =>  $codigo_error,
             "msj_if"     => $msj_if
             
                );
            echo json_encode($miArray);
            
           
                //echo json_encode($_POST['enviar_id']);
                exit;



}
// guardar nueva nota de venta

if($_POST['action'] == 'nota_venta')
{

                $tdoc = $_POST['tip_cpe'];
                $t = explode("-", $tdoc);
                $cod  = $t[0];
                $tip  = $t[1];  
                $hora = date('h:i:s');
                $query=$connect->prepare("INSERT INTO tbl_venta_cab(idempresa,tipocomp,serie,correlativo,fecha_emision,fecha_vencimiento,condicion_venta,op_gravadas,op_exoneradas,op_inafectas,igv,total,codcliente,vendedor,obs,hora_emision,idcliente) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
                $resultado=$query->execute([$_POST['empresa'], $tip ,$_POST['serie'],$_POST['numero'],$_POST['fecha_emision'],$_POST['fecha_vencimiento'],$_POST['condicion'],$_POST['op_g'],$_POST['op_e'],$_POST['op_i'],$_POST['igv'],$_POST['total'],$_POST['ruc_persona'], $_SESSION["id"], $_POST["obs"],$hora,$_POST['id_ruc']]);
                $lastInsertId = $connect->lastInsertId();
                //registro detalle compra
                $visa = $_POST['visa'];    
                $efectivo = $_POST['efectivo'];



                for($i = 0; $i< count($_POST['idarticulo']); $i++)
                {
                $item                  = $_POST['itemarticulo'][$i];
                $idarticulo            = $_POST['idarticulo'][$i];
                $nomarticulo           = $_POST['nomarticulo'][$i];
                $cantidad              = $_POST['cantidad'][$i];
                $mxmn                  = $_POST['mxmn'][$i];
                $afectacion            = $_POST['afectacion'][$i];
                $tipo_precio           = '01';
                $unidad                = 'NIU';
                $costo                 =  $_POST['precio_compra'][$i];
                $factor                = $_POST['factor'][$i];
                $cantidadu             = $_POST['cantidadu'][$i];
                $cantidad_total        = $factor*$cantidad + $cantidadu;

                $valor_unitario_total  = $_POST['valor_unitario'][$i]/$factor;
                $igv_unitario          = 0;
                $precio_venta          = $_POST['precio_venta'][$i]/$factor;

                $precio_unitario       = ($precio_venta - ($igv_unitario/$cantidad_total));
                $precio_venta_total    = $precio_venta*$cantidad_total;
                $igv_total             = $precio_venta_total - $valor_unitario_total;
                $precio_compra            = $_POST['precio_compra'][$i];

                /* if($afectacion == '10')
                {
                $valor_unitario = $precio_venta / 1.18;
                }
                else
                {
                $valor_unitario = $precio_venta;

                }
                $igv = $precio_venta - $valor_unitario;*/

                $insert_query_detalle =$connect->prepare("INSERT INTO tbl_venta_det(idventa,item,idproducto,cantidad,valor_unitario,precio_unitario,igv,porcentaje_igv,valor_total,importe_total,costo,cantidad_factor,factor,cantidad_unitario,mxmn) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $resultado_detalle = $insert_query_detalle->execute([$lastInsertId,$item,$idarticulo,$cantidad_total,$precio_unitario,$precio_venta,$igv_total,18,($cantidad_total*$precio_unitario*$factor),($cantidad_total*$precio_venta),$costo,$cantidad,$factor,$cantidadu,$mxmn]);

                // actualizar serie + correlativo
                $update_query_serie = $connect->prepare("UPDATE tbl_series SET correlativo = correlativo + ? WHERE serie = ? and correlativo = ? and id_empresa = ?");
                $resultado_serie   = $update_query_serie->execute([1,$_POST['serie'],$_POST['numero'],$_POST['empresa']]); 

                $query_stock  = $connect->prepare("UPDATE tbl_productos SET stock = stock - ? WHERE id=?");
                $resultado_stock = $query_stock->execute([$cantidad_total,$idarticulo]);                                                           

                }

                if($visa>0)
                {
                $fdp = '2';
                $query_fdp = $connect->prepare("INSERT INTO tbl_venta_pago(id_venta,fdp,importe_pago) VALUES (?,?,?)");
                $resultado_fdp = $query_fdp->execute([$lastInsertId,$fdp,$visa]);

                $insert_ctemov =$connect->prepare("INSERT INTO tbl_cta_cobrar(tipo,persona,tipo_doc,ser_doc,num_doc,monto,fecha,empresa) VALUES(?,?,?,?,?,?,?,?)");
                $resultado_detalle = $insert_ctemov->execute(['2',$_POST['ruc_persona'], $tip ,$_POST['serie'],$_POST['numero'],$visa,$_POST['fecha_emision'],$_POST['empresa']]);


                }
                if($efectivo>0)
                {
                $fdp = '1';
                $query_fdp = $connect->prepare("INSERT INTO tbl_venta_pago(id_venta,fdp,importe_pago) VALUES (?,?,?)");
                $resultado_fdp = $query_fdp->execute([$lastInsertId,$fdp,$efectivo]);

                $insert_ctemov =$connect->prepare("INSERT INTO tbl_cta_cobrar(tipo,persona,tipo_doc,ser_doc,num_doc,monto,fecha,empresa) VALUES(?,?,?,?,?,?,?,?)");
                $resultado_detalle = $insert_ctemov->execute(['2',$_POST['ruc_persona'], $tip ,$_POST['serie'],$_POST['numero'],$efectivo+$_POST['vuelto'],$_POST['fecha_emision'],$_POST['empresa']]);
                }






                $query_empresa = "SELECT * FROM tbl_empresas WHERE id_empresa = $_POST[empresa]";
                $resultado_empresa = $connect->prepare($query_empresa);
                $resultado_empresa->execute();
                $row_empresa = $resultado_empresa->fetch(PDO::FETCH_ASSOC);



                $emisor =   array(
                'tipodoc'       => '6',
                'ruc'           => $row_empresa['ruc'], 
                'razon_social'  => $row_empresa['razon_social'], 
                'nombre_comercial'  => ' ', 
                'direccion'     => $row_empresa['direccion'], 
                'pais'          => 'PE', 
                'departamento'  => $row_empresa['departamento'],//LAMBAYEQUE 
                'provincia'     => $row_empresa['provincia'],//CHICLAYO 
                'distrito'      => $row_empresa['distrito'], //CHICLAYO
                'ubigeo'        => $row_empresa['ubigeo'], //CHICLAYO
                'usuario_sol'   => $row_empresa['usuario_sol'], //USUARIO SECUNDARIO EMISOR ELECTRONICO
                'clave_sol'     => $row_empresa['clave_sol'], //CLAVE DE USUARIO SECUNDARIO EMISOR ELECTRONICO
                'certificado'  => $row_empresa['certificado'],
                'clave_certificado'  =>$row_empresa['clave_certificado']
                );
                //buscar datos cliente

                $query_cliente = "SELECT * FROM tbl_contribuyente WHERE num_doc = $_POST[ruc_persona]";
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
                'pais'          => 'PE'
                );  
                $numero = $_POST['total'];
                include 'numeros.php';
                $texto=convertir($numero);

                $comprobante =  array(
                'tipodoc'       =>  $tip , //01->FACTURA, 03->BOLETA, 07->NC, 08->ND
                'serie'         => $_POST['serie'],
                'correlativo'   => $_POST['numero'],
                'fecha_emision' => $_POST['fecha_emision'],
                'moneda'        => 'PEN', //PEN->SOLES; USD->DOLARES
                'total_opgravadas'=> $_POST['op_g'], //OP. GRAVADAS
                'total_opexoneradas'=>$_POST['op_e'],
                'total_opinafectas'=>$_POST['op_i'],
                'igv'           => $_POST['igv'],
                'total'         => $_POST['total'],
                'total_texto'   => $texto
                );

                //********************DATOS DE COMPROBANTE - DETALLE*********************//

                //echo 'el id ultimo es '.$lastInsertId;
                $lista_cpe_det = $connect->prepare("SELECT * FROM vw_tbl_venta_det WHERE idventa=$lastInsertId");

                $lista_cpe_det->execute();
                $row_cpe_det=$lista_cpe_det->fetchAll(PDO::FETCH_ASSOC);
                //print_r($row_cpe_det);

                $detalle = $row_cpe_det;

                // var_dump($detalle1);






                require_once("phpqrcode/qrlib.php");
                //CREAR QR INICIO
                //codigo qr
                /*RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | 
                MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION |TIPO DE DOCUMENTO ADQUIRENTE |
                NUMERO DE DOCUMENTO ADQUIRENTE |*/

                $ruc = $row_empresa['ruc'];
                $tipo_documento =  $tip ; //factura
                $serie = $_POST['serie'];
                $correlativo = $_POST['numero'];
                $igv = $_POST['igv'];
                $total = $_POST['total'];
                $fecha = $_POST['fecha_emision'];
                $tipodoccliente = $row_cliente['tipo_doc'];
                $nro_doc_cliente = $row_cliente['num_doc'];

                $nombrexml = $ruc."-".$tipo_documento."-".$serie."-".$correlativo;
                $text_qr = $ruc." | ".$tipo_documento." | ".$serie." | ".$correlativo." | ".$igv." | ".$total." | ".$fecha." | ".$tipodoccliente." | ".$nro_doc_cliente;
                // $ruta_qr = '../../sunat/qr/'.$nombrexml.'.png';
                $ruta_qr = '../../sunat/'.$row_empresa['ruc'].'/qr/'.$nombrexml.'.png';
                QRcode::png($text_qr, $ruta_qr, 'Q',15, 0);

                echo json_encode($lastInsertId);
                exit;

}

////////////////////////////guardar pos


if($_POST['action'] == 'nueva_venta_pos')
{
$redondeo = 0;

                $t = explode("-",$_POST['tip_cpe']);
                $cod = $t[0];
                $tdoc = $t[1];
               $localemp = $_SESSION['almacen'];
                $d = '1';
                $query_det = "SELECT * FROM tbl_por_det WHERE id = $d";
                $resultado_det = $connect->prepare($query_det);
                $resultado_det->execute();
                $row_det = $resultado_det->fetch(PDO::FETCH_ASSOC);
                
                $por_det = $row_det['por'];
                $cod_det = $row_det['codigo'];
                $importe_det = 0;
                $cotidet = $_POST['id_coti_ref'];
                
                if($_POST['ven']==0)
                {
                $vendedor = $_SESSION["id"];
                }
                else
                {
                $vendedor = $_POST['ven'];
                }
                $obs = '';
                $cuotas_credito=0;
                $hora = date('h:i:s');
                
                $num_det ='';
                $guiarem = '';
                $ocompra = '';
                $moneda = 'PEN';
                
                 if($_POST['redondeo']>0)
        {
            $redondeo = $_POST['redondeo'];
        }
                
$query=$connect->prepare("INSERT INTO tbl_venta_cab(idempresa,tipocomp,serie,correlativo,fecha_emision,fecha_vencimiento,condicion_venta,op_gravadas,op_exoneradas,op_inafectas,igv,total,codcliente,vendedor,obs,cuotas_credito,hora_emision,idcliente,por_det,cod_det,imp_det,guia_remision,orden_compra,codmoneda,local,redondeo) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
$resultado=$query->execute([$_POST['empresa'],$tdoc,$_POST['serie'],$_POST['numero'],$_POST['fecha_emision'],$_POST['fecha_vencimiento'],$_POST['condicion'],$_POST['op_g'],$_POST['op_e'],$_POST['op_i'],$_POST['igv'],$_POST['total'],$_POST['ruc_persona'], $vendedor,$obs,$cuotas_credito,$hora,$_POST['id_ruc'],$por_det,$cod_det,$importe_det,$guiarem,$ocompra,$moneda,$localemp,$redondeo]);

$lastInsertId = $connect->lastInsertId();

                $visa = $_POST['visa'];
                $cvisa = $_POST['cvisa'];    
                $efectivo = $_POST['efectivo'];
                $vuelto   = $_POST['vuelto'];

   if($cotidet > 0)
  {
    $query_coti=$connect->prepare("UPDATE tbl_coti_cab SET acuenta=? WHERE id = ?");
	$resultado_coti = $query_coti->execute([$_POST['total'],$cotidet]);
  }
                //registro detalle compra


                for($i = 0; $i< count($_POST['idarticulo']); $i++)
                {
                $item                  = $_POST['itemarticulo'][$i];
                $idarticulo            = $_POST['idarticulo'][$i];
                $nomarticulo           = $_POST['nomarticulo'][$i];
                $cantidad              = $_POST['cantidad'][$i];

                $afectacion            = $_POST['afectacion'][$i];
                $tipo_precio           = '01';
                $unidad                = 'NIU';
                $costo                 =  $_POST['precio_compra'][$i];
                $factor                = $_POST['factor'][$i];
                $cantidadu             = $_POST['cantidadu'][$i];
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

                $igv_total             = $importe_total - $valor_total;
                $precio_compra            = $_POST['precio_compra'][$i];
    /**************buscando el detalle del producto***************/
    $query_pro = "SELECT * FROM tbl_productos WHERE id = $idarticulo ";
    $resultado_pro=$connect->prepare($query_pro);
    $resultado_pro->execute(); 
    $row_pro = $resultado_pro->fetch(PDO::FETCH_ASSOC);
    $num_reg_pro=$resultado_pro->rowCount();
    $des = $row_pro['descripcion'];
    /**************fin del detalle******************************/




    $insert_query_detalle =$connect->prepare("INSERT INTO tbl_venta_det(idventa,item,idproducto,cantidad,valor_unitario,precio_unitario,igv,porcentaje_igv,valor_total,importe_total,costo,cantidad_factor,factor,cantidad_unitario,mxmn,nombre_producto,descripcion_producto) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $resultado_detalle = $insert_query_detalle->execute([$lastInsertId,$item,$idarticulo,$cantidad_total,$precio_venta_unitario,$precio_venta,$igv_total,18,$valor_total,$importe_total,$costo,$cantidad,$factor,$cantidadu,$mxmn,$nomarticulo,$des]);

                // actualizar serie + correlativo
                $update_query_serie = $connect->prepare("UPDATE tbl_series SET correlativo = correlativo + ? WHERE serie = ? and correlativo = ? and id_empresa = ?");
                $resultado_serie   = $update_query_serie->execute([1,$_POST['serie'],$_POST['numero'],$_POST['empresa']]);


        if($cotidet > 0)
            {
        $query_coti_det=$connect->prepare("UPDATE tbl_coti_det SET entregado=? WHERE idventa = ? AND idproducto = ?");
    	$resultado_coti_det = $query_coti_det->execute([$cantidad_total,$cotidet,$idarticulo ]);
         }




                if($tdoc=='01' || $tdoc=='03' || $tdoc=='99')
                {
                //ACTUALIZA STOCK
                //buscar insumos de tabla recetas

                $query_articulos = "SELECT * FROM tbl_recetas WHERE id_producto = '$idarticulo'";
                $resultado_articulos = $connect->prepare($query_articulos);
                $resultado_articulos->execute();
                $num_reg_articulos=$resultado_articulos->rowCount();



                if($num_reg_articulos>=1)
                {
                foreach($resultado_articulos as $receta_insumo)
                {
                $idarticulo = $receta_insumo['id_insumo'];

                $query_insumo = $connect->prepare("UPDATE tbl_productos SET stock = stock - ?  WHERE id = ?");
                $resultado_insumo = $query_insumo->execute([$cantidad_total*$receta_insumo['cantidad'],$idarticulo]);                                                       


                }

                }
                else
                {
                $query_stock  = $connect->prepare("UPDATE tbl_productos SET stock = stock - ? WHERE id=?");
                $resultado_stock = $query_stock->execute([$cantidad_total,$idarticulo]);

                }



                }
                else if($tdoc=='07')
                {
                //ACTUALIZA STOCK

                $query_stock  = $connect->prepare("UPDATE tbl_productos SET stock = stock + ? WHERE id=?");
                $resultado_stock = $query_stock->execute([$cantidad,$idarticulo]);
                }


                }

                //insert deuda por cobrar

                $insert_ctemov =$connect->prepare("INSERT INTO tbl_cta_cobrar(tipo,persona,tipo_doc,ser_doc,num_doc,monto,fecha,empresa) VALUES(?,?,?,?,?,?,?,?)");
                $resultado_detalle = $insert_ctemov->execute(['1',$_POST['ruc_persona'],$tdoc,$_POST['serie'],$_POST['numero'],$_POST['total'],$_POST['fecha_emision'],$_POST['empresa']]);


                if($_POST['condicion'] == '1')
                {
                if($visa>0)
                {
                $fdp = '2';
                $query_fdp = $connect->prepare("INSERT INTO tbl_venta_pago(id_venta,fdp,importe_pago) VALUES (?,?,?)");
                $resultado_fdp = $query_fdp->execute([$lastInsertId,$cvisa,$visa]);
                
                $insert_ctemov =$connect->prepare("INSERT INTO tbl_cta_cobrar(tipo,persona,tipo_doc,ser_doc,num_doc,monto,fecha,empresa) VALUES(?,?,?,?,?,?,?,?)");
                $resultado_detalle = $insert_ctemov->execute(['2',$_POST['ruc_persona'],$tdoc,$_POST['serie'],$_POST['numero'],$visa,$_POST['fecha_emision'],$_POST['empresa']]);
                
                
                }
                if($efectivo>0)
                {
                $fdp = '1';
                $query_fdp = $connect->prepare("INSERT INTO tbl_venta_pago(id_venta,fdp,importe_pago,fecha_pago) VALUES (?,?,?,?)");
                $resultado_fdp = $query_fdp->execute([$lastInsertId,$fdp,$efectivo,$_POST['fecha_emision']]);
                
                $insert_ctemov =$connect->prepare("INSERT INTO tbl_cta_cobrar(tipo,persona,tipo_doc,ser_doc,num_doc,monto,fecha,empresa) VALUES(?,?,?,?,?,?,?,?)");
                $resultado_detalle = $insert_ctemov->execute(['2',$_POST['ruc_persona'],$tdoc,$_POST['serie'],$_POST['numero'],$efectivo+$_POST['vuelto'],$_POST['fecha_emision'],$_POST['empresa']]);
                }
                
                }
                else
                {
                $cuotas = $_POST['cuotas'];
                $importe_pago  = $_POST['importe_pago_cuota'];
                $num_cuota = 1;
                
                for($i = 0; $i< count($_POST['datepago']); $i++)
                {
                $fecha_cuota            = $_POST['datepago'][$i];
                $importe_cuota          = $_POST['montocuota'][$i];
                
                $query_fdp = $connect->prepare("INSERT INTO tbl_venta_cuota(id_venta,num_cuota,importe_cuota,fecha_cuota,moneda) VALUES (?,?,?,?,?)");
                $resultado_fdp = $query_fdp->execute([$lastInsertId,$num_cuota,$importe_cuota,$fecha_cuota,$moneda]);
                
                
                $num_cuota = $num_cuota+1;
                
                
                
                }
                
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
        'servidor_link'     =>$row_empresa['link']
        );
                //buscar datos cliente

        $query_cliente = "SELECT * FROM tbl_contribuyente WHERE num_doc = $_POST[ruc_persona] AND empresa = $_POST[empresa]";
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
                
                $lista_cpe_cab = "SELECT * FROM vw_tbl_venta_cab WHERE id=$lastInsertId";
                $resultado_cpe_cab = $connect->prepare($lista_cpe_cab);
                $resultado_cpe_cab->execute();
                $row_cpe_cab = $resultado_cpe_cab->fetch(PDO::FETCH_ASSOC);


                $comprobante =  array(
                'tipodoc'            => $row_cpe_cab['tipocomp'], //01->FACTURA, 03->BOLETA, 07->NC, 08->ND
                'serie'              => $row_cpe_cab['serie'],
                'correlativo'        => $row_cpe_cab['correlativo'],
                'fecha_emision'      => $row_cpe_cab['fecha_emision'],
                'fecha_vencimiento'  => $row_cpe_cab['fecha_vencimiento'],
                'condicion_venta'    => $row_cpe_cab['condicion_venta'],
                'cuotas_credito'     => $row_cpe_cab['cuotas_credito'],
                'moneda'             => $row_cpe_cab['codmoneda'], //PEN->SOLES; USD->DOLARES
                'total_opgravadas'   => $row_cpe_cab['op_gravadas'], //OP. GRAVADAS
                'total_opexoneradas' => $row_cpe_cab['op_exoneradas'],
                'total_opinafectas'  => $row_cpe_cab['op_inafectas'],
                'igv'                => $row_cpe_cab['igv'],
                'total'              => ($row_cpe_cab['total']-$row_cpe_cab['redondeo']),
                'cod_det'            => $row_cpe_cab['cod_det'],
                'por_det'            => $row_cpe_cab['por_det'],
                'imp_det'            => $row_cpe_cab['imp_det'],
                'tc'                 => $row_cpe_cab['tc'],
                'total_texto'        => $texto,
                 'redondeo'           => $row_cpe_cab['redondeo']
                );


                //********************DATOS DE COMPROBANTE - DETALLE*********************//

                //echo 'el id ultimo es '.$lastInsertId;
                $lista_cpe_det = $connect->prepare("SELECT * FROM vw_tbl_venta_det WHERE idventa=$lastInsertId");

                $lista_cpe_det->execute();
                $row_cpe_det=$lista_cpe_det->fetchAll(PDO::FETCH_ASSOC);
                //print_r($row_cpe_det);

                $detalle = $row_cpe_det;
                
                //var_dump($detalle);
          //*******************DATOS DE LAS CUOTAS *********************//
        $cuota=0;
        if($_POST['condicion'] == '2')
        {
            $lista_cpe_cuo = $connect->prepare("SELECT * FROM tbl_venta_cuota WHERE id_venta=$lastInsertId");
            
            $lista_cpe_cuo->execute();
            $row_cpe_cuo=$lista_cpe_cuo->fetchAll(PDO::FETCH_ASSOC);
            //print_r($row_cpe_det);
            
            $cuota = $row_cpe_cuo;
        }

                // var_dump($detalle1);

                if($tdoc=='01' || $tdoc=='03')
                {

                //$xml->CrearXMLFactura($ruta, $emisor, $cliente, $comprobante, $detalle);
                
                $xml->CrearXMLFactura($ruta, $emisor, $cliente, $comprobante, $detalle,$cuota);


                require_once("../../sunat/api/ApiFacturacion.php");

                $objApi = new ApiFacturacion();
                }

                if($row_empresa['envio_automatico']=='SI')
                {
                if($tdoc=='03' && $row_empresa['envio_resumen']=='SI')
                {
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

                echo json_encode($lastInsertId);
                exit;

                }

                else if($tdoc=='01' || $tdoc=='03' || $tdoc == '99')
                {

                if($tdoc=='01' || $tdoc=='03')
                {

                $respuesta = $objApi->EnviarComprobanteElectronico($emisor,$nombrexml,$connect,$lastInsertId);

                }
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
                $cod_sunat = $respuesta['cod_sunat'];
                $msj_sunat = $respuesta['msj_sunat'];
                $hash_cdr  = $respuesta['hash_cdr'];
                $respcurl  = $respuesta['respcurl'];
                $idfactura = $lastInsertId;
                $respuestahash = $respuesta['respuestahash'];
                $link = $respuesta['link'];
                
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
            
            $query=$connect->prepare("UPDATE tbl_venta_cab SET hash=?,feestado=? ,fecodigoerror=?,femensajesunat=? WHERE id=?;");
		$resultado=$query->execute([$hash_cdr,$estadofe,$cod_sunat,$msj_sunat,$idfactura]);
            
            $miArray= array
            ("id_emp"      => $row_empresa['ruc'],
             "cod_sunat" => $cod_sunat,
             "msj_sunat" => $msj_sunat,
             "hash_cdr"  => $hash_cdr,
             "lastInsertId"=>$idfactura,
             "respuestahash"=>$respuestahash,
             "respcurl"     => $respcurl,
             "link"         => $link
             
                );
            //echo json_encode($emisor);
            echo json_encode($miArray);

                //echo json_encode($lastInsertId);
                exit;
                }
                }
                // si envio automatico es NO
                else
                {
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

                $miArray= array
                ("id_emp"      => $row_empresa['ruc'],
                "cod_sunat" => '',
                "msj_sunat" => '',
                "hash_cdr"  => '',
                "lastInsertId"=>$lastInsertId,
                "respuestahash"=>'',
                "respcurl"     => '',
                "link"         => ''
             
                );

                echo json_encode($miArray);
                
                exit;
                }
}

// enviar resumen de boletas

if($_POST['action'] == 'resumen_cpe')
{

                $fecha    = $_POST['f_ini'];
                $f        = explode('-',$fecha);
                //print_r($f);exit;

                $f        = $f[0].''.$f[1].''.$f[2];  /*fecha formateada YYYYMMDD*/
                $fecha_envio = $f[0].'-'.$f[1].'-'.$f[2];
                $empresa  = $_POST['empresa'];

                //buscar ruc emisor

                $query_empresa = "SELECT * FROM vw_tbl_empresas WHERE id_empresa = $empresa";
                $resultado_empresa = $connect->prepare($query_empresa);
                $resultado_empresa->execute();
                $row_empresa = $resultado_empresa->fetch(PDO::FETCH_ASSOC);

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
                        'servidor_link'     =>$row_empresa['link']
                        );


                 $serie=date('Ymd');
                 $query_articuloss = "SELECT * FROM tbl_series WHERE id_td='RC' AND serie =$serie and id_empresa = $empresa";
                $resultado_articuloss = $connect->prepare($query_articuloss);
                $resultado_articuloss->execute();
                $num_reg_articuloss=$resultado_articuloss->rowCount();



                if($num_reg_articuloss == 0)
                {
                    $correlativo_rc =1;
                    
                $insert_ctemov =$connect->prepare("INSERT INTO tbl_series(id_td,id_doc,serie,correlativo,id_empresa,estado,flat) VALUES(?,?,?,?,?,?,?)");
                $resultado_detalle = $insert_ctemov->execute(['RC',62, $serie ,$correlativo_rc,$empresa,'1','1']);
                    
                    
                }
                else
                {
                $query_empresaa = "SELECT * FROM tbl_series WHERE id_td='RC' AND serie =$serie and id_empresa = $empresa";
                $resultado_empresaa = $connect->prepare($query_empresaa);
                $resultado_empresaa->execute();
                $row_empresaa = $resultado_empresaa->fetch(PDO::FETCH_ASSOC);

                $correlativo_rc = $row_empresaa['correlativo'] + 1;

                $query_ctr = $connect->prepare("UPDATE tbl_series SET correlativo = ? WHERE id_td=? AND serie =? and id_empresa = ?");
                $resultado_ctr = $query_ctr->execute([$correlativo_rc,'RC',$serie,$empresa]);


                    
                }



                $cabecera = array(
                "tipodocr"       =>"RC",
                "serier"         =>date('Ymd'),
                "correlativor"   =>$correlativo_rc,
                "fecha_emision" =>$fecha,            
                "fecha_envio"   =>date('Y-m-d') 
                );




                //nombre de resumen = RUC - RC -YYYYMMDD-NUM.XML
                $nombrexml = $row_empresa['ruc'].'-'.$cabecera['tipodocr'].'-'.$cabecera['serier'].'-'.$cabecera['correlativor'];
                $rutaxml = "../../sunat/".$row_empresa['ruc']."/xml/";

                $lista_cpe_det = $connect->prepare("SELECT * FROM vw_tbl_resumen_cpe WHERE fecha_emision='$fecha'");
                $lista_cpe_det->execute();
                $row_cpe_det=$lista_cpe_det->fetchAll(PDO::FETCH_ASSOC);
                //$items= $row_cpe_det;
                $items = array();
                $i=1;
                foreach($row_cpe_det as $det)
                { 


                $items[] = array(
                "items"              => $i,
                "tipodoc"           => $det['tipodoc'],
                "serie"             => $det['serie'],
                "correlativo"       => $det['correlativo'],
                "condicion"         => $det['condicion'], //1->Registro, 2->Actuali, 3->Bajas
                "moneda"            => $det['moneda'],          
                "importe_total"     => $det['importe_total'],
                "valor_total"       => $det['valor_total'],
                "igv_total"         => $det['igv_total'],
                "tipo_total"        => $det['tipo_total'], //GRA->01, EXO->02, INA->03
                "codigo_afectacion" => $det['codigo_afectacion'],
                "nombre_afectacion" => $det['nombre_afectacion'],
                "tipo_afectacion"   => $det['tipo_afectacion'],
                "fecha_emision"     => $det['fecha_emision'],
                "id_empresa"        => $det['idempresa'],
                "id"                => $det['id'],
                "docpersona"        => $det['docpersona'], 
                "tipo_doc"        => $det['tipo_doc'], 
                "nombrepersona"     => $det['nombrepersona'], 
                "docmodifica"       => $det['docmodifica'], 
                "tipdocmodifica"    => $det['tipdocmodifica']    
                );
                // actualizar serie + correlativo
                $update_query_serie = $connect->prepare("UPDATE tbl_venta_cab SET serie_resumen =  ?,numero_resumen = ? WHERE  id = ?");
                $resultado_serie   = $update_query_serie->execute([$cabecera['serier'],$cabecera['correlativor'],$det['id']]);




                $i=$i+1;
                }



                require_once("../../sunat/api/xml.php");

                $xml = new GeneradorXML();

                $serier = $cabecera['serier'];
                $numeror =$cabecera['correlativor'];

                $xml->CrearXMLResumenDocumentos($emisor, $cabecera, $items, $rutaxml.$nombrexml);

                require_once("../../sunat/api/ApiFacturacion.php");

                $objApi = new ApiFacturacion();

                $ticket = $objApi->EnviarResumenComprobantes($emisor,$nombrexml,$connect,$serier,$numeror);
                
                //var_dump($ticket);

                $respuesta = $objApi->ConsultarTicket($emisor, $nombrexml, $ticket);
                
                //var_dump($respuesta);
                //$me = json_decode($respuesta, true);
                $cod_sunat = $respuesta['cod_sunat'];
                $msj_sunat = $respuesta['msj_sunat'];
                $hash_cdr = $respuesta['hash_cdr'];
                
                /*$me = json_decode($resultado, true);
                $jsondata['mensaje'] = $me['msj_sunat'];*/
                
                 if($cod_sunat == '0')
                {
                    $query_ctr = $connect->prepare("UPDATE tbl_venta_cab SET feestado = ? WHERE id=? ");
                $resultado_ctr = $query_ctr->execute(['8',$id_baja]);
                }
                
                echo 'ticket : '.$ticket.'- codigo : '.$cod_sunat.' mensaje : '.$msj_sunat;
                exit;


}


//  comunicacion de baja - boletas

if($_POST['action'] == 'baja_cpe_boletas')
{
   

                $fecha    = $_POST['fechab'];
                $f        = explode('-',$fecha);
                //print_r($f);exit;

                $f        = $f[0].''.$f[1].''.$f[2];  /*fecha formateada YYYYMMDD*/
                $fecha_envio = $f[0].'-'.$f[1].'-'.$f[2];
                $empresa  = $_POST['empresa'];
                
                for($i = 0; $i< count($_POST['bajaId']); $i++)
                {
                $id_baja = $_POST['bajaId'][$i];
                //echo $id_baja;
                $query_ctr = $connect->prepare("UPDATE tbl_venta_cab SET feestado = ? WHERE id=? ");
                $resultado_ctr = $query_ctr->execute(['4',$id_baja]);
                
                //exit;
                }

                //buscar ruc emisor

                $query_empresa = "SELECT * FROM vw_tbl_empresas WHERE id_empresa = $empresa";
                $resultado_empresa = $connect->prepare($query_empresa);
                $resultado_empresa->execute();
                $row_empresa = $resultado_empresa->fetch(PDO::FETCH_ASSOC);

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
                        'servidor_link'     =>$row_empresa['link']
                        );


                 $serie=date('Ymd');
                 $query_articuloss = "SELECT * FROM tbl_series WHERE id_td='RC' AND serie =$serie and id_empresa = $empresa";
                $resultado_articuloss = $connect->prepare($query_articuloss);
                $resultado_articuloss->execute();
                $num_reg_articuloss=$resultado_articuloss->rowCount();



                if($num_reg_articuloss == 0)
                {
                    $correlativo_rc =1;
                    
                $insert_ctemov =$connect->prepare("INSERT INTO tbl_series(id_td,id_doc,serie,correlativo,id_empresa,estado,flat) VALUES(?,?,?,?,?,?,?)");
                $resultado_detalle = $insert_ctemov->execute(['RC',62, $serie ,$correlativo_rc,$empresa,'1','1']);
                    
                    
                }
                else
                {
                $query_empresaa = "SELECT * FROM tbl_series WHERE id_td='RC' AND serie =$serie and id_empresa = $empresa";
                $resultado_empresaa = $connect->prepare($query_empresaa);
                $resultado_empresaa->execute();
                $row_empresaa = $resultado_empresaa->fetch(PDO::FETCH_ASSOC);

                $correlativo_rc = $row_empresaa['correlativo'] + 1;

                $query_ctr = $connect->prepare("UPDATE tbl_series SET correlativo = ? WHERE id_td=? AND serie =? and id_empresa = ?");
                $resultado_ctr = $query_ctr->execute([$correlativo_rc,'RC',$serie,$empresa]);


                    
                }



                $cabecera = array(
                "tipodocr"       =>"RC",
                "serier"         =>date('Ymd'),
                "correlativor"   =>$correlativo_rc,
                "fecha_emision" =>$fecha,            
                "fecha_envio"   =>date('Y-m-d') 
                );




                //nombre de resumen = RUC - RC -YYYYMMDD-NUM.XML
                $nombrexml = $row_empresa['ruc'].'-'.$cabecera['tipodocr'].'-'.$cabecera['serier'].'-'.$cabecera['correlativor'];
                $rutaxml = "../../sunat/".$row_empresa['ruc']."/xml/";

                $query_baja = "SELECT * FROM vw_tbl_resumen_baja_boletas WHERE fecha_emision='$fecha' AND idempresa='$empresa'";
                $lista_cpe_det = $connect->prepare($query_baja);
                
                //echo $query_baja;
                $lista_cpe_det->execute();
                $row_cpe_det=$lista_cpe_det->fetchAll(PDO::FETCH_ASSOC);
                //$items= $row_cpe_det;
                $items = array();
                $i=1;
                foreach($row_cpe_det as $det)
                { 


                $items[] = array(
                "items"              => $i,
                "tipodoc"           => $det['tipodoc'],
                "serie"             => $det['serie'],
                "correlativo"       => $det['correlativo'],
                "condicion"         => '3', //1->Registro, 2->Actuali, 3->Bajas
                "moneda"            => $det['moneda'],          
                "importe_total"     => $det['importe_total'],
                "valor_total"       => $det['valor_total'],
                "igv_total"         => $det['igv_total'],
                "tipo_total"        => $det['tipo_total'], //GRA->01, EXO->02, INA->03
                "codigo_afectacion" => $det['codigo_afectacion'],
                "nombre_afectacion" => $det['nombre_afectacion'],
                "tipo_afectacion"   => $det['tipo_afectacion'],
                "fecha_emision"     => $det['fecha_emision'],
                "id_empresa"        => $det['idempresa'],
                "id"                => $det['id'],
                "docpersona"        => $det['docpersona'], 
                "tipo_doc"        => $det['tipo_doc'], 
                "nombrepersona"     => $det['nombrepersona'], 
                "docmodifica"       => $det['docmodifica'], 
                "tipdocmodifica"    => $det['tipdocmodifica']    
                );
                // actualizar serie + correlativo
                $update_query_serie = $connect->prepare("UPDATE tbl_venta_cab SET serie_resumen =  ?,numero_resumen = ? WHERE  id = ?");
                $resultado_serie   = $update_query_serie->execute([$cabecera['serier'],$cabecera['correlativor'],$det['id']]);




                $i=$i+1;
                }



                require_once("../../sunat/api/xml.php");

                $xml = new GeneradorXML();

                $serier = $cabecera['serier'];
                $numeror =$cabecera['correlativor'];

                $xml->CrearXMLResumenDocumentos($emisor, $cabecera, $items, $rutaxml.$nombrexml);

                require_once("../../sunat/api/ApiFacturacion.php");

                $objApi = new ApiFacturacion();

                $ticket = $objApi->EnviarResumenBajas($emisor,$nombrexml,$connect,$serier,$numeror);
                
                //var_dump($ticket);

                $respuesta = $objApi->ConsultarTicket($emisor, $nombrexml, $ticket);
                
                //var_dump($respuesta);
                //$me = json_decode($respuesta, true);
                $cod_sunat = $respuesta['cod_sunat'];
                $msj_sunat = $respuesta['msj_sunat'];
                $hash_cdr = $respuesta['hash_cdr'];
                $response = $mensaje['respuestacurl'];
                $httpcode = $mensaje['httpcode'];
                
                /*$me = json_decode($resultado, true);
                $jsondata['mensaje'] = $me['msj_sunat'];*/
                
                if($cod_sunat == '0')
                {
                    $query_ctr = $connect->prepare("UPDATE tbl_venta_cab SET feestado = ? WHERE id=? ");
                $resultado_ctr = $query_ctr->execute(['5',$id_baja]);
                }
                
                echo 'ticket : '.$ticket.'- codigo : '.$cod_sunat.' mensaje : '.$msj_sunat.' respuesta sunat : '.$response;
                exit;


}

//  comunicacion de baja - facturas

if($_POST['action'] == 'baja_cpe_facturas')

{            
    //var_dump($_POST);

                $empresa  = $_POST['empresa'];
                $fecha    = $_POST['fechab'];

                //buscar ruc emisor
              

                $query_empresa = "SELECT * FROM vw_tbl_empresas WHERE id_empresa = $empresa";
                $resultado_empresa = $connect->prepare($query_empresa);
                $resultado_empresa->execute();
                $row_empresa = $resultado_empresa->fetch(PDO::FETCH_ASSOC);


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
                        'servidor_link'     =>$row_empresa['link']
                        );

                 $serie=date('Ymd');
                 $query_articuloss = "SELECT * FROM tbl_series WHERE id_td='RA' AND serie =$serie and id_empresa = $empresa";
                $resultado_articuloss = $connect->prepare($query_articuloss);
                $resultado_articuloss->execute();
                $num_reg_articuloss=$resultado_articuloss->rowCount();



                if($num_reg_articuloss == 0)
                {
                    $correlativo_rc =1;
                    
                $insert_ctemov =$connect->prepare("INSERT INTO tbl_series(id_td,id_doc,serie,correlativo,id_empresa,estado,flat) VALUES(?,?,?,?,?,?,?)");
                $resultado_detalle = $insert_ctemov->execute(['RA',64, $serie ,$correlativo_rc,$empresa,'1','1']);
                    
                    
                }
                else
                {
                $query_empresaa = "SELECT * FROM tbl_series WHERE id_td='RA' AND serie =$serie and id_empresa = $empresa";
                $resultado_empresaa = $connect->prepare($query_empresaa);
                $resultado_empresaa->execute();
                $row_empresaa = $resultado_empresaa->fetch(PDO::FETCH_ASSOC);

                $correlativo_rc = $row_empresaa['correlativo'] + 1;

                $query_ctr = $connect->prepare("UPDATE tbl_series SET correlativo = ? WHERE id_td=? AND serie =? and id_empresa = ?");
                $resultado_ctr = $query_ctr->execute([$correlativo_rc,'RA',$serie,$empresa]);


                    
                }

                $tipo_res    = 'RA';
                $serie_res   = date('Ymd');

                $cabecera = array(
                "tipodocr"       =>$tipo_res,
                "serier"         =>$serie_res,
                "correlativor"   =>$correlativo_rc,
                "fecha_emision" =>$fecha,            
                "fecha_envio"   =>date('Y-m-d') 
                );




                //nombre de resumen = RUC - RC -YYYYMMDD-NUM.XML
                //RRRRRRRRRRR-RA-YYYYMMDD-CCC
                $nombrexml = $row_empresa['ruc'].'-RA-'.$serie_res.'-'.$correlativo_rc;
                $rutaxml = "../../sunat/".$row_empresa['ruc']."/xml/";

               for($i = 0; $i< count($_POST['bajaId']); $i++)
               {
                 $id_cpe_bol = $_POST['bajaId'][$i];
                 

                  $update_query_serie = $connect->prepare("UPDATE tbl_venta_cab SET feestado=? WHERE id = ?");
                  $resultado_serie   = $update_query_serie->execute(['4',$id_cpe_bol]);
                  
               }
                

                $lista_cpe_det = $connect->prepare("SELECT * FROM vw_tbl_resumen_baja_facturas WHERE idempresa='$empresa' AND fecha_emision = '$fecha'");

                $lista_cpe_det->execute();
                $row_cpe_det=$lista_cpe_det->fetchAll(PDO::FETCH_ASSOC);
                //$items= $row_cpe_det;
                $items = array();
                $i=1;
                foreach($row_cpe_det as $det)
                { 
                $items[] = array(
                "items"              => $i,
                "tipodoc"           => $det['tipodoc'],
                "serie"             => $det['serie'],
                "correlativo"       => $det['correlativo'],
                "condicion"         => $det['condicion'], //1->Registro, 2->Actuali, 3->Bajas
                "moneda"            => $det['moneda'],          
                "importe_total"     => $det['importe_total'],
                "valor_total"       => $det['valor_total'],
                "igv_total"         => $det['igv_total'],
                "tipo_total"        => $det['tipo_total'], //GRA->01, EXO->02, INA->03
                "codigo_afectacion" => $det['codigo_afectacion'],
                "nombre_afectacion" => $det['nombre_afectacion'],
                "tipo_afectacion"   => $det['tipo_afectacion'],
                "fecha_emision"     => $det['fecha_emision'],
                "id_empresa"        => $det['idempresa'],
                "motivo"			=> "ERROR EN DOCUMENTO",
                "id"                => $det['id']    
                );
                // actualizar serie + correlativo
                $update_query_serie = $connect->prepare("UPDATE tbl_venta_cab SET serie_resumen =  ?,numero_resumen = ? WHERE  id = ?");
                $resultado_serie   = $update_query_serie->execute([$cabecera['serier'],$cabecera['correlativor'],$det['id']]);




                $i=$i+1;
                }



                require_once("../../sunat/api/xml.php");

                $xml = new GeneradorXML();

                $serier = $cabecera['serier'];
                $numeror =$cabecera['correlativor'];

                $xml->CrearXmlBajaDocumentos($emisor, $cabecera, $items, $rutaxml.$nombrexml);

                require_once("../../sunat/api/ApiFacturacion.php");

                $objApi = new ApiFacturacion();

                $ticket = $objApi->EnviarResumenBajas($emisor,$nombrexml,$connect,$serier,$numeror);

                $respuesta = $objApi->ConsultarTicket($emisor, $nombrexml, $ticket);
                
                //var_dump($respuesta);
                //$me = json_decode($respuesta, true);
                $cod_sunat = $respuesta['cod_sunat'];
                $msj_sunat = $respuesta['msj_sunat'];
                $hash_cdr = $respuesta['hash_cdr'];
                
                /*$me = json_decode($resultado, true);
                $jsondata['mensaje'] = $me['msj_sunat'];*/
                if($cod_sunat == '0')
                {
                    $query_ctr = $connect->prepare("UPDATE tbl_venta_cab SET feestado = ? WHERE id=? ");
                $resultado_ctr = $query_ctr->execute(['6',$id_baja]);
                }
                
                
                
                echo 'ticket : '.$ticket .'<br />' .'codigo : '.$cod_sunat.'<br />' .' mensaje : '.$msj_sunat;
                exit;


}

// solicitar descarga  cdr


// consultar estado del cpe



?>
