<?php 
$usabarras  = $_SESSION["usabarras"];
$pormayor   = $_SESSION["venta_por_mayor"];
//echo $usabarras;
$empresa = $_SESSION["id_empresa"];
$hoy = date('Y-m-d');


$query_data = "SELECT * FROM tbl_empresas WHERE id_empresa=$empresa";
$resultado = $connect->prepare($query_data);
$resultado->execute();
$row_empresa = $resultado->fetch(PDO::FETCH_ASSOC);


$query_documento = "SELECT * FROM tbl_tipo_documento WHERE fe='1' AND id in ('09')";
$resultado_documento=$connect->prepare($query_documento);
$resultado_documento->execute(); 
$num_reg_documento=$resultado_documento->rowCount();


$lista1=$connect->query("SELECT * FROM tbl_contribuyente WHERE empresa= $empresa");
$resultado1=$lista1->fetchAll(PDO::FETCH_OBJ);


$query_forma = "SELECT * FROM tbl_gre_motivo ORDER BY id";
$resultado_forma=$connect->prepare($query_forma);
$resultado_forma->execute(); 
$num_reg_forma=$resultado_forma->rowCount();

$query_chofer = "SELECT * FROM tbl_gre_conductor WHERE estado ='1' AND empresa = $empresa ORDER BY id";
$resultado_chofer=$connect->prepare($query_chofer);
$resultado_chofer->execute(); 
$num_reg_chofer=$resultado_chofer->rowCount();

$query_vehiculo = "SELECT * FROM tbl_gre_vehiculo WHERE estado ='1' AND idempresa = $empresa ORDER BY id";
$resultado_vehiculo=$connect->prepare($query_vehiculo);
$resultado_vehiculo->execute(); 
$num_reg_vehiculo=$resultado_vehiculo->rowCount();

$query_transporte = "SELECT * FROM tbl_gre_transportista WHERE empresa = $empresa ORDER BY id";
$resultado_transporte=$connect->prepare($query_transporte);
$resultado_transporte->execute(); 
$num_reg_transporte=$resultado_transporte->rowCount();

$query_almacen = "SELECT * FROM tbl_almacen WHERE empresa = $empresa ORDER BY id";
$resultado_almacen=$connect->prepare($query_almacen);
$resultado_almacen->execute(); 
$num_reg_almacen=$resultado_almacen->rowCount();


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
      <form id="gre_nueva" name="gre_nueva">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col">
                  <h2 class="h5 page-title">Nueva GRE </h2>
                </div>
<div class="col-12">
<div class="row justify-content-center">
<div class="col-sm-12 p-1">  
<fieldset class="row border border-primary rounded p-3 mx-1">
<legend class="w-auto">Datos de Documento:</legend>
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
<div class="col-lg-2 col-sm-6 col-sm-4">
<label for="">Serie:</label>
<input type="text" class="form-control" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="4" readonly name="serie" id="serie">
</div>
<div class="col-lg-2 col-sm-6 col-sm-4">
<label for="">Numero:</label>
<input type="text" class="form-control" readonly name="numero" id="numero">
<input type="hidden" id="vendedor" name="vendedor" value="<?= $_SESSION['id'] ?>">
<input type="hidden" id="empresa" name="empresa" value="<?= $_SESSION['id_empresa']?>">
</div>
<div class="col-lg-2 col-sm-6 col-sm-4">
<label for="">Fecha Emision</label>
<input type="date" class="form-control" value="<?=$hoy?>" name="fecha_emision" id="fecha_emision" readonly="">
</div>
<div class="col-lg-2 col-sm-6 col-sm-4">
<label for="">Fecha Traslado</label>
<input type="date" class="form-control" value="<?=$hoy?>" name="fecha_traslado" id="fecha_traslado">
</div>
<div class="col-lg-2 col-sm-6 col-sm-4">
<label>Tipo.Transportista:</label>
<select name="ttransporte" id="ttransporte" class="form-control select2">
<option value="02">Transporte privado</option>
<option value="01">Transporte público</option>
</select>
</div>

<div class="col-lg-2 col-sm-6 col-sm-3">
<label for="">Motivo</label>
<select class="form-control select2" style="width: 100%;" name="motivo" id="motivo">
<?php 
while($row_forma = $resultado_forma->fetch(PDO::FETCH_ASSOC) )
{?>
<option value="<?= $row_forma['id'] ?>"><?=$row_forma['descripcion']?></option>;
<?php  } ?>
</select>
</div>


<div class="col-lg-2 col-sm-6 col-sm-3" style="display: none">
   <label for="">Otros</label>
<input type="text" class="form-control" value="" name="motros" id="motros" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled=""> 
</div>


<div class="col-lg-2 col-sm-6 col-sm-3">
<label for="">Placa Carreta</label>
<input type="text" class="form-control" name="carreta" id="carreta">
</div>

<div class="col-lg-2 col-sm-6 col-sm-3">
<label for="">Vehiculo</label>
<div class="input-group">

<select class="form-control select2"  name="vehiculo" id="vehiculo">
<?php 
while($row_vehiculo = $resultado_vehiculo->fetch(PDO::FETCH_ASSOC) )
{?>
<option value="<?= $row_vehiculo['id'] ?>"><?=$row_vehiculo['placa']?></option>;
<?php  } ?>
</select>
<div class="input-group-append">
<button class="btn btn-danger" data-toggle="modal" data-target="#ModalVehiculo" type="button"><i class="fe fe-plus"></i></button>
</div>
</div>
</div>

<div class="col-lg-3 col-sm-6 col-sm-4">
<label for="">Chofer</label>
<div class="input-group">
<select class="form-control select2"  name="chofer" id="chofer">
<?php 
while($row_chofer = $resultado_chofer->fetch(PDO::FETCH_ASSOC) )
{?>
<option value="<?= $row_chofer['id'] ?>"><?=$row_chofer['nombre'].' '.$row_chofer['apellido']?></option>;
<?php  } ?>
</select>
<div class="input-group-append">
<button class="btn btn-danger" data-toggle="modal" data-target="#ModalChofer" type="button"><i class="fe fe-plus"></i></button>
</div>
</div>
</div>

<div class="col-lg-3 col-sm-6 col-sm-4">
<label for="">Transportista</label>
<div class="input-group">
<select class="form-control select2"  name="transportista" id="transportista">
<option value="" selected="">-NINGUNO-</option>
<?php 
while($row_transporte = $resultado_transporte->fetch(PDO::FETCH_ASSOC) )
{?>
<option value="<?= $row_transporte['id'] ?>"><?=$row_transporte['razon']?></option>;
<?php  } ?>
</select>
<div class="input-group-append">
<button class="btn btn-danger" type="button"><i class="fe fe-plus"></i></button>
</div>
</div>
</div>

<div class="col-lg-2 col-sm-6 col-sm-3">
<label for="">Peso en KG.</label>
<input type="text" class="form-control" name="peso" id="peso" value="1">
</div>
<div class="col-lg-2 col-sm-6 col-sm-3">
<label for="">Nro Cajas</label>
<input type="text" class="form-control" name="ncajas" id="ncajas">
</div>
<div class="col-lg-2 col-sm-6 col-sm-3">
<label for="">Nro Carga</label>
<input type="text" class="form-control" name="ncarga" id="ncarga">
</div>
<div class="col-lg-2 col-sm-6 col-sm-3">
<label for="">DAM</label>
<input type="text" class="form-control" name="dam" id="dam">
</div>
<div class="col-lg-2 col-sm-6 col-sm-3">
<label for="">Contenedor</label>
<input type="text" class="form-control" name="contenedor" id="contenedor">
</div>
<div class="col-lg-2 col-sm-6 col-sm-3">
<label for="">Precinto</label>
<input type="text" class="form-control" name="precinto" id="precinto">
</div>

<!--
<div class="col-lg-2 col-sm-6 col-sm-3">
<label for="">Tip. Doc</label>
<select name="docadicional" id="docadicional" class="form-control">
<option value="" selected="">-NINGUNO-</option>
<option value="50">Declaración Aduanera de Mercancías (DAM)</option>
<option value="09">GUÍA REMITENTE</option>
<option value="01">FACTURA</option>
<option value="03">BOLETA</option>
</select>
</div>

<div class="col-lg-2 col-sm-6 col-sm-3">
<label for="">Serie y Numero</label>
<input type="text" class="form-control" name="nserie" id="nserie">
</div>
-->
<div class="col-sm-2" style="display: none">
<label for="">Tipo Doc Ref.</label>
<div class="input-group">
<input type="hidden" name="id_venta_ref" id="id_venta_ref">
<input type="hidden" id="cod_doc_ref" name="cod_doc_ref">
<input type="text" class="form-control" name="tip_ref" id="tip_ref" readonly>
<span class="input-group-btn">
<button type="button" class="btn btn-primary" onclick="documentorefgre()"><i class="fe fe-search"></i></button>
</span>
</div>
</div>
<div class="col-sm-2" style="display: none">
<label for="">Serie Doc Ref.</label>
<input type="text" class="form-control" id="serie_ref" name="serie_ref" readonly>
</div>
<div class="col-sm-2" style="display: none">
<label for="">Num Doc Ref</label>
<input type="text" class="form-control" id="num_ref" name="num_ref" readonly>
</div>

</fieldset>
</div>
<div class="col-sm-6">  
<fieldset class="row border border-primary rounded p-3 mx-1">
 <legend class="w-auto">Datos de Emisor:</legend>
<div class="col-sm-3">
<label for="">Ruc:</label>

<input type="text" class="form-control" name="ruc_e" id="ruc_e" readonly value="<?=$row_empresa['ruc']?>">
</div>
<div class="col-sm-9">
<label for="">Razon Social:</label>
<input type="text" class="form-control" name="razon_e" id="razon_e" readonly value="<?=$row_empresa['razon_social']?>">
</div>
<div class="col-lg-12 col-sm-6 col-sm-12">
<label for="">Direccion</label>
<div class="input-group">

<select class="form-control select2"  name="ppartida" id="ppartida">
<?php 
while($row_almacen = $resultado_almacen->fetch(PDO::FETCH_ASSOC) )
{?>
<option value="<?= $row_almacen['id'] ?>"><?=$row_almacen['direccion']?></option>;
<?php  } ?>
</select>
<div class="input-group-append">
<button class="btn btn-danger" type="button"><i class="fe fe-plus"></i></button>
</div>
</div>
</div>

</fieldset>
</div>
<div class="col-sm-6">  
<fieldset class="row border border-primary rounded p-3 mx-1">
 <legend class="w-auto">Datos de Destinatario:</legend>
<div class="row">
<div class="col-lg-5 col-sm-6 col-sm-4">
<label for="">Cliente</label>
<div class="input-group">
<span class="input-group-btn">
<button type="button" class="btn btn-danger go-class" data-toggle="modal" data-target="#ModalClientes"><i class="fe fe-search"></i></button>
</span>
<input type="hidden" id="id_ruc" name="id_ruc" value="">
<input type="hidden" name="action" value="nueva_gre">
<input type="text" class="form-control" name="ruc_persona" id="ruc_persona" maxlength="11" required>
<span class="input-group-btn">
<button type="button" class="btn btn-primary" onclick="cliente2()"><i class="fe fe-search"></i></button>
</span>
</div>
</div>
<div class="col-lg-7 col-sm-6 col-sm-8">

<label for="">Razon Social</label>
<input type="text" class="form-control" name="razon_social" id="razon_social" readonly>
</div>

<div class="col-lg-12 col-sm-6 col-sm-12">
<label for="">Direccion</label>
<div class="input-group">

<select class="form-control select2"  name="pllegada" id="pllegada" required>
<option value="">-SELECCIONAR-</option>

</select>

<div class="input-group-append">
<button class="btn btn-danger"  data-toggle="modal" data-target="#ModalDestino" type="button"><i class="fe fe-plus"></i></button>
</div>
</div>
</div>


</div>
</fieldset>
</div>
</div>

              
                    
                    <div class="clearfix">
                      <div class="row mt-3">
                        <?php if($pormayor == 'SI'){ ?>
                        <div class="col-lg-2 col-sm-6 col-sm-4">

                          <button class="btn btn-primary mt-4" type="button" data-toggle="modal" data-target="#addProdcuto"><i class="fa fa-plus-circle"></i> P. Minorista</button>
                           
                        </div>
                        <div class="col-lg-2 col-sm-6 col-sm-4">
                          <button class="btn btn-secondary mt-4" type="button" data-toggle="modal" data-target="#addProdcuto1"><i class="fa fa-plus-circle"></i> P. Mayorista</button>
                        </div>
                        <?php } ?>
                        <div class="col-lg-2 col-sm-6 col-sm-4">

                          <button class="btn btn-info mt-4" type="button" data-toggle="modal" data-target="#addProdcuto2"><i class="fa fa-plus-circle"></i> Productos</button>
                        </div>

                         <div class="col-lg-2 col-sm-6 col-sm-4">

                          <button class="btn btn-success mt-4" type="button" id="btnGuardarGRE"><i class="fa fa-save"></i> Guardar</button>
                         </div>
                         
                          <div class="col-lg-2 col-sm-6 col-sm-4">                                                
                          <a href="<?=base_url()?>/gre" class="btn btn-danger mt-4" type="button"><i class="fa fa-close"></i> Cancelar</a>
                          </div>

                          <div class="col-lg-6 col-sm-6 col-sm-6">   
                          <label for="">Obs</label>
                          <textarea class="form-control" name="obs" id="obs" col="10" row="15" ></textarea>     
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
                      <th width="50%">Producto</th>
                      <th>Cant.</th>
                      <th>Unidad</th>
                      <th>Precio</th>
                      <th>Total</th>
                      
                    </tr>
                  </thead>
                <tbody id="detalleventa">
                      
                    </tbody>
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

        </form>
      </main> <!-- main -->
    </div> <!-- .wrapper -->

          <!-- /fin modal pagos -->
    <?php include 'views/modules/modals/persona.php'?>
    <?php include 'views/modules/modals/vehiculo_add.php'?>
    <?php include 'views/modules/modals/destino_add.php'?>
    <?php include 'views/modules/modals/chofer_add.php'?>  
    <?php include 'views/modules/modals/buscar_doc_ref_gre.php' ?> 
    <?php include 'views/modules/modals/buscar_contribuyente_nv.php' ?> 
    <?php include 'views/modules/modals/articulo_venta.php' ?>
    <?php include 'views/template/pie.php'?>

    <script src="assets/js/nuevo_vehiculo.js?v=3"></script>
    <script src="assets/js/nuevo_chofer.js?v=3"></script>
    <script src="assets/js/nuevo_destino.js?v=3"></script>     

<script>
    /*$("#motivo").change(() => $("#motros").val($( "#motivo option:selected" ).text()));*/
    
 $(document).ready(function () {
        $('#motivo').change(function (e) {
            var motros = document.getElementById('motros');
            $("#motros").val($( "#motivo option:selected" ).text());
           //alert($(this).val() );
          if ($(this).val() === "13") {
              motros.disabled = false;
              //alert('elegio:'+$(this).val())
            //$('#motros').prop("disabled", false);
          } else {
             motros.disabled = true;
          }
        })
      });

</script>

  </body>
</html>