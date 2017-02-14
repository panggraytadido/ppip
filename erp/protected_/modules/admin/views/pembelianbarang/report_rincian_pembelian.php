<?php
    Yii::import('application.extensions.fpdf17.*');
    include("fpdf.php");
?>

<?php 
class FPDF_AutoWrapTable extends FPDF {
	private $data = array();
	private $options = array(
		'filename' => '',
		'destinationfile' => '',
		'paper_size'=>'A4',
		'orientation'=>'L'
	);
 
function __construct($data = array(), $options = array()) {
	parent::__construct();
	$this->data = $data;
	$this->options = $options;
}
 
public function rptDetailData () {
	//
	$border = 0;
	$this->AddPage();
	$this->SetAutoPageBreak(true,60);
	$this->AliasNbPages();
	$left = 25;
	
//Untuk Membuat Tanggal cetak
	$timezone = "Asia/Jakarta";
        if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
        $datedisposisi= date("d-m-Y H:i:s");
 
	//header
	$this->SetFont("", "I", 9);
	$this->MultiCell(0, 5,$datedisposisi, 0,'R');
	$this->SetFont("", "B", 12);
	$this->Image('themes/inspinia/img/logo.png',30,20,0);
	$this->Cell(30, 1, " ", 0);
	$this->MultiCell(200, 5, 'CINDY GROUP',0);
	$this->MultiCell(200, 10, '',0);
	$this->Cell(0, 1, " ", "B");
	$this->Ln(10);
	$this->SetFont("", "B", 9);
	$this->SetX($left); $this->Cell(0, 10, 'LAPORAN DATA PEMBELIAN BARANG', 0, 1,'C');
	$this->Ln(10);
 
	$h = 20;
	$left = 40;
	$top = 80;	
	
	#tableheader
	$this->SetFillColor(225,225,225);	
	$left = $this->GetX();
		$this->SetX($left += 0); $this->Cell(30, $h, 'No.', 1, 0, 'C',true);
		$this->SetX($left += 30); $this->Cell(100, $h, 'Supplier', 1, 0, 'C',true);
		$this->SetX($left += 100); $this->Cell(60, $h, 'Tanggal', 1, 0, 'C',true);
		$this->SetX($left += 60); $this->Cell(80, $h, 'Keterangan', 1, 0, 'C',true);
		$this->SetX($left += 80); $this->Cell(50, $h, 'Via Bank', 1, 0, 'C',true);
		$this->SetX($left += 50); $this->Cell(120, $h, 'Nama Barang', 1, 0, 'C',true);
		$this->SetX($left += 120); $this->Cell(60, $h, 'Jumlah Beli', 1, 0, 'C',true);
		$this->SetX($left += 60); $this->Cell(60, $h, 'Jumlah Jual', 1, 0, 'C',true);
		$this->SetX($left += 60); $this->Cell(60, $h, 'Harga / Kg', 1, 0, 'C',true);
		$this->SetX($left += 60); $this->Cell(85, $h, 'Debit', 1, 0, 'C',true);
		$this->SetX($left += 85); $this->Cell(85, $h, 'Kredit', 1, 0, 'C',true);
		$this->SetX($left += 85); $this->Cell(85, $h, 'Jumlah', 1, 1, 'C',true);
		//$this->Ln(20);
 
	$this->SetFont('Arial','',10);
	$this->SetWidths(array(30,100,60,80,50,120,60,60,60,85,85,85,));
	$this->SetAligns(array('C','C','C','C','L','L','R','R','R','R','R','R'));
	
	$no = 1; $this->SetFillColor(255);
		foreach ($this->data as $baris) {
			$this->Row(
				array($no++,
					$baris['supplier'],
					$baris['tanggal'],
					$baris['jenistransfer'],
					$baris['bank'],
					$baris['barang'],
					number_format($baris['beli'] , 0 , ',' , '.'),
					number_format($baris['jual'] , 0 , ',' , '.'),
					number_format( $baris['harga'] , 0 , '' , '.'),
					number_format( $baris['debit'] , 0 , '' , '.'),
					number_format( $baris['kredit'] , 0 , '' , '.'),
					number_format( $baris['saldo'] , 0 , '' , '.')
	));
                        $totalBeli[] = $baris['beli'];
                        $totalJual[] = $baris['jual'];
                        $totalHarga[] = $baris['harga'];
                        $totalDebit[] = $baris['debit'];
                        $totalKredit[] = $baris['kredit'];
                        $totalSaldo[] = $baris['saldo'];
        }
        
        
        //Fungsi jumlah total
	$this->SetFont("", "B", 11);
	$this->Cell(440, $h, 'Total', 1, 0, 'C',true);
                
	$this->Cell(60, $h, ''. number_format( array_sum($totalBeli) , 0 , ',' , '.' ) .'', 1, 0, 'R',true);
	$this->Cell(60, $h, ''. number_format( array_sum($totalJual) , 0 , ',' , '.' ) .'', 1, 0, 'R',true);
	$this->Cell(60, $h, ''." ".'', 1, 0, 'R',true);
	$this->Cell(85, $h, ''. number_format( array_sum($totalDebit) , 0 , '' , '.' ) .'', 1, 0, 'R',true);
	$this->Cell(85, $h, ''. number_format( array_sum($totalKredit) , 0 , '' , '.' ) .'', 1, 0, 'R',true);
	$this->Cell(85, $h, ''. number_format( array_sum($totalDebit)-array_sum($totalKredit) , 0 , '' , '.' ) .'', 1, 0, 'R',true);               
	$this->Ln(10);
         
 
}
//Fungsi Untuk Membuat Footer
function Footer()
{
    //Position at 3 cm from bottom
    $this->SetY(-30);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
	//garis bottom
	$this->Cell(0, 1, " ", "B");
	$this->Ln(10);
    //Page number
    $this->Cell(0,10,'Hal.ke : '.$this->PageNo(),0,0,'R');
}

public function printPDF () {
 
if ($this->options['paper_size'] == "A4") {
$a = 8.3 * 72; //1 inch = 72 pt
$b = 13.0 * 72;
$this->FPDF($this->options['orientation'], "pt", array($a,$b));
} else {
$this->FPDF($this->options['orientation'], "pt", $this->options['paper_size']);
}
 
$this->SetAutoPageBreak(false);
$this->AliasNbPages();
$this->SetFont("arial", "B", 10);
//$this->AddPage();
 
$this->rptDetailData();
 
$this->Output($this->options['filename'],$this->options['destinationfile']);
}
 
private $widths;
private $aligns;
 
function SetWidths($w)
{
//Set the array of column widths
$this->widths=$w;
}
 
function SetAligns($a)
{
//Set the array of column alignments
$this->aligns=$a;
}
 
function Row($data)
{
        //Calculate the height of the row
        $nb=0;
        for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $h=20*$nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for($i=0;$i<count($data);$i++)
        {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,20,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
        }
        //Go to the next line
        $this->Ln($h);
}
 
function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
    $this->AddPage($this->CurOrientation);
}
 
function NbLines($w,$txt)
{
//Computes the number of lines a MultiCell of width w will take
$cw=&$this->CurrentFont['cw'];
if($w==0)
$w=$this->w-$this->rMargin-$this->x;
$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
$s=str_replace("\r",'',$txt);
$nb=strlen($s);
if($nb>0 and $s[$nb-1]=="\n")
	$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
			$l+=$cw[$c];
		if($l>$wmax)
			{
			if($sep==-1)
				{
					if($i==$j)
					$i++;
				}
		else
			$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			}
else
	$i++;
	}
return $nl;                     
}
} //end of class



/* contoh penggunaan dengan data diambil dari database mysql
 *
 * 1. buatlah database di mysql
 * 2. buatlah tabel 'pegawai' dengan field: nip, nama, alamat, email dan website
 * 3. isikan beberapa contoh data ke tabel pegawai tersebut.
 *
 * */
 
#koneksi ke database (disederhanakan)
/*
require_once("koneksi.php");

#ambil data dari DB dan masukkan ke array
$data = array();
$query = "SELECT * FROM tb_beli ORDER BY supplier";
$sql = mysql_query ($query);
while ($row = mysql_fetch_assoc($sql)) {
array_push($data, $row);
}
 * 
 */


$data = Pembelianbarang::model()->rincianPembelian();



//pilihan
$options = array(
'filename' => '', //nama file penyimpanan, kosongkan jika output ke browser
'destinationfile' => 'I', //I=inline browser (default), F=local file, D=download
'paper_size'=>'A4',	//paper size: A4, A3, A4, A5, Letter, Legal
'orientation'=>'L' //orientation: P=portrait, L=landscape
);
 
$tabel = new FPDF_AutoWrapTable($data, $options);
$tabel->printPDF();

?>