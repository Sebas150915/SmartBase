<?php 

$empresa = $_SESSION["id_empresa"];
$hoy = date('Y-m-d');

$query_documento = "SELECT * FROM tbl_tipo_documento WHERE fe='1' AND id in ('CT', 'NP')";
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
$calculaigv='NO';

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
      <form id="cotizacion_nueva" name="cotizacion_nueva">
        <div class="container-fluid">
          <h3>Nueva Cotizacion</h3>
          <hr>
          <div class="row justify-content-center">
            <div class="col-12">
              <div class="row">
                      <div class="col-lg-3 col-sm-6 col-sm-2">
                        <label for="">Cliente</label>
                        <div class="input-group">
                        <span class="input-group-btn">
                          <button type="button" class="btn btn-danger go-class" data-toggle="modal" data-target="#ModalClientes"><i class="fe fe-search"></i></button>
                        </span>
                          <input type="hidden" id="id_ruc" name="id_ruc" value="">
                          <input type="hidden" name="action" value="nueva_cotizacion">
                          <input type="text" class="form-control" name="ruc_persona" id="ruc_persona" maxlength="11" required>
                        <span class="input-group-btn"> 
                            <button type="button" class="btn btn-primary" onclick="cliente()"><i class="fe fe-search"></i></button>
                        </span>
                        </div>
                      </div>
                      <div class="col-lg-5 col-sm-6 col-sm-4">

                        <label for="">Razon Social</label>
                        <input type="text" class="form-control" name="razon_social" id="razon_social" readonly>
                      </div>
                      
                      <div class="col-lg-4 col-sm-6 col-sm-4">
                        <label for="">Direccion</label>
                        <input type="text" class="form-control" name="razon_direccion" id="razon_direccion" readonly>
                      </div>
                      
                    
                    </div>
                    <div class="row">
                      <div class="col-lg-3 col-sm-6 col-sm-4">
                        <label for="">Pedido</label>
                        <input type="text" class="form-control" name="orden_compra" id="orden_compra">
                      </div>
                      <div class="col-lg-3 col-sm-6 col-sm-4">
                        <label for="">Fecha Emision</label>
                        <input type="date" class="form-control" value="<?=$hoy?>" name="fecha_emision" id="fecha_emision" readonly="">
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
                        <input type="date" class="form-control" value="<?=$hoy?>" name="fecha_vencimiento" id="fecha_vencimiento" readonly="">
                      </div>
                      
                      
                      <div class="col-lg-3 col-sm-6 col-sm-4">
                        <label for="">Tipo Doc:</label>
                         <select class="form-control select2" style="width: 100%;" name="tip_cpe" id="tip_cpe" required>
                  
                          <option value="">Seleccionar Documento</option>
                          <?php 
                                  while($row_documento = $resultado_documento->fetch(PDO::FETCH_ASSOC) )
                             {?>
                              <option value="<?= $row_documento['cod'].'-'.$row_documento['id'] ?>"><?=$row_documento['nombre']?></option>;
                             <?php  } ?>
                        </select>
                      </div>
                      <div class="col-lg-3 col-sm-6 col-sm-4">
                        <label for="">Serie:</label>
                        <input type="text" class="form-control" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="4" readonly name="serie" id="serie">
                      </div>
                      <div class="col-lg-3 col-sm-6 col-sm-4">
                        <label for="">Numero:</label>
                        <input type="text" class="form-control" readonly name="numero" id="numero">
                        <input type="hidden" id="vendedor" name="vendedor" value="<?= $_SESSION['id'] ?>">
                        <input type="hidden" id="empresa" name="empresa" value="<?= $_SESSION['id_empresa']?>">
                      </div>
                      <div class="col-lg-3 col-sm-6 col-sm-4">
                        <label for="">Validez:</label>
                        <input type="number" class="form-control text-center" name="validez" id="validez" value="7">
                      </div>
                    </div>
                    
                    
                     <div class="row">
                     <div class="col-sm-12">
                     <label for="">Descripcion</label>
                     <textarea name="descripcion" id="descripcion" cols="30" rows="5" class="form-control"></textarea>
                     </div>
                     </div>
                    
                    
                    
                    
                    <div class="clearfix">
                      <div class="row mt-3">

                        <?php if($_SESSION["usabarras"]  == 'SI'){ ?>
                        <div class="col-lg-2 col-sm-6 col-sm-4">
                          <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addProdcuto"><i class="fa fa-plus-circle"></i> P. Minorista</button>
                           
                        </div>
                        <div class="col-lg-2 col-sm-6 col-sm-4">
                          <button class="btn btn-secondary" type="button" data-toggle="modal" data-target="#addProdcuto1"><i class="fa fa-plus-circle"></i> P. Mayorista</button>
                        </div>
                        <?php }
                        else{ ?>
                        <div class="col-lg-2 col-sm-6 col-sm-4">
                          <button class="btn btn-secondary" type="button" data-toggle="modal" data-target="#addProdcuto2"><i class="fa fa-plus-circle"></i> Productos</button>
                        </div>
                        <?php }
                        ?>



                         <div class="col-lg-2 col-sm-6 col-sm-4">
                          <button class="btn btn-success" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                         </div>
                         
                          <div class="col-lg-2 col-sm-6 col-sm-4">                                                
                          <a href="<?=base_url()?>/ventas" class="btn btn-danger" type="button"><i class="fa fa-close"></i> Cancelar</a>
                          </div>

                       
                      </div>
                    </div>
                    <hr>
                    <?php if($_SESSION["usabarras"] =='SI'){ ?>
                    <div class="row">
                      <div class="col-sm-4">
                        <label for="">Codigo de Barras</label>
                        <input type="text" class="form-control" name="cbarras" id="cbarras">
                      </div>
                    </div>
                    <hr>
                    <?php } ?>
                    <div class="row">
                     <div class="col-xs-12 col-sm-12 col-md-12" style="overflow: auto; position: relative;border: 0px; width: 100%; ">
                    <table id="tabla" class="table table-bordered table-hover table-striped datatables dataTable no-footer" width="100%" bordercolor="#00CC66">
                  <thead class="bg-dark" style="color:white" >
                    <tr>
                      <th width="10%">Acciones</th>
                      <th width="5%">Item</th>
                      <th width="50%">Producto</th>
                      <th>Cant.</th>
                      <th>Unidad</th>
                      <th>Precio</th>
                      <th>Total</th>
                      
                    </tr>
                  </thead>
                
                   <tfoot>
                    <tr>
                      <th colspan="5"></th>
                      <th>Op. Gravadas</th>
                      <td><input type="text" class="form-control text-right" name="op_g" id="op_g" value="0.00" readonly></td>
                    </tr>
                    <tr>
                      <th colspan="5"></th>
                      <th>Op. Exonerada</th>
                      <td><input type="text" class="form-control text-right" name="op_e" id="op_e" value="0.00" readonly></td>
                    </tr>
                    <tr>
                      <th colspan="5"></th>
                      <th>Op. Inafecta</th>
                      <td><input type="text" class="form-control text-right" name="op_i" id="op_i" value="0.00" readonly></td>
                    </tr>
                    <tr>
                      <th colspan="5"></th>
                      <th>I.G.V.</th>
                      <td><input type="text" class="form-control text-right" name="igv" id="igv" value="0.00" readonly></td>
                    </tr>
                    <tr>
                      <th colspan="5"></th>
                      <th>Total</th>
                       <td><input type="text" class="form-control text-right" name="total" id="total" value="0.00" readonly></td>
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
    <?php include 'views/modules/modals/buscar_contribuyente.php' ?> 
    <?php include 'views/modules/modals/articulo_venta.php' ?> 
    <?php include 'views/template/pie.php' ?>


      <script src="<?=media()?>/js/funciones_cotizacion.js?v=<?=date('s')?>"></script>
   

      <script src="<?=media()?>/js/sunat_reniec.js"></script>
      <script>
          listarcliente(); 
      </script>

  </body>
</html>