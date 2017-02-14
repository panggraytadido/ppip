<?php
$this->pageTitle = "Laporan - Slip Gaji Karyawan";

$this->breadcrumbs=array(
	'Keuangan',
        'Keuangan Harian',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Keuangan Harian</b>
                    </div>
                </div>
                
                <div class="ibox-content">

                    <?php if(Yii::app()->user->hasFlash('success')):?>
                            <div class="alert alert-success fade in">
                                <button type="button" class="close close-sm" data-dismiss="alert">
                                    <i class="fa fa-times"></i>
                                </button>
                                <?php echo Yii::app()->user->getFlash('success'); ?>
                            </div>    
                    <?php endif; ?>

                    <div class="text-center article-title">
                        <h1>Laporan Data Keuangan Harian</h1>
                        <span class="text-muted"><i class="fa fa-clock-o"></i> <?php echo $tanggalLabel; ?></span>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Uraian</th>
                            <th>Jumlah (Rp.)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="text-align: center">1.</td>
                            <td>Jumlah Uang Cash</td>
                            <td style="text-align: right"><?php echo number_format($jumlahUangCash); ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: center">2.</td>
                            <td>Jumlah Uang Setoran</td>
                            <td style="text-align: right"><?php echo number_format($jumlahUangSetoran); ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: center">3.</td>
                            <td>Jumlah Uang Tabungan (Jumlah Pendapatan)</td>
                            <td style="text-align: right"><?php echo number_format($jumlahUangTabungan); ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: center"><b>4.</b></td>
                            <td><b>Total Uang Harian</b></td>
                            <td style="text-align: right"><b><?php echo number_format($totalUangHarian); ?></b></td>
                        </tr>
                        <tr>
                            <td style="text-align: center">5.</td>
                            <td>Jumlah Transfer</td>
                            <td style="text-align: right"><?php echo number_format($jumlahTransfer); ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: center">6.</td>
                            <td>Jumlah Beli Tunai</td>
                            <td style="text-align: right"><?php echo number_format($jumlahBeliTunai); ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: center">7.</td>
                            <td>Jumlah Pengeluaran</td>
                            <td style="text-align: right"><?php echo number_format($jumlahPengeluaran); ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: center"><b>8.</b></td>
                            <td><b>Sisa Uang Harian</b></td>
                            <td style="text-align: right"><b><?php echo number_format($sisaUangHarian); ?><input type="hidden" value="<?php echo $sisaUangHarian ?>" name="sisauangharian" id="sisauangharian"></b></td>
                        </tr>
                        </tbody>
                    </table>

            <?php   
                    $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                            'type'=>'horizontal',
                            'id'=>'keuanganharian-form',
                            'enableAjaxValidation'=>true,
                            'clientOptions'=>array(
                                            'validateOnChange'=>true,
                            )
                    )); 
            ?>

            <input type="hidden" name="tanggal" value="<?php echo (isset($tanggal)) ? $tanggal : ''; ?>" />
                    
            <div class="col-sm-6">
                    <?php echo $form->textFieldGroup($modelInput, 'jumlah', array(
                                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-12 digits'),
                                                    'widgetOptions' => array(
                                                                'htmlOptions'=>array(
                                                                        'onchange' => 'selisih(this);'
                                                                    )
                                                        )
                                                )
                    ); ?>
            </div>
            <div class="clearfix"></div>

            <div class="col-sm-6">
                
                    <?php echo $form->textFieldGroup($modelInput, 'seratusribu', array(
                                                    'prepend' => '<i class="fa fa-times"></i>',
                                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-12 digits'),
                                                    'widgetOptions' => array(
                                                                'htmlOptions'=>array(
                                                                        'onkeyup'=>'seratusribu(this.value)'
                                                                    )
                                                        )
                                                )
                    ); ?>
					
					<input type="hidden" id="n_seratusribuhidden" name="n_seratusribuhidden">
                            
                    <?php echo $form->textFieldGroup($modelInput, 'limapuluhribu', array(
                                                    'prepend' => '<i class="fa fa-times"></i>',
                                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-12 digits'),
                                                    'widgetOptions' => array(
                                                                'htmlOptions'=>array(
                                                                        'onchange'=>'limapuluhribu(this.value)'
                                                                    )
                                                        )
                                                )
                    ); ?>
					
					<input type="hidden" id="n_limapuluhribuhidden" name="n_limapuluhribuhidden">
                        
                    <?php echo $form->textFieldGroup($modelInput, 'duapuluhribu', array(
                                                    'prepend' => '<i class="fa fa-times"></i>',
                                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-12 digits'),
                                                    'widgetOptions' => array(
                                                                'htmlOptions'=>array(
                                                                        'onchange'=>'duapuluhribu(this.value)'
                                                                    )
                                                        )
                                                )
                    ); ?>
					
					<input type="hidden" id="n_duapuluhribuhidden" name="n_duapuluhribuhidden">
                    
                    <?php echo $form->textFieldGroup($modelInput, 'sepuluhribu', array(
                                                    'prepend' => '<i class="fa fa-times"></i>',
                                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-12 digits'),
                                                    'widgetOptions' => array(
                                                                'htmlOptions'=>array(
                                                                        'onchange'=>'sepuluhribu(this.value)'
                                                                    )
                                                        )
                                                )
                    ); ?>
					
					<input type="hidden" id="n_sepuluhribuhidden" name="n_sepuluhribuhidden">
                        
                    <?php echo $form->textFieldGroup($modelInput, 'limaribu', array(
                                                    'prepend' => '<i class="fa fa-times"></i>',
                                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-12 digits'),
                                                    'widgetOptions' => array(
                                                                'htmlOptions'=>array(
                                                                        'onchange'=>'limaribu(this.value)'
                                                                    )
                                                        )
                                                )
                    ); ?>
					
					<input type="hidden" id="n_limaribuhidden" name="n_limaribuhidden">
                        
                    <?php echo $form->textFieldGroup($modelInput, 'duaribu', array(
                                                    'prepend' => '<i class="fa fa-times"></i>',
                                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-12 digits'),
                                                    'widgetOptions' => array(
                                                                'htmlOptions'=>array(
                                                                        'onchange'=>'duaribu(this.value)'
                                                                    )
                                                        )
                                                )
                    ); ?>
					
					<input type="hidden" id="n_duaribuhidden" name="n_duaribuhidden">
                    
                    <?php echo $form->textFieldGroup($modelInput, 'seribu', array(
                                                    'prepend' => '<i class="fa fa-times"></i>',
                                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-12 digits'),
                                                    'widgetOptions' => array(
                                                                'htmlOptions'=>array(
                                                                        'onkeyup'=>'hitung()'
                                                                    )
                                                        )
                                                )
                    ); ?>
					
					<input type="hidden" id="n_seribuhidden" name="n_seribuhidden">

            </div>
            
            <div class="col-sm-6">
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">&nbsp;</label>
                    <div class="col-sm-11">
                        <div class="input-group">
                            <span class="input-group-addon">=</span> 
                            <input id="n_seratusribu" value="<?php echo ($modelInput->id!=null) ? ($modelInput->seratusribu*100000) : '0'; ?>" class="form-control" type="text" readonly />
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">&nbsp;</label>
                    <div class="col-sm-11">
                        <div class="input-group">
                            <span class="input-group-addon">=</span> 
                            <input id="n_limapuluhribu" value="<?php echo ($modelInput->id!=null) ? ($modelInput->limapuluhribu*50000) : '0'; ?>" class="form-control" type="text" readonly />
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">&nbsp;</label>
                    <div class="col-sm-11">
                        <div class="input-group">
                            <span class="input-group-addon">=</span> 
                            <input id="n_duapuluhribu" value="<?php echo ($modelInput->id!=null) ? ($modelInput->duapuluhribu*20000) : '0'; ?>" class="form-control" type="text" readonly />
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">&nbsp;</label>
                    <div class="col-sm-11">
                        <div class="input-group">
                            <span class="input-group-addon">=</span> 
                            <input id="n_sepuluhribu" value="<?php echo ($modelInput->id!=null) ? ($modelInput->sepuluhribu*10000) : '0'; ?>" class="form-control" type="text" readonly />
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">&nbsp;</label>
                    <div class="col-sm-11">
                        <div class="input-group">
                            <span class="input-group-addon">=</span> 
                            <input id="n_limaribu" value="<?php echo ($modelInput->id!=null) ? ($modelInput->limaribu*5000) : '0'; ?>" class="form-control" type="text" readonly />
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">&nbsp;</label>
                    <div class="col-sm-11">
                        <div class="input-group">
                            <span class="input-group-addon">=</span> 
                            <input id="n_duaribu" value="<?php echo ($modelInput->id!=null) ? ($modelInput->duaribu*2000) : '0'; ?>" class="form-control" type="text" readonly />
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">&nbsp;</label>
                    <div class="col-sm-11">
                        <div class="input-group">
                            <span class="input-group-addon">=</span> 
                            <input id="n_seribu" value="<?php echo ($modelInput->id!=null) ? ($modelInput->seribu*1000) : '0'; ?>" class="form-control" type="text" readonly />
                        </div>
                    </div>
                </div>
                    
            </div>
            <div class="clearfix"></div>
            
            <div class="col-sm-6">
                    <?php echo $form->textFieldGroup($modelInput, 'totalkeuanganharian', array(
                                                    'prepend' => '<i class="fa fa-times"></i>',
                                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-12 digits'),
                                                    'widgetOptions' => array(
                                                                'htmlOptions'=>array(
                                                                        'onchange' => 'selisih()'
                                                                    )
                                                        )
                                                )
                    ); ?>
                        
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Jumlah (-) kurang, (+) lebih</label>
                        <div class="col-sm-7">
                            <input id="kuranglebih" name="kuranglebih" value="<?php if($modelInput->kelebihan >= $modelInput->kekurangan){echo $modelInput->kelebihan;}elseif($modelInput->kekurangan > $modelInput->kelebihan){echo $modelInput->kekurangan;}elseif($modelInput->kelebihan==null){echo '0';} ?>" class="form-control" type="text" readonly />
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label class="col-sm-5 control-label">&nbsp;</label>
                        <div class="col-sm-7">
                            <input id="submit" name="submit" value="Simpan" class="btn btn-primary" type="submit" />
                        </div>
                    </div>

            </div>
            <div class="clearfix"></div>

                    <?php $this->endWidget(); ?>

                </div>
            </div>	              
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        $(".digits").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and . ()
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                 // Allow: Ctrl+A, Command+A
                (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
                 // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                     // let it happen, don't do anything
                     return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    });
    
    function selisih()
    {
		
        var jumlahawal = parseInt(document.getElementById('Pencatatankeuanganharian_jumlah').value);
        //var totalperhitungan = parseInt(document.getElementById('Pencatatankeuanganharian_totalkeuanganharian').value);
        var sisauangharian = parseInt($('#sisauangharian').val());

		$('#Pencatatankeuanganharian_totalkeuanganharian').val(jumlahawal);					
			
		if(jumlahawal>sisauangharian)
		{
			$('#kuranglebih').val(jumlahawal-sisauangharian);
		}
		else if(jumlahawal<sisauangharian)
		{
			$('#kuranglebih').val(parseInt(sisauangharian)-parseInt(jumlahawal));
		}
		else if(jumlahawal==sisauangharian)
		{
			$('#kuranglebih').val(0);
		}		
    }
    
    function seratusribu(val)
    {
		var awal = parseInt($("#Pencatatankeuanganharian_jumlah").val());
		var sisauangharian = parseInt($('#sisauangharian').val());
		if(val!='' || val!=0)
		{			
			document.getElementById('n_seratusribu').value = val * 100000;
			$('#n_seratusribuhidden').val(val * 100000);
			$('#Pencatatankeuanganharian_totalkeuanganharian').val("");
			
			var seratusribu = $('#n_seratusribu').val();
			if(seratusribu!='' || seratusribu!=0)
			{
				seratusribu = parseInt($('#n_seratusribu').val());
			}
			else 
			{
				seratusribu=0;
			}
			
			var total = awal+seratusribu;
			$('#Pencatatankeuanganharian_totalkeuanganharian').val(total);
			if(total>sisauangharian)
			{
				$('#kuranglebih').val(total-sisauangharian);
			}
			else if(total<sisauangharian)
			{
				$('#kuranglebih').val(parseInt(sisauangharian)-parseInt(total));
			}
			else if(total==sisauangharian)
			{
				$('#kuranglebih').val(0);
			}	
		}
		else
		{
			var total = parseInt($('#Pencatatankeuanganharian_totalkeuanganharian').val())-$('#n_seratusribuhidden').val();
			$('#Pencatatankeuanganharian_totalkeuanganharian').val(total);
			document.getElementById('n_seratusribu').value = 0;
			$('#kuranglebih').val(sisauangharian-total);
		}
		
    }
	
    function limapuluhribu(val)
    {
		var awal = parseInt($("#Pencatatankeuanganharian_jumlah").val());
		var sisauangharian = parseInt($('#sisauangharian').val());
		if(val!='' || val!=0)
		{			
			document.getElementById('n_limapuluhribu').value = val * 50000;
			$('#n_limapuluhribuhidden').val(val * 50000);
			$('#Pencatatankeuanganharian_totalkeuanganharian').val("");
			
			var seratusribu = $('#n_seratusribu').val();
			if(seratusribu!='' || seratusribu!=0)
			{
				seratusribu = parseInt($('#n_seratusribu').val());
			}
			else 
			{
				seratusribu=0;
			}
									
			var total = awal+seratusribu+parseInt(val*50000);
			$('#Pencatatankeuanganharian_totalkeuanganharian').val(total);
			if(total>sisauangharian)
			{
				$('#kuranglebih').val(total-sisauangharian);
			}
			else if(total<sisauangharian)
			{
				$('#kuranglebih').val(parseInt(sisauangharian)-parseInt(total));
			}
			else if(total==sisauangharian)
			{
				$('#kuranglebih').val(0);
			}	
		}
		else
		{
			var total = parseInt($('#Pencatatankeuanganharian_totalkeuanganharian').val())-$('#n_limapuluhribuhidden').val();			
			$('#Pencatatankeuanganharian_totalkeuanganharian').val(total);
			document.getElementById('n_limapuluhribu').value = 0;
			$('#kuranglebih').val(sisauangharian-total);
		}
    }
	
    function duapuluhribu(val)
    {
		var awal = parseInt($("#Pencatatankeuanganharian_jumlah").val());
		var sisauangharian = parseInt($('#sisauangharian').val());
		if(val!='' || val!=0)
		{			
			document.getElementById('n_duapuluhribu').value = val * 20000;
			$('#n_duapuluhribuhidden').val(val * 20000);
			$('#Pencatatankeuanganharian_totalkeuanganharian').val("");
			
			var seratusribu = $('#n_seratusribu').val();
			if(seratusribu!='' || seratusribu!=0)
			{
				seratusribu = parseInt($('#n_seratusribu').val());
			}
			else 
			{
				seratusribu=0;
			}
			
			var limapuluhribu = $('#n_limapuluhribu').val();
			if(limapuluhribu!='' || limapuluhribu!=0)
			{
				limapuluhribu = parseInt($('#n_limapuluhribu').val());
			}
			else 
			{
				limapuluhribu=0;
			}
			
			
			
			var total = awal+seratusribu+limapuluhribu+parseInt(val*20000);
			$('#Pencatatankeuanganharian_totalkeuanganharian').val(total);
			if(total>sisauangharian)
			{
				$('#kuranglebih').val(total-sisauangharian);
			}
			else if(total<sisauangharian)
			{
				$('#kuranglebih').val(parseInt(sisauangharian)-parseInt(total));
			}
			else if(total==sisauangharian)
			{
				$('#kuranglebih').val(0);
			}	
		}
		else
		{
			var total = parseInt($('#Pencatatankeuanganharian_totalkeuanganharian').val())-$('#n_duapuluhribuhidden').val();
			$('#Pencatatankeuanganharian_totalkeuanganharian').val(total);
			document.getElementById('n_duapuluhribu').value = 0;
			$('#kuranglebih').val(sisauangharian-total);
		}
    }
	
    function sepuluhribu(val)
    {
		var awal = parseInt($("#Pencatatankeuanganharian_jumlah").val());
		var sisauangharian = parseInt($('#sisauangharian').val());
		if(val!='' || val!=0)
		{			
			document.getElementById('n_sepuluhribu').value = val * 10000;
			$('#n_sepuluhribuhidden').val(val * 10000);
			$('#Pencatatankeuanganharian_totalkeuanganharian').val("");
			
			//
			var seratusribu = $('#n_seratusribu').val();
			if(seratusribu!='' || seratusribu!=0)
			{
				seratusribu = parseInt($('#n_seratusribu').val());
			}
			else 
			{
				seratusribu=0;
			}
			
			var limapuluhribu = $('#n_limapuluhribu').val();
			if(limapuluhribu!='' || limapuluhribu!=0)
			{
				limapuluhribu = parseInt($('#n_limapuluhribu').val());
			}
			else 
			{
				limapuluhribu=0;
			}
			
			var duapuluhribu = $('#n_duapuluhribu').val();
			if(duapuluhribu!='' || duapuluhribu!=0)
			{
				duapuluhribu = parseInt($('#n_duapuluhribu').val());
			}
			else 
			{
				duapuluhribu=0;
			}
			//
			
			var total = awal+seratusribu+limapuluhribu+duapuluhribu+parseInt(val*10000);			
			$('#Pencatatankeuanganharian_totalkeuanganharian').val(total);
			if(total>sisauangharian)
			{
				$('#kuranglebih').val(total-sisauangharian);
			}
			else if(total<sisauangharian)
			{
				$('#kuranglebih').val(parseInt(sisauangharian)-parseInt(total));
			}
			else if(total==sisauangharian)
			{
				$('#kuranglebih').val(0);
			}	
		}
		else
		{
			var total = parseInt($('#Pencatatankeuanganharian_totalkeuanganharian').val())-$('#n_sepuluhribuhidden').val();
			$('#Pencatatankeuanganharian_totalkeuanganharian').val(total);
			document.getElementById('n_sepuluhribu').value = 0;
			$('#kuranglebih').val(sisauangharian-total);
		}
    }
	
    function limaribu(val)
    {
		var awal = parseInt($("#Pencatatankeuanganharian_jumlah").val());
		var sisauangharian = parseInt($('#sisauangharian').val());
		if(val!='' || val!=0)
		{			
			document.getElementById('n_limaribu').value = val * 5000;
			$('#n_limaribuhidden').val(val * 5000);
			$('#Pencatatankeuanganharian_totalkeuanganharian').val("");
			
			
			//
			var seratusribu = $('#n_seratusribu').val();
			if(seratusribu!='' || seratusribu!=0)
			{
				seratusribu = parseInt($('#n_seratusribu').val());
			}
			else 
			{
				seratusribu=0;
			}
			
			var limapuluhribu = $('#n_limapuluhribu').val();
			if(limapuluhribu!='' || limapuluhribu!=0)
			{
				limapuluhribu = parseInt($('#n_limapuluhribu').val());
			}
			else 
			{
				limapuluhribu=0;
			}
			
			var duapuluhribu = $('#n_duapuluhribu').val();
			if(duapuluhribu!='' || duapuluhribu!=0)
			{
				duapuluhribu = parseInt($('#n_duapuluhribu').val());
			}
			else 
			{
				duapuluhribu=0;
			}
			
			var sepuluhribu = $('#n_sepuluhribu').val();
			if(sepuluhribu!='' || sepuluhribu!=0)
			{
				sepuluhribu = parseInt($('#n_sepuluhribu').val());
			}
			else 
			{
				sepuluhribu=0;
			}
			//
			
			
			var total = awal+seratusribu+limapuluhribu+duapuluhribu+sepuluhribu+parseInt(val*5000);					
			$('#Pencatatankeuanganharian_totalkeuanganharian').val(total);
			if(total>sisauangharian)
			{
				$('#kuranglebih').val(total-sisauangharian);
			}
			else if(total<sisauangharian)
			{
				$('#kuranglebih').val(parseInt(sisauangharian)-parseInt(total));
			}
			else if(total==sisauangharian)
			{
				$('#kuranglebih').val(0);
			}	
		}
		else
		{
			var total = parseInt($('#Pencatatankeuanganharian_totalkeuanganharian').val())-$('#n_limaribuhidden').val();
			$('#Pencatatankeuanganharian_totalkeuanganharian').val(total);
			document.getElementById('n_limaribu').value = 0;
			$('#kuranglebih').val(sisauangharian-total);
		}
    }
	
    function duaribu(val)
    {
		var awal = parseInt($("#Pencatatankeuanganharian_jumlah").val());
		var sisauangharian = parseInt($('#sisauangharian').val());
		if(val!='' || val!=0)
		{			
			document.getElementById('n_duaribu').value = val * 10000;
			$('#n_duaribuhidden').val(val * 10000);
			$('#Pencatatankeuanganharian_totalkeuanganharian').val("");
			
			//
			var seratusribu = $('#n_seratusribu').val();
			if(seratusribu!='' || seratusribu!=0)
			{
				seratusribu = parseInt($('#n_seratusribu').val());
			}
			else 
			{
				seratusribu=0;
			}
			
			var limapuluhribu = $('#n_limapuluhribu').val();
			if(limapuluhribu!='' || limapuluhribu!=0)
			{
				limapuluhribu = parseInt($('#n_limapuluhribu').val());
			}
			else 
			{
				limapuluhribu=0;
			}
			
			var duapuluhribu = $('#n_duapuluhribu').val();
			if(duapuluhribu!='' || duapuluhribu!=0)
			{
				duapuluhribu = parseInt($('#n_duapuluhribu').val());
			}
			else 
			{
				duapuluhribu=0;
			}
			
			var sepuluhribu = $('#n_sepuluhribu').val();
			if(sepuluhribu!='' || sepuluhribu!=0)
			{
				sepuluhribu = parseInt($('#n_sepuluhribu').val());
			}
			else 
			{
				sepuluhribu=0;
			}
			
			var limaribu = $('#n_limaribu').val();
			if(limaribu!='' || limaribu!=0)
			{
				limaribu = parseInt($('#n_limaribu').val());
			}
			else 
			{
				limaribu=0;
			}
			//
			
			var total = awal+seratusribu+limapuluhribu+duapuluhribu+sepuluhribu+limaribu+parseInt(val*2000);					
			$('#Pencatatankeuanganharian_totalkeuanganharian').val(total);
			if(total>sisauangharian)
			{
				$('#kuranglebih').val(total-sisauangharian);
			}
			else if(total<sisauangharian)
			{
				$('#kuranglebih').val(parseInt(sisauangharian)-parseInt(total));
			}
			else if(total==sisauangharian)
			{
				$('#kuranglebih').val(0);
			}	
		}
		else
		{
			var total = parseInt($('#Pencatatankeuanganharian_totalkeuanganharian').val())-$('#n_duaribuhidden').val();
			$('#Pencatatankeuanganharian_totalkeuanganharian').val(total);
			document.getElementById('n_duaribu').value = 0;
			$('#kuranglebih').val(sisauangharian-total);
		}
    }
    function seribu(val)
    {
		var awal = parseInt($("#Pencatatankeuanganharian_jumlah").val());
		var sisauangharian = parseInt($('#sisauangharian').val());
		if(val!='' || val!=0)
		{			
			document.getElementById('n_seribu').value = val * 1000;
			$('#n_seribuhidden').val(val * 1000);
			$('#Pencatatankeuanganharian_totalkeuanganharian').val("");
			
			//
			var seratusribu = $('#n_seratusribu').val();
			if(seratusribu!='' || seratusribu!=0)
			{
				seratusribu = parseInt($('#n_seratusribu').val());
			}
			else 
			{
				seratusribu=0;
			}
			
			var limapuluhribu = $('#n_limapuluhribu').val();
			if(limapuluhribu!='' || limapuluhribu!=0)
			{
				limapuluhribu = parseInt($('#n_limapuluhribu').val());
			}
			else 
			{
				limapuluhribu=0;
			}
			
			var duapuluhribu = $('#n_duapuluhribu').val();
			if(duapuluhribu!='' || duapuluhribu!=0)
			{
				duapuluhribu = parseInt($('#n_duapuluhribu').val());
			}
			else 
			{
				duapuluhribu=0;
			}
			
			var sepuluhribu = $('#n_sepuluhribu').val();
			if(sepuluhribu!='' || sepuluhribu!=0)
			{
				sepuluhribu = parseInt($('#n_sepuluhribu').val());
			}
			else 
			{
				sepuluhribu=0;
			}
			
			var limaribu = $('#n_limaribu').val();
			if(limaribu!='' || limaribu!=0)
			{
				limaribu = parseInt($('#n_limaribu').val());
			}
			else 
			{
				limaribu=0;
			}
			
			var duaribu = $('#n_duaribu').val();
			if(duaribu!='' || duaribu!=0)
			{
				duaribu = parseInt($('#n_duaribu').val());
			}
			else 
			{
				duaribu=0;
			}
			
			//
			
			var total = awal+seratusribu+limapuluhribu+duapuluhribu+sepuluhribu+limaribu;//+duaribu; //+parseInt(val*1000);			
			$('#Pencatatankeuanganharian_totalkeuanganharian').val(total);
			if(total>sisauangharian)
			{
				$('#kuranglebih').val(total-sisauangharian);
			}
			else if(total<sisauangharian)
			{
				$('#kuranglebih').val(parseInt(sisauangharian)-parseInt(total));
			}
			else if(total==sisauangharian)
			{
				$('#kuranglebih').val(0);
			}	
		}
		else
		{
			var total = parseInt($('#Pencatatankeuanganharian_totalkeuanganharian').val())-$('#n_seribuhidden').val();
			$('#Pencatatankeuanganharian_totalkeuanganharian').val(total);
			document.getElementById('n_seribu').value = 0;
			$('#kuranglebih').val(sisauangharian-total);
		}
    }    

	function hitung()
	{
		var sisauangharian = parseInt($('#sisauangharian').val());
		
		var seratusseribu = (parseInt(document.getElementById('Pencatatankeuanganharian_seratusribu').value)*100000);   		
		var n_seratusribu = parseInt($('#n_seratusribu').val(seratusseribu));
		if(seratusseribu!='')
		{
			n_seratusribu =parseInt(seratusseribu);
		}
		else
		{
			n_seratusribu=parseInt(0);
		}
				
				
        var seribu = $('#Pencatatankeuanganharian_seribu').val();//(document.getElementById('Pencatatankeuanganharian_seribu').value*1000);   						
		if(seribu!='' || seribu!=0)
		{
			seribu = parseInt($('#Pencatatankeuanganharian_seribu').val())*1000;
			$('#n_seribu').val(seribu);
			n_seribu = parseInt(seribu);
		}
		else
		{			
			$('#n_seribu').html(0);
			n_seribu=parseInt(0);
		}			
		
		var totalpecahan = eval(n_seratusribu)+eval(n_seribu);	
		var total = parseInt($('#Pencatatankeuanganharian_totalkeuanganharian').val());
		
		var totalkeseluruhan = totalpecahan+total;
		$('#Pencatatankeuanganharian_totalkeuanganharian').val(totalkeseluruhan);
	}
	
</script>