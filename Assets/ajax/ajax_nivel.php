<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();

//####################################CREAR####################################////

if($_POST['action'] == 'addNivel')
{
	
	$nombre 	  = $_POST['nombre_nivel'];
	$empresa      = $_POST['empresa'];
	
    	$query=$connect->prepare("INSERT INTO tbl_alq_nivel(id_empresa,nombre,estado) VALUES (?,?,?);");
		$resultado=$query->execute([$empresa,$nombre,'1']);


	if($resultado)
	{
		$msg='ok';
	}
	else
	{
		$msg='error1';
	}
	echo $msg;
	exit;
}

//####################################EDITAR ####################################////
if($_POST['action'] == 'editNivel')
{
	$nombre = $_POST['update_nombre'];
	$id     = $_POST['update_id'];

	$query=$connect->prepare("UPDATE tbl_alq_nivel SET nombre=? WHERE id = ?");
	$resultado = $query->execute([$nombre,$id]);

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

//####################################ELIMINAR####################################////
if($_POST['action'] == 'deleteNivel')
{
	
	$id     = $_POST['delete_id'];

	$query=$connect->prepare("UPDATE tbl_alq_nivel SET estado=? WHERE id = ?");
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