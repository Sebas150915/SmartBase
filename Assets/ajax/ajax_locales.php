<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();



//####################################CREAR CLIENTE####################################////

if($_POST['action'] == 'addLocal')
{
	$query=$connect->prepare("INSERT INTO tbl_alq_local(idempresa,nombre,area,ubi,imp_alqui,estado) VALUES (?,?,?,?,?,?);");
	$resultado=$query->execute([$_POST['empresa'],$_POST['local'],$_POST['arealocal'],$_POST['ubilocal'],$_POST['importe'],'1']);


	if($resultado)
	{
		$msg='Local agregado con exito';
	}
	else
	{
		$msg='error al agregar local';
	}
	echo $msg;
	exit;
}

//####################################EDITAR CLIENTE####################################////
if($_POST['action'] == 'ediLocal')
{
	$query=$connect->prepare("UPDATE tbl_alq_local SET nombre=?,area=?,ubi=?,imp_alqui=? WHERE id = ?");
	$resultado = $query->execute([$_POST['update_nombre'],$_POST['update_area'],$_POST['update_ubi'],$_POST['update_importe'],$_POST['update_id']]);

	if($resultado)
	{
		$msg='Actualizado con exito';
	}
	else
	{
		$msg = 'error al actualizar';
	}

	echo $msg;
	exit;


}

//####################################ELIMINAR CATEGORIA####################################////
if($_POST['action'] == 'delLocal')
{
    //var_dump($_POST);
	$query=$connect->prepare("UPDATE tbl_alq_local SET estado=? WHERE id = ?");
	$resultado = $query->execute([$_POST['estado'],$_POST['delete_id']]);

	if($resultado)
	{
		$msg='Procesado con exito';
	}
	else
	{
		$msg = 'error';
	}

	echo $msg;
	exit;


}