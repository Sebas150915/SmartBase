<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();

////////////llamar datos mediante un json para editar /////////////
/*if($_POST['action']=='buscar_contratos')
{
   // var_dump($_POST);
  
    $data="no hay datos";
    $id_contrato=$_POST['id'];
  $productos = "SELECT * FROM tbl_alq_contratos WHERE id_contrato=$id_contrato";
    $resultado_productos  = $connect->prepare($productos);
   $resultado_productos->execute();
   $row_productos = $resultado_productos->fetch(PDO::FETCH_ASSOC);

   $data = json_encode($row_productos,true);

  echo $data;

   exit();

} */

//####################################CREAR ####################################////

if($_POST['action'] == 'addContrato')
{
    /*$file = $_FILES['contrato'];
        // Definir la ubicación donde se guardará el archivo
        $uploadDir = 'contratos/';
        $uploadFile = $uploadDir . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $uploadFile);*/
    
	$query=$connect->prepare("INSERT INTO tbl_alq_contratos(id_local,id_cliente,fecha_contrato,fecha_inicio_alquiler,fecha_vencimiento,importe_alquiler_soles,importe_alquiler_dolares,moneda_alquiler,tipo_cambio,observaciones) VALUES (?,?,?,?,?,?,?,?,?,?);");
	$resultado=$query->execute([$_POST['local'],$_POST['cliente'],$_POST['fcontrato'],$_POST['finialquiler'],$_POST['fvencimiento'],$_POST['importesoles'],$_POST['importedolar'],$_POST['moneda'],$_POST['tcambio'],$_POST['obs'],]);


	if($resultado)
	{
		$msg=$resultado;
	}
	else
	{
		$msg='error1';
	}
	echo $msg;
	exit;
}

//####################################EDITAR ####################################////
if($_POST['action'] == 'editContrato')
{
    var_dump($_POST);
	$query=$connect->prepare("UPDATE tbl_alq_contratos SET id_cliente=?,fecha_contrato=?,fecha_inicio_alquiler=?,fecha_vencimiento=?,importe_alquiler_soles=?,importe_alquiler_dolares=?,moneda_alquiler=?,tipo_cambio=?,observaciones=? WHERE id_contrato = ?");
	$resultado = $query->execute([$_POST['update_cliente'],$_POST['update_fcontrato'],$_POST['update_finialquiler'],$_POST['update_fvencimiento'],$_POST['update_importesoles'],$_POST['update_importedolar'],$_POST['update_moneda'],$_POST['update_tcambio'],$_POST['update_obs'], $_POST['update_id']]);
	if($resultado)
	{
		$msg='ok';
	}
	else
	{
		$msg = 'error';
	}

	echo $msg;
	exit;


}

//####################################ELIMINAR CATEGORIA####################################////
if($_POST['action'] == 'delCategoria')
{
	
	$id     = $_POST['delete_id'];

	$query=$connect->prepare("UPDATE tbl_categorias SET estado=? WHERE id = ?");
	$resultado = $query->execute(['0',$id]);

	if($resultado)
	{
		$msg='ok';
	}
	else
	{
		$msg = 'error';
	}

	echo $msg;
	exit;


}