<?php 
$empresa = $_SESSION["id_empresa"];


$lista2=$connect->query("SELECT * FROM vw_tbl_coti_cab WHERE empresa= $empresa AND tipocomp in ('CT','NP') ORDER BY id DESC ");
$resultado2=$lista2->fetchAll(PDO::FETCH_OBJ);

//echo $_SESSION["precio"];
$query_emp = "SELECT * FROM tbl_empresas WHERE id_empresa=$empresa";
$resultado_emp = $connect->prepare($query_emp);
$resultado_emp->execute();
$row_emp = $resultado_emp->fetch(PDO::FETCH_ASSOC);
//print_r($row_emp);
$calculaigv=$row_emp['calculaigv'];
//echo 'hola: '.$calculaigv
$hoy = date('Y-m-d');
//sumo 1 día
//echo date("d-m-Y",strtotime($hoy."+ 1 days")); 
//resto 1 día
$min_date = date("Y-m-d",strtotime($hoy."- 4 days")); 
/*****************/
    $query_tc = $connect->prepare("SELECT * FROM tbl_tipo_cambio WHERE fecha = '$hoy' ");
    $query_tc->execute();
    $row_tc   = $query_tc->fetch(PDO::FETCH_ASSOC);

    $tc='1.000';
   if($row_tc){ $tc  = $row_tc['tventa']; }
    




$query_documento = "SELECT * FROM tbl_tipo_documento WHERE fe='1' AND id in ('01','03')";
$resultado_documento=$connect->prepare($query_documento);
$resultado_documento->execute(); 
$num_reg_documento=$resultado_documento->rowCount();

/*
$lista1=$connect->query("SELECT * FROM tbl_contribuyente WHERE empresa= $empresa");
$resultado1=$lista1->fetchAll(PDO::FETCH_OBJ);

*/

$query_forma = "SELECT * FROM tbl_forma_pago ORDER BY tipo";
$resultado_forma=$connect->prepare($query_forma);
$resultado_forma->execute(); 
$num_reg_forma=$resultado_forma->rowCount();

$query_tipo = "SELECT * FROM tbl_tipo_pago  WHERE empresa = $empresa AND id <> 1";
$resultado_tipo=$connect->prepare($query_tipo);
$resultado_tipo->execute(); 
$num_reg_tipo=$resultado_tipo->rowCount();

$query_detraccion = "SELECT * FROM tbl_por_det";
$resultado_detraccion=$connect->prepare($query_detraccion);
$resultado_detraccion->execute(); 
$num_reg_detraccion=$resultado_detraccion->rowCount();


$query_vendedor = "SELECT * FROM tbl_vendedor WHERE idempresa = $empresa";
$resultado_vendedor=$connect->prepare($query_vendedor);
$resultado_vendedor->execute(); 
$num_reg_vendedor=$resultado_vendedor->rowCount();
?>



<!doctype html>
<html lang="en">
  <head>
       <?php include 'views/template/head.php' ?>

        <style>
        .table-bordered th, .table-bordered td 
        {
        border: 1px solid #dce4ec;
        border-left-width: 1px;
        }
        .table-bordered {
        border: 1px solid #d3dbe3;
        border-right-width: 1px;
        }
        .my-class-form-control-group{
        display:flex;
        align-items:Center;
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
         <div class="row justify-content-left">
           <h3>Nueva Venta</h3>
         </div>
        
          <hr>
          <div class="row justify-content-center">
            <div class="col-12">
              <div class="row">
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Cliente</label>
                        <div class="input-group">
                        <span class="input-group-btn">
                          <button type="button" class="btn btn-danger go-class" data-toggle="modal" data-target="#ModalClientes"><i class="fe fe-search"></i></button>
                        </span>
                          <input type="hidden" id="id_ruc" name="id_ruc" value="">
                          <input type="hidden" id="detalles" name="detalles" value="0">
                          <input type="hidden" name="action" value="nueva_venta">
                          <input type="text" class="form-control" name="ruc_persona" id="ruc_persona" maxlength="11" required>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary" onclick="cliente()"><i class="fe fe-search"></i></button>
                        </span>
                        </div>
                      </div>
                      <div class="col-lg-4 col-sm-6 col-sm-4">

                        <label for="">Razon Social</label>
                        <input type="text" class="form-control" name="razon_social" id="razon_social" readonly>
                      </div>
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Obs</label>
                        <input type="text" class="form-control" name="obs" id="obs" onkeyup="javascript:this.value=this.value.toUpperCase();">
                      </div>
                      <div class="col-lg-4 col-sm-6 col-sm-4">
                        <label for="">Direccion</label>
                        <input type="text" class="form-control" name="razon_direccion" id="razon_direccion" readonly>
                      </div>
                      
                    
                    </div>
              <div class="row">
                      <div class="col-lg-2 col-sm-6 col-sm-4">
                        <label for="">Cotizacion</label>
                        <input type="text" class="form-control" name="orden_compra" id="orden_compra">
                      </div>
                      <div class="col-lg-2 col-sm-6 col-sm-4">
                        <label for="">Fecha Emision</label>
                        <input type="date" class="form-control" value="<?=$hoy?>" name="fecha_emision" id="fecha_emision"  min="<?=$min_date?>" max="<?=$hoy?>">
                      </div>
                      
                      <div class="col-lg-2 col-sm-6 col-sm-4">
                        <label for="">Fecha Vencimiento</label>
                        <input type="date" class="form-control" value="<?=$hoy?>" name="fecha_vencimiento" id="fecha_vencimiento" >
                      </div>
                      
                      
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

                        <input type="hidden" id="relacionado_id" name="relacionado_id" value="0">
                        <input type="hidden" id="estadopagoanticipo" name="estadopagoanticipo" value="0">
                        <input type="hidden" id="relacionado_serie" name="relacionado_serie" value="">




                      </div>
                      <div class="col-lg-2 col-sm-6 col-sm-4">
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
                        <select name="moneda" id="moneda" class="form-control select2">
                          <option value="PEN">SOLES</option>
                          <option value="USD">DOLAR</option>
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
                                <option value="<?= $row_det['id'] ?>"><?=$row_det['por']. '| '.$row_det['nombre']?></option>;
                               <?php  } ?>
                          </select>
                          <input type="hidden" name="por_det" id="por_det" value="0">
                      </div>
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Importe Detraccon:</label>
                        <input type="text" class="form-control text-right" name="imp_det" id="imp_det" readonly value="0.00">
                      </div>
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Saldo a Pagar:</label>
                        <input type="text" class="form-control text-right" name="saldo_ft" id="saldo_ft" readonly value="0.00">
                      </div>
                      <div class="col-lg-4 col-sm-6 col-sm-6">
                        <label for="">Correo:</label>
                        <input type="text" class="form-control" name="correo_cliente" id="correo_cliente">
                      </div>
                       <div class="col-lg-2 col-sm-6 col-sm-3">
                        <label for="">Vendedor:</label>
                         <select class="form-control select2" style="width: 100%;" name="ven" id="ven">

                          <option value="0">--VENDEDOR POR DEFECTO--</option>
                  
                            <?php 
                                    while($row_ven = $resultado_vendedor->fetch(PDO::FETCH_ASSOC) )
                               {?>
                                <option value="<?= $row_ven['id'] ?>"><?=$row_ven['nombre'].'% | '.$row_ven['apellido']?></option>;
                               <?php  } ?>
                          </select>
                        
                      </div>
                      <div class="col-lg-2 col-sm-6 col-sm-2">
                        <label for="">Guia Remision</label>
                        <input type="text" class="form-control"  name="nguiar" id="nguiar" value="">
                      </div>
                    </div>

<!--div class="row justify-content-left">
<div class="col-sm-5">                            
<fieldset class="row border border-primary rounded p-3" >
<legend class="w-auto">Datos de </legend>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
  <label class="form-check-label" for="inlineRadio1">1</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
  <label class="form-check-label" for="inlineRadio2">2</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3" disabled>
  <label class="form-check-label" for="inlineRadio3">3 (disabled)</label>
</div>
</fieldset>
</div>
</div-->


<div class="clearfix">
<div class="row mt-3">
<?php if($_SESSION["venta_por_mayor"] == 'SI'){ ?>
<div class="col-lg-2 col-sm-6 col-sm-4">
<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addProdcuto"><i class="fa fa-plus-circle"></i> P. Minorista</button>
</div>
<div class="col-lg-2 col-sm-6 col-sm-4">
<button class="btn btn-secondary" type="button" data-toggle="modal" data-target="#addProdcuto1"><i class="fa fa-plus-circle"></i> P. Mayorista</button>
</div>
<?php } 
else {?>
<div class="col-lg-2 col-sm-6 col-sm-4">
<button class="btn btn-info" type="button" data-toggle="modal" data-target="#addProdcuto2"><i class="fa fa-plus-circle"></i> Productos</button>
</div>
<?php }?>
<div class="col-lg-2 col-sm-6 col-sm-4">
<button class="btn btn-success" type="button" id="btnGuardarft"><i class="fa fa-save"></i> Guardar</button>
</div>
<div class="col-lg-2 col-sm-6 col-sm-4">
<button id="btnPagar" class="btn btn-warning" type="button" data-toggle="modal" data-target="#addPago"><i class="fa fa-usd"></i> Pagos</button>
<button id="btnCuota" class="btn btn-warning" type="button" data-toggle="modal" data-target="#addCuota"><i class="fa fa-usd"></i> Cuotas</button>
</div>
<div class="col-lg-2 col-sm-6 col-sm-4">
<button class="btn btn-warning" type="button" onClick="openModalanticipos();" ><i class="fa fa-usd"></i> Anticipos</button>
</div>
<div class="col-lg-2 col-sm-6 col-sm-4">                                                
<a href="<?=base_url()?>/ventas" class="btn btn-danger" type="button"><i class="fa fa-close"></i> Cancelar</a>
</div>

</div>
                    </div>
                    <hr>
                <?php if($_SESSION["usabarras"] == 'SI'){ ?>
                    <div class="row">
                      <div class="col-sm-4">
                        <label for="">Codigo de Barras</label>
                        <input type="text" class="form-control" name="cbarras" id="cbarras" >
                        <!--onkeypress="return teclas(event);"-->
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
<th colspan="3"></th>
<th>Op. Gravadas</th>
<td><input type="text" class="form-control text-right" name="op_g" id="op_g" value="0.00" readonly></td>
<th>Anticipo</th>
<td><input type="text" class="form-control text-right" name="anticipo_pago" id="anticipo_pago" value="0.00" readonly></td>
</tr>

<tr>
<th colspan="3"></th>
<th>Op. Exonerada</th>
<td><input type="text" class="form-control text-right" name="op_e" id="op_e" value="0.00" readonly></td>
<th>Saldo.A</th>
<td><input type="text" class="form-control text-right" name="anticipo_saldo" id="anticipo_saldo" value="0.00" readonly></td>
</tr>

<tr>
<th colspan="3"></th>
<th>Op. Inafecta</th>
<td><input type="text" class="form-control text-right" name="op_i" id="op_i" value="0.00" readonly></td>
<th>Total.C</th>
<td><input type="text" class="form-control text-right" name="anticipo_total" id="anticipo_total" value="0.00" readonly></td>
</tr>

<tr>
<th colspan="3"></th>
<th>I.G.V.</th>
<td><input type="text" class="form-control text-right" name="igv" id="igv" value="0.00" readonly></td>
<th></th>
<td></td>
</tr>

<tr>
<th colspan="3"></th>
<th>Total</th>
<td><input type="text" class="form-control text-right" name="total" id="total" value="0.00" readonly></td>
<th></th>
<td></td>
</tr>

</tfoot>


</table>


                   </div>
                  </div>



                                  
              
              
             
            </div> <!-- /.col -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
        <!--modal forma de pago-->
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
<!--fin modal forma de pago-->

<!--modal cuotas-->
<div class="modal fade" id="addCuota" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="exampleModalLabel">Registrar Cuotas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">      
                    
<div id="cargador">
<div>
<div class="row">
<div class="col-sm-12">
   <div class="my-class-form-control-group">
      <input type="numeric" class="form-control text-right mr-2" name="cuotas" id="cuotas" placeholder="Cuotas" value="0" /><input type="text" class="form-control text-center text-bold mr-2" name="importe_pago_cuota" id="importe_pago_cuota" readonly style="color: red; background-color: #96EC94; font-weight: bold; font-size: 20px;" /><button class="btn btn-dark" type="button" onclick="agregar_campo();"><i class="fas fa-plus"></i></button>
    </div>
</div>

</div>
</div>
</div>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-save"></i> Guardar</button>
       
      </div>
    </div>
  </div>
</div>
<!--fin modal uotas-->
        </form>
      </main> <!-- main -->
    </div> <!-- .wrapper -->

    <!-- /fin modal pagos -->
          <?php  include 'views/modules/modals/persona.php' ?> 
          <?php include 'views/modules/modals/buscar_contribuyente.php' ?> 
          <?php include 'views/modules/modals/articulo_venta.php' ?> 
          <?php include 'views/modules/modals/venta-pedidos.php' ?>
          <?php include 'views/template/pie.php' ?>



<script src="<?=media()?>/js/funciones_ventas.js?v=17"></script>
   

      <script src="<?=media()?>/js/sunat_reniec.js"></script>
      <script>
            /*letras o cuotas*/
            var contador=0;
            var detalle=0;
            function agregar_campo() 
            { 
               cont++;
	           detalles++;
              $("#cargador").append("<div class='row mt-1'><div class='col-sm-12'><div class='my-class-form-control-group'><input type='date' class='form-control mr-2' name='datepago[]' /><input type='text' class='form-control text-right' value='0.00' name='montocuota[]'><button class='btn btn-danger' type='button' onclick='eliminar_campo(this);''><i class='fas fa-minus'></i></button></div></div></div>"); 
              
              	$('#valor_unitario').val(cuotas.value);
            } 
                
            function eliminar_campo(campos) 
            { 
              $(campos).parent().remove();
              detalle=detalle-1;
            }
            

listarcliente(); 
   
</script>
  </body>
</html>