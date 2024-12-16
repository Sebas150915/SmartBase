<?php 

$id_nv = $rutas[1];
$empresa = $_SESSION["id_empresa"];
$pormayor = $_SESSION["venta_por_mayor"];
$usabarras = $_SESSION["usabarras"];
$hoy = date('Y-m-d');




$query_documento = "SELECT * FROM tbl_tipo_documento WHERE fe='1' AND id in ('NP')";
$resultado_documento=$connect->prepare($query_documento);
$resultado_documento->execute(); 
$num_reg_documento=$resultado_documento->rowCount();


$lista1=$connect->query("SELECT * FROM tbl_contribuyente WHERE empresa= $empresa");
$resultado1=$lista1->fetchAll(PDO::FETCH_OBJ);


$query_forma = "SELECT * FROM tbl_forma_pago ORDER BY tipo";
$resultado_forma=$connect->prepare($query_forma);
$resultado_forma->execute(); 
$num_reg_forma=$resultado_forma->rowCount();

$query_tipo = "SELECT * FROM tbl_tipo_pago WHERE id <> 1";
$resultado_tipo=$connect->prepare($query_tipo);
$resultado_tipo->execute(); 
$num_reg_tipo=$resultado_tipo->rowCount();

$query_nv = "SELECT * FROM tbl_coti_cab WHERE id =$id_nv";
$resultado_nv=$connect->prepare($query_nv);
$resultado_nv->execute();
$row_nv = $resultado_nv->fetch(PDO::FETCH_ASSOC);
$num_reg_nv=$resultado_nv->rowCount();

$cliente1 = $row_nv['codcliente'];



$query_nvd = "SELECT * FROM vw_tbl_coti_det WHERE idventa =$id_nv";
$resultado_nvd=$connect->prepare($query_nvd);
$resultado_nvd->execute();
//$row_nvd = $resultado_nvd->fetch(PDO::FETCH_ASSOC);
$num_reg_nvd=$resultado_nvd->rowCount();

$query_cl = "SELECT * FROM tbl_contribuyente WHERE num_doc =$cliente1";
$resultado_cl=$connect->prepare($query_cl);
$resultado_cl->execute();
$row_cl = $resultado_cl->fetch(PDO::FETCH_ASSOC);
$num_reg_cl=$resultado_cl->rowCount();

//print_r($row_nv);

?>
<!doctype html>
<html lang="en">
  <head>

       <?php include 'views/template/head.php' ?>

       <style>
         .table-bordered th, .table-bordered td {
                      border: 1px solid #dce4ec;
                        border-left-width: 1px;
                    }
          .table-bordered {
                border: 1px solid #d3dbe3;
                  border-right-width: 1px;
              }
                    
           </style> 
  </head>
  <body class="horizontal dark  ">
    <div class="wrapper">
      <?php
       if($_SESSION['perfil']=='1')
       {
       include 'views/template/nav.php';
       }
       else
       {
       include 'views/template/nav_ventas.php';
       } ?>
      
      <main role="main" class="main-content">
      <form id="nota_pedido_editar" name="nota_pedido_editar">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col">
                  <h2 class="h5 page-title">Editar nota de Venta </h2>
                </div>
            <div class="col-12">
              <div class="row">
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Cliente</label>
                         <div class="input-group">
                        <span class="input-group-btn">
                          <button type="button" class="btn btn-danger go-class" data-toggle="modal" data-target="#ModalClientes"><i class="fe fe-search"></i></button>
                        </span>
                          <input type="hidden" id="id_nv" name="id_nv" value="<?=$row_nv['id']?>">
                          <input type="hidden" id="id_ruc" name="id_ruc" value="<?=$$row_cl['id_persona']?>">
                          <input type="hidden" name="action" value="nota_pedido_editar">
                          <input type="text" class="form-control" name="ruc_persona" id="ruc_persona" maxlength="11" required value="<?=$row_nv['codcliente']?>">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary" onclick="cliente2()"><i class="fe fe-search"></i></button>
                        </span>
                        </div>
                      </div>
                      <div class="col-lg-4 col-sm-6 col-sm-4">

                        <label for="">Razon Social</label>
                        <input type="text" class="form-control" name="razon_social" id="razon_social" value="<?=$row_cl['nombre_persona']?>" readonly>
                      </div>
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Obs</label>
                        <input type="text" class="form-control" name="obs" id="obs" onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?=$row_nv['obs']?>">
                      </div>
                      <div class="col-lg-4 col-sm-6 col-sm-4">
                        <label for="">Direccion</label>
                        <input type="text" class="form-control" name="razon_direccion" value="<?=$row_cl['direccion_persona']?>" id="razon_direccion" readonly>
                      </div>
                      
                    
                    </div>
                    <div class="row">
                      <div class="col-lg-3 col-sm-6 col-sm-4">
                        <label for="">Cotizacion</label>
                        <input type="text" class="form-control" name="orden_compra" id="orden_compra">
                      </div>
                      <div class="col-lg-3 col-sm-6 col-sm-4">
                        <label for="">Fecha Emision</label>
                        <input type="date" class="form-control" value="<?=$row_nv['fecha_emision']?>" name="fecha_emision" id="fecha_emision" readonly="">
                      </div>
                      <div class="col-lg-3 col-sm-6 col-sm-4">
                        <label for="">Condicion</label>
                        <select class="form-control select2" style="width: 100%;" name="condicion" id="condicion">
                  
                            
                            <?php 
                                    while($row_forma = $resultado_forma->fetch(PDO::FETCH_ASSOC) )
                               {?>
                                <option value="<?= $row_forma['tipo'] ?>"><?=$row_forma['nombre_fdp']?></option>;
                               <?php  } ?>
                          </select>
                      </div>
                      <div class="col-lg-3 col-sm-6 col-sm-4">
                        <label for="">Fecha Vencimiento</label>
                        <input type="date" class="form-control" value="<?=$row_nv['fecha_vencimiento']?>" name="fecha_vencimiento" id="fecha_vencimiento" readonly="">
                      </div>
                      
                      
                      <div class="col-lg-3 col-sm-6 col-sm-4">
                        <label for="">Tipo Doc:</label>
                         <select class="form-control select2" style="width: 100%;" name="tip_cpe" id="tip_cpe" required>
                  
                          
                          <?php 
                                  while($row_documento = $resultado_documento->fetch(PDO::FETCH_ASSOC) )
                             {?>
                              <option value="<?= $row_documento['cod'].'-'.$row_documento['id'] ?>"><?=$row_documento['nombre']?></option>;
                             <?php  } ?>
                        </select>
                      </div>
                      <div class="col-lg-3 col-sm-6 col-sm-4">
                        <label for="">Serie:</label>
                        <input type="text" class="form-control" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="4" readonly name="serie" id="serie" value="<?=$row_nv['serie']?>">
                      </div>
                      <div class="col-lg-3 col-sm-6 col-sm-4">
                        <label for="">Numero:</label>
                        <input type="text" class="form-control" readonly name="numero" id="numero" value="<?=$row_nv['correlativo']?>">
                        <input type="hidden" id="vendedor" name="vendedor" value="<?= $_SESSION['id'] ?>">
                        <input type="hidden" id="empresa" name="empresa" value="<?= $_SESSION['id_empresa']?>">
                      </div>
                    </div>
                    <div class="clearfix">
                      <div class="row mt-3">
                        <?php if($pormayor == 'SI'){ ?>
                        <div class="col-lg-2 col-sm-6 col-sm-4">
                          <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addProdcuto"><i class="fa fa-plus-circle"></i> P. Minorista</button>
                           
                        </div>
                        <div class="col-lg-2 col-sm-6 col-sm-4">
                          <button class="btn btn-secondary" type="button" data-toggle="modal" data-target="#addProdcuto1"><i class="fa fa-plus-circle"></i> P. Mayorista</button>
                        </div>
                        <?php } ?>
                        <div class="col-lg-2 col-sm-6 col-sm-4">
                          <button class="btn btn-info" type="button" data-toggle="modal" data-target="#addProdcuto2"><i class="fa fa-plus-circle"></i> Productos</button>
                        </div>

                         <div class="col-lg-2 col-sm-6 col-sm-4">
                          <button class="btn btn-success" type="button" id="btnGuardarENP"><i class="fa fa-save"></i> Guardar</button>
                         </div>
                         <div class="col-lg-2 col-sm-6 col-sm-4">
                          <button id="btnPagar" class="btn btn-warning" type="button" data-toggle="modal" data-target="#addPago"><i class="fa fa-usd"></i> Pagos</button>
                           
                        </div>
                          <div class="col-lg-2 col-sm-6 col-sm-4">                                                
                          <a href="<?=base_url()?>/ventas" class="btn btn-danger" type="button"><i class="fa fa-close"></i> Cancelar</a>
                          </div>

                       
                      </div>
                    </div>
                    <?php if($usabarras == 'SI'){ ?>
                    <hr>
                      <div class="row">
                      <div class="col-sm-4">
                        <label for="">Codigo de Barras</label>
                        <input type="text" class="form-control" name="cbarras" id="cbarras">
                      </div>
                    </div>
                    <?php } ?>
                    <hr>

                    <div class="row">
                     <div class="col-xs-12 col-sm-12 col-md-12" style="overflow: auto; position: relative;border: 0px; width: 100%; ">
                    <table id="tabla" class="table table-bordered table-hover table-striped datatables dataTable no-footer" width="100%" bordercolor="#00CC66">
                  <thead class="bg-dark" style="color:white" >
                    <tr>
                      <th width="10%">Acciones</th>
                      <th width="5%">Item</th>
                      <th width="45%">Producto</th>
                      <th>Cant.</th>                      
                      <th>Cant1.</th>
                      <th>Precio</th>
                      <th>Total</th>                      
                    </tr>
                  </thead>
                   <tbody>
                      <?php foreach($resultado_nvd as $nvd){ ?>
          <tr id="fila<?=$nvd['item']?>">
              <td><button type="button" class="btn btn-danger" onclick="eliminar(<?=$nvd['item']?>)"><i class="fe fe-trash-2"></i></button></td>
              <td><?=$nvd['item']?></td>
              <td><input type="hidden" name="itemarticulo[]" value="<?=$nvd['item']?>"><input type="hidden" name="idarticulo[]" value="<?=$nvd['codigo']?>"><input type="hidden" name="nomarticulo[]" value="<?=$nvd['descripcion']?>"><input type="hidden" name="mxmn[]" value="<?=$nvd['mxmn']?>"><?=$nvd['descripcion']?>-<?=$nvd['mxmn']?></td>

              <td><input type="hidden" name="precio_compra[]" value="<?=$nvd['costo']?>"><input type="hidden" name="factor[]" value="<?=$nvd['factor']?>"><input type="text" min="1" class="form-control input-sm" name="cantidad[]" id="cantidad[]" value="<?=$nvd['cantidad_factor']?>" onkeyup="modificarSubtotales()" required pattern="[0-9]{1,5}">
              <input type="hidden" class="form-control input-sm" name="cantidada[]" id="cantidada[]" value="<?=$nvd['cantidad_factor']?>" readonly>
              </td>

              <td><input type="text" min="1" class="form-control input-sm" name="cantidadu[]" id="cantidadu[]" value="<?=$nvd['cantidad_unitario']?>" required onkeyup="modificarSubtotales()">
                  <input type="hidden" class="form-control input-sm" name="cantidadua[]" id="cantidadua[]" value="<?=$nvd['cantidad_unitario']?>" readonly>
              </td>

              <td><input type="hidden" class="form-control input-sm" name="valor_unitario[]" id="valor_unitario[]" value="<?=$nvd['valor_unitario']?>" readonly>
              <input type="hidden" class="form-control input-sm" name="igv_unitario[]" id="igv_unitario[]" value="<?=$nvd['igv']?>" readonly>
              <input type="text" class="form-control input-sm" name="precio_venta[]" id="precio_venta[]" value="<?=$nvd['precio_unitario']?>" onkeyup="modificarSubtotales()" ></td>

              <td><span id="subtotal<?=$nvd['item']?>" name="subtotal"><?=$nvd['importe_total']?></span><input type="hidden" id="afectacion<?=$nvd['item']?>" name="afectacion[]" class="form-control input-sm" value="<?=$nvd['codigo_afectacion_alt']?>"></td>

          </tr>
                      <?php } ?>
                   </tbody>
                   <tfoot>
                    <tr>
                      <th colspan="5"></th>
                      <th>Op. Gravadas</th>
                      <td><input type="text" class="form-control text-right" name="op_g" id="op_g" readonly value="<?=$row_nv['op_gravadas']?>"></td>
                    </tr>
                    <tr>
                      <th colspan="5"></th>
                      <th>Op. Exonerada</th>
                      <td><input type="text" class="form-control text-right" name="op_e" id="op_e" readonly value="<?=$row_nv['op_exoneradas']?>"></td>
                    </tr>
                    <tr>
                      <th colspan="5"></th>
                      <th>Op. Inafecta</th>
                      <td><input type="text" class="form-control text-right" name="op_i" id="op_i"  readonly value="<?=$row_nv['op_inafectas']?>"></td>
                    </tr>
                    <tr>
                      <th colspan="5"></th>
                      <th>I.G.V.</th>
                      <td><input type="text" class="form-control text-right" name="igv" id="igv" readonly value="<?=$row_nv['igv']?>"></td>
                    </tr>
                    <tr>
                      <th colspan="5"></th>
                      <th>Total</th>
                       <td><input type="text" class="form-control text-right" name="total" id="total" readonly value="<?=$row_nv['total']?>"></td>
                    </tr>
                   </tfoot>
                                       
                 
                </table>
                   </div>
                  </div>



                                  
              
              
             
            </div> <!-- /.col -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
          <div class="modal fade" id="addPago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="exampleModalLabel">Registrar Pagos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <label for="">Monto a Pagar</label>
           <input type="text" name="montopago" id="montopago" value="" readonly class="form-control border-danger text-center text-bold" style="color: red; background-color: #96EC94; font-weight: bold; font-size: 20px;">
          </div>
           </div>
          <div class="row">
            <div class="col-sm-12">
              <label for="">Efectivo</label>
              <input type="text" name="efectivo" onkeyup="pagos()" id="efectivo" value="0.00" class="form-control text-right">
            </div>
            
          </div>
          <div class="row">
            <div class="col-sm-6">
              <label for="">Otro Medio de Pago</label>
              <select name="cvisa" id="cvisa" class="form-control">
                
               
                <?php 
                        while($row_tipo = $resultado_tipo->fetch(PDO::FETCH_ASSOC) )
                   {?>
                <option value="<?= $row_tipo['id'] ?>"><?=$row_tipo['nombre']?></option>;
                   <?php  } ?>
              </select>

            </div>
            <div class="col-sm-6">
              <label for="">Importe</label>
               <input type="text" name="visa" onkeyup="pagos()"  id="visa" value="0.00" class="form-control text-right"> 
            </div>

          </div>
          <div class="row">
            <div class="col-sm-6">
              <label for="">Saldo</label>
            <input type="text" value="0.00" name="saldo" id="saldo" readonly class="form-control border-danger text-center text-bold" style="color: red; background-color: #96EC94; font-weight: bold; font-size: 20px;">
            </div>
            <div class="col-sm-6">
              <label for="">Vuelto</label>
            <input type="text" value="0.00" name="vuelto" id="vuelto" readonly class="form-control border-danger text-center text-bold" style="color: red; background-color: #96EC94; font-weight: bold; font-size: 20px;">
            </div>           
            
          </div>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-save"></i> Guardar</button>
       
      </div>
    </div>
  </div>
</div>
        </form>
      </main> <!-- main -->
    </div> <!-- .wrapper -->

          <!-- /fin modal pagos -->
       <?php include 'views/modules/modals/persona.php' ?> 
     <?php include 'views/modules/modals/buscar_contribuyente_env.php' ?> 
      <?php include 'views/modules/modals/articulo_venta.php' ?> 
    <?php include 'views/template/pie.php' ?>


      <script src="<?=media()?>/js/funciones_cotizacion.js?v=6"></script>
   

      <script src="<?=media()?>/js/sunat_reniec.js"></script>

  </body>
</html>