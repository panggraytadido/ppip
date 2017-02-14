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
	$pdf->SetFont("", "B", 24);
	//$pdf->Image('../img/logo.png',10,5,0);
	$pdf->MultiCell(100, 0, 'CINDY GROUP', 0, 'L');
    $pdf->Ln();
	$pdf->SetFont("", "", 10);
	$pdf->Cell(140, 15, 'PENYALUR IKAN CUE, IKAN BASAH, FROZEN FISH DAN PINDANG HYGIENIS', 0, 0,'L');
	$pdf->Cell(0, 10, 'Kepada  Yth.', 0, 0,'C');
	$pdf->Ln(12);
	$pdf->Cell(120, 0, 'Jl. Tulang Kuning No. 04 RT.02 RW.05', 0, 0,'L');
        	
	$pdf->Cell(90, -25, 'Parung, '.$_GET['tanggal'].'', 0, 0,'C');
	//$pdf->Cell(-90, 0, ''.Pelanggan::model()->findByPk($_GET['pelangganid'])->nama.'', 0, 0,'C');        
        
	$pdf->Ln(5);
	$pdf->Cell(30, 0, 'Tlp. Solihin', 0, 0,'L');
	$pdf->Cell(30, 0, ': 0815 1419 9203', 0, 0,'L');
	$pdf->Cell(90, 0, '-  0877 7586 5113', 0, 0,'L');
	$pdf->Cell(0, 0, 'di', 0, 0,'C');
	$pdf->Ln(5);
	$pdf->Cell(30, 0, 'Kantor', 0, 0,'L');
	$pdf->Cell(60, 0, ': (0251) 8614839', 0, 0,'L');
	
	//$pdf->Cell(150, 0, ''.Pelanggan::model()->findByPk($_GET['pelangganid'])->alamat.'', 0, 0,'C');
        
 
	$pdf->Ln(5);
	 
        // Colors, line width and bold font
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetLineWidth(.1);
        $pdf->SetFont('Courier','B');
        
        // Lebar Header Sesuaikan Jumlahnya dengan Jumlah Field Tabel Database
        $w = array(10, 70, 70);
        for($i=0;$i<count($header);$i++)
         $pdf->Cell($w[$i],6,$header[$i],1,0,'C',true);
        $pdf->Ln();
        // Color and font restoration
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetLineWidth(.1);
        $pdf->SetFont('Courier','','9');
        
        // Data
        $fill = false;
        foreach($data as $row)
        {
            // Field Dari Database Yang Ingin ditampilkan
            // $pdf->Cell($w[Ubah Ini],6,$row[Ubah Ini],'LR',0,'L',$fill);
            $pdf->Cell($w[0],4,$row[0],'LR',0,'C',$fill);
            $pdf->Cell($w[1],4,$row[1],'LR',0,'L',$fill); 
            $pdf->Cell($w[2],4,$row[2],'LR',0,'C',$fill);           
            $pdf->Ln();
            $fill = !$fill;
         }
         
        // Closing line
        $pdf->Cell(array_sum($w),0,'','T');
        $pdf->Ln();

        $pdf->SetFont('Courier','','10');

        $pdf->Cell(10, 6, '', 1, 0,'L');
        $pdf->Cell(70, 6, '', 1, 0,'L');
        $pdf->Cell(20, 6, '', 1, 0,'C');

    	      
           
    }
 }
 
$pdf = new FPDF();

$pdf->SetFont('Courier','',10);
$pdf->AddPage();

$pdf->Line(5,35,205,35);

$pdf->Rect(5, 5, 200, 287, 'D'); //For A4

//$pdf->SetLineWidth(200);

//$pdf->Ln();
//$pdf->Cell(140, 70, 'Nama', 0, 0,'L');
//$pdf->Cell(50, 70, 'Bulan', 0, 0,'L');  



$pdf->Cell(70, 60, 'Nama ', 0, 0,'L');
$pdf->Cell(150, 60, 'Bulan ', 0, 0,'L');
$pdf->Ln(0.1);
$pdf->Cell(10, 70, 'Total Gaji ', 0, 0,'L');


$pdf->Ln(0.1);
$pdf->Cell(10, 80, 'Terbilang', 0, 0,'L');
$pdf->Cell(77, 70, 'Jabatan', 10, 10,'R');
//$pdf->Cell(90, 120, 'Totalnya', 0, 0,'L');

$pdf->Ln(0);
$header = array('No.','Deskripsi','Jumlah');
$w = array(10, 80, 90);
for($i=0;$i<count($header);$i++)
 $pdf->Cell($w[$i],6,$header[$i],1,0,'C',true);
$pdf->Ln();
// Color and font restoration

$pdf->SetTextColor(0);
$pdf->SetLineWidth(.1);
$pdf->SetFont('Courier','','9');

$data = array(1,2,3);
$fill = false;
$no=1;
foreach($data as $row)
{
	// Field Dari Database Yang Ingin ditampilkan
	// $pdf->Cell($w[Ubah Ini],6,$row[Ubah Ini],'LR',0,'L',$fill);
	$pdf->Cell($w[0],4,$no,'LR',0,'C',$fill);
	$pdf->Cell($w[1],4,$no,'LR',0,'L',$fill); 
	$pdf->Cell($w[2],4,$no,'LR',0,'C',$fill);	
	$pdf->Ln();
	$fill = !$fill;
	$no++;
 }
 
 $pdf->Cell(100,0,'','T');
        $pdf->Ln();

        $pdf->SetFont('Courier','','10');

        $pdf->Cell(10, 6, '', 1, 0,'L');
        $pdf->Cell(80, 6, '', 1, 0,'L');
        $pdf->Cell(90, 6, '', 1, 0,'C');
        
        
//$pdf->Rect(10, 60, 190, 70, 'D'); //For A4
//$pdf->Ln(100);
//$pdf->Line(1,100,1,1);


//$pdf->Cell(100, 120, 'Total ', 0, 0,'L');
//$pdf->Cell(10, 120, 'Totalnya', 0, 0,'L');
//$pdf->Cell(80);
// Centered text in a framed 20*10 mm cell and line break
//$pdf->Cell(20,10,'Title',1,1,'C');

$pdf->Output();

?>