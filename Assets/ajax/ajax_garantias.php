<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();



//####################################CREAR GARANTIA####################################////

if($_POST['action'] == 'addGarantia')
{
    
    $moneda=$_POST['moneda'];
	$query=$connect->prepare("INSERT INTO tbl_alq_garantias(meses_garantia,importe_soles,importe_dolares,fecha_garantia,estado,moneda,tc,idcliente) VALUES (?,?,?,?,?,?,?,?);");
	$resultado=$query->execute([$_POST['meses'],$_POST['importe_soles'],$_POST['importe_dolares'],$_POST['fecha_garantia'],'1',$_POST['moneda'],$_POST['tipcambio'],$_POST['cliente']]);


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

//####################################EDITAR####################################////
if($_POST['action'] == 'editGarantia')
{

	$query=$connect->prepare("UPDATE tbl_alq_garantias SET meses_garantia=?,importe_soles=?,importe_dolares=?, fecha_garantia=? WHERE id_garantia = ?");
	$resultado = $query->execute([$_POST['update_meses'],$_POST['update_soles'],$_POST['update_dolares'],$_POST['update_fecha'],$_POST['update_id']]);

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
if($_POST['action'] == 'delGarantia')
{

	$query=$connect->prepare("UPDATE tbl_alq_garantias SET estado=? WHERE id_garantia = ?");
	$resultado = $query->execute(['0',$_POST['delete_id']]);

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