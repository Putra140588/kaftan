<div class="notifalert"></div>
<div class="form-group">
	<label class="col-sm-4 control-label">ID Order</label>
	<div class="col-sm-3">
		<input type="text" readonly value="<?php echo $id_order?>" class="col-xs-10 col-sm-8">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">Total Amount</label>
	<div class="col-sm-3">
		<input type="text" readonly value="<?php echo $symbol.' '.$totalpayment = ($iso_code == 'IDR') ? formatnum($total_payment) : $total_payment;?>" class="col-xs-10 col-sm-8"><?php echo $iso_code?>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">Total Balance</label>
	<div class="col-sm-3">
		<input type="text" readonly value="<?php echo $symbol.' '.$totalbalance = ($iso_code == 'IDR') ? formatnum($total_balance) : $total_balance;?>" class="col-xs-10 col-sm-8"><?php echo $iso_code?>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">Status</label>
	<div class="col-sm-3">
		<?php echo '<span class="'.pay_status($payment_result).'">'.$payment_result.'</span>';?>
	</div>
</div>
<div class="form-group required">
	<label class="col-sm-4 control-label">Enter Amount Pay</label>
	<div class="col-sm-3">
		<?php 
		$totpay = ($iso_code == 'IDR') ? intval($total_balance) : $total_balance;
		$onkeyup = ($iso_code == 'IDR') ? 'onkeyup="document.getElementById(\'amountformat\').innerHTML=formatPrice(this.value)"' : 'onkeyup="document.getElementById(\'amountformat\').innerHTML=this.value"';?>
		<input type="text" name="amountpay" id="amountpay" placeholder="<?php echo $symbol?>" value="<?php echo str_replace("-", '', $totpay)?>" onkeypress="return decimals(event,this.id)" <?php echo $onkeyup?> class="col-xs-10 col-sm-8 paid"><?php echo $iso_code?>
		<?php 
		$tobal = ($iso_code == 'IDR') ? formatnum($total_balance) : $total_balance;
		echo '<h3>'.$symbol.' <span id="amountformat">'.str_replace("-", '', $tobal).'</span></h3>'?>
	</div>
</div>
<div class="form-group required">
	<label class="col-sm-4 control-label">
		Date of Pay
	</label>
	<div class="col-sm-2">
		<div class="input-group">
			<input type="text" class="date-picker input-sm form-control paid" name="datepay" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd"/>
			<span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>					
		</div>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">
		Notes
	</label>
	<div class="col-sm-4">
		<input type="text" placeholder="Notes" name="paynote" class="col-xs-10 col-sm-12 paid"/>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label"></label>
	<div class="col-sm-2">
		<button class="btn btn-white btn-info btn-bold ace-icon fa fa-floppy-o bigger-120 paid" type="button" data-rel="tooltip" data-placement="top" title="" onclick="ajaxform('<?php echo base_url(MODULE.'/'.$class.'/save_pay/')?>','content',$('#form-ajax')[0])" data-original-title="Save"> Save</button>
	</div>
</div>
<?php if ($payment_result == 'Paid'){?>
<script type="text/javascript">
	$(".paid").prop('disabled',true);
	$(".notifalert").each(function(e){
		$(this).addClass('alert alert-warning');
		$(this).text('Order already paid, form is disabled!');
	})
		
</script>
<?php }?>
<script>
$('.date-picker').datepicker({
	autoclose: true,
	todayHighlight: true
})</script>