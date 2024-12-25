<?php 

//echo $usabarras;
$empresa = $_SESSION["id_empresa"];
$hoy = date('Y-m-d');

$query_documento = "SELECT * FROM tbl_tipo_documento WHERE fe='1' AND id='20'";
$resultado_documento=$connect->prepare($query_documento);
$resultado_documento->execute(); 
$num_reg_documento=$resultado_documento->rowCount();



$lista1=$connect->query("SELECT * FROM tbl_contribuyente WHERE empresa= $empresa");
$resultado1=$lista1->fetchAll(PDO::FETCH_OBJ);


$query_forma = "SELECT * FROM tbl_forma_pago ORDER BY dias";
$resultado_forma=$connect->prepare($query_forma);
$resultado_forma->execute(); 
$num_reg_forma=$resultado_forma->rowCount();

$query_tipo = "SELECT * FROM tbl_tipo_pago WHERE id <> 1";
$resultado_tipo=$connect->prepare($query_tipo);
$resultado_tipo->execute(); 
$num_reg_tipo=$resultado_tipo->rowCount();


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
      <form id="nueva_retencion" name="nueva_retencion">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col">
                  <h2 class="h5 page-title">Nueva Retencion </h2>
                </div>
          <div class="col-12">
             <div class="row">
                
                      <div class="col-lg-2 col-sm-6 col-sm-4">
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
                      <div class="col-lg-1 col-sm-6 col-sm-4">
                        <label for="">Serie:</label>
                        <input type="text" class="form-control" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="4" readonly name="serie" id="serie">
                      </div>
                      <div class="col-lg-1 col-sm-6 col-sm-4">
                        <label for="">Numero:</label>
                        <input type="text" class="form-control" readonly name="numero" id="numero">
                        <input type="hidden" id="vendedor" name="vendedor" value="<?= $_SESSION['id'] ?>">
                        <input type="hidden" id="tipodocumento" name="tipodocumento" value="20" />
                        <input type='hidden' id='regular' name='regular' value='00' />
                        <input type='hidden' id='pago' name='pago' value='CONTADO' />
                        <input type="hidden" id="empresa" name="empresa" value="<?= $_SESSION['id_empresa']?>">
                      </div>

                      <div class="col-lg-2 col-sm-6 col-sm-4">
                        <label for="">Fecha Emision</label>
                        <input type="date" class="form-control" value="<?=$hoy?>" name="fecha_emision" id="fecha_emision" >
                      </div>
                   <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Proveedor</label>
                        <div class="input-group">
                        <span class="input-group-btn">
                        <button type="button" class="btn btn-danger go-class" data-toggle="modal" data-target="#ModalClientes"><i class="fe fe-search"></i></button>
                        </span>
                        <input type="hidden" id="id_ruc" name="id_ruc" value="0">
                        <input type="hidden" name="action" value="nota_venta">
                        <input type="text" class="form-control" name="ruc_persona" id="ruc_persona" maxlength="11" required>
                        <span class="input-group-btn">
                        <button type="button" class="btn btn-primary" onclick="cliente2()"><i class="fe fe-search"></i></button>
                        </span>
                        </div>
                        </div>
                      
                        <div class="col-lg-4 col-sm-6 col-sm-4">

                        <label for="">Razon Social</label>
                        <input type="text" class="form-control" name="razon_social" id="razon_social" readonly>
                        <input type="hidden" class="form-control" name="razon_direccion" id="razon_direccion" readonly>
                        </div>                        
                      
                    </div>
                    <hr>

              <div class="row">
                <div class="col-sm-2">
                  <label for="">Tip Doc Rel</label>
                  <select name="tipdocrel" id="tipdocrel" class="form-control">
                    <option value="01">FACTURA</option>
                    <option value="07">NOTA DE CREDITO</option>
                    <option value="08">NOTA DE DEBITO</option>
                    <option value="12">TICKET MAQUINA REGISTRADORA</option>
                  </select>
                </div>
                <div class="col-sm-2">
                  <label for="">Fecha Rel.</label>
                  <input type="date" class="form-control" name="fecharel" id="fecharel">
                </div>
                <div class="col-sm-2">
                  <label for="">Serie Rel.</label>
                  <input type="text" class="form-control" minlength="4" maxlength="4" name="serielrel" id="serielrel">
                </div>
                <div class="col-sm-2">
                  <label for="">Correltivo Rel.</label>
                  <input type="text" class="form-control" maxlength="8" name="numerorel" id="numerorel">
                </div>
                <div class="col-sm-2">
                  <label for="">Importe Rel</label>
                  <input type="text" class="form-control input-money text-right" name="totalrel" id="totalrel" value="0.00">
                </div>
                <div class="col-sm-2">
                  <label for="">Moneda</label>
                  <select name="moneda" id="moneda" class="form-control">
                    <option value="PEN">SOLES</option>
                    <option value="USD">DOLARES</option>
                  </select>
                </div>
                
                                       
              </div>
              <hr>
                   
                    <div class="clearfix">
                      <div class="row mt-3">
                        <div class="col-sm-3">
                
                          <button class="btn btn-primary btn-block" type="button" onclick="agregadetalleret()"><i class="fa fa-plus"></i> Agregar</button>
                </div>
                     
                         <div class="col-lg-3 col-sm-6 col-sm-4">
                          <button class="btn btn-success btn-block" onclick="gnota()" type="button" id="btnGuardarRE"><i class="fa fa-save"></i> Guardar</button>
                         </div>
                        <div class="col-lg-3 col-sm-6 col-sm-4">                                                
                          <a href="<?=base_url()?>/retenciones" class="btn btn-danger btn-block" type="button"><i class="fa fa-close"></i> Cancelar</a>
                          </div>

                       
                      </div>
                    </div>
                   
                    <hr>

                    <div class="row">
                     <div class="col-xs-12 col-sm-12 col-md-12" style="overflow: auto; position: relative;border: 0px; width: 100%; ">
                    <table id="tabla" class="table table-bordered table-hover table-striped datatables dataTable no-footer" width="100%" bordercolor="#00CC66">
                  <thead class="bg-dark" style="color:white" >
                    <tr>
                      <th width="2%">#</th>
                            <th width="10%">Tipo</th>
                            <th width="10%">Serie</th>
                            <th width="10%">Número</th>
                            <th width="10%">Fecha</th>
                            <th width="10%">Mon</th>
                            <th width="10%">T.Doc</th>
                            <th width="10%">T.Pag</th>           
                          <td width="8%">T(3%)</th>
                          <td width="10%">Ret</th>
                          <td width="10%">Neto</th>
                  </thead>
                
                   <tfoot>
                    <tr>
                      <th colspan="9"></th>
                      <th>Importe Total</th>
                      <td><input type="text" class="form-control text-right" name="importeret" id="importeret" value="0.00" readonly></td>
                    </tr>
                    <tr>
                      <th colspan="9"></th>
                      <th>Tasa</th>
                      <td><input type="text" class="form-control text-right" name="tasaret" id="tasaret" value="3.00" readonly></td>
                    </tr>
                    <tr>
                      <th colspan="9"></th>
                      <th>Importe Percibido</th>
                      <td><input type="text" class="form-control text-right" name="op_i" id="op_i" value="0.00" readonly></td>
                    </tr>
                    
                   </tfoot>
                                       
                 
                </table>
                   </div>
                  </div>



                                  
              
              
             
  </div> <!-- /.col -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
          
        </form>
         <?php include 'views/template/pie.php' ?>
      </main> <!-- main -->
    </div> <!-- .wrapper -->

          <!-- /fin modal pagos -->
       <?php include 'views/modules/modals/persona.php' ?>
     
    <?php include 'views/modules/modals/buscar_contribuyente_nv.php' ?>

 
      <script src="assets/js/funciones_retencion.js?v=8"></script>
    <script src="<?=media()?>/js/tablas.js"></script>

      <script src="assets/js/sunat_reniec.js"></script>

  </body>
</html>