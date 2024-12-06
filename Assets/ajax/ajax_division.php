<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();


//####################################CREAR CLIENTE####################################////

if($_POST['action'] == 'addDivision')
{
	
    $empresa =$_POST['empresa'];

    $query = $connect->prepare("INSERT INTO tbl_division(nombre,empresa,estado) VALUES(?,?,?) ");
    $resultado = $query->execute([$_POST['division'],$empresa,'1']);

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
if($_POST['action'] == 'editDivision')
{

	

	$query=$connect->prepare("UPDATE tbl_division SET nombre=? WHERE id = ?");
	 $resultado = $query->execute([$_POST['update_division'],$_POST['update_id']]);

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