<?php 
class GeneradorXML
{
      function CrearXMLGRE($nombrexml, $emisor, $cliente, $cabecera, $detalle)
      {
            $doc = new DOMDocument();
            $doc->formatOutput = FALSE;
            $doc->preserveWhiteSpace = TRUE;
            /*
            $doc->encoding = 'ISO-8859-1';
            $xmlCPE = '<?xml version="1.0" encoding="iso-8859-1"?>
            */
            $doc->encoding = 'utf-8';
            $xmlCPE = '<?xml version="1.0" encoding="utf-8"?>
            <DespatchAdvice xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns="urn:oasis:names:specification:ubl:schema:xsd:DespatchAdvice-2">
            <ext:UBLExtensions>
            <ext:UBLExtension>
            <ext:ExtensionContent>
            </ext:ExtensionContent>
            </ext:UBLExtension>
            </ext:UBLExtensions>
            <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
            <cbc:CustomizationID>2.0</cbc:CustomizationID>
            <cbc:ID>'.$cabecera["serie"].'-'.$cabecera["correlativo"].'</cbc:ID>
            <cbc:IssueDate>'.$cabecera["fecha_emision"].'</cbc:IssueDate>
            <cbc:IssueTime>'.$cabecera["hora_emision"].'</cbc:IssueTime>
            <cbc:DespatchAdviceTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">09</cbc:DespatchAdviceTypeCode>
            <cbc:Note>'.$cabecera["nota"].'</cbc:Note>';

            $xmlCPE = $xmlCPE .
             ' 
            <cac:Signature>
            <cbc:ID>'.$cabecera["ruc_empresa"].'</cbc:ID>
            <cac:SignatoryParty>
            <cac:PartyIdentification>
            <cbc:ID schemeID="6" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'. $cabecera["ruc_empresa"].'</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
            <cbc:Name><![CDATA['.$cabecera["razon_social"].']]></cbc:Name>
            </cac:PartyName>
            </cac:SignatoryParty>
            <cac:DigitalSignatureAttachment>
            <cac:ExternalReference>
            <cbc:URI>'. $cabecera["ruc_empresa"].'</cbc:URI>
            </cac:ExternalReference>
            </cac:DigitalSignatureAttachment>
            </cac:Signature>   
            ';

            $xmlCPE = $xmlCPE .
             '<cac:DespatchSupplierParty>
            <cbc:CustomerAssignedAccountID schemeID="'.$cabecera["tipo_doc_empresa"].'">'.$cabecera["ruc_empresa"].'</cbc:CustomerAssignedAccountID>
            <cac:Party>
            <cac:PartyIdentification>
            <cbc:ID schemeID="'.$cabecera["tipo_doc_empresa"].'" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$cabecera["ruc_empresa"].'</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyLegalEntity>
            <cbc:RegistrationName><![CDATA['.$cabecera["razon_social"].']]></cbc:RegistrationName>
            </cac:PartyLegalEntity>
            </cac:Party>
            </cac:DespatchSupplierParty>

            <cac:DeliveryCustomerParty>
            <cbc:CustomerAssignedAccountID schemeID="'.$cabecera["tipo_doc_cliente"] . '">' . $cabecera["nro_doc_cliente"] . '</cbc:CustomerAssignedAccountID>
            <cac:Party>
            <cac:PartyIdentification>
            <cbc:ID schemeID="'.$cabecera["tipo_doc_cliente"].'" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$cabecera["nro_doc_cliente"].'</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyLegalEntity>
            <cbc:RegistrationName><![CDATA['.$cabecera["razon_social_cliente"].']]></cbc:RegistrationName>
            </cac:PartyLegalEntity>
            </cac:Party>
            </cac:DeliveryCustomerParty>

            <cac:Shipment>
            <cbc:ID>SUNAT_Envio</cbc:ID>
            <cbc:HandlingCode>'.$cabecera["cod_motivo_traslado"].'</cbc:HandlingCode>
            <cbc:GrossWeightMeasure unitCode="KGM">'.number_format($cabecera["peso"],2).'</cbc:GrossWeightMeasure>
            ';
            
            if($cabecera["cod_motivo_traslado"] =='08' || $cabecera["cod_motivo_traslado"] =='09' )
            {
              $xmlCPE = $xmlCPE.'
               <cbc:Information>'.$cabecera["motivo_traslado"].'</cbc:Information>
             
              <cbc:TotalTransportHandlingUnitQuantity>'.$cabecera["nro_paquetes"].'</cbc:TotalTransportHandlingUnitQuantity>';
            }

           

            $xmlCPE = $xmlCPE.' <cac:ShipmentStage>
            <cbc:TransportModeCode>'.$cabecera["tipo_transportista"].'</cbc:TransportModeCode>
            <cac:TransitPeriod>
            <cbc:StartDate>'.$cabecera["fecha_emision"].'</cbc:StartDate>
            </cac:TransitPeriod>';
            
            if($cabecera["tipo_transportista"] =='01')
            {
               $xmlCPE = $xmlCPE.'<cac:CarrierParty>
               <cac:PartyIdentification>
               <cbc:ID schemeID="'.$cabecera["tipo_documento_transporte"].'">'.$cabecera["nro_documento_transporte"].'</cbc:ID>
               </cac:PartyIdentification>
               <cac:PartyName>
               <cbc:Name><![CDATA['.$cabecera["razon_social_transporte"].']]></cbc:Name>
               </cac:PartyName>
               </cac:CarrierParty>';
            }
                     
               $xmlCPE = $xmlCPE.'
               <cac:TransportMeans>
               <cac:RoadTransport>
               <cbc:LicensePlateID>'.$cabecera["placa_vehiculo"].'</cbc:LicensePlateID>
               </cac:RoadTransport>
               </cac:TransportMeans>

               <cac:DriverPerson>
               <cbc:ID schemeID="'.$cabecera["cod_tipo_doc_chofer"].'">' . $cabecera["nro_doc_chofer"].'</cbc:ID>
               <cbc:FirstName>'.$cabecera["nombre_chofer"].'</cbc:FirstName>
               <cbc:FamilyName>'.$cabecera["apellido_chofer"].'</cbc:FamilyName>
               <cbc:JobTitle>Principal</cbc:JobTitle>
               <cac:IdentityDocumentReference>
               <cbc:ID>'.$cabecera["licencia"].'</cbc:ID>
               </cac:IdentityDocumentReference>
               </cac:DriverPerson>

            </cac:ShipmentStage>

             <cac:Delivery>
            <cac:DeliveryAddress>
             <!-- UBIGEO DE LLEGADA -->
            <cbc:ID schemeName="Ubigeos" schemeAgencyName="PE:INEI">'.$cabecera["ubigeo_destino"].'</cbc:ID>';

            if($cabecera["cod_motivo_traslado"] =='04')
            {
            $xmlCPE = $xmlCPE.'<!-- CODIGO DE ESTABLECIMIENTO ANEXO DE LLEGADA -->
           <cbc:AddressTypeCode listAgencyName="PE:SUNAT" listName="Establecimientos anexos" listID="'.$cabecera["nro_doc_cliente"].'">0000</cbc:AddressTypeCode>';
            }

            $xmlCPE = $xmlCPE.'<cbc:StreetName><![CDATA['.$cabecera["direccion_destino"].']]></cbc:StreetName>
            <cac:AddressLine>
            <cbc:Line><![CDATA['.$cabecera["direccion_destino"].']]></cbc:Line>
            </cac:AddressLine>
            <!-- PUNTO DE GEOREFERENCIA DE LLEGADA -->
            <!--  -->
            </cac:DeliveryAddress>
            <cac:Despatch>
             <!-- UBIGEO DE PARTIDA -->
               <cac:DespatchAddress>
               <cbc:ID schemeAgencyName="PE:INEI" schemeName="Ubigeos"><![CDATA['.$cabecera["ubigeo_partida"].']]></cbc:ID>';
                if($cabecera["cod_motivo_traslado"] =='04')
            {
            $xmlCPE = $xmlCPE.'
            <!-- CODIGO DE ESTABLECIMIENTO ANEXO DE PARTIDA -->
           <cbc:AddressTypeCode listAgencyName="PE:SUNAT" listName="Establecimientos anexos" listID="'.$cabecera["ruc_empresa"].'">0000</cbc:AddressTypeCode>';
            }



               $xmlCPE = $xmlCPE.'
               <cac:AddressLine>
               <cbc:Line><![CDATA['.$cabecera["direccion_partida"].']]></cbc:Line>
               </cac:AddressLine>
               </cac:DespatchAddress>
            </cac:Despatch>

            </cac:Delivery>
            <cac:TransportHandlingUnit>
               <cac:TransportEquipment>
               <cbc:ID>'.$cabecera["placa_vehiculo"] .'</cbc:ID>
               <cac:AttachedTransportEquipment>
               <cbc:ID>'.$cabecera["placa_vehiculo"] .'</cbc:ID>
               </cac:AttachedTransportEquipment>
               </cac:TransportEquipment>
               </cac:TransportHandlingUnit>';
             
            
            $xmlCPE = $xmlCPE .'<cac:OriginAddress>
            <cbc:ID>'.$cabecera["ubigeo_partida"] .'</cbc:ID>
            <cbc:StreetName><![CDATA['.$cabecera["direccion_partida"].']]></cbc:StreetName>
            </cac:OriginAddress>
            </cac:Shipment>';

            for ($i = 0; $i < count($detalle); $i++) {
            $xmlCPE = $xmlCPE . '
            <cac:DespatchLine>
            <cbc:ID>' . $detalle[$i]["item"] . '</cbc:ID>';
            
            $xmlCPE = $xmlCPE .'<cbc:DeliveredQuantity unitCode="NIU">'.$detalle[$i]["cantidad"].'</cbc:DeliveredQuantity>';
            
            $xmlCPE = $xmlCPE .'<cac:OrderLineReference>
            <cbc:LineID>' . $detalle[$i]["nro_orden"] . '</cbc:LineID>
            </cac:OrderLineReference>

            <cac:Item>
            <cbc:Description>
         <![CDATA['.$detalle[$i]["nombreproducto"].']]>
         </cbc:Description>
                     <cbc:Name><![CDATA['.$detalle[$i]["nombreproducto"].']]></cbc:Name>

            <cac:SellersItemIdentification>
            <cbc:ID>' . $detalle[$i]["codigoproducto"] . '</cbc:ID>
            </cac:SellersItemIdentification>
            </cac:Item>
            </cac:DespatchLine>';
            }
            $xml = $xmlCPE.'
            </DespatchAdvice>';




            $doc->loadXML($xml);
            $doc->save($nombrexml.'.XML');

            return 'XML DE GUIA CREADO';   
      }

      function CrearXMLFactura($nombrexml, $emisor, $cliente, $comprobante, $detalle,$cuota)
      {



      		$doc = new DOMDocument();
      		$doc->formatOutput = FALSE;
      		$doc->preserveWhiteSpace = TRUE;
      		$doc->encoding = 'utf-8';

      	    $xml = '<?xml version="1.0" encoding="UTF-8"?>
         <Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
         <ext:UBLExtensions>
            <ext:UBLExtension>
               <ext:ExtensionContent />
            </ext:UBLExtension>
         </ext:UBLExtensions>
         <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
         <cbc:CustomizationID>2.0</cbc:CustomizationID>';

          if($comprobante['por_det']>0)
          {
            $xml .='<cbc:ProfileID schemeName="Tipo de Operacion" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo51">1001</cbc:ProfileID>';
          }


         $xml .='<cbc:ID>'.$comprobante['serie'].'-'.$comprobante['correlativo'].'</cbc:ID>
         <cbc:IssueDate>'.$comprobante['fecha_emision'].'</cbc:IssueDate>
         <cbc:IssueTime>00:00:00</cbc:IssueTime>
         <cbc:DueDate>'.$comprobante['fecha_vencimiento'].'</cbc:DueDate>';
         if($comprobante['por_det']>0)
         {
           $InvoiceTypeCode = '1001';
         }
         else
         {
           $InvoiceTypeCode = '0101';
         }
         $xml .='
         <cbc:InvoiceTypeCode listID="'.$InvoiceTypeCode.'">'.$comprobante['tipodoc'].'</cbc:InvoiceTypeCode>
         <cbc:Note languageLocaleID="1000"><![CDATA['.trim($comprobante['total_texto']).']]></cbc:Note>';
        if($comprobante['por_det']>0)
         {
           $xml .='<cbc:Note languageLocaleID="2006">Operacion Sujeta a detraccion</cbc:Note>';
         }

        $xml .=
         '<cbc:DocumentCurrencyCode>'.$comprobante['moneda'].'</cbc:DocumentCurrencyCode>';

//AQUI AGREGAMOS EL DOCUMENTO DE REFERENCIA DE ANTICIPO	
/**/	
if ($comprobante["NROACTICIPO"] <> "") {	
   for ($z = 0; $z < count($comprobante["NROACTICIPO"]); $z++) {
     
$xml .=
  '<cac:AdditionalDocumentReference>
  <cbc:ID>'.$comprobante["NROACTICIPO"][$z]["serienumero"]. '</cbc:ID>
        <cbc:DocumentTypeCode>'.$comprobante["NROACTICIPO"][$z]["tipodoc"]. '</cbc:DocumentTypeCode>
        <cbc:DocumentStatusCode>'.$comprobante["NROACTICIPO"][$z]["orden"].'</cbc:DocumentStatusCode>
        <cac:IssuerParty>
           <cac:PartyIdentification><cbc:ID schemeID="'.$cliente['tipodoc'].'">'.$cliente["ruc"].'</cbc:ID>
           </cac:PartyIdentification>
        </cac:IssuerParty>
     </cac:AdditionalDocumentReference>';
  
  }
}


$xml .=
         '<cac:Signature>
            <cbc:ID>'.$comprobante['serie'].'-'.$comprobante['correlativo'].'</cbc:ID>
            <cbc:Note><![CDATA['.$emisor['nombre_comercial'].']]></cbc:Note>
            <cac:SignatoryParty>
               <cac:PartyIdentification>
                  <cbc:ID>'.$emisor['ruc'].'</cbc:ID>
               </cac:PartyIdentification>
               <cac:PartyName>
                  <cbc:Name><![CDATA['.$emisor['razon_social'].']]></cbc:Name>
               </cac:PartyName>
            </cac:SignatoryParty>
            <cac:DigitalSignatureAttachment>
               <cac:ExternalReference>
                  <cbc:URI>#SIGN-EMPRESA</cbc:URI>
               </cac:ExternalReference>
            </cac:DigitalSignatureAttachment>
         </cac:Signature>
         <cac:AccountingSupplierParty>
            <cac:Party>
               <cac:PartyIdentification>
                  <cbc:ID schemeID="'.$emisor['tipodoc'].'">'.$emisor['ruc'].'</cbc:ID>
               </cac:PartyIdentification>
               <cac:PartyName>
                  <cbc:Name><![CDATA['.$emisor['nombre_comercial'].']]></cbc:Name>
               </cac:PartyName>

               <cac:PartyTaxScheme>
                  <cbc:RegistrationName><![CDATA['.$emisor['razon_social'].']]></cbc:RegistrationName>
                  <cbc:CompanyID schemeID="6">'.$emisor['ruc'].'</cbc:CompanyID>
                  <cac:TaxScheme>
                  <cbc:ID schemeID="6">'.$emisor['ruc'].'</cbc:ID>
                  </cac:TaxScheme>
               </cac:PartyTaxScheme>
              

               <cac:PartyLegalEntity>
                  <cbc:RegistrationName><![CDATA['.$emisor['razon_social'].']]></cbc:RegistrationName>
                  <cac:RegistrationAddress>
                     <cbc:ID>'.$emisor['ubigeo'].'</cbc:ID>
                     <cbc:AddressTypeCode>0000</cbc:AddressTypeCode>
                     <cbc:CitySubdivisionName>NONE</cbc:CitySubdivisionName>
                     <cbc:CityName>'.$emisor['provincia'].'</cbc:CityName>
                     <cbc:CountrySubentity>'.$emisor['departamento'].'</cbc:CountrySubentity>
                     <cbc:District>'.$emisor['distrito'].'</cbc:District>
                     <cac:AddressLine>
                        <cbc:Line><![CDATA['.$emisor['direccion'].']]></cbc:Line>
                     </cac:AddressLine>
                     <cac:Country>
                        <cbc:IdentificationCode>'.$emisor['pais'].'</cbc:IdentificationCode>
                     </cac:Country>
                  </cac:RegistrationAddress>
               </cac:PartyLegalEntity>
               <cac:Contact>
                  <cbc:Name><![CDATA[]]></cbc:Name>
               </cac:Contact>

            </cac:Party>
         </cac:AccountingSupplierParty>
         <cac:AccountingCustomerParty>
            <cac:Party>
               <cac:PartyIdentification>
                  <cbc:ID schemeID="'.$cliente['tipodoc'].'">'.$cliente['ruc'].'</cbc:ID>
               </cac:PartyIdentification>


               <cac:PartyName>
            <cbc:Name><![CDATA['.$cliente['razon_social'].']]></cbc:Name>
         </cac:PartyName>
         <cac:PartyTaxScheme>
            <cbc:RegistrationName><![CDATA['.$cliente['razon_social'].']]></cbc:RegistrationName>
            <cbc:CompanyID schemeID="6">'.$cliente['ruc'].'</cbc:CompanyID>
            <cac:TaxScheme>
               <cbc:ID schemeID="6">'.$cliente['ruc'].'</cbc:ID>
            </cac:TaxScheme>
         </cac:PartyTaxScheme>



               <cac:PartyLegalEntity>
                  <cbc:RegistrationName><![CDATA['.$cliente['razon_social'].']]></cbc:RegistrationName>
                  <cac:RegistrationAddress>
                     <cac:AddressLine>
                        <cbc:Line><![CDATA['.$cliente['direccion'].']]></cbc:Line>
                     </cac:AddressLine>
                     <cac:Country>
                        <cbc:IdentificationCode>'.$cliente['pais'].'</cbc:IdentificationCode>
                     </cac:Country>
                  </cac:RegistrationAddress>
               </cac:PartyLegalEntity>
            </cac:Party>
         </cac:AccountingCustomerParty>';

         if($comprobante['por_det']>0)
         {
            $tc  = $comprobante['tc'];
            $imp_det = $comprobante['imp_det'];
            if($comprobante['moneda'] == 'USD')
            {
               $imp_det = $imp_det * $tc; 
            }
            $xml .=
            '<cac:PaymentMeans>
               <cbc:ID>Detraccion</cbc:ID>
               <cbc:PaymentMeansCode>001</cbc:PaymentMeansCode>
               <cac:PayeeFinancialAccount>
                <cbc:ID>'.$emisor['cta_detraccion'].'</cbc:ID>
               </cac:PayeeFinancialAccount>           
            </cac:PaymentMeans>

            <cac:PaymentTerms>
               <cbc:ID>Detraccion</cbc:ID>
               <cbc:PaymentMeansID>'.$comprobante['cod_det'].'</cbc:PaymentMeansID>
               <cbc:PaymentPercent>'.$comprobante['por_det'].'</cbc:PaymentPercent> 
               <cbc:Amount currencyID="PEN">'.$imp_det.'</cbc:Amount>         
            </cac:PaymentTerms>';
         }

         if($comprobante['condicion_venta']=='1')
         {
            $forma_pago = 'Contado';
         }
         else
         {
           $forma_pago = 'Credito';  
         }

         $xml .=
         '<cac:PaymentTerms>
               <cbc:ID>FormaPago</cbc:ID>
               <cbc:PaymentMeansID>'.$forma_pago.'</cbc:PaymentMeansID>';
               
           if($comprobante['condicion_venta']=='2')
         {
            $xml.='<cbc:Amount currencyID="'.$comprobante['moneda'].'">'.$comprobante['total'].'</cbc:Amount>';

         }
         $xml.='</cac:PaymentTerms>';
         
        if($comprobante['condicion_venta']=='2')
         {
          foreach($cuota as $j=>$c)
          {
            $xml.='<cac:PaymentTerms>        
                <cbc:ID>FormaPago</cbc:ID>
                <cbc:PaymentMeansID schemeAgencyName="PE:SUNAT">Cuota'.$c['num_cuota'].'</cbc:PaymentMeansID>
                <cbc:Amount currencyID="'.$c['moneda'].'">'.$c['importe_cuota'].'</cbc:Amount>
                <cbc:PaymentDueDate>'.$c['fecha_cuota'].'</cbc:PaymentDueDate> 
            </cac:PaymentTerms>';
          }

         }

/*SEGUNDA PARTE DE ANTICIPOS*/
if ($comprobante["NROACTICIPO"] <> "") {	
   for ($z = 0; $z < count($comprobante["NROACTICIPO"]); $z++) {
     
 $xml.=
  '<cac:PrepaidPayment>
        <cbc:ID>'.$comprobante["NROACTICIPO"][$z]["orden"].'</cbc:ID>
        <cbc:PaidAmount currencyID="PEN">'.$comprobante["NROACTICIPO"][$z]["monto"].'</cbc:PaidAmount>
     </cac:PrepaidPayment>';
  }
     
     
   for ($z = 0; $z < count($comprobante["NROACTICIPO"]); $z++) {	
 $xml.=
  '<cac:AllowanceCharge>
  <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
  <cbc:AllowanceChargeReasonCode>04</cbc:AllowanceChargeReasonCode>
  <cbc:MultiplierFactorNumeric>'.$comprobante["NROACTICIPO"][$z]["orden"].'</cbc:MultiplierFactorNumeric>
  <cbc:Amount currencyID="' . $comprobante["moneda"] . '">'.$comprobante["NROACTICIPO"][$z]["monto"].'</cbc:Amount>
  <cbc:BaseAmount currencyID="' . $comprobante["moneda"] . '">'.$comprobante["NROACTICIPO"][$z]["monto"].'</cbc:BaseAmount>
  </cac:AllowanceCharge>
  ';
  }	
  }
/*SEGUNDA PARTE DE ANTICIPOS*/

$xml.='<cac:TaxTotal>
            <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.number_format($comprobante['igv'],2, '.', '').'</cbc:TaxAmount>
            <cac:TaxSubtotal>
               <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.number_format($comprobante['total_opgravadas'],2, '.', '').'</cbc:TaxableAmount>
               <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.number_format($comprobante['igv'],2, '.', '').'</cbc:TaxAmount>
               <cac:TaxCategory>
                  <cac:TaxScheme>
                     <cbc:ID>1000</cbc:ID>
                     <cbc:Name>IGV</cbc:Name>
                     <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                  </cac:TaxScheme>
               </cac:TaxCategory>
            </cac:TaxSubtotal>';
           

            if($comprobante['total_opexoneradas']>0){
               $xml.='<cac:TaxSubtotal>
                  <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.number_format($comprobante['total_opexoneradas'],2, '.', '').'</cbc:TaxableAmount>
                  <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">0.00</cbc:TaxAmount>
                  <cac:TaxCategory>
                     <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                     <cac:TaxScheme>
                        <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9997</cbc:ID>
                        <cbc:Name>EXO</cbc:Name>
                        <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                     </cac:TaxScheme>
                  </cac:TaxCategory>
               </cac:TaxSubtotal>';
            }

            if($comprobante['total_opinafectas']>0){
               $xml.='<cac:TaxSubtotal>
                  <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.number_format($comprobante['total_opinafectas'],2, '.', '').'</cbc:TaxableAmount>
                  <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">0.00</cbc:TaxAmount>
                  <cac:TaxCategory>
                     <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                     <cac:TaxScheme>
                        <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9998</cbc:ID>
                        <cbc:Name>INA</cbc:Name>
                        <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                     </cac:TaxScheme>
                  </cac:TaxCategory>
               </cac:TaxSubtotal>';
            }

$total_antes_de_impuestos = $comprobante['total_opgravadas']+$comprobante['total_opexoneradas']+$comprobante['total_opinafectas'];

$xml.='</cac:TaxTotal>';

/* ULTIMA PARTE DE ANTICIPO*/

if ($comprobante["NROACTICIPO"] <> "") {
	
$xml.='
<cac:LegalMonetaryTotal>
<cbc:LineExtensionAmount currencyID="' . $comprobante["moneda"] . '">' . $comprobante["subanticipo"] . '</cbc:LineExtensionAmount>
<cbc:TaxInclusiveAmount currencyID="' . $comprobante["moneda"] . '">' . $comprobante["totalanticipo"] . '</cbc:TaxInclusiveAmount>
<cbc:PrepaidAmount currencyID="' . $comprobante["moneda"] . '">' . $comprobante["pagadoanticipo"] . '</cbc:PrepaidAmount>
<cbc:PayableAmount currencyID="' . $comprobante["moneda"] . '">'.$comprobante["total"].'</cbc:PayableAmount>
</cac:LegalMonetaryTotal>
';

}else{

$xml.='<cac:LegalMonetaryTotal>
<cbc:LineExtensionAmount currencyID="'.$comprobante['moneda'].'">'.number_format($total_antes_de_impuestos,2, '.', '').'</cbc:LineExtensionAmount>
<cbc:TaxInclusiveAmount currencyID="'.$comprobante['moneda'].'">'.number_format($comprobante['total'],2, '.', '').'</cbc:TaxInclusiveAmount>';
if($comprobante['redondeo']>0){
$xml.='<cbc:PayableRoundingAmount currencyID="PEN">'.$comprobante['redondeo'].'</cbc:PayableRoundingAmount>';  
}

$xml.='<cbc:PayableAmount currencyID="'.$comprobante['moneda'].'">'.number_format($comprobante['total'],2, '.', '').'</cbc:PayableAmount>
</cac:LegalMonetaryTotal>';

}




foreach($detalle as $k=>$v){

      	   $xml.='<cac:InvoiceLine>
      	      <cbc:ID>'.$v['item'].'</cbc:ID>
      	      <cbc:InvoicedQuantity unitCode="'.$v['unidad'].'">'.$v['cantidad'].'</cbc:InvoicedQuantity>
      	      <cbc:LineExtensionAmount currencyID="'.$comprobante['moneda'].'">'.number_format($v['valor_total'],2, '.', '').'</cbc:LineExtensionAmount>
      	      <cac:PricingReference>
      	         <cac:AlternativeConditionPrice>
      	            <cbc:PriceAmount currencyID="'.$comprobante['moneda'].'">'.number_format($v['precio_unitario'],2, '.', '').'</cbc:PriceAmount>
      	            <cbc:PriceTypeCode>'.$v['tipo_precio'].'</cbc:PriceTypeCode>
      	         </cac:AlternativeConditionPrice>
      	      </cac:PricingReference>
      	      <cac:TaxTotal>
      	         <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.number_format($v['igv'],2, '.', '').'</cbc:TaxAmount>
      	         <cac:TaxSubtotal>
      	            <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.number_format($v['valor_total'],2, '.', '').'</cbc:TaxableAmount>
      	            <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.number_format($v['igv'],2, '.', '').'</cbc:TaxAmount>
      	            <cac:TaxCategory>
      	               <cbc:Percent>'.$v['porcentaje_igv'].'</cbc:Percent>
      	               <cbc:TaxExemptionReasonCode>'.$v['codigo_afectacion_alt'].'</cbc:TaxExemptionReasonCode>
      	               <cac:TaxScheme>
      	                  <cbc:ID>'.$v['codigo_afectacion'].'</cbc:ID>
      	                  <cbc:Name>'.$v['nombre_afectacion'].'</cbc:Name>
      	                  <cbc:TaxTypeCode>'.$v['tipo_afectacion'].'</cbc:TaxTypeCode>
      	               </cac:TaxScheme>
      	            </cac:TaxCategory>
      	         </cac:TaxSubtotal>
      	      </cac:TaxTotal>
      	      <cac:Item>
      	         <cbc:Description><![CDATA['.$v['descripcion'].']]></cbc:Description>
      	         <cac:SellersItemIdentification>
      	            <cbc:ID>'.$v['codigo'].'</cbc:ID>
      	         </cac:SellersItemIdentification>
      	      </cac:Item>
      	      <cac:Price>
      	         <cbc:PriceAmount currencyID="'.$comprobante['moneda'].'">'.number_format($v['valor_unitario'],5, '.', '').'</cbc:PriceAmount>
      	      </cac:Price>
      	   </cac:InvoiceLine>';
      	   	
      	   	}

      	   	$xml.="</Invoice>";

      	    $doc->loadXML($xml);
      	    $doc->save($nombrexml.'.XML');
             
      } 

      /////////////////////////////////////NOTA DE CREDITO //////////////////////////////////////////////////////////////////////////////////////
      function CrearXMLNotaCredito($nombrexml, $emisor, $cliente, $comprobante, $detalle)
      {

                     $doc = new DOMDocument();
                     $doc->formatOutput = FALSE;
                     $doc->preserveWhiteSpace = TRUE;
                     $doc->encoding = 'utf-8';      

                     $xml = '<?xml version="1.0" encoding="UTF-8"?>
                              <CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
                                 <ext:UBLExtensions>
                                    <ext:UBLExtension>
                                       <ext:ExtensionContent />
                                    </ext:UBLExtension>
                                 </ext:UBLExtensions>
                                 <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
                                 <cbc:CustomizationID>2.0</cbc:CustomizationID>
                                 <cbc:ID>'.$comprobante['serie'].'-'.$comprobante['correlativo'].'</cbc:ID>
                                 <cbc:IssueDate>'.$comprobante['fecha_emision'].'</cbc:IssueDate>
                                 <cbc:IssueTime>00:00:01</cbc:IssueTime>
                                 <cbc:Note languageLocaleID="1000"><![CDATA['.trim($comprobante['total_texto']).']]></cbc:Note>
                                 <cbc:DocumentCurrencyCode>'.$comprobante['moneda'].'</cbc:DocumentCurrencyCode>
                                 <cac:DiscrepancyResponse>
                                    <cbc:ReferenceID>'.$comprobante['serie_ref'].'-'.$comprobante['correlativo_ref'].'</cbc:ReferenceID>
                                    <cbc:ResponseCode>'.$comprobante['codmotivo'].'</cbc:ResponseCode>
                                    <cbc:Description>'.$comprobante['descripcion'].'</cbc:Description>
                                 </cac:DiscrepancyResponse>
                                 <cac:BillingReference>
                                    <cac:InvoiceDocumentReference>
                                       <cbc:ID>'.$comprobante['serie_ref'].'-'.$comprobante['correlativo_ref'].'</cbc:ID>
                                       <cbc:DocumentTypeCode>'.$comprobante['tipodoc_ref'].'</cbc:DocumentTypeCode>
                                    </cac:InvoiceDocumentReference>
                                 </cac:BillingReference>
                                 <cac:Signature>
                                    <cbc:ID>'.$emisor['ruc'].'</cbc:ID>
                                    <cbc:Note><![CDATA['.$emisor['nombre_comercial'].']]></cbc:Note>
                                    <cac:SignatoryParty>
                                       <cac:PartyIdentification>
                                          <cbc:ID>'.$emisor['ruc'].'</cbc:ID>
                                       </cac:PartyIdentification>
                                       <cac:PartyName>
                                          <cbc:Name><![CDATA['.$emisor['razon_social'].']]></cbc:Name>
                                       </cac:PartyName>
                                    </cac:SignatoryParty>
                                    <cac:DigitalSignatureAttachment>
                                       <cac:ExternalReference>
                                          <cbc:URI>#SIGN-EMPRESA</cbc:URI>
                                       </cac:ExternalReference>
                                    </cac:DigitalSignatureAttachment>
                                 </cac:Signature>
                                 <cac:AccountingSupplierParty>
                                    <cac:Party>
                                       <cac:PartyIdentification>
                                          <cbc:ID schemeID="'.$emisor['tipodoc'].'">'.$emisor['ruc'].'</cbc:ID>
                                       </cac:PartyIdentification>
                                       <cac:PartyName>
                                          <cbc:Name><![CDATA['.$emisor['nombre_comercial'].']]></cbc:Name>
                                       </cac:PartyName>
                                       <cac:PartyLegalEntity>
                                          <cbc:RegistrationName><![CDATA['.$emisor['razon_social'].']]></cbc:RegistrationName>
                                          <cac:RegistrationAddress>
                                             <cbc:ID>'.$emisor['ubigeo'].'</cbc:ID>
                                             <cbc:AddressTypeCode>0000</cbc:AddressTypeCode>
                                             <cbc:CitySubdivisionName>NONE</cbc:CitySubdivisionName>
                                             <cbc:CityName>'.$emisor['provincia'].'</cbc:CityName>
                                             <cbc:CountrySubentity>'.$emisor['departamento'].'</cbc:CountrySubentity>
                                             <cbc:District>'.$emisor['distrito'].'</cbc:District>
                                             <cac:AddressLine>
                                                <cbc:Line><![CDATA['.$emisor['direccion'].']]></cbc:Line>
                                             </cac:AddressLine>
                                             <cac:Country>
                                                <cbc:IdentificationCode>'.$emisor['pais'].'</cbc:IdentificationCode>
                                             </cac:Country>
                                          </cac:RegistrationAddress>
                                       </cac:PartyLegalEntity>
                                    </cac:Party>
                                 </cac:AccountingSupplierParty>
                                 <cac:AccountingCustomerParty>
                                    <cac:Party>
                                       <cac:PartyIdentification>
                                          <cbc:ID schemeID="'.$cliente['tipodoc'].'">'.$cliente['ruc'].'</cbc:ID>
                                       </cac:PartyIdentification>
                                       <cac:PartyLegalEntity>
                                          <cbc:RegistrationName><![CDATA['.$cliente['razon_social'].']]></cbc:RegistrationName>
                                          <cac:RegistrationAddress>
                                             <cac:AddressLine>
                                                <cbc:Line><![CDATA['.$cliente['direccion'].']]></cbc:Line>
                                             </cac:AddressLine>
                                             <cac:Country>
                                                <cbc:IdentificationCode>'.$cliente['pais'].'</cbc:IdentificationCode>
                                             </cac:Country>
                                          </cac:RegistrationAddress>
                                       </cac:PartyLegalEntity>
                                    </cac:Party>
                                 </cac:AccountingCustomerParty>


                                 <cac:TaxTotal>
                                    <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.number_format($comprobante['igv'],2, '.', '').'</cbc:TaxAmount>
                                    <cac:TaxSubtotal>
                                       <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.number_format($comprobante['total_opgravadas'],2, '.', '').'</cbc:TaxableAmount>
                                       <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.number_format($comprobante['igv'],2, '.', '').'</cbc:TaxAmount>
                                       <cac:TaxCategory>
                                          <cac:TaxScheme>
                                             <cbc:ID>1000</cbc:ID>
                                             <cbc:Name>IGV</cbc:Name>
                                             <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                                          </cac:TaxScheme>
                                       </cac:TaxCategory>
                                    </cac:TaxSubtotal>';

                                    if($comprobante['total_opexoneradas']>0){
                                       $xml.='<cac:TaxSubtotal>
                                          <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.number_format($comprobante['total_opexoneradas'],2, '.', '').'</cbc:TaxableAmount>
                                          <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">0.00</cbc:TaxAmount>
                                          <cac:TaxCategory>
                                             <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                                             <cac:TaxScheme>
                                                <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9997</cbc:ID>
                                                <cbc:Name>EXO</cbc:Name>
                                                <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                                             </cac:TaxScheme>
                                          </cac:TaxCategory>
                                       </cac:TaxSubtotal>';
                                    }

                                    if($comprobante['total_opinafectas']>0){
                                       $xml.='<cac:TaxSubtotal>
                                          <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.number_format($comprobante['total_opinafectas'],2, '.', '').'</cbc:TaxableAmount>
                                          <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">0.00</cbc:TaxAmount>
                                          <cac:TaxCategory>
                                             <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                                             <cac:TaxScheme>
                                                <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9998</cbc:ID>
                                                <cbc:Name>INA</cbc:Name>
                                                <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                                             </cac:TaxScheme>
                                          </cac:TaxCategory>
                                       </cac:TaxSubtotal>';
                                    }

                                 $xml.='</cac:TaxTotal>
                                 <cac:LegalMonetaryTotal>
                                    <cbc:PayableAmount currencyID="'.$comprobante['moneda'].'">'.number_format($comprobante['total'],2, '.', '').'</cbc:PayableAmount>
                                 </cac:LegalMonetaryTotal>';
                                 
                                 foreach($detalle as $k=>$v){

                                    $xml.='<cac:CreditNoteLine>
                                       <cbc:ID>'.$v['item'].'</cbc:ID>
                                       <cbc:CreditedQuantity unitCode="'.$v['unidad'].'">'.$v['cantidad'].'</cbc:CreditedQuantity>
                                       <cbc:LineExtensionAmount currencyID="'.$comprobante['moneda'].'">'.number_format($v['valor_total'],2, '.', '').'</cbc:LineExtensionAmount>
                                       <cac:PricingReference>
                                          <cac:AlternativeConditionPrice>
                                             <cbc:PriceAmount currencyID="'.$comprobante['moneda'].'">'.number_format($v['precio_unitario'],2, '.', '').'</cbc:PriceAmount>
                                             <cbc:PriceTypeCode>'.$v['tipo_precio'].'</cbc:PriceTypeCode>
                                          </cac:AlternativeConditionPrice>
                                       </cac:PricingReference>
                                       <cac:TaxTotal>
                                          <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.number_format($v['igv'],2, '.', '').'</cbc:TaxAmount>
                                          <cac:TaxSubtotal>
                                             <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.number_format($v['valor_total'],2, '.', '').'</cbc:TaxableAmount>
                                             <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.number_format($v['igv'],2, '.', '').'</cbc:TaxAmount>
                                             <cac:TaxCategory>
                                                <cbc:Percent>'.$v['porcentaje_igv'].'</cbc:Percent>
                                                <cbc:TaxExemptionReasonCode>'.$v['codigo_afectacion_alt'].'</cbc:TaxExemptionReasonCode>
                                                <cac:TaxScheme>
                                                   <cbc:ID>'.$v['codigo_afectacion'].'</cbc:ID>
                                                   <cbc:Name>'.$v['nombre_afectacion'].'</cbc:Name>
                                                   <cbc:TaxTypeCode>'.$v['tipo_afectacion'].'</cbc:TaxTypeCode>
                                                </cac:TaxScheme>
                                             </cac:TaxCategory>
                                          </cac:TaxSubtotal>
                                       </cac:TaxTotal>
                                       <cac:Item>
                                          <cbc:Description><![CDATA['.$v['descripcion'].']]></cbc:Description>
                                          <cac:SellersItemIdentification>
                                             <cbc:ID>'.$v['codigo'].'</cbc:ID>
                                          </cac:SellersItemIdentification>
                                       </cac:Item>
                                       <cac:Price>
                                          <cbc:PriceAmount currencyID="'.$comprobante['moneda'].'">'.number_format($v['valor_unitario'],5, '.', '').'</cbc:PriceAmount>
                                       </cac:Price>
                                    </cac:CreditNoteLine>';
                                 }
                                 $xml.='</CreditNote>';

                                    $doc->loadXML($xml);
                                    $doc->save($nombrexml.'.XML'); 
      }

      ////////////////////////////////NOTA DE DEBITO///////////////////////////////////////////
      function CrearXMLNotaDebito($nombrexml, $emisor, $cliente, $comprobante, $detalle)
      {

                     $doc = new DOMDocument();
                     $doc->formatOutput = FALSE;
                     $doc->preserveWhiteSpace = TRUE;
                     $doc->encoding = 'utf-8';    

                     $xml = '<?xml version="1.0" encoding="UTF-8"?>
                              <DebitNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:DebitNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
                                 <ext:UBLExtensions>
                                    <ext:UBLExtension>
                                       <ext:ExtensionContent />
                                    </ext:UBLExtension>
                                 </ext:UBLExtensions>
                                 <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
                                 <cbc:CustomizationID>2.0</cbc:CustomizationID>
                                 <cbc:ID>'.$comprobante['serie'].'-'.$comprobante['correlativo'].'</cbc:ID>
                                 <cbc:IssueDate>'.$comprobante['fecha_emision'].'</cbc:IssueDate>
                                 <cbc:IssueTime>00:00:03</cbc:IssueTime>
                                 <cbc:Note languageLocaleID="1000"><![CDATA['.$comprobante['total_texto'].']]></cbc:Note>
                                 <cbc:DocumentCurrencyCode>'.$comprobante['moneda'].'</cbc:DocumentCurrencyCode>
                                 <cac:DiscrepancyResponse>
                                    <cbc:ReferenceID>'.$comprobante['serie_ref'].'-'.$comprobante['correlativo_ref'].'</cbc:ReferenceID>
                                    <cbc:ResponseCode>'.$comprobante['codmotivo'].'</cbc:ResponseCode>
                                    <cbc:Description>'.$comprobante['descripcion'].'</cbc:Description>
                                 </cac:DiscrepancyResponse>
                                 <cac:BillingReference>
                                    <cac:InvoiceDocumentReference>
                                       <cbc:ID>'.$comprobante['serie_ref'].'-'.$comprobante['correlativo_ref'].'</cbc:ID>
                                       <cbc:DocumentTypeCode>'.$comprobante['tipodoc_ref'].'</cbc:DocumentTypeCode>
                                    </cac:InvoiceDocumentReference>
                                 </cac:BillingReference>
                                 <cac:Signature>
                                    <cbc:ID>'.$emisor['ruc'].'</cbc:ID>
                                    <cbc:Note><![CDATA['.$emisor['nombre_comercial'].']]></cbc:Note>
                                    <cac:SignatoryParty>
                                       <cac:PartyIdentification>
                                          <cbc:ID>'.$emisor['ruc'].'</cbc:ID>
                                       </cac:PartyIdentification>
                                       <cac:PartyName>
                                          <cbc:Name><![CDATA['.$emisor['razon_social'].']]></cbc:Name>
                                       </cac:PartyName>
                                    </cac:SignatoryParty>
                                    <cac:DigitalSignatureAttachment>
                                       <cac:ExternalReference>
                                          <cbc:URI>#SIGN-EMPRESA</cbc:URI>
                                       </cac:ExternalReference>
                                    </cac:DigitalSignatureAttachment>
                                 </cac:Signature>
                                 <cac:AccountingSupplierParty>
                                    <cac:Party>
                                       <cac:PartyIdentification>
                                          <cbc:ID schemeID="'.$emisor['tipodoc'].'">'.$emisor['ruc'].'</cbc:ID>
                                       </cac:PartyIdentification>
                                       <cac:PartyName>
                                          <cbc:Name><![CDATA['.$emisor['nombre_comercial'].']]></cbc:Name>
                                       </cac:PartyName>
                                       <cac:PartyLegalEntity>
                                          <cbc:RegistrationName><![CDATA['.$emisor['razon_social'].']]></cbc:RegistrationName>
                                          <cac:RegistrationAddress>
                                             <cbc:ID>'.$emisor['ubigeo'].'</cbc:ID>
                                             <cbc:AddressTypeCode>0000</cbc:AddressTypeCode>
                                             <cbc:CitySubdivisionName>NONE</cbc:CitySubdivisionName>
                                             <cbc:CityName>'.$emisor['provincia'].'</cbc:CityName>
                                             <cbc:CountrySubentity>'.$emisor['departamento'].'</cbc:CountrySubentity>
                                             <cbc:District>'.$emisor['distrito'].'</cbc:District>
                                             <cac:AddressLine>
                                                <cbc:Line><![CDATA['.$emisor['direccion'].']]></cbc:Line>
                                             </cac:AddressLine>
                                             <cac:Country>
                                                <cbc:IdentificationCode>'.$emisor['pais'].'</cbc:IdentificationCode>
                                             </cac:Country>
                                          </cac:RegistrationAddress>
                                       </cac:PartyLegalEntity>
                                    </cac:Party>
                                 </cac:AccountingSupplierParty>
                                    <cac:AccountingCustomerParty>
                                    <cac:Party>
                                       <cac:PartyIdentification>
                                          <cbc:ID schemeID="'.$cliente['tipodoc'].'">'.$cliente['ruc'].'</cbc:ID>
                                       </cac:PartyIdentification>
                                       <cac:PartyLegalEntity>
                                          <cbc:RegistrationName><![CDATA['.$cliente['razon_social'].']]></cbc:RegistrationName>
                                          <cac:RegistrationAddress>
                                             <cac:AddressLine>
                                                <cbc:Line><![CDATA['.$cliente['direccion'].']]></cbc:Line>
                                             </cac:AddressLine>
                                             <cac:Country>
                                                <cbc:IdentificationCode>'.$cliente['pais'].'</cbc:IdentificationCode>
                                             </cac:Country>
                                          </cac:RegistrationAddress>
                                       </cac:PartyLegalEntity>
                                    </cac:Party>
                                 </cac:AccountingCustomerParty>
                                 <cac:TaxTotal>
                                    <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['igv'].'</cbc:TaxAmount>
                                    <cac:TaxSubtotal>
                                       <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['total_opgravadas'].'</cbc:TaxableAmount>
                                       <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['igv'].'</cbc:TaxAmount>
                                       <cac:TaxCategory>
                                          <cac:TaxScheme>
                                             <cbc:ID>1000</cbc:ID>
                                             <cbc:Name>IGV</cbc:Name>
                                             <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                                          </cac:TaxScheme>
                                       </cac:TaxCategory>
                                    </cac:TaxSubtotal>
                                 </cac:TaxTotal>
                                 <cac:RequestedMonetaryTotal>
                                    <cbc:PayableAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['total'].'</cbc:PayableAmount>
                                 </cac:RequestedMonetaryTotal>';
                                 
                                 foreach($detalle as $k=>$v){

                                    $xml.='<cac:DebitNoteLine>
                                       <cbc:ID>'.$v['item'].'</cbc:ID>
                                       <cbc:DebitedQuantity unitCode="'.$v['unidad'].'">'.$v['cantidad'].'</cbc:DebitedQuantity>
                                       <cbc:LineExtensionAmount currencyID="'.$comprobante['moneda'].'">'.$v['valor_total'].'</cbc:LineExtensionAmount>
                                       <cac:PricingReference>
                                          <cac:AlternativeConditionPrice>
                                             <cbc:PriceAmount currencyID="'.$comprobante['moneda'].'">'.$v['precio_unitario'].'</cbc:PriceAmount>
                                             <cbc:PriceTypeCode>'.$v['tipo_precio'].'</cbc:PriceTypeCode>
                                          </cac:AlternativeConditionPrice>
                                       </cac:PricingReference>
                                       <cac:TaxTotal>
                                          <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.$v['igv'].'</cbc:TaxAmount>
                                          <cac:TaxSubtotal>
                                             <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.$v['valor_total'].'</cbc:TaxableAmount>
                                             <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.$v['igv'].'</cbc:TaxAmount>
                                             <cac:TaxCategory>
                                                <cbc:Percent>'.$v['porcentaje_igv'].'</cbc:Percent>
                                                <cbc:TaxExemptionReasonCode>10</cbc:TaxExemptionReasonCode>
                                                <cac:TaxScheme>
                                                   <cbc:ID>'.$v['codigo_afectacion'].'</cbc:ID>
                                                   <cbc:Name>'.$v['nombre_afectacion'].'</cbc:Name>
                                                   <cbc:TaxTypeCode>'.$v['tipo_afectacion'].'</cbc:TaxTypeCode>
                                                </cac:TaxScheme>
                                             </cac:TaxCategory>
                                          </cac:TaxSubtotal>
                                       </cac:TaxTotal>
                                       <cac:Item>
                                          <cbc:Description><![CDATA['.$v['descripcion'].']]></cbc:Description>
                                          <cac:SellersItemIdentification>
                                             <cbc:ID>'.$v['codigo'].'</cbc:ID>
                                          </cac:SellersItemIdentification>
                                       </cac:Item>
                                       <cac:Price>
                                          <cbc:PriceAmount currencyID="'.$comprobante['moneda'].'">'.$v['valor_unitario'].'</cbc:PriceAmount>
                                       </cac:Price>
                                    </cac:DebitNoteLine>';
                                 
                                 }

                                    $xml.='</DebitNote>';

                                    $doc->loadXML($xml);
                                    $doc->save($nombrexml.'.XML'); 
      }

      ///////////////////////////////RESUMEN DE BOLETAS//////////////////////////////////////
      function CrearXMLResumenDocumentos($emisor, $cabecera, $detalle, $nombrexml)
      {

           /* $doc = new DOMDocument();
            $doc->formatOutput = FALSE;
            $doc->preserveWhiteSpace = TRUE;
            $doc->encoding = 'utf-8';   */
            
            $doc = new DOMDocument();
            $doc->formatOutput = FALSE;
            $doc->preserveWhiteSpace = TRUE;
            $doc->encoding = 'ISO-8859-1';

            $xml = '<?xml version="1.0" encoding="iso-8859-1" standalone="no"?>
    <SummaryDocuments 
	xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1" 
	xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" 
	xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" 
	xmlns:ds="http://www.w3.org/2000/09/xmldsig#" 
	xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" 
	xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1"
	xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" 
	xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2">
	<ext:UBLExtensions>
		<ext:UBLExtension>
                        <ext:ExtensionContent>
			</ext:ExtensionContent>
		</ext:UBLExtension>
	</ext:UBLExtensions>
	<cbc:UBLVersionID>2.0</cbc:UBLVersionID>
	<cbc:CustomizationID>1.1</cbc:CustomizationID>
              <cbc:ID>'.$cabecera['tipodocr'].'-'.$cabecera['serier'].'-'.$cabecera['correlativor'].'</cbc:ID>
              <cbc:ReferenceDate>'.$cabecera['fecha_emision'].'</cbc:ReferenceDate>
              <cbc:IssueDate>'.$cabecera['fecha_envio'].'</cbc:IssueDate>
              <cac:Signature>
                  <cbc:ID>'.$cabecera['tipodocr'].'-'.$cabecera['serier'].'-'.$cabecera['correlativor'].'</cbc:ID>
                  <cac:SignatoryParty>
                      <cac:PartyIdentification>
                          <cbc:ID>'.$emisor['ruc'].'</cbc:ID>
                      </cac:PartyIdentification>
                      <cac:PartyName>
                          <cbc:Name><![CDATA['.$emisor['razon_social'].']]></cbc:Name>
                      </cac:PartyName>
                  </cac:SignatoryParty>
                  <cac:DigitalSignatureAttachment>
                      <cac:ExternalReference>
                          <cbc:URI>'.$cabecera['tipodocr'].'-'.$cabecera['serier'].'-'.$cabecera['correlativor'].'</cbc:URI>
                      </cac:ExternalReference>
                  </cac:DigitalSignatureAttachment>
              </cac:Signature>
              <cac:AccountingSupplierParty>
                  <cbc:CustomerAssignedAccountID>'.$emisor['ruc'].'</cbc:CustomerAssignedAccountID>
                  <cbc:AdditionalAccountID>'.$emisor['tipodoc'].'</cbc:AdditionalAccountID>
                  <cac:Party>
                      <cac:PartyLegalEntity>
                          <cbc:RegistrationName><![CDATA['.$emisor['razon_social'].']]></cbc:RegistrationName>
                      </cac:PartyLegalEntity>
                  </cac:Party>
              </cac:AccountingSupplierParty>';

              foreach ($detalle as $k => $v) {
               
                
                 $xml.='<sac:SummaryDocumentsLine>
                     <cbc:LineID>'.$v['items'].'</cbc:LineID>
                     <cbc:DocumentTypeCode>'.$v['tipodoc'].'</cbc:DocumentTypeCode>
                     <cbc:ID>'.$v['serie'].'-'.$v['correlativo'].'</cbc:ID>
                     <cac:AccountingCustomerParty>

<cbc:CustomerAssignedAccountID>'.$v['docpersona'].'</cbc:CustomerAssignedAccountID>

<cbc:AdditionalAccountID>'.$v['tipo_doc'].'</cbc:AdditionalAccountID>
</cac:AccountingCustomerParty>';

if($v['tipodoc'] == '07')
{
   $xml .='<cac:BillingReference>
<cac:InvoiceDocumentReference>
<!-- Serie y numero de comprobante modificado -->
<cbc:ID>'.$v['docmodifica'].'</cbc:ID>
<!-- Tipo de comprobante modificado - Catalogo No. 01 -->
<cbc:DocumentTypeCode>'.$v['tipdocmodifica'].'</cbc:DocumentTypeCode>
</cac:InvoiceDocumentReference>
</cac:BillingReference>';
}

                     $xml.='<cac:Status>
                        <cbc:ConditionCode>'.$v['condicion'].'</cbc:ConditionCode>
                     </cac:Status>                
                     <sac:TotalAmount currencyID="'.$v['moneda'].'">'.$v['importe_total'].'</sac:TotalAmount><sac:BillingPayment>
                               <cbc:PaidAmount currencyID="'.$v['moneda'].'">'.$v['valor_total'].'</cbc:PaidAmount>
                               <cbc:InstructionID>'.$v['tipo_total'].'</cbc:InstructionID>
                           </sac:BillingPayment><cac:TaxTotal>
                         <cbc:TaxAmount currencyID="'.$v['moneda'].'">'.$v['igv_total'].'</cbc:TaxAmount>';
                         
                         if($v['codigo_afectacion']!='1000'){
                         $xml.='<cac:TaxSubtotal>
                             <cbc:TaxAmount currencyID="'.$v['moneda'].'">'.$v['igv_total'].'</cbc:TaxAmount>
                             <cac:TaxCategory>
                                 <cac:TaxScheme>
                                     <cbc:ID>'.$v['codigo_afectacion'].'</cbc:ID>
                                     <cbc:Name>'.$v['nombre_afectacion'].'</cbc:Name>
                                     <cbc:TaxTypeCode>'.$v['tipo_afectacion'].'</cbc:TaxTypeCode>
                                 </cac:TaxScheme>
                             </cac:TaxCategory>
                         </cac:TaxSubtotal>';
                        }

                         $xml.='<cac:TaxSubtotal>
                             <cbc:TaxAmount currencyID="'.$v['moneda'].'">'.$v['igv_total'].'</cbc:TaxAmount>
                             <cac:TaxCategory>
                                 <cac:TaxScheme>
                                     <cbc:ID>1000</cbc:ID>
                                     <cbc:Name>IGV</cbc:Name>
                                     <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                                 </cac:TaxScheme>
                             </cac:TaxCategory>
                         </cac:TaxSubtotal>';

                     $xml.='</cac:TaxTotal>
                 </sac:SummaryDocumentsLine>';

                
              }
              
          $xml.='</SummaryDocuments>';

            $doc->loadXML($xml);
            $doc->save($nombrexml.'.XML'); 
      }

      ////////////////////baja cpe////////////////////////////
      function CrearXmlBajaDocumentos($emisor, $cabecera, $detalle, $nombrexml)
      {

            $doc = new DOMDocument();
            $doc->formatOutput = FALSE;
            $doc->preserveWhiteSpace = TRUE;
            $doc->encoding = 'utf-8';   

             $xml = '<?xml version="1.0" encoding="UTF-8"?>
<VoidedDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:VoidedDocuments-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        <ext:UBLExtensions>
            <ext:UBLExtension>
                <ext:ExtensionContent />
            </ext:UBLExtension>
        </ext:UBLExtensions>
        <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
        <cbc:CustomizationID>1.0</cbc:CustomizationID>
        <cbc:ID>'.$cabecera['tipodocr'].'-'.$cabecera['serier'].'-'.$cabecera['correlativor'].'</cbc:ID>
        <cbc:ReferenceDate>'.$cabecera['fecha_emision'].'</cbc:ReferenceDate>
        <cbc:IssueDate>'.$cabecera['fecha_envio'].'</cbc:IssueDate>
        <cac:Signature>
            <cbc:ID>'.$cabecera['tipodocr'].'-'.$cabecera['serier'].'-'.$cabecera['correlativor'].'</cbc:ID>
            <cac:SignatoryParty>
                <cac:PartyIdentification>
                    <cbc:ID>'.$emisor['ruc'].'</cbc:ID>
                </cac:PartyIdentification>
                <cac:PartyName>
                    <cbc:Name><![CDATA['.$emisor['razon_social'].']]></cbc:Name>
                </cac:PartyName>
            </cac:SignatoryParty>
            <cac:DigitalSignatureAttachment>
                <cac:ExternalReference>
                    <cbc:URI>'.$cabecera['tipodocr'].'-'.$cabecera['serier'].'-'.$cabecera['correlativor'].'</cbc:URI>
                </cac:ExternalReference>
            </cac:DigitalSignatureAttachment>
        </cac:Signature>
        <cac:AccountingSupplierParty>
            <cbc:CustomerAssignedAccountID>'.$emisor['ruc'].'</cbc:CustomerAssignedAccountID>
            <cbc:AdditionalAccountID>'.$emisor['tipodoc'].'</cbc:AdditionalAccountID>
            <cac:Party>
                <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA['.$emisor['razon_social'].']]></cbc:RegistrationName>
                </cac:PartyLegalEntity>
            </cac:Party>
        </cac:AccountingSupplierParty>';

        foreach ($detalle as $k => $v) {
           $xml.='<sac:VoidedDocumentsLine>
               <cbc:LineID>'.$v['items'].'</cbc:LineID>
               <cbc:DocumentTypeCode>'.$v['tipodoc'].'</cbc:DocumentTypeCode>
               <sac:DocumentSerialID>'.$v['serie'].'</sac:DocumentSerialID>
               <sac:DocumentNumberID>'.$v['correlativo'].'</sac:DocumentNumberID>
               <sac:VoidReasonDescription><![CDATA['.$v['motivo'].']]></sac:VoidReasonDescription>
           </sac:VoidedDocumentsLine>';
        }
        
    $xml.='</VoidedDocuments>';

            $doc->loadXML($xml);
            $doc->save($nombrexml.'.XML'); 
      } 

}
?>