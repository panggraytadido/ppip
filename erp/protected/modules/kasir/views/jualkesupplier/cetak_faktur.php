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
	//header
	$this->SetFont("", "B", 24);
	//$this->Image('../img/logo.png',10,5,0);
	$this->MultiCell(100, 0, 'CINDY GROUP', 0, 'L');
        $this->Ln();
	$this->SetFont("", "", 10);
	$this->Cell(140, 15, 'PENYALUR IKAN CUE, IKAN BASAH, FROZEN FISH DAN PINDANG HYGIENIS', 0, 0,'L');
	$this->Cell(0, 10, 'Kepada  Yth.', 0, 0,'C');
	$this->Ln(12);
	$this->Cell(120, 0, 'Jl. Tulang Kuning No. 04 RT.02 RW.05', 0, 0,'L');
        	
        $this->Cell(90, -25, 'Parung, '.$_GET['tanggal'].'', 0, 0,'C');
        $this->Cell(-90, 0, ''.  Supplier::model()->findByPk($_GET['supplierpembeliid'])->namaperusahaan.'', 0, 0,'C');        
        
	$this->Ln(5);
	$this->Cell(30, 0, 'Tlp. Solihin', 0, 0,'L');
	$this->Cell(30, 0, ': 0815 1419 9203', 0, 0,'L');
	$this->Cell(90, 0, '-  0877 7586 5113', 0, 0,'L');
	$this->Cell(0, 0, 'di', 0, 0,'C');
	$this->Ln(5);
	$this->Cell(30, 0, 'Kantor', 0, 0,'L');
	$this->Cell(60, 0, ': (0251) 8614839', 0, 0,'L');
	
	$this->Cell(150, 0, ''.Supplier::model()->findByPk($_GET['supplierpembeliid'])->alamat.'', 0, 0,'C');
        
 
	$this->Ln(5);
	 
        // Colors, line width and bold font
        $this->SetFillColor(255,255,255);
        $this->SetTextColor(0,0,0);
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(.1);
        $this->SetFont('Courier','B');
        
        // Lebar Header Sesuaikan Jumlahnya dengan Jumlah Field Tabel Database
        $w = array(10, 70, 20, 30, 30, 30);
        for($i=0;$i<count($header);$i++)
         $this->Cell($w[$i],6,$header[$i],1,0,'C',true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(255,255,255);
        $this->SetTextColor(0);
        $this->SetLineWidth(.1);
        $this->SetFont('Courier','','9');
        
        // Data
        $fill = false;
        foreach($data as $row)
        {
            // Field Dari Database Yang Ingin ditampilkan
            // $this->Cell($w[Ubah Ini],6,$row[Ubah Ini],'LR',0,'L',$fill);
            $this->Cell($w[0],4,$row[0],'LR',0,'C',$fill);
            $this->Cell($w[1],4,$row[1],'LR',0,'L',$fill); 
            $this->Cell($w[2],4,$row[2],'LR',0,'C',$fill);
            $this->Cell($w[3],4,$row[3],'LR',0,'R',$fill);
            $this->Cell($w[4],4,$row[4],'LR',0,'R',$fill); 
            $this->Cell($w[5],4,$row[5],'LR',0,'R',$fill);
            $this->Ln();
            $fill = !$fill;
         }
         
        // Closing line
        $this->Cell(array_sum($w),0,'','T');
        $this->Ln();

        $this->SetFont('Courier','','10');

        $this->Cell(10, 6, '', 1, 0,'L');
        $this->Cell(70, 6, '', 1, 0,'L');
        $this->Cell(20, 6, '', 1, 0,'C');
        $this->Cell(30, 6, '', 1, 0,'R');
        $this->Cell(30, 6, '', 1, 0,'R');
        $this->Cell(30, 6, '', 1, 1,'R');  


        $this->SetFont('Courier','','10');
        $this->Cell(60, 6, 'Diterima oleh,', 0, 0,'C');
        $this->Cell(70, 6, 'Kasir,', 0, 0,'C');
        $this->Cell(30, 6, 'Total', 1, 0,'L');
  
    		
        $dataku = Jualkesupplier::model()->detailFormFaktur($_GET['supplierpembeliid'],$_GET['tanggal']);
        $hargatotal=array();
  
        for($i=0;$i<count($dataku);$i++)
        { 
           $hargatotal[]=$dataku[$i]['hargatotal'];  
        }
 
        $totalHarga = array_sum($hargatotal);

        $tanggal = $_GET['tanggal'];
       // $noFaktur =Pelanggan::model()->findByPk($_GET['pelangganid'])->kode."/".$_GET['tanggal'];
        $faktur = Faktursupplier::model()->find("supplierpembeliid=".intval($_GET['supplierpembeliid'])." and cast(tanggal as date)='$tanggal' and pembelianke=".$_GET['pembelianke']);

        $this->Cell(0, 6, number_format($totalHarga), 1, 1,'R');
        $this->Cell(60, 6, '', 0, 0,'C');    
        $this->Cell(70, 6, '', 0, 0,'C');
        $this->Cell(30, 6, 'Diskon', 1, 0,'L');   
        $this->Cell(0, 6, ''.  number_format($faktur->diskon).'', 1, 1,'R');
        $this->Cell(60, 6, ''.  Supplier::model()->findByPk($_GET['supplierpembeliid'])->namaperusahaan.'', 0, 0,'C');
        $this->Cell(70, 6, ''.'Bpk. Sholihin'.'', 0, 0,'C');
        $this->Cell(30, 6, 'Bayar', 1, 0,'L');
        $this->Cell(0, 6, ''.number_format($faktur->bayar).'', 1, 1,'R');
                
    }
 }
 
  $pdf = new FPDF_AutoWrapTable();
  $header = array('No.','Barang','Box','Netto','Harga Satuan','Harga Total');
  $no=1;
  
    // Load Data dari Database
   $dataku = Jualkesupplier::model()->detailFormFaktur($_GET['supplierpembeliid'],$_GET['tanggal']);
   $hargatotal=array();
   for($i=0;$i<count($dataku);$i++){
      // Simpan Kedalam Array dengan Batasan |
      @$apit[] .=  $no++."|".$dataku[$i]['barang']."|".$dataku[$i]['box']."|".$dataku[$i]['jumlah']."|".number_format($dataku[$i]['hargasatuan'])."|".number_format($dataku[$i]['hargatotal']);  
      $hargatotal[]=$dataku[$i]['hargatotal'];  
   }
   

   // Cetak Laporan
    $data = $pdf->LoadData($apit);
    $pdf->SetFont('Courier','',10);
    $pdf->AddPage();
    $pdf->FancyTable($header,$data);
    $pdf->Output();
 
?>