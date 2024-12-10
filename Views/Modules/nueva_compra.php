<?php 

$empresa = $_SESSION["id_empresa"];
$hoy = date('Y-m-d');

    $query_tc = $connect->prepare("SELECT * FROM tbl_tipo_cambio WHERE fecha = '$hoy'");
    $query_tc->execute();
    $row_tc   = $query_tc->fetch(PDO::FETCH_ASSOC);

      $tc='1.000';
   if($row_tc){ $tc  = $row_tc['tventa']; }
    

$query_documento = "SELECT * FROM tbl_tipo_documento WHERE  id in ('01','03','99','09','00')";
$resultado_documento=$connect->prepare($query_documento);
$resultado_documento->execute(); 
$num_reg_documento=$resultado_documento->rowCount();

$query_detraccion = "SELECT * FROM tbl_por_det";
$resultado_detraccion=$connect->prepare($query_detraccion);
$resultado_detraccion->execute(); 
$num_reg_detraccion=$resultado_detraccion->rowCount();


$lista1=$connect->query("SELECT * FROM tbl_contribuyente WHERE empresa= $empresa");
$resultado1=$lista1->fetchAll(PDO::FETCH_OBJ);


$query_forma = "SELECT * FROM tbl_forma_pago ORDER BY tipo";
$resultado_forma=$connect->prepare($query_forma);
$resultado_forma->execute(); 
$num_reg_forma=$resultado_forma->rowCount();


$query_alm = "SELECT * FROM tbl_almacen WHERE empresa= $empresa";
$resultado_alm=$connect->prepare($query_alm);
$resultado_alm->execute(); 
$num_reg_alm=$resultado_alm->rowCount();



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
      <form id="venta_nueva" name="venta_nueva">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12">
              <div class="row">
                      <div class="col-lg-3 col-sm-6 col-sm-2">
                        <label for="">Proveedor</label>
                        <div class="input-group">
                        <span class="input-group-btn">
                          <button type="button" class="btn btn-danger go-class" data-toggle="modal" data-target="#ModalClientes"><i class="fa fa-search"></i></button>
                        </span>
                          <input type="hidden" id="id_ruc" name="id_ruc" value="">
                          <input type="hidden" name="action" value="nueva_venta">
                          <input type="text" class="form-control" name="ruc_persona" id="ruc_persona" maxlength="11" required>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary" onclick="cliente()"><i class="fa fa-search"></i></button>
                        </span>
                        </div>
                      </div>
                      <div class="col-lg-3 col-sm-6 col-sm-4">

                        <label for="">Razon Social</label>
                        <input type="text" class="form-control" name="razon_social" id="razon_social" readonly>
                      </div>
                      <div class="col-lg-3 col-sm-6 col-sm-4">
                        <label for="">Direccion</label>
                        <input type="text" class="form-control" name="razon_direccion" id="razon_direccion" readonly>
                      </div>
                      <div class="col-lg-3 col-sm-6 col-sm-2">
                        <label for="">Orden de compra</label>
                        <input type="text" class="form-control" name="orden_compra" id="orden_compra">
                      </div>
                    
                    </div>

                    <div class="row mt-1">
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Fecha Emision</label>
                        <input type="date" class="form-control" value="<?=$hoy?>" name="fecha_emision" id="fecha_emision">
                      </div>
                      
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Fecha Vencimiento</label>
                        <input type="date" class="form-control" value="<?=$hoy?>" name="fecha_vencimiento" id="fecha_vencimiento">
                      </div>
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Condicion</label>
                        <select class="form-control select2" style="width: 100%;" name="condicion" id="condicion">
                  
                            
                            <?php 
                                    while($row_forma = $resultado_forma->fetch(PDO::FETCH_ASSOC) )
                               {?>
                                <option value="<?= $row_forma['tipo'] ?>"><?=$row_forma['nombre_fdp']?></option>;
                               <?php  } ?>
                          </select>
                      </div>
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Moneda</label>
                        <select name="moneda" id="moneda" class="form-control">
                          <option value="PEN">Soles</option>
                          <option value="USD">Dolar</option>
                        </select>
                      </div>
                      
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Tipo de Cambio</label>
                        <input type="text" class="form-control text-right"  name="tcambio" id="tcambio" value="<?=$tc?>">
                      </div>
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Detraccion</label>
                        <select class="form-control select2" style="width: 100%;" name="det" id="det">
                  
                            <?php 
                                    while($row_det = $resultado_detraccion->fetch(PDO::FETCH_ASSOC) )
                               {?>
                                <option value="<?= $row_det['id'] ?>"><?=$row_det['por'].'% | '.$row_det['nombre']?></option>;
                               <?php  } ?>
                          </select>
                          <input type="hidden" name="por_det" id="por_det" value="0">
                      </div>
                      
                      
                    </div>
                    <div class="row mt-1">
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Tipo Doc:</label>
                         <select class="form-control select2" style="width: 100%;" name="tip_cpe" id="tip_cpe">
                  
                          <option selected="selected">Seleccionar Documento</option>
                          <?php 
                                  while($row_documento = $resultado_documento->fetch(PDO::FETCH_ASSOC) )
                             {?>
                              <option value="<?= $row_documento['id'] ?>"><?=$row_documento['nombre']?></option>;
                             <?php  } ?>
                        </select>
                      </div>
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Serie:</label>
                        <input type="text" class="form-control" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="4" name="serie" id="serie" required>
                      </div>
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Numero:</label>
                        <input type="text" class="form-control"  name="numero" id="numero" required maxlength="8">
                        <input type="hidden" id="vendedor" name="vendedor" value="<?= $_SESSION['id'] ?>">
                        <input type="hidden" id="empresa" name="empresa" value="<?= $_SESSION['id_empresa']?>">
                      </div>
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Importe Detraccon:</label>
                        <input type="text" class="form-control text-right" name="imp_det" id="imp_det" readonly value="0.00">
                      </div>
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Saldo a Pagar:</label>
                        <input type="text" class="form-control text-right" name="saldo_ft" id="saldo_ft" readonly value="0.00">
                      </div>
                      
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Almacen</label>
                        <select class="form-control select2" style="width: 100%;" name="alm" id="alm">
                  
                            <?php 
                                    while($row_alm = $resultado_alm->fetch(PDO::FETCH_ASSOC) )
                               {?>
                                <option value="<?= $row_alm['id'] ?>"><?=$row_alm['nombre']?></option>;
                               <?php  } ?>
                          </select>
                          
                      </div>
                      
                    </div>
                    <div class="row d-none">
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Tip. Doc Ref.</label>
                        <select name="tdocref" id="tdocref" disabled class="form-control">
                          <option value=""></option>
                          <option value="01">FACTURA</option>
                          <option value="03">BOLETA</option>
                          
                        </select>
                      </div>
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Serie Doc. Ref.</label>
                        <input type="text" maxlength="4" id="sdocref" name="sdocref" value="" disabled class="form-control">
                      </div>
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Num. Doc. Ref.</label>
                        <input type="text" maxlength="8" id="ndocref" name="ndocref" value="" disabled class="form-control">
                      </div>
                    </div>


                    <div class="clearfix">
                      <div class="row mt-3">
                        <div class="col-lg-12 col-sm-12 col-md-12">
                          <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addProdcuto1"><i class="fa fa-plus-circle"></i> Agregar Producto</button>
                          <button class="btn btn-success" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                          <button class="btn btn-success" onclick="validaCompra()" >Validar Cpe SUNAT</button>
                          
                          <a href="<?=base_url()?>/compras" class="btn btn-danger" type="button"><i class="fa fa-close"></i> Cancelar</a>
                        </div>
                       
                      </div>
                    </div>
          
                    <hr>

                    <div class="row">
                     <div class="col-xs-12 col-sm-12 col-md-12" style="overflow: auto; position: relative;border: 0px; width: 100%; ">
                    <table id="tabla" class="table table-bordered table-hover table-striped datatables dataTable no-footer" width="100%" bordercolor="#00CC66">
                  <thead class="bg-dark" style="color:white" >
                    <tr>
                      <th width="8%">Acciones</th>
                      <th width="5%">Item</th>
                      <th>Producto</th>
                      <th>Cant.</th>
                      <th>Costo Uni.</th>
                      <th>%Min</th>
                      <th>P. Min.</th>
                      <th>%May</th>
                      <th>P. May.</th>
                      <th>Total</th>
                      
                    </tr>
                  </thead>
                
                          
                 
                </table>
                 <hr>
                <table id="tabla1" class="table table-striped table-bordered  nowrap no-footer" width="100%">
                   <thead>
                    <tr>
                      <th>Op. Gravadas</th>
                      <th>Op. Exonerada</th>
                      <th>Op. Inafecta</th>
                      <th>I.G.V.</th>
                      <th>Total</th>
                    </tr>
                   </thead> 
                      <tbody>
                        <tr>
                          <td><input type="text" class="form-control text-right" name="op_g" id="op_g" value="0.00" readonly></td>
                          <td><input type="text" class="form-control text-right" name="op_e" id="op_e" value="0.00" readonly></td>
                           <td><input type="text" class="form-control text-right" name="op_i" id="op_i" value="0.00" readonly></td>
                           <td><input type="text" class="form-control text-right" name="igv" id="igv" value="0.00" readonly></td>
                            <td><input type="text" class="form-control text-right" name="total" id="total" value="0.00" readonly></td>
                        </tr>
                      </tbody>                  
                                      
                                   
                                      
                 
                </table>
                   </div>
                  </div>



                                  
              
              
             
            </div> <!-- /.col -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
          
        </form>
      </main> <!-- main -->
    </div> <!-- .wrapper -->

          <!-- /fin modal pagos -->
       <?php include 'views/modules/modals/persona.php' ?> 
     <?php include 'views/modules/modals/buscar_contribuyente.php' ?> 
      <?php include 'views/modules/modals/articulo_compra.php' ?> 
    <?php include 'views/template/pie.php' ?>
        <script defer="" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

     <!-- <script type="text/javascript" src="Assets/vendors/inputMask/inputmask.js" charset="utf-8"></script>-->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.8/jquery.inputmask.bundle.min.js"></script>


      <script src="<?=media()?>/js/funciones_compras.js?v=1"></script>
   

      <script src="<?=media()?>/js/sunat_reniec.js"></script>
      
  <script type="text/javascript">
      $(document).ready(function () {
      $('#tcambio')
        .inputmask({
          alias: 'decimal', 
          allowMinus: false,  
          digits: 4, 
          max: 999999.9999
      });
    })
  </script>
   <script type="text/javascript">
      $(document).ready(function () {
      $('#imp_det')
        .inputmask({
          alias: 'decimal', 
          allowMinus: false,  
          digits: 2, 
          max: 999999.99
      });
    })
  </script>
  <script type="text/javascript">
      $(document).ready(function () {
      $('#saldo_ft')
        .inputmask({
          alias: 'decimal', 
          allowMinus: false,  
          digits: 2, 
          max: 999999.99
      });
    })
  </script>
      <script>
              $(function(){
                $("#tip_cpe").change(function(){
                  if($(this).val()=="07"){
                    $("#tdocref").prop("disabled",false);
                    $("#sdocref").prop("disabled",false);
                    $("#ndocref").prop("disabled",false);
                  }
                  else
                  {
                    $("#tdocref").prop("disabled",true);
                    $("#sdocref").prop("disabled",true);
                    $("#ndocref").prop("disabled",true);
                  }
                });
              });
              
              listarcliente(); 
           </script> 

<script>

function validaCompra()
{
   var ruc = $('#ruc_persona').val();
   var serie = $('#serie').val();
   var numero = $('#numero').val();
   var fecha = $('#fecha').val();
   var total = $('#total').val();

   
 

}

</script>
  </body>
</html>