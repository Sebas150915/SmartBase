<?php
require_once("signature.php");


class ApiFacturacion
{
	public function EnviarComprobanteElectronicoGRE($emisor, $nombre,$connect,$lastInsertId, $rutacertificado="../../sunat/api/", $ruta_archivo_xml = "../../sunat/", $ruta_archivo_cdr = "../../sunat/")
	{
	          if($emisor['servidor_gre'] == '1')
	          {
	              $id          = 'test-85e5b0ae-255c-4891-a595-0b98c65c9854';
	              $clave       = 'test-Hty/M6QshYvPgItX2P0+Kw==';
	              $usuario_sol = 'MODDATOS';
	              $pass_sol    = 'MODDATOS';
	              $ws          = "https://gre-test.nubefact.com/v1/clientessol/";
	              $ws1         = "https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/";
	              $ws2         = "https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/envios/";
	          }
	          else
	          {
	            $clave        = $emisor['secretgre'];
				$id           = $emisor['idgre'];
				$usuario_sol  = $emisor['usuariosol'];
				$pass_sol     = $emisor['clavesol']; 
				$ws           = "https://api-seguridad.sunat.gob.pe/v1/clientessol/";
				$ws1          = "https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/";
				$ws2          = "https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/envios/";
	          }
				$ruc              = $emisor['ruc'];
				$ruta_archivo_xml = $ruta_archivo_xml.$emisor['ruc'].'/xml/';
				$ruta_archivo_cdr = $ruta_archivo_cdr.$emisor['ruc'].'/cdr/';
				$ruta_archivo     = $ruta_archivo_xml.$nombre.'.XML';
				$ruta             = $ruta_archivo_xml.$nombre.'.XML';

				//firma del documento
				$objSignature = new Signature();

				$flg_firma = "0";
				$ruta = $ruta_archivo_xml.$nombre.'.XML';

				$ruta_firma = $rutacertificado.$emisor['certificado'];
				$pass_firma = $emisor['clave_certificado'];

				$resp = $objSignature->signature_xml($flg_firma, $ruta, $ruta_firma, $pass_firma);

				//print_r($resp);

				$hash = $resp['hash_cpe'];
                $mensaje['hash_cpe']  = $hash;
				$archivo = $nombre.".ZIP";

				$clave = str_replace("+", "%2B", $clave);
				$clave = str_replace("==", "%3D%3D", $clave);	
				//$clave=urldecode($clave);
				$mensaje['ruc'] = $ruc;
				$username=$ruc.$usuario_sol;
				$password=$pass_sol;	

				$mensaje['clave']=$clave;
				$mensaje['id']=$id;
				$rx = 'grant_type=password&scope=https%3A%2F%2Fapi-cpe.sunat.gob.pe&client_id='.$id.'&client_secret='.$clave.'&username='.$username.'&password='.$pass_sol;
				//print($rx);

				$curl = curl_init();

				curl_setopt_array($curl, array(
				CURLOPT_URL => $ws.$id.'/oauth2/token/',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $rx,

				CURLOPT_HTTPHEADER => array(
				'Content-Type: application/x-www-form-urlencoded',
				'Cookie: BIGipServerpool-e-plataformaunica-https=!AD/6CuMXesPusLZoLUSwSxm25CXT9pqEiS/gStZtI51eyIrzkqt6fc23l3KONnyH5aAfxNr4G1s27w==; TS019e7fc2=019edc9eb812f481dae473f7280de0831b7083ec8c88495c4ca5d90749d56b12585077c38867c3ff18d56ab2173ad574eac3766d11'
				),
				));

				$response = curl_exec($curl);
				//var_dump($response);
				curl_close($curl);
				$response=json_decode($response);
				// $rx = 'grant_type=password&scope=https%3A%2F%2Fapi-cpe.sunat.gob.pe&client_id='.$id.'&client_secret='.$clave.'&username='.$username.'&password='.$password;
				// echo $rx;
				if(isset($response->cod))
				{
    				$mensaje['numerror1']=$response->cod;
    				$mensaje['msj_sunat1']=$response->msg;
    				$mensaje['hash_cdr1'] ='';
    				$mensaje['token1']    = '';
				}
				else{
                    $mensaje['numerror1']='';
                    $mensaje['msj_sunat1']='';
                    $mensaje['hash_cdr1'] ='';
                    $token_access=$response->access_token;
                    $mensaje['token1'] =$token_access;
                    $zip = new ZipArchive();
                    $filenameXMLCPE = $nombre . '.zip';
                    $rutazip = $ruta_archivo_xml.$nombre.".ZIP";
				/*******************/
				//Generar el .zip
				//$nombrezip = $nombre.".ZIP";
				/*	$rutazip = $ruta_archivo_xml.$nombre.".ZIP";
				if($zip->open($rutazip,ZIPARCHIVE::CREATE)===true){
				$zip->addFile($ruta, $nombre.'.XML');
				$zip->close();
				}
 
				***************/
				//sleep(4);
				if ($zip->open($rutazip, ZIPARCHIVE::CREATE) === true)
				{
        			$zip->addFile($ruta, $nombre.'.XML'); //ORIGEN, DESTINO
        			$zip->close();
				}
				$ruta_ws=$ws1.$nombre;	
				$passcodeen =hash_file('sha256', $rutazip);
				$mensaje['hash_cpe']=$passcodeen;
				/************************envio de zip*****************************/
				$curl = curl_init();
				//https://api-cpe.sunat.gob.pe/.../XXXXXXX-09-TJ01-00000030)
				curl_setopt_array($curl, array(
				CURLOPT_URL => $ruta_ws,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS =>'{
				"archivo" : {
				"nomArchivo" : "'.$nombre.'.zip",
				"arcGreZip" : "'. base64_encode(file_get_contents($rutazip)) . '",
				"hashZip" : "'.$passcodeen.'"
				}
				}',
				CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer '. $token_access,
				'Content-Type: application/json'
				),
				));
				//echo $passcodeen;
				$response2 = curl_exec($curl);
				curl_close($curl);
                
				//var_dump($response2);
				//$mensaje['response2'] = $response2;
				/*
				echo $response;
				echo $token_access;
				*/
				//echo  'response2:'.$response2;

				$response2=json_decode($response2);
				$ticket=$response2->numTicket;
				$fecRecepcion=$response2->fecRecepcion;
				$mensaje['ticket2'] =$ticket;
				$mensaje['fecRecepcion2'] =$fecRecepcion;

				//var_dump($response2->message);
				//echo $ticket;

				if(isset($response2->status)=='401'){

				$mensaje['cod_sunat2']=$response2->status;
				$mensaje['numerror2']='88';
				$mensaje['msj_sunat2']=$response2->message;
				$mensaje['msj_link']='';
				$mensaje['hash_cdr2'] ='';

				}else if(isset($response2->error)){

				$mensaje['cod_sunat2']=$response2->numerror;
				$mensaje['numerror2']='88';
				$mensaje['msj_sunat2']=$response2->error_description;
				$mensaje['msj_link']='';
				$mensaje['hash_cdr2'] ='';

				}else{

				$curl = curl_init();
				/************************consulta ticket*****************************/
				curl_setopt_array($curl, array(
				CURLOPT_URL => $ws2.$ticket,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_HTTPHEADER => array(
				'numRucEnvia: '.$ruc,
				'numTicket: '.$ticket,
				'Authorization: Bearer '. $token_access,
				),
				));

				$response3 = curl_exec($curl);
				//var_dump($response3);
				$response3=json_decode($response3);
				$codRespuesta=$response3->codRespuesta;

				curl_close($curl);

				$mensaje['cod_sunat'] =$codRespuesta;

				if($codRespuesta=='99'){

				$error=$response3->error;
				$mensaje['numerror']=$error->numError;
				$mensaje['msj_sunat']=$error->desError;	
				$mensaje['msj_link']='';
				$mensaje['hash_cdr'] ='';
				$mensaje['arcCdr']   = '';

				}else if($codRespuesta=='98'){

				$mensaje['numerror']='98';
				$mensaje['msj_sunat']='Envío en proceso';
				$mensaje['msj_link']='';
				$mensaje['hash_cdr'] ='';
				$mensaje['arcCdr']   = '';

				}else if($codRespuesta=='0'){



				$mensaje['arcCdr']=$response3->arcCdr;
				$mensaje['indCdrGenerado']=$response3->indCdrGenerado;
				$mensaje['numerror']      = '';
				 if($emisor['servidor_gre'] =='1')
                 {
                     $nombre = explode('-',$nombre);
                     $nr     = $nombre[0];
                     $td     = $nombre[1];
                     $sd     = $nombre[2];
                     $nd     = $nombre[3];
                     $nd     = ltrim($nd, "0");
                     $nd     = intval($nd);
                     $nombre = $nr.'-'.$td.'-'.$sd.'-'.$nd;
                     
                 }
                 $mensaje['ndoc'] = $nombre;
				


				file_put_contents($ruta_archivo_cdr . 'R-' . $nombre . '.ZIP', base64_decode($response3->arcCdr));
				
                
				//extraemos archivo zip a xml
				$zip = new ZipArchive;
				if ($zip->open($ruta_archivo_cdr . 'R-' . $nombre . '.ZIP') === TRUE) {
				$zip->extractTo($ruta_archivo_cdr);
				$zip->close();
				}
				unlink($ruta_archivo_cdr . 'R-' . $nombre . '.ZIP');

				//=============hash CDR=================
				$doc_cdr = new DOMDocument();
				if (file_exists(dirname(__FILE__) . '/' . $ruta_archivo_cdr . 'R-' . $nombre . '.XML')) {
				$doc_cdr->load(dirname(__FILE__) . '/' . $ruta_archivo_cdr . 'R-' . $nombre . '.XML');
				}else{
				$doc_cdr->load(dirname(__FILE__) . '/' . $ruta_archivo_cdr . 'R-' . $nombre . '.xml');           
				}

				//$mensaje['cod_sunat'] = $doc_cdr->getElementsByTagName('ResponseCode')->item(0)->nodeValue;
				$mensaje['msj_sunat'] = $doc_cdr->getElementsByTagName('Description')->item(0)->nodeValue;
				$mensaje['hash_cdr'] = $doc_cdr->getElementsByTagName('DigestValue')->item(0)->nodeValue;
				$mensaje['msj_link'] = $doc_cdr->getElementsByTagName('DocumentDescription')->item(0)->nodeValue;


				}else{

				$mensaje['numerror']='88';
				$mensaje['msj_sunat']='SUNAT FUERA DE SERVICIO';
				$mensaje['hash_cdr'] ='';
				$mensaje['msj_link'] ='';

				}

				}

				}

			/*	$query=$connect->prepare("UPDATE tbl_gre_cab SET hash=?,ticket=? ,mensaje=?,numerror=? WHERE id=?;");
				$resultado=$query->execute([$resp['hash_cpe'],$ticket,$mensaje['msj_sunat'],$mensaje['numerror'],$lastInsertId]);*/
				return $mensaje;

    }

	public function EnviarComprobanteElectronico($emisor, $nombre,$connect,$lastInsertId, $rutacertificado="../../sunat/api/", $ruta_archivo_xml = "../../sunat/", $ruta_archivo_cdr = "../../sunat/")
	{
		$ruta_archivo_xml = $ruta_archivo_xml.$emisor['ruc'].'/xml/';
	    $ruta_archivo_cdr = $ruta_archivo_cdr.$emisor['ruc'].'/cdr/';

		//firma del documento
		$objSignature = new Signature();

		$flg_firma = "0";
		$ruta = $ruta_archivo_xml.$nombre.'.XML';

		$ruta_firma = $rutacertificado.$emisor['certificado'];
		$pass_firma = $emisor['clave_certificado'];
		
        $mensaje['rutacert'] = $lastInsertId;
        
		$resp = $objSignature->signature_xml($flg_firma, $ruta, $ruta_firma, $pass_firma);

		//var_dump($resp);
	   $hash = $resp['hash_cpe'];
       $mensaje['respuestahash'] = $hash;
		//Generar el .zip

		$zip = new ZipArchive();

		$nombrezip = $nombre.".ZIP";
		$rutazip = $ruta_archivo_xml.$nombre.".ZIP";

		if($zip->open($rutazip,ZIPARCHIVE::CREATE)===true){
			$zip->addFile($ruta, $nombre.'.XML');
			$zip->close();
		}

		//Enviamos el archivo a sunat
        $link = $emisor['servidor_link'];
        $mensaje['link'] = $link;
		$ws   = $link;
		//echo $ws;
        //$ws = "https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl";
        //$ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
		$ruta_archivo = $rutazip;
		$nombre_archivo = $nombrezip;

		//echo $ruta_archivo.','.$nombre_archivo;

		$contenido_del_zip = base64_encode(file_get_contents($ruta_archivo));

         //echo $contenido_del_zip;
		$xml_envio ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
				 <soapenv:Header>
				 	<wsse:Security>
				 		<wsse:UsernameToken>
				 			<wsse:Username>'.$emisor['ruc'].$emisor['usuario_sol'].'</wsse:Username>
				 			<wsse:Password>'.$emisor['clave_sol'].'</wsse:Password>
				 		</wsse:UsernameToken>
				 	</wsse:Security>
				 </soapenv:Header>
				 <soapenv:Body>
				 	<ser:sendBill>
				 		<fileName>'.$nombre_archivo.'</fileName>
				 		<contentFile>'.$contenido_del_zip.'</contentFile>
				 	</ser:sendBill>
				 </soapenv:Body>
				</soapenv:Envelope>';

             //echo $xml_envio;
			$header = array(
						 "Content-type: text/xml;charset=\"utf-8\"",
						"Accept: text/xml",
						 "Accept-Header: application/xml+",
						"Cache-Control: no-cache",
						"Pragma: no-cache",
						"SOAPAction: urn:sendBill",
						"Content-lenght: ".strlen($xml_envio)
					);


			$ch = curl_init();
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($ch,CURLOPT_URL,$ws);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
			curl_setopt($ch,CURLOPT_TIMEOUT,33000);
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$xml_envio);
			curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			//para ejecutar los procesos de forma local en windows
			//enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
		//	curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
			//curl_setopt($ch, CURLOPT_CAINFO, base_url()."/sunat/api/cacert.pem");

            //echo  dirname(__FILE__)."/cacert.pem";
			$response = curl_exec($ch);
           //print_r($response);
           $mensaje['respcurl'] = $response;
			$httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
			curl_close($ch);
			//echo $httpcode;
			$mensaje['httpcode'] = $httpcode;
			$mensaje['estado'] = "0";
			if($httpcode == 200 || $httpcode == 500)
			{ //200->La comunicación fue satisfactoria
				$doc = new DOMDocument();
				$doc->loadXML($response);
                
				        //$codigo = '.';
						//$mensaje = ' ha sido Aceptada por SUNAT';	
                      //===================VERIFICAMOS SI HA ENVIADO CORRECTAMENTE EL COMPROBANTE=====================//
					if(isset($doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue))
					{
						$cdr = $doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue;
						 file_put_contents($ruta_archivo_cdr . 'R-' . $nombre . '.ZIP', base64_decode($cdr));
						$cdr = base64_decode($cdr);
                       //var_dump($cdr);
						//extraemos archivo zip a xml
						$zip = new ZipArchive;
						//echo 'ruta '.$ruta_archivo_cdr."R-".$nombrezip;
						if($zip->open($ruta_archivo_cdr."R-".$nombrezip)===true)
						{
						    if($link =='https://ose.nubefact.com/ol-ti-itcpe/billService?wsdl')
						    {
						        $zip->extractTo($ruta_archivo_cdr,'R-'.$nombre.'.xml');
                                $zip->close();
                                /*cambiamos el nombre del archivo*/
                                
                                rename($ruta_archivo_cdr.'/R-'.$nombre.'.xml',$ruta_archivo_cdr.'/R-'.$nombre.'.XML');
						     
						    }
						    else
						    {
                                $zip->extractTo($ruta_archivo_cdr,'R-'.$nombre.'.XML');
                                $zip->close();
						    }
						
							
						}
						
						
						//eliminamos los archivos Zipeados
                       //unlink($ruta_archivo_xml.$nombre . '.ZIP');
                       //unlink($ruta_archivo_cdr . 'R-' . $nombre . '.ZIP');
						$doc_cdr = new DOMDocument();
						

						if (file_exists($ruta_archivo_cdr.'R-'.$nombre.'.XML')) 
						{
						$doc_cdr->load($ruta_archivo_cdr.'R-'.$nombre.'.XML');
						}
						else 
						{
						   
						$doc_cdr->load($ruta_archivo_cdr.'R-'.$nombre.'.XML');
						
						}

						$mensaje['estado']    = "";
						$mensaje['cod_sunat'] = $doc_cdr->getElementsByTagName('ResponseCode')->item(0)->nodeValue;
						if(isset($doc->getElementsByTagName("faultstring")->item(0)->nodeValue)){
							$mensaje['cod_sunat1'] = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
						}else{
							$mensaje['cod_sunat1'] ='';
						}
						
                        $mensaje['msj_sunat'] = $doc_cdr->getElementsByTagName('Description')->item(0)->nodeValue;
                        $mensaje['hash_cdr'] = $doc_cdr->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                        
						
					}
					else{		
						/*$estadofe = "2";
						$codigo  = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
						$mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
						
						//echo "error ".$codigo.": ".$mensaje; */
						$mensaje['estado']    = "";
						$mensaje['cod_sunat'] = $doc->getElementsByTagName('faultcode')->item(0)->nodeValue;
						$mensaje['cod_sunat1'] = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;

if(isset($doc->getElementsByTagName('message')->item(0)->nodeValue)){
                        $mensaje['msj_sunat'] = $doc->getElementsByTagName('message')->item(0)->nodeValue;
					}else{
						$mensaje['msj_sunat'] =$doc->getElementsByTagName('faultstring')->item(0)->nodeValue;
					}

                        //$mensaje['cod_sunat'] = $doc->getElementsByTagName('statusCode')->item(0)->nodeValue;
                        //$mensaje['msj_sunat'] = "EL COMPROBANTE ESTA SIENDO PROCESADO";
                        $mensaje['hash_cdr'] = "";
					}		

			}
			else{ 
			    
			       
					$mensaje['estado']    = '0';
					$mensaje['cod_sunat'] = '019999999';
					$mensaje['cod_sunat1'] = "";
					$mensaje['msj_sunat'] = 'SUNAT NO RESPONDE, VUELVA A INTENTAR EN UNOS MINUTOS.';
					$mensaje['hash_cdr']  ='';
					

			}/*
			//echo $codigo;
			if($estadofe<>'1')
			{
			$cod = explode('.',$codigo);
			$codigo = $cod[1];
		    }

		$query=$connect->prepare("UPDATE tbl_venta_cab SET hash=?,feestado=? ,fecodigoerror=?,femensajesunat=? WHERE id=?;");
		$resultado=$query->execute([$resp['hash_cpe'],$estadofe,$codigo,$mensaje,$lastInsertId]);*/

		return $mensaje;
	}
	
	

	public function EnviarResumenComprobantes($emisor,$nombre, $connect,$serier,$numeror, $rutacertificado="../../sunat/api/", $ruta_archivo_xml = "../../sunat/",$ruta_archivo_cdr = "../../sunat/")
	{

				$ruta_archivo_xml = $ruta_archivo_xml.$emisor['ruc'].'/xml/';
		        $ruta_archivo_cdr = $ruta_archivo_cdr.$emisor['ruc'].'/cdr/';

				//firma del documento
				$objSignature = new Signature();

				$flg_firma = "0";
				//$ruta_archivo_xml = "xml/";
				$ruta = $ruta_archivo_xml.$nombre.'.XML';

				$ruta_firma = $rutacertificado.$emisor['certificado'];
				$pass_firma = $emisor['clave_certificado'];

				$resp = $objSignature->signature_xml($flg_firma, $ruta, $ruta_firma, $pass_firma);

				//print_r($resp);
		        $hash = $resp['hash_cpe'];


				//Generar el .zip

				$zip = new ZipArchive();

				$nombrezip = $nombre.".ZIP";
				$rutazip = $ruta_archivo_xml.$nombre.".ZIP";
                
				if($zip->open($rutazip,ZIPARCHIVE::CREATE)===true){
					$zip->addFile($ruta, $nombre.'.XML');
					$zip->close();
				}


				//Enviamos el archivo a sunat
		        $link = $emisor['servidor_link'];
		        //echo 'link de resumen: '.$link;
				$ws   = $link;
		        //$ws = "https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl";
		        //$ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";

				$ruta_archivo = $ruta_archivo_xml.$nombrezip;

				$nombre_archivo = $nombrezip;
				

				$contenido_del_zip = base64_encode(file_get_contents($ruta_archivo));
				
				$xml_envio ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
						 <soapenv:Header>
						 	<wsse:Security>
						 		<wsse:UsernameToken>
						 			<wsse:Username>'.$emisor['ruc'].$emisor['usuario_sol'].'</wsse:Username>
						 			<wsse:Password>'.$emisor['clave_sol'].'</wsse:Password>
						 		</wsse:UsernameToken>
						 	</wsse:Security>
						 </soapenv:Header>
						 <soapenv:Body>
						 	<ser:sendSummary>
						 		<fileName>'.$nombre_archivo.'</fileName>
						 		<contentFile>'.$contenido_del_zip.'</contentFile>
						 	</ser:sendSummary>
						 </soapenv:Body>
						</soapenv:Envelope>';


					$header = array(
								"Content-type: text/xml; charset=\"utf-8\"",
								"Accept: text/xml",
								"Cache-Control: no-cache",
								"Pragma: no-cache",
								"SOAPAction: ",
								"Content-lenght: ".strlen($xml_envio)
							);


					$ch = curl_init();
					curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
					curl_setopt($ch,CURLOPT_URL,$ws);
					curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
					curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
					curl_setopt($ch,CURLOPT_TIMEOUT,30);
					curl_setopt($ch,CURLOPT_POST,true);
					curl_setopt($ch,CURLOPT_POSTFIELDS,$xml_envio);
					curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
					//para ejecutar los procesos de forma local en windows
					//enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
					//curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");

					$response = curl_exec($ch);
					
					
                    //echo 'aqui llegue';
					$httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
					//echo $httpcode.'----'.$response;
					$estadofe = "0";

					$ticket = "0";
					if($httpcode == 200)
					{
						$doc = new DOMDocument();
						$doc->loadXML($response);

						if (isset($doc->getElementsByTagName('ticket')->item(0)->nodeValue)) 
						{
			                $ticket = $doc->getElementsByTagName('ticket')->item(0)->nodeValue;
							//echo "TODO OK : ".$ticket;
							$estadofe ="7";
							$codigo='.';
							$mensaje = 'Resumen Enviado';
						}
						else
						{		

							$codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
							$mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
					    	echo "error :".$codigo.": ".$mensaje; 
							$ticket='';
							$estadofe="2";
						}

					}
					else{
						echo curl_error($ch);
						echo "Problema de conexión";
						$codigo = '.';
						$mensaje = '';	
						$ticket = '';
						$estadofe="3";
					}

					$cod = explode('.',$codigo);
					$codigo = $cod[1];

					$query=$connect->prepare("UPDATE tbl_venta_cab SET hash=?,feestado=? ,fecodigoerror=?,femensajesunat=?,ticket=? WHERE serie_resumen=? and numero_resumen=?");
					$resultado=$query->execute([$resp['hash_cpe'],$estadofe,$codigo,$mensaje,$ticket,$serier,$numeror]);

					curl_close($ch);
					return $ticket;
	}
		public function EnviarResumenBajas($emisor,$nombre, $connect,$serier,$numeror, $rutacertificado="../../sunat/api/", $ruta_archivo_xml = "../../sunat/",$ruta_archivo_cdr = "../../sunat/")
	{

				$ruta_archivo_xml = $ruta_archivo_xml.$emisor['ruc'].'/xml/';
		        $ruta_archivo_cdr = $ruta_archivo_cdr.$emisor['ruc'].'/cdr/';

				//firma del documento
				$objSignature = new Signature();

				$flg_firma = "0";
				//$ruta_archivo_xml = "xml/";
				$ruta = $ruta_archivo_xml.$nombre.'.XML';

				$ruta_firma = $rutacertificado.$emisor['certificado'];
				$pass_firma = $emisor['clave_certificado'];

				$resp = $objSignature->signature_xml($flg_firma, $ruta, $ruta_firma, $pass_firma);

				//print_r($resp);
		        $hash = $resp['hash_cpe'];


				//Generar el .zip

				$zip = new ZipArchive();

				$nombrezip = $nombre.".ZIP";
				$rutazip = $ruta_archivo_xml.$nombre.".ZIP";
                
				if($zip->open($rutazip,ZIPARCHIVE::CREATE)===true){
					$zip->addFile($ruta, $nombre.'.XML');
					$zip->close();
				}


				//Enviamos el archivo a sunat
		        $link = $emisor['servidor_link'];
		        //echo 'link de resumen: '.$link;
				$ws   = $link;
		        //$ws = "https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl";
		        //$ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";

				$ruta_archivo = $ruta_archivo_xml.$nombrezip;

				$nombre_archivo = $nombrezip;
				

				$contenido_del_zip = base64_encode(file_get_contents($ruta_archivo));
				
				$xml_envio ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
						 <soapenv:Header>
						 	<wsse:Security>
						 		<wsse:UsernameToken>
						 			<wsse:Username>'.$emisor['ruc'].$emisor['usuario_sol'].'</wsse:Username>
						 			<wsse:Password>'.$emisor['clave_sol'].'</wsse:Password>
						 		</wsse:UsernameToken>
						 	</wsse:Security>
						 </soapenv:Header>
						 <soapenv:Body>
						 	<ser:sendSummary>
						 		<fileName>'.$nombre_archivo.'</fileName>
						 		<contentFile>'.$contenido_del_zip.'</contentFile>
						 	</ser:sendSummary>
						 </soapenv:Body>
						</soapenv:Envelope>';


					$header = array(
								"Content-type: text/xml; charset=\"utf-8\"",
								"Accept: text/xml",
								"Cache-Control: no-cache",
								"Pragma: no-cache",
								"SOAPAction: ",
								"Content-lenght: ".strlen($xml_envio)
							);


					$ch = curl_init();
					curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
					curl_setopt($ch,CURLOPT_URL,$ws);
					curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
					curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
					curl_setopt($ch,CURLOPT_TIMEOUT,30);
					curl_setopt($ch,CURLOPT_POST,true);
					curl_setopt($ch,CURLOPT_POSTFIELDS,$xml_envio);
					curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
					//para ejecutar los procesos de forma local en windows
					//enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
					//curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");

					$response = curl_exec($ch);
					
					//$mensaje['respuestacurl'] = $response;
					
                   // echo $response;
					$httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
					//echo $httpcode.'----'.$response;
					
					$estadofe = "0";

					$ticket = "0";
					if($httpcode == 200)
					{
						$doc = new DOMDocument();
						$doc->loadXML($response);

						if (isset($doc->getElementsByTagName('ticket')->item(0)->nodeValue)) 
						{
			                $ticket = $doc->getElementsByTagName('ticket')->item(0)->nodeValue;
							//echo "TODO OK : ".$ticket;
							$estadofe ="4";
							$codigo='.';
							$mensaje = 'Resumen Enviado';
						}
						else
						{		

							$codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
							$mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
					    	echo "error :".$codigo.": ".$mensaje; 
							$ticket='';
							$estadofe="2";
						}

					}
					else{
						echo curl_error($ch);
						echo "Problema de conexión";
						$codigo = '.';
						$mensaje = '';	
						$ticket = '';
						$estadofe="3";
					}

					$cod = explode('.',$codigo);
					$codigo = $cod[1];

					$query=$connect->prepare("UPDATE tbl_venta_cab SET hash=?,feestado=? ,fecodigoerror=?,femensajesunat=?,ticket=? WHERE serie_resumen=? and numero_resumen=?");
					$resultado=$query->execute([$resp['hash_cpe'],$estadofe,$codigo,$mensaje,$ticket,$serier,$numeror]);

					curl_close($ch);
					return $ticket;
	}

	public function EnviarBajaCpe($emisor,$nombre, $connect,$serier,$numeror, $rutacertificado="../../sunat/api/", $ruta_archivo_xml = "../../sunat/",$ruta_archivo_cdr = "../../sunat/")
	{

				$ruta_archivo_xml = $ruta_archivo_xml.$emisor['ruc'].'/xml/';
		        $ruta_archivo_cdr = $ruta_archivo_cdr.$emisor['ruc'].'/cdr/';

				//firma del documento
				$objSignature = new Signature();

				$flg_firma = "0";
				//$ruta_archivo_xml = "xml/";
				$ruta = $ruta_archivo_xml.$nombre.'.XML';

				$ruta_firma = $rutacertificado.$emisor['certificado'];
				$pass_firma = $emisor['clave_certificado'];

				$resp = $objSignature->signature_xml($flg_firma, $ruta, $ruta_firma, $pass_firma);

				//print_r($resp);
		        $hash = $resp['hash_cpe'];


				//Generar el .zip

				$zip = new ZipArchive();

				$nombrezip = $nombre.".ZIP";
				$rutazip = $ruta_archivo_xml.$nombre.".ZIP";

				if($zip->open($rutazip,ZIPARCHIVE::CREATE)===true){
					$zip->addFile($ruta, $nombre.'.XML');
					$zip->close();
				}


				//Enviamos el archivo a sunat
		        $link = $emisor['servidor_link'];
		        //echo 'link de resumen: '.$link;
				$ws   = $link;
		        //$ws = "https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl";
		        //$ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";

				$ruta_archivo = $ruta_archivo_xml.$nombrezip;

				$nombre_archivo = $nombrezip;
				

				$contenido_del_zip = base64_encode(file_get_contents($ruta_archivo));
				
				$xml_envio ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
						 <soapenv:Header>
						 	<wsse:Security>
						 		<wsse:UsernameToken>
						 			<wsse:Username>'.$emisor['ruc'].$emisor['usuario_sol'].'</wsse:Username>
						 			<wsse:Password>'.$emisor['clave_sol'].'</wsse:Password>
						 		</wsse:UsernameToken>
						 	</wsse:Security>
						 </soapenv:Header>
						 <soapenv:Body>
						 	<ser:sendSummary>
						 		<fileName>'.$nombre_archivo.'</fileName>
						 		<contentFile>'.$contenido_del_zip.'</contentFile>
						 	</ser:sendSummary>
						 </soapenv:Body>
						</soapenv:Envelope>';


					$header = array(
								"Content-type: text/xml; charset=\"utf-8\"",
								"Accept: text/xml",
								"Cache-Control: no-cache",
								"Pragma: no-cache",
								"SOAPAction: ",
								"Content-lenght: ".strlen($xml_envio)
							);


					$ch = curl_init();
					curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
					curl_setopt($ch,CURLOPT_URL,$ws);
					curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
					curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
					curl_setopt($ch,CURLOPT_TIMEOUT,30);
					curl_setopt($ch,CURLOPT_POST,true);
					curl_setopt($ch,CURLOPT_POSTFIELDS,$xml_envio);
					curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
					//para ejecutar los procesos de forma local en windows
					//enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
					curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");

					$response = curl_exec($ch);

					$httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
					$estadofe = "0";

					$ticket = "0";
					if($httpcode == 200)
					{
						$doc = new DOMDocument();
						$doc->loadXML($response);

						if (isset($doc->getElementsByTagName('ticket')->item(0)->nodeValue)) 
						{
			                $ticket = $doc->getElementsByTagName('ticket')->item(0)->nodeValue;
							//echo "TODO OK : ".$ticket;
							$estadofe ="4";
							$codigo='.';
							$mensaje = 'Pendiente de Baja';
							
						/*$mensaje['estado']    = "";
						$mensaje['cod_sunat'] = $doc_cdr->getElementsByTagName('ResponseCode')->item(0)->nodeValue;
                        $mensaje['msj_sunat'] = $doc_cdr->getElementsByTagName('Description')->item(0)->nodeValue;
                        $mensaje['hash_cdr'] = $doc_cdr->getElementsByTagName('DigestValue')->item(0)->nodeValue;*/
                        
						}
						else
						{		

							$codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
							$mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
							//echo "error ".$codigo.": ".$mensaje; 
							$ticket='';
							$estadofe="2";
						}

					}
					else{
						echo curl_error($ch);
						echo "Problema de conexión";
						$codigo = '.';
						$mensaje = '';	
						$ticket = '';
						$estadofe="0";
					}

					$cod = explode('.',$codigo);
					$codigo = $cod[1];

					$query=$connect->prepare("UPDATE tbl_venta_cab SET hash=?,feestado=? ,fecodigoerror=?,femensajesunat=?,ticket=? WHERE serie_resumen=? and numero_resumen=?");
					$resultado=$query->execute([$resp['hash_cpe'],$estadofe,$codigo,$mensaje,$ticket,$serier,$numeror]);

					curl_close($ch);
					return $ticket;
	}


	function ConsultarTicket($emisor, $nombre, $ticket,$rutacertificado="../../sunat/api/",  $ruta_archivo_xml = "../../sunat/", $ruta_archivo_cdr = "../../sunat/")
	{
		   $ruta_archivo_xml = $ruta_archivo_xml.$emisor['ruc'].'/xml/';
		   $ruta_archivo_cdr = $ruta_archivo_cdr.$emisor['ruc'].'/cdr/';

			//Enviamos el archivo a sunat
		        $link = $emisor['servidor_link'];
		        //echo 'link de resumen: '.$link;
				$ws   = $link;
			//$nombre	= $emisor["ruc"]."-".$cabecera["tipodoc"]."-".$cabecera["serie"]."-".$cabecera["correlativo"];
			$nombre_xml	= $nombre.".XML";

			//===============================================================//
			//FIRMADO DEL cpe CON CERTIFICADO DIGITAL
			$objSignature = new Signature();
			$flg_firma = "0";
			$ruta = $ruta_archivo_xml.$nombre_xml;
	        //echo $ruta;
			$ruta_firma = $rutacertificado.$emisor['certificado'];
			$pass_firma = $emisor['clave_certificado'];

			//===============================================================//

			//ALMACENAR EL ARCHIVO EN UN ZIP
			$zip = new ZipArchive();

			$nombrezip = $nombre.".ZIP";

			if($zip->open($nombrezip,ZIPARCHIVE::CREATE)===true)
			{
				$zip->addFile($ruta, $nombre_xml);
				$zip->close();
			}

			//===============================================================//

			//ENVIAR ZIP A SUNAT
			$ruta_archivo = $ruta_archivo_xml.$nombre;
			//$nombre_archivo = $nombre;
			//$ruta_archivo_cdr = "cdr/";

			$contenido_del_zip = base64_encode(file_get_contents($ruta_archivo.'.ZIP'));
			//FIN ZIP

			$xml_envio = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
	        <soapenv:Header>
	        <wsse:Security>
	        <wsse:UsernameToken>
	        <wsse:Username>'.$emisor['ruc'].$emisor['usuario_sol'].'</wsse:Username>
	        <wsse:Password>'.$emisor['clave_sol'].'</wsse:Password>
	        </wsse:UsernameToken>
	        </wsse:Security>
	        </soapenv:Header>
	        <soapenv:Body>
	        <ser:getStatus>
	        <ticket>' . $ticket . '</ticket>
	        </ser:getStatus>
	        </soapenv:Body>
	        </soapenv:Envelope>';


			$header = array(
						"Content-type: text/xml; charset=\"utf-8\"",
						"Accept: text/xml",
						"Cache-Control: no-cache",
						"Pragma: no-cache",
						"SOAPAction: ",
						"Content-lenght: ".strlen($xml_envio)
					);


			$ch = curl_init();
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($ch,CURLOPT_URL,$ws);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
			curl_setopt($ch,CURLOPT_TIMEOUT,30);
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$xml_envio);
			curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
			//para ejecutar los procesos de forma local en windows
			//enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
			//curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");

			$response = curl_exec($ch);
			//var_dump($response);
			$mensaje['respuestacurl'] = $response;
			$httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
            curl_close($ch);
			//echo "codigo:".$httpcode;
            $mensaje['httpcode'] = $httpcode;
			if($httpcode == 200)
			{
				$doc = new DOMDocument();
				$doc->loadXML($response);

				if(isset($doc->getElementsByTagName('content')->item(0)->nodeValue)){
					$cdr = $doc->getElementsByTagName('content')->item(0)->nodeValue;
					$cdr = base64_decode($cdr);
					
					//echo $cdr;
					

					file_put_contents($ruta_archivo_cdr."R-".$nombre.".ZIP", $cdr);

					$zip = new ZipArchive;
					if($zip->open($ruta_archivo_cdr."R-".$nombre.".ZIP")===true)
					{
					     if($link =='https://ose.nubefact.com/ol-ti-itcpe/billService?wsdl')
						    {
						        $zip->extractTo($ruta_archivo_cdr,'R-'.$nombre.'.xml');
                                $zip->close();
                                /*cambiamos el nombre del archivo*/
                                
                                rename($ruta_archivo_cdr.'/R-'.$nombre.'.xml',$ruta_archivo_cdr.'/R-'.$nombre.'.XML');
						     
						    }
						    else
						    {
                                $zip->extractTo($ruta_archivo_cdr,'R-'.$nombre.'.XML');
                                $zip->close();
						    }
						
					}
				unlink($ruta_archivo_cdr . 'R-' . $nombre . '.ZIP');
				//unlink('../../assets/ajax/'. 'R-' . $nombre . '.ZIP');
					$doc_cdr = new DOMDocument();
            $doc_cdr->load($ruta_archivo_cdr . 'R-' . $nombre . '.XML');
            $mensaje['cod_sunat'] = $doc_cdr->getElementsByTagName('ResponseCode')->item(0)->nodeValue;
            $mensaje['msj_sunat'] = $doc_cdr->getElementsByTagName('Description')->item(0)->nodeValue;
            $mensaje['hash_cdr'] = $doc_cdr->getElementsByTagName('DigestValue')->item(0)->nodeValue;
					//echo "TODO OK EN RESUMEN";
					
				}else{		
					//$mensaje['cod_sunat'] = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
					//$mensaje['msj_sunat'] = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
					$mensaje['cod_sunat'] = $doc->getElementsByTagName('statusCode')->item(0)->nodeValue;
					
					if($mensaje['cod_sunat'] == '0')
					{
                    $mensaje['msj_sunat'] = "PROCESADO CORRECTAMENTE";
					}
					else if($mensaje['cod_sunat'] == '98')
					{
                    $mensaje['msj_sunat'] = "EN PROCESO , ESPERE";
					}
					else if($mensaje['cod_sunat'] == '99')
					{
                    $mensaje['msj_sunat'] = "PROCESADO CON ERRORES";
					}
					
					
					
					
					//echo "error en envio de resumen ".$codigo.": ".$mensaje; 
				}

			}else{
				//echo curl_error($ch);
					$mensaje['cod_sunat'] = '019999999';
					$mensaje['msj_sunat'] = 'NO HAY RESPUESTA DE SUNAT';
					$mensaje['hash_cdr'] ='';
				//echo "Problema de conexión en resumen ";
			}

			
			//echo json_encode($mensaje);
			return $mensaje;
	}


	function consultarComprobante($emisor, $comprobante)
	{

			try{
					//Enviamos el archivo a sunat
        $link = $emisor['servidor_link'];
        
		$ws   = $link;
        //$ws = "https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl";
        //$ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
					$soapUser = "";  
					$soapPassword = "";

					$xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
					xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" 
					xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
						<soapenv:Header>
							<wsse:Security>
								<wsse:UsernameToken>
									<wsse:Username>'.$emisor['ruc'].$emisor['usuariosol'].'</wsse:Username>
									<wsse:Password>'.$emisor['clavesol'].'</wsse:Password>
								</wsse:UsernameToken>
							</wsse:Security>
						</soapenv:Header>
						<soapenv:Body>
							<ser:getStatus>
								<rucComprobante>'.$emisor['ruc'].'</rucComprobante>
								<tipoComprobante>'.$comprobante['tipodoc'].'</tipoComprobante>
								<serieComprobante>'.$comprobante['serie'].'</serieComprobante>
								<numeroComprobante>'.$comprobante['correlativo'].'</numeroComprobante>
							</ser:getStatus>
						</soapenv:Body>
					</soapenv:Envelope>';
				
					$headers = array(
						"Content-type: text/xml;charset=\"utf-8\"",
						"Accept: text/xml",
						"Cache-Control: no-cache",
						"Pragma: no-cache",
						"SOAPAction: ",
						"Content-length: " . strlen($xml_post_string),
					); 			
				
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_URL, $ws);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
					curl_setopt($ch, CURLOPT_TIMEOUT, 30);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				
					//para ejecutar los procesos de forma local en windows
					//enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
					curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");

					$response = curl_exec($ch);
					$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					curl_close($ch);
					echo var_dump($response);
					
				} catch (Exception $e) {
					echo "SUNAT ESTA FUERA SERVICIO: ".$e->getMessage();
				}



	}

}
?>