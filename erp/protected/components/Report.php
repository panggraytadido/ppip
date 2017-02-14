<?php

require_once("http://localhost:8080/JavaBridge/java/Java.inc");	

class Report extends CApplicationComponent
{		
		 
	public function Export($fileName="",$fileExport="",$param=array(),$paramName=array(),$format="")
	{									
		
		$system = new JavaClass('java.lang.System');
		$class = new JavaClass("java.lang.Class");
		$class->forName("org.postgresql.Driver");
		$driverManager = new JavaClass("java.sql.DriverManager");
		$conn = $driverManager->getConnection("jdbc:postgresql://localhost:5432/cindygroup?user=postgres&password=dido");
			
		//compilation
		$compileManager = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
		$viewer = new JavaClass("net.sf.jasperreports.view.JasperViewer");
		$report = $compileManager->compileReport("C:/xampp/htdocs/cindygroupserver/erp/report/".$fileName);
		
		// fill
		$fillManager = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
		$params = new Java("java.util.HashMap");
			
		$paramNames = array_keys($paramName);			
		for($i=0;$i<=count($param)-1;$i++)
		{			
			$params->put($paramNames[$i],$param[$i]);						
		}
	
		$emptyDataSource = new Java("net.sf.jasperreports.engine.JREmptyDataSource");	
		$runmanager = new Java("net.sf.jasperreports.engine.JasperRunManager");	
		$jasperPrint = $fillManager->fillReport($report, $params, $conn);
	
		$dt = new DateTime();	
		if($format=="pdf")
		{
			$file= $fileExport."-".$dt->format('Y-m-d').".pdf";
							
			$exporter= new Java("net.sf.jasperreports.engine.export.JRPdfExporter");
	
			$exporter->setParameter(java("net.sf.jasperreports.engine.JRExporterParameter")->JASPER_PRINT,$jasperPrint);
			$outputPath = realpath(".")."\\"."export.pdf";
			$exporter->setParameter(java("net.sf.jasperreports.engine.JRExporterParameter")->OUTPUT_FILE_NAME,$outputPath);
								
	
			//header("Content-type: application/pdf");
			//header("Content-Disposition: attachment; filename=$file");
			header('Content-type: application/pdf');
			header('Content-Disposition: inline; filename="' . $fileName . '"');
			///header('Content-Transfer-Encoding: binary');
			//header('Accept-Ranges: bytes');
			//@readfile($file); 
	
			$exporter->exportReport();

			readfile($outputPath);
			unlink($outputPath);
		}
		
		if($format=="pdfhide")
		{
			$file= $fileExport."-".$dt->format('Y-m-d').".pdf";

			if(file_exists($file))
			{
				echo "";
			}
			else 
			{	
				$exporter= new Java("net.sf.jasperreports.engine.export.JRPdfExporter");
				
				$exporter->setParameter(java("net.sf.jasperreports.engine.JRExporterParameter")->JASPER_PRINT,$jasperPrint);
				$outputPath = realpath(".")."\\".$file;
				$exporter->setParameter(java("net.sf.jasperreports.engine.JRExporterParameter")->OUTPUT_FILE_NAME,$outputPath);
					
				$exporter->exportReport();
			}
							
			//readfile($outputPath);
			//unlink($outputPath);
		}
			
		if($format=="xls")
		{
			$file= $fileExport."-".$dt->format('Y-m-d').".xls";
				
			$outputPath = realpath(".")."\\"."export.xls";
			$exporter = new java("net.sf.jasperreports.engine.export.JRXlsExporter");
			$exporter->setParameter(java("net.sf.jasperreports.engine.JRExporterParameter")->JASPER_PRINT,$jasperPrint);
			$exporter->setParameter(java("net.sf.jasperreports.engine.JRExporterParameter")->OUTPUT_FILE_NAME,$outputPath);
	
			header("Content-type: application/xls");
			header("Content-Disposition: attachment; filename=$file");
	
			readfile($outputPath);	
			$exporter->exportReport();
		}
	}
	
	
	
}
