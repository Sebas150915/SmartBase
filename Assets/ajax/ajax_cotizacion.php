<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
require_once("../../sunat/api/xml.php");
require_once("../../sunat/api/ApiFacturacion.php");
session_start();

if($_POST['action'] == 'nota_pedido_editar')
{
  //print_r($_POST);
        $t = explode("-",$_POST['tip_cpe']);
        $cod = $t[0];
        $tdoc = $t[1];

        $hora = date('h:i:s');
        $visa = $_POST['visa'];
        $cvisa = $_POST['cvisa'];    
        $efectivo = $_POST['efectivo'];
        $query_insumo = $connect->prepare("UPDATE tbl_coti_cab SET op_gravadas =  ?, op_exoneradas=?,op_inafectas=?,igv=?, total=?  WHERE id = ?");
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

        if($tdoc=='01' || $tdoc=='03' || $tdoc  == '99' || $tdoc  == 'NP' || $tdoc  == 'CT')
        {
              
                /*buscar producto*/
                $query_bpro = "SELECT * FROM tbl_coti_det WHERE idproducto = $idarticulo AND idventa = $_POST[id_nv] ";
                $resultado_bpro = $connect->prepare($query_bpro);
                $resultado_bpro->execute();
                $num_reg_bpro=$resultado_bpro->rowCount();
                /*fin buscar producto*/
                /*producto nuevo*/
                if($num_reg_bpro == 0)
                {
                $insert_query_detalle =$connect->prepare("INSERT INTO tbl_coti_det(idventa,item,idproducto,cantidad,valor_unitario,precio_unitario,igv,porcentaje_igv,valor_total,importe_total,costo,cantidad_factor,factor,cantidad_unitario,mxmn) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
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

                		$insert_query_detalle =$connect->prepare("UPDATE tbl_coti_det SET item=?,idproducto=?,cantidad=?,valor_unitario=?,precio_unitario=?,igv=?,porcentaje_igv=?,valor_total=?,importe_total=?,costo=?,cantidad_factor=?,factor=?,cantidad_unitario=? WHERE idventa=? AND item = ? and idproducto = ?");
                		$resultado_detalle = $insert_query_detalle->execute([$item,$idarticulo,$cantidad_total,$precio_venta_unitario,$precio_venta,$igv_total,18,$valor_total,$importe_total,'0',$cantidad,$factor,$cantidadu,$_POST['id_nv'],$item,$idarticulo]);
                	}
                	else
                	{
                		$del_pro =$connect->prepare("DELETE FROM tbl_coti_det WHERE idventa=? AND item = ? and idproducto = ?");
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

if($_POST['action'] == 'listarClientes')
{
       $idcliente = $_POST['idempresa'];
       $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql="SELECT * FROM tbl_contribuyente WHERE empresa like '$idcliente'";
        $resultado=$connect->prepare($sql);
        $resultado->execute();
        $num_reg=$resultado->rowCount();
        $detalletabla = '';
        foreach($resultado as $serie )
        {
            $cont =1;
            $id         = $serie['id_persona'];
            $ruc        = $serie['num_doc'];
            $razon      = $serie['nombre_persona'];
            $direccion  = $serie['direccion_persona'];
            

            $detalletabla .='<tr>
             <td>'.$ruc.'</td>
             <td>'.$razon.'</td>'.
            '<td><a class="btn btn-success" onclick="enviacliente(\''.$id.'\',\''.$ruc.'\',\''.$razon.'\',\''.$direccion.'\')"><i class="fe fe-check"></i></a></td>       
            </tr>';
             $cont++;
        }

        $arrayData['detalle'] = $detalletabla;

        echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);


        exit();
}
// guardar nueva venta

if($_POST['action'] == 'nueva_cotizacion')
 {
            

            $t = explode("-",$_POST['tip_cpe']);
            $cod = $t[0];
            $tdoc = $t[1];
            $localemp = $_SESSION['almacen'];


            $query=$connect->prepare("INSERT INTO tbl_coti_cab(idempresa,tipocomp,serie,correlativo,fecha_emision,fecha_vencimiento,condicion_venta,op_gravadas,op_exoneradas,op_inafectas,igv,total,id_cliente,codcliente,vendedor,dias,obs,local) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
            $resultado=$query->execute([$_POST['empresa'],$tdoc,$_POST['serie'],$_POST['numero'],$_POST['fecha_emision'],$_POST['fecha_vencimiento'],$_POST['condicion'],$_POST['op_g'],$_POST['op_e'],$_POST['op_i'],$_POST['igv'],$_POST['total'],$_POST['id_ruc'],$_POST['ruc_persona'], $_SESSION["id"],$_POST['validez'],$_POST['descripcion'],$localemp]);
                $lastInsertId = $connect->lastInsertId();

                $visa = $_POST['visa'];
                $cvisa = $_POST['cvisa'];    
                $efectivo = $_POST['efectivo'];

              
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

                              $igv_total             = $importe_total - $valor_total;
                              $precio_compra            = $_POST['precio_compra'][$i];

                             

                                  
                                  $insert_query_detalle =$connect->prepare("INSERT INTO tbl_coti_det(idventa,item,idproducto,cantidad,valor_unitario,precio_unitario,igv,porcentaje_igv,valor_total,importe_total,costo,cantidad_factor,factor,cantidad_unitario,mxmn) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                                  $resultado_detalle = $insert_query_detalle->execute([$lastInsertId,$item,$idarticulo,$cantidad_total,$precio_venta_unitario,$precio_venta,$igv_total,18,$valor_total,$importe_total,$costo,$cantidad,$factor,$cantidadu,$mxmn]);

                                      // actualizar serie + correlativo
                                      $update_query_serie = $connect->prepare("UPDATE tbl_series SET correlativo = correlativo + ? WHERE serie = ? and correlativo = ? and id_empresa = ?");
                                    $resultado_serie   = $update_query_serie->execute([1,$_POST['serie'],$_POST['numero'],$_POST['empresa']]);


                                         
                                                                     
                                      
                                           

                                        
                    }
             echo $lastInsertId;
 }





?>
