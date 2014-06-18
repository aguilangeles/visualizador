<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Xml
{
	public function TrazaXML($idcPath)
	{
		$XmlTraza = new SimpleXMLElement("<XmlTraza></XmlTraza>");		

        $XmlTraza->addChild('IdIDC',$idcPath);
        $XmlTraza->addChild('CreationDate',date('Y-m-d h:i:s'));
        $XmlTraza->addChild('Status','Web');

        $log = $XmlTraza->addChild('Log');
        $log->addChild('TimeStamp', date('Y-m-d h:i:s'));
        $log->addChild('Message', 'Generacion XMLs');

        return self::FormatXml($XmlTraza);
	}

	public function MetaXML($idcPath, $docType, $fromPath)
	{
		$XmlMeta = new SimpleXMLElement("<XmlMetas></XmlMetas>");		

		$fi = new FilesystemIterator($fromPath, FilesystemIterator::SKIP_DOTS);

		foreach ($fi as $fileinfo) {
			if($fileinfo->isFile())
			{
				$meta = $XmlMeta->addChild('Meta');

				$meta->addChild('IdIDC',$idcPath);
	        	$meta->addChild('CreationDate',date('Y-m-d h:i:s'));
	        	$meta->addChild('Status','Invalid');

				$image = $meta->addChild('Image');
				$campo = $image->addChild('Campo');
				$campo->addAttribute('Name','Id Imagen');
				$campo->addAttribute('Value',$fileinfo->getFilename());

			    foreach ($docType->OCRs as $ocr) {
					$campo = $image->addChild('Campo');
					$campo->addAttribute('Name',$ocr->ocr_meta_desc);
					$campo->addAttribute('Value','');
				}
			}
		}

        return self::FormatXml($XmlMeta);
	}

	public function MapeoXML($idcPath, $docType, $fromPath, $id)
	{
		$XmlMapeo = new SimpleXMLElement("<XmlMapeo></XmlMapeo>");		

        $XmlMapeo->addChild('IdIDC',$idcPath);
        $XmlMapeo->addChild('CreationDate',date('Y-m-d h:i:s'));
        $XmlMapeo->addChild('Status','Web');

		$fi = new ArrayObject(iterator_to_array(new FilesystemIterator($fromPath, FilesystemIterator::SKIP_DOTS)));
		$fi->natsort(); //sort directory 

		$cont = 1;
		foreach ($fi as $fileinfo) {
			if($fileinfo->isFile())
			{
				$MapeoList = $XmlMapeo->addChild('MapeoList');
				$MapeoList->addChild('FileName',$fileinfo->getFilename());
				$MapeoList->addChild('IsCarat','False');
				$MapeoList->addChild('IdPapel',$cont);
				$MapeoList->addChild('Order',$cont);
				$MapeoList->addChild('Deleted','false');
				$MapeoList->addChild('C1', ($docType->doc_type_level == 1) ? $id: "");
				$MapeoList->addChild('C2', ($docType->doc_type_level == 2) ? $id: "");
				$MapeoList->addChild('C3', ($docType->doc_type_level == 3) ? $id: "");
				$MapeoList->addChild('C4', ($docType->doc_type_level == 4) ? $id: "");
				$MapeoList->addChild('Size',$fileinfo->getSize());
				$MapeoList->addChild('Face','Anverso');

	    		$cont++;
    		}
		}

        return self::FormatXml($XmlMapeo);
	}

	public function CaratXML($idcPath, $docType, $subType, $metas, $id)
	{
		$XmlCarat = new SimpleXMLElement("<XmlCarat></XmlCarat>");		

		$carat = $XmlCarat->addChild('Caratula');
        $carat->addChild('IdIDC',$idcPath);
        $carat->addChild('CreationDate',date('Y-m-d h:i:s'));
        $carat->addChild('Status','WEB');
        $carat->addChild('DocType', $docType->doc_type_desc);
        $carat->addChild('CrtType', 'Agregado');
        $carat->addChild('Secuencial', 1);
        $carat->addChild('SubTypeCode', $subType);
        $carat->addChild('Level', 'c' . $docType->doc_type_level);
        $carat->addChild('user', Yii::app()->user->name);

        $metadato = $carat->addChild('Metadato');
        $campo = $metadato->addChild('Campo');
		$campo->addAttribute('Name','Id');
		$campo->addAttribute('Value', $id);
        
        foreach ($metas as $meta) {
        	if($meta != "")
        	{
	        	$meta_array = explode(':', $meta);
	        	$valor = "";

	        	if(isset($meta_array[1]))
	        		$valor = $meta_array[1];

				$campo = $metadato->addChild('Campo');
				$campo->addAttribute('Name',$meta_array[0]);
				$campo->addAttribute('Value',$valor);
			}
        }

        return self::FormatXml($XmlCarat);
	}

	public function CapturaXML($idcPath)
	{
		$XmlCaptura = new SimpleXMLElement("<XmlCaptura></XmlCaptura>");		

        $XmlCaptura->addChild('IdIDC',$idcPath);
        $XmlCaptura->addChild('CreationDate',date('Y-m-d h:i:s'));
        $XmlCaptura->addChild('Status','Web');

        return self::FormatXml($XmlCaptura);
	}

	public function IdcXml($idcPath, $imgQty)
	{
		$XmlIDCs = new SimpleXMLElement("<XmlIDCs></XmlIDCs>");		

	    // $XmlIDCs = $xml->addChild('XmlIDCs');
        $XmlIDCs->addChild('Id',$idcPath);
        $XmlIDCs->addChild('CreationDate',date('Y-m-d h:i:s'));
        $XmlIDCs->addChild('ImageQty', $imgQty);
        $XmlIDCs->addChild('DeletedQty', 0);
        $XmlIDCs->addChild('Status','Web');

        return self::FormatXml($XmlIDCs);
	}

	public function FormatXml($xml)
	{
    	$dom = new DOMDocument("1.0");

       	$dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        $formatted_xml = $dom->saveXML();

        return simplexml_load_string ($formatted_xml);
	}
}