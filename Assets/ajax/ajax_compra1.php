<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
require_once("../../sunat/api/xml.php");
require_once("../../sunat/api/ApiFacturacion.php");
session_start();

if($_POST['action'] == 'compra_editar') {
    $tdoc = $_POST['tip_cpe'];
    $hora = date('h:i:s');

    // Actualización en tbl_compra_cab
    $query_insumo = $connect->prepare("UPDATE tbl_compra_cab SET op_gravadas = ?, op_exoneradas = ?, op_inafectas = ?, igv = ?, total = ? WHERE id = ?");
    $resultado_insumo = $query_insumo->execute([$_POST['op_g'], $_POST['op_e'], $_POST['op_i'], $_POST['igv'], $_POST['total'], $_POST['id_nv']]);

    // Obtener los IDs originales de los ítems de la compra antes de procesar los nuevos ítems
    $query_items_originales = $connect->prepare("SELECT id FROM tbl_compra_det WHERE idventa = ?");
    $query_items_originales->execute([$_POST['id_nv']]);
    $items_originales = $query_items_originales->fetchAll(PDO::FETCH_COLUMN); // Obtener un array con los IDs

    // Almacenar los ítems que se procesan en la nueva solicitud
    $items_procesados = [];
    $item = 1;

    // Registro o actualización en tbl_compra_det
    for($i = 0; $i < count($_POST['idarticulo']); $i++) 
    {
        $idarticulo = $_POST['idarticulo'][$i];
        $items_procesados[] = $_POST['id_detalle'][$i];  // Almacenar el ID del artículo procesado (nuevo o existente)

        // Calcular las cantidades y precios
        $cantidad_total = $_POST['cantidad'][$i];
        $afectacion = $_POST['afectacion'][$i];
        $precio_venta = $_POST['precio_venta'][$i] / $_POST['factor'][$i];
        $valor_total = $afectacion == 10 ? ($cantidad_total * $precio_venta) / 1.18 : $cantidad_total * $precio_venta;

        // Actualización de productos en el stock
        if (in_array($tdoc, ['01', '03', '99'])) {
            $query_stock = $connect->prepare("UPDATE tbl_productos SET stock = stock - ? WHERE id = ?");
            $resultado_stock = $query_stock->execute([$cantidad_total, $idarticulo]);
        }

        // Si el ítem ya existe (tiene ID), actualizar; si no, insertar uno nuevo
        if (!empty($_POST['id_detalle'][$i])) {
            // Actualizar detalle existente
            $query_detalle = $connect->prepare("UPDATE tbl_compra_det SET cantidad = ?, valor_unitario = ?, precio_unitario = ?, igv = ?, valor_total = ?, importe_total = ? WHERE id = ?");
            $resultado_detalle = $query_detalle->execute([$cantidad_total, $valor_total, $precio_venta, $afectacion, $valor_total, $valor_total, $_POST['id_detalle'][$i]]);
        } else {
            // Insertar nuevo detalle
            $query_detalle = $connect->prepare("INSERT INTO tbl_compra_det (idventa, item, idproducto, cantidad, valor_unitario, precio_unitario, igv, valor_total, importe_total)
                                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $resultado_detalle = $query_detalle->execute([$_POST['id_nv'], $item, $idarticulo, $cantidad_total, $valor_total, $precio_venta, $afectacion, $valor_total, $valor_total]);
        }

        // Verificar si se ejecutó correctamente
        if (!$resultado_detalle) {
          //  echo json_encode(["status" => "error", "message" => "Error al actualizar el detalle de la compra"]);
            exit;
        }
    
        $item++;
    }

    // Encontrar los ítems originales que no fueron procesados y eliminarlos
    $items_a_eliminar = array_diff($items_originales, $items_procesados); // Encuentra los que no se procesaron

    if (count($items_a_eliminar) > 0) {
        $query_eliminar = $connect->prepare("DELETE FROM tbl_compra_det WHERE id = ?");
        foreach ($items_a_eliminar as $id_detalle_a_eliminar) {
            $query_eliminar->execute([$id_detalle_a_eliminar]);
        }
    }

    // Ingresar el registro en la tabla tbl_cta_pagar
    $query_cta_cobrar = $connect->prepare("INSERT INTO tbl_cta_pagar (tipo, persona, tipo_doc, ser_doc, num_doc, monto, fecha, empresa)
                                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $resultado_cta_cobrar = $query_cta_cobrar->execute([1, $_POST['ruc_persona'], $tdoc, $_POST['serie'], $_POST['numero'], $_POST['total'], $_POST['fecha_emision'], $_POST['empresa']]);

    // Respuesta exitosa
   // echo json_encode(["status" => "success", "id_nv" => $_POST['id_nv']]);
      echo json_encode($_POST['id_nv']);
    exit;
}



// guardar nueva compra

if($_POST['action'] == 'nueva_venta')
 {
     
     $localemp = $_POST['alm'];
            $query=$connect->prepare("INSERT INTO tbl_compra_cab(idempresa,tipocomp,serie,correlativo,fecha_emision,fecha_vencimiento,condicion_venta,op_gravadas,op_exoneradas,op_inafectas,igv,total,codcliente,vendedor,detraccion,imp_detraccion,saldo_ft,idalmacen,idcliente) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
            $resultado=$query->execute([$_POST['empresa'],$_POST['tip_cpe'],$_POST['serie'],$_POST['numero'],$_POST['fecha_emision'],$_POST['fecha_vencimiento'],$_POST['condicion'],$_POST['op_g'],$_POST['op_e'],$_POST['op_i'],$_POST['igv'],$_POST['total'],$_POST['ruc_persona'], $_SESSION["id"],$_POST['det'],$_POST['imp_det'],$_POST['saldo_ft'],$localemp,$_POST['id_ruc']]);
                $lastInsertId = $connect->lastInsertId();
               //registro detalle compra


                    for($i = 0; $i< count($_POST['idarticulo']); $i++)
                    {
                              $item                  = $_POST['itemarticulo'][$i];
                              $idarticulo            = $_POST['idarticulo'][$i];
                              $nomarticulo           = $_POST['nomarticulo'][$i];
                              $cantidad              = $_POST['cantidad'][$i];
                              $precio_venta          = $_POST['precio_venta'][$i];

                              $por1                  = $_POST['por1'][$i];
                              $precio1               = $_POST['precio1'][$i];
                              $por2                  = $_POST['por2'][$i];
                              $precio2               = $_POST['precio2'][$i];


                              $afectacion            = $_POST['afectacion'][$i];
                              $tipo_precio           ='01';
                              $unidad                = 'NIU';

                              $fecven                = $_POST['fecven'][$i];

                             
                                      if($afectacion == '10')
                                      {
                                        $valor_unitario = $precio_venta / 1.18;
                                      }
                                      else
                                      {
                                        $valor_unitario = $precio_venta;

                                      }
                                   $igv = $precio_venta - $valor_unitario;
                                  
                                  $insert_query_detalle =$connect->prepare("INSERT INTO tbl_compra_det(idventa,item,idproducto,cantidad,valor_unitario,precio_unitario,igv,porcentaje_igv,valor_total,importe_total,vencimiento) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
                                  $resultado_detalle = $insert_query_detalle->execute([$lastInsertId,$item,$idarticulo,$cantidad,$valor_unitario,$precio_venta,$igv,18,($cantidad*$valor_unitario),($cantidad*$precio_venta),$fecven]);

                                                                    
                                 //ACTUALIZAR COSTO, PORCENTAJE MAYORISTA, PORCENTAJE MINORISTA, PRECIO MAYORISTA Y PRECIO MINORISTA

                                        $query_insumo_costo = $connect->prepare("UPDATE tbl_productos SET costo= ?,por1=?,precio_venta=?,por2=?,precio2=? WHERE id=?");
                                        $resultado_insumo_costo = $query_insumo_costo->execute([$precio_venta,$por1,$precio1,$por2,$precio2,$idarticulo]);

                                //ACTUALIZA STOCK

                                        $query_stock  = $connect->prepare("UPDATE tbl_productos SET stock = stock + ? WHERE id=?");
                                        $resultado_stock = $query_stock->execute([$cantidad,$idarticulo]);

                                        

                                                
                    }

                      //insert deuda por pagar


                    if($_POST['tip_cpe']=='01' || $_POST['tip_cpe']=='03')
                                        {
                                            $insert_ctemov =$connect->prepare("INSERT INTO tbl_cta_pagar(tipo,persona,tipo_doc,ser_doc,num_doc,monto,fecha,empresa) VALUES(?,?,?,?,?,?,?,?)");
                          $resultado_detalle = $insert_ctemov->execute(['1',$_POST['ruc_persona'],$_POST['tip_cpe'],$_POST['serie'],$_POST['numero'],$_POST['total'],$_POST['fecha_emision'],$_POST['empresa']]);
                                        }
                                        else if($_POST['tip_cpe']=='07')
                                        {
                                           $insert_ctemov =$connect->prepare("INSERT INTO tbl_cta_pagar(tipo,persona,tipo_doc,ser_doc,num_doc,monto,fecha,empresa) VALUES(?,?,?,?,?,?,?,?)");
                          $resultado_detalle = $insert_ctemov->execute(['2',$_POST['ruc_persona'],$_POST['tip_cpe'],$_POST['serie'],$_POST['numero'],$_POST['total'],$_POST['fecha_emision'],$_POST['empresa']]);
                                        }


                    
        

          
            
            echo json_encode($lastInsertId);
                exit;
}



// guardar nueva venta

if($_POST['action'] == 'nueva_nota_credito_compra')
 {
            $query=$connect->prepare("INSERT INTO tbl_compra_cab(idempresa,tipocomp,serie,correlativo,fecha_emision,fecha_vencimiento,condicion_venta,op_gravadas,op_exoneradas,op_inafectas,igv,total,codcliente,vendedor,tipocomp_ref,serie_ref,correlativo_ref) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
            $resultado=$query->execute([$_POST['empresa'],$_POST['tip_cpe'],$_POST['serie'],$_POST['numero'],$_POST['fecha_emision'],$_POST['fecha_vencimiento'],$_POST['condicion'],$_POST['op_g'],$_POST['op_e'],$_POST['op_i'],$_POST['igv'],$_POST['total'],$_POST['ruc_persona'], $_SESSION["id"],$_POST['tdocref'],$_POST['sdocref'],$_POST['ndocref']]);
                $lastInsertId = $connect->lastInsertId();
               //registro detalle compra


                    for($i = 0; $i< count($_POST['idarticulo']); $i++)
                    {
                              $item                  = $_POST['itemarticulo'][$i];
                              $idarticulo            = $_POST['idarticulo'][$i];
                              $nomarticulo           = $_POST['nomarticulo'][$i];
                              $cantidad              = $_POST['cantidad'][$i];
                              $precio_venta          = $_POST['precio_venta'][$i];

                              $por1                  = $_POST['por1'][$i];
                              $precio1               = $_POST['precio1'][$i];
                              $por2                  = $_POST['por2'][$i];
                              $precio2               = $_POST['precio2'][$i];


                              $afectacion            = $_POST['afectacion'][$i];
                              $tipo_precio           ='01';
                              $unidad                = 'NIU';

                                      if($afectacion == '10')
                                      {
                                        $valor_unitario = $precio_venta / 1.18;
                                      }
                                      else
                                      {
                                        $valor_unitario = $precio_venta;

                                      }
                                   $igv = $precio_venta - $valor_unitario;
                                  
                                  $insert_query_detalle =$connect->prepare("INSERT INTO tbl_compra_det(idventa,item,idproducto,cantidad,valor_unitario,precio_unitario,igv,porcentaje_igv,valor_total,importe_total) VALUES(?,?,?,?,?,?,?,?,?,?)");
                                  $resultado_detalle = $insert_query_detalle->execute([$lastInsertId,$item,$idarticulo,$cantidad,$valor_unitario,$precio_venta,$igv,18,($cantidad*$valor_unitario),($cantidad*$precio_venta)]);

                                                                    
                                 //ACTUALIZAR COSTO, PORCENTAJE MAYORISTA, PORCENTAJE MINORISTA, PRECIO MAYORISTA Y PRECIO MINORISTA

                                        $query_insumo_costo = $connect->prepare("UPDATE tbl_productos SET costo= ?,por1=?,precio_venta=?,por2=?,precio2=? WHERE id=?");
                                        $resultado_insumo_costo = $query_insumo_costo->execute([$precio_venta,$por1,$precio1,$por2,$precio2,$idarticulo]);

                                //ACTUALIZA STOCK

                                        $query_stock  = $connect->prepare("UPDATE tbl_productos SET stock = stock - ? WHERE id=?");
                                        $resultado_stock = $query_stock->execute([$cantidad,$idarticulo]);

                                        

                                                
                    }

                      //insert deuda por pagar


                    if($_POST['tip_cpe']=='01' || $_POST['tip_cpe']=='03')
                                        {
                                            $insert_ctemov =$connect->prepare("INSERT INTO tbl_cta_pagar(tipo,persona,tipo_doc,ser_doc,num_doc,monto,fecha,empresa) VALUES(?,?,?,?,?,?,?,?)");
                          $resultado_detalle = $insert_ctemov->execute(['1',$_POST['ruc_persona'],$_POST['tip_cpe'],$_POST['serie'],$_POST['numero'],$_POST['total'],$_POST['fecha_emision'],$_POST['empresa']]);
                                        }
                                        else if($_POST['tip_cpe']=='07')
                                        {
                                           $insert_ctemov =$connect->prepare("INSERT INTO tbl_cta_pagar(tipo,persona,tipo_doc,ser_doc,num_doc,monto,fecha,empresa) VALUES(?,?,?,?,?,?,?,?)");
                          $resultado_detalle = $insert_ctemov->execute(['2',$_POST['ruc_persona'],$_POST['tip_cpe'],$_POST['serie'],$_POST['numero'],$_POST['total'],$_POST['fecha_emision'],$_POST['empresa']]);
                                        }


                    
        

          
            
            echo json_encode($lastInsertId);
                exit;
}

?>
