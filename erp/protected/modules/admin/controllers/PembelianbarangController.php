<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class PembelianbarangController extends Controller
{
    public $pageTitle = 'Admin - Pembelian Barang';
    
    public function filters()
    {
            return array(

            );
    }
    
    public function actionIndex()
    {
        $model=new Pembelianbarang('search');
        $model->unsetAttributes();
       
        $this->render('index',array(
                'model'=>$model,                  
        ));
    }
    
     public function actionSubmit()
        {
            $model=new Pembelianbarang;
            $model->attributes=$_POST['Pembelianbarang'];
            if(isset($_POST['Pembelianbarang']))
            {	
                $valid = $model->validate();
                if($valid)
                { 
                    if($_POST['Pembelianbarang']['pilihan']==1)
                    {
                        echo CJSON::encode(array('result'=>'OK','pilihan'=>$_POST['Pembelianbarang']['pilihan']));
                    }
                    elseif($_POST['Pembelianbarang']['pilihan']==2)
                    {
                        echo CJSON::encode(array('result'=>'OK','supplierid'=>$_POST['Pembelianbarang']['supplier'],'pilihan'=>$_POST['Pembelianbarang']['pilihan']));
                    }
                    elseif($_POST['Pembelianbarang']['pilihan']==3)
                    {
                        echo CJSON::encode(array('result'=>'OK','pilihan'=>$_POST['Pembelianbarang']['pilihan']));
                    }                      
                }
                else
                {
                    $error = CActiveForm::validate($model);
                    if($error!='[]')
                            echo $error;
                    Yii::app()->end();
                }	
                Yii::app()->end();
            }
        }
        
    public function sortBySubkey(&$array, $subkey, $sortType = SORT_ASC) {
            foreach ($array as $subarray) {
                $keys[] = $subarray[$subkey];
            }
            array_multisort($keys, $sortType, $array);
        }    
        
        public function cmp($a, $b)
        {
            if ($a['supplierid'] == $b['supplierid']) {
                return 0;
            }
            return ($a['supplierid'] > $b['supplierid']) ? -1 : 1;
        }
        
    function sortBy($field, &$array, $direction = 'asc')
    {
	usort($array, create_function('$a, $b', '
		$a = $a["' . $field . '"];
		$b = $b["' . $field . '"];

		if ($a == $b)
		{
			return 0;
		}

		return ($a ' . ($direction == 'desc' ? '>' : '<') .' $b) ? -1 : 1;
	'));

	return true;
    }    
        
    //rincian pembelian    
    public function actionPrintRincianPembelian()
    {
        $this->render('report_rincian_pembelian',array(                       
        ));
        /*
        $connection=Yii::app()->db;
        $sql ="select distinct s.id as supplierid,s.namaperusahaan as supplier,cast(tf.tanggal as date),jt.nama as jenistransfer,r.namabank as bank,b.nama as barang,pb.jumlah as beli,tf.kredit as jual,tf.debit as debit, tf.saldo as harga,tf.kredit,tf.saldo  from master.barang b 
inner join transaksi.penerimaanbarang pb on pb.barangid=b.id 
	inner join transaksi.transfer tf on tf.supplierid=pb.supplierid 
		inner join referensi.jenistransfer jt on jt.id=tf.jenistransferid
			inner join master.supplier s on s.id=tf.supplierid
				left join master.rekening r on r.id=tf.rekeningid
					where tf.tanggal=pb.tanggal and jt.id=1 
        union 
        select distinct s.id as supplierid,s.namaperusahaan as supplier,cast(tf.tanggal as date) as tanggal,jt.nama as jenistransfer,r.namabank as bank,b.nama as barang,tf.debit as beli,pb.jumlah as jual,tf.debit as debit, tf.kredit as harga,tf.kredit,tf.saldo  
                from master.barang b inner join transaksi.penjualanbarangkesupplier pb on pb.barangid=b.id 
                        inner join transaksi.transfer tf on tf.supplierid=pb.supplierpembeliid 
                                inner join referensi.jenistransfer jt on jt.id=tf.jenistransferid
					inner join master.supplier s on s.id=tf.supplierid
						left join master.rekening r on r.id=tf.rekeningid
							where tf.tanggal=pb.tanggal and jt.id=2
	union						
        select distinct s.id as supplierid,s.namaperusahaan as supplier,cast(tf.tanggal as date) as tanggal,jt.nama as jenistransfer,r.namabank as bank,cast(tf.debit as char) as barang,
	tf.debit as beli,tf.debit as jual,tf.debit as debit,tf.kredit as harga,tf.kredit as kredit,tf.saldo
         from transaksi.transfer tf 
        inner join master.rekening r on r.id=tf.rekeningid
                inner join referensi.jenistransfer jt on jt.id=tf.jenistransferid
                        inner join master.supplier s on s.id=tf.supplierid																																							";            
        $data=$connection->createCommand($sql)->queryAll();
        
       $sort = array();
        foreach($data as $k=>$v) {
            $sort['supplierid'][$k] = $v['supplierid'];
            //$sort['text'][$k] = $v['text'];
        }

        $data1 = array_multisort($sort['supplierid'], SORT_DESC, $data);
        print("<pre>".print_r($data,true)."</pre>");
        /*
        echo Yii::app()->Report->Export("laporan_pembayaran_all_supplier.jrxml","Pembelian Barang All",array(),
        array(),"pdf");
         * 
         */
    }
    
    //rincian pembelian    
    public function actionPrintRincianPerSupplier()
    {
        $this->render('print_rincian_persupplier',array(                       
        ));
    }
    
    public function actionPrintRincianAllSupplier()
    {
        $this->render('print_rincian_all_supplier',array(                       
        ));
        /*
        echo Yii::app()->Report->Export("laporan_pembayaran_all_supplier.jrxml","Pembelian Barang All",array(),
                array(),"pdf");
         * 
         */
               
    }
    
    public function actionTe()
    {
		$connection=Yii::app()->db; 
        $sql2="select * from transaksi.penerimaanbarang pb 
			inner join transaksi.transfer tf on pb.id=tf.penerimaanbarangid 
			where tf.jenistransferid=1 and tf.kredit=0";														
			$data2=$connection->createCommand($sql2)->queryAll();
			for($i=0;$i<count($data2);$i++)
			{
				$hargaBarang = Hargabarang::model()->find('barangid='.$data2[$i]["barangid"].' AND supplierid='.$data2[$i]["supplierid"])->hargagrosir;
				$pb[] = $data2[$i]["jumlah"]*$hargaBarang;
			}
			
        print("<pre>".print_r(array_sum($pb),true)."</pre>");
    }
    
    
       
}    