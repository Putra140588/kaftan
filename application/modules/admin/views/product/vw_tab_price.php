<div class="form-group required">
	<label class="col-sm-4 control-label"> Base Price </label>
	<div class="col-sm-2">
		<input type="text" placeholder="<?php echo $_SESSION['symbol']?>" name="baseprice" class="col-xs-10 col-sm-5" value="<?php echo isset($base_price) ? $base_price : ''?>" id="baseprice" onkeypress="return decimals(event,this.id)" onkeyup="ajaxcall('<?php echo base_url(MODULE.'/product/add_tax')?>',$('#taxes').val() + '#' + this.value,'finalprice')" required/>
	</div>
</div>
<div class="form-group required">
	<label class="col-sm-4 control-label">Tax</label>
	<div class="col-sm-2">		
		<select class="form-control" name="tax" id="taxes" onchange="javascript:ajaxcall('<?php echo base_url(MODULE.'/product/add_tax')?>',this.value + '#' + $('#baseprice').val(),'finalprice')" required>
			<option value="" selected disabled>Choose a Tax</option>
			<?php 
			$tax = $this->m_admin->get_table('tb_tax','*',array('deleted'=>0));
			foreach ($tax as $row){
				if (!empty($id_tax)){$select = ($id_tax == $row->id_tax) ? 'selected' : '';}
				echo '<option value="'.$row->id_tax.'#'.$row->rate.'" '.$select.'>'.$row->name.'</option>';
			}
			?>
		</select>		
	</div>
</div>
<div class="form-group required">
	<label class="col-sm-4 control-label"> Final Price </label>
	<div class="col-sm-2">
		<input type="text" placeholder="<?php echo $_SESSION['symbol']?>" name="finalprice" class="col-xs-10 col-sm-5" value="<?php echo isset($final_price) ? $final_price : ''?>" id="finalprice" onkeypress="return decimals(event,this.id)" readonly required/>
	</div>
</div>
<?php if (isset($id_product)){
	$this->load->view($class.'/vw_spesific_price');
}?>
