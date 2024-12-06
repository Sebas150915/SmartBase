<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();


//####################################CREAR CLIENTE####################################////

if($_POST['action'] == 'addZona')
{
	
    $empresa =$_POST['empresa'];

    $query = $connect->prepare("INSERT INTO tbl_zona(nombre,empresa,estado) VALUES(?,?,?) ");
    $resultado = $query->execute([$_POST['zona'],$empresa,'1']);

    if($resultado)
    {
    	$msg = 'exito';
    }
    else
    {
    	$msg = 'error';
    }

    echo $msg;

    exit();
}

//####################################EDITAR CLIENTE####################################////
if($_POST['action'] == 'editZona')
{

	

	$query=$connect->prepare("UPDATE tbl_zona SET nombre=? WHERE id = ?");
	 $resultado = $query->execute([$_POST['update_zona'],$_POST['update_id']]);

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
if($_POST['action'] == 'delVendedor')
{
   $query=$connect->prepare("UPDATE tbl_vendedor SET estado=? WHERE id = ?");
	$resultado = $query->execute([$_POST['estado'],$_POST['delete_id']]);

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




?>