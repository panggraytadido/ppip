<?php
    Yii::import('application.extensions.fpdf17.*');
    include("fpdf.php");
?>

<?php 
class FPDF_AutoWrapTable extends FPDF{
    
 // Load data = Pecah Array 
 function LoadData($apit){
  $data = array();
  if (is_array($apit)) {
  foreach($apit as $coba)
   $data[] = explode('|',$coba);
  }
  return $data;
 }
 
 // Fungsi Membuat Tabel
 function FancyTable($header, $data){
     
	//Untuk Membuat Tanggal cetak
	$timezone = "Asia/Jakarta";
        if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
        $datedisposisi= date("d-m-Y H:i:s");

 
	//header
	$this->SetFont("", "I", 9);
	$this->MultiCell(0, 0,$datedisposisi, 0,'R');
	$this->SetFont("", "B", 12);
	//$this->Image('img/logo.png',10,5,0);
	$this->MultiCell(42, 0, 'CINDY GROUP', 0, 0);
	$this->MultiCell(0, 5, '',0);
	$this->Cell(0, 1, " ", "B");
	$this->Ln(5);
	$this->SetFont("", "B", 9);
	$this->Cell(0, 5, 'RINCIAN HARGA BARANG', 0, 0,'C');
	$this->Ln(10);
	 
        // Colors, line width and bold font
        $this->SetFillColor(255,255,255);
        $this->SetTextColor(0,0,0);
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(.2);
        $this->SetFont('','B');
        // Lebar Header Sesuaikan Jumlahnya dengan Jumlah Field Tabel Database
        $w = array(7,25, 45, 45, 25, 25, 25);
        for($i=0;$i<count($header);$i++)
         $this->Cell($w[$i],6,$header[$i],1,0,'C',true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(225,225,225);
        $this->SetTextColor(0);
        $this->SetLineWidth(.2);
        $this->SetFont('');
        
        // Data
        $fill = false;
        foreach($data as $row)
        {
            // Field Dari Database Yang Ingin ditampilkan
            // $this->Cell($w[Ubah Ini],6,$row[Ubah Ini],'LR',0,'L',$fill);
            $this->Cell($w[0],6,$row[0],'LR',0,'C',$fill);
            $this->Cell($w[1],6,$row[1],'LR',0,'C',$fill); 
            $this->Cell($w[2],6,$row[2],'LR',0,'C',$fill);
            $this->Cell($w[3],6,$row[3],'LR',0,'C',$fill);
            $this->Cell($w[4],6,$row[4],'LR',0,'C',$fill); 
            $this->Cell($w[5],6,$row[5],'LR',0,'C',$fill);
            $this->Cell($w[6],6,$row[6],'LR',0,'C',$fill);
            $this->Ln();
            $fill = !$fill;
         }
        // Closing line
                         
  
 }
 
  //Fungsi Untuk Membuat Footer
	function Footer()
	{
		//Position at 3 cm from bottom
		$this->SetY(-10);
		//Arial italic 8
		$this->SetFont('Arial','I',8);
		//garis bottom
		$this->Cell(0, 1, " ", "B");
		$this->Ln(10);
		//Page number
		$this->Cell(0,-10,'Hal.ke : '.$this->PageNo(),0,0,'R');
	}
 }

        $pdf = new FPDF_AutoWrapTable();
        // Pendefinisian Header Tabel 
        $header = array('No','Divisi','Supplier','Nama Barang','Harga Modal','Harga Grosir','Harga Eceran');
        
		$data = Hargabarang::model()->reportHargaBarang();
        
		
        //load data
        if(count($data)>0)
        {
            $no=1;
            for($i=0;$i<count($data);$i++)
            {
                // Simpan Kedalam Array dengan Batasan |
                @$d[] .=  $no++."|".$data[$i]['divisi']."|".$data[$i]['supplier']."|".$data[$i]['barang']."|".number_format( $data[$i]['hargamodal'] , 0 , '' , '.' )."|" . number_format( $data[$i]['hargagrosir'] , 0 , '' , '.' )."|".number_format( $data[$i]['hargaeceran'] , 0 , '' , '.' );  
            }



            // Cetak Laporan
            $data = $pdf->LoadData($d);
            $pdf->SetFont('Arial','',9);
            $pdf->AddPage();
            $pdf->FancyTable($header,$data);
            $pdf->Output();
        }
        else
        {
            echo 'Tidak ada data';
        }
        
        
         
 
?>