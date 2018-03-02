<div class="hr dotted hr-double"></div>
<div class="tabbable">
	<ul class="nav nav-tabs padding-18">
		<li class="active">
			<a data-toggle="tab" href="#sp">
			<i class="green ace-icon fa fa-leaf bigger-120"></i>
				Specific Prices
			</a>
		</li>
		<li>
			<a data-toggle="tab" href="#detail">
				<i class="orange ace-icon fa fa-rss bigger-120"></i>
				Details
			</a>
		</li>
	</ul>
	<div class="tab-content no-border padding-24">
		<div id="sp" class="tab-pane in active">
			<div class="form-group">
				<label class="col-sm-4 control-label">Group</label>
				<div class="col-sm-3">
					<select name="spgroup" class="form-control" onchange="ajaxcall('<?php echo base_url(MODULE.'/'.$class.'/show_customer')?>',this.value,'custgroup')">
						<option value="" selected disabled>Choose a group</option>
						<?php foreach ($group as $row){
							echo '<option value="'.$row->id_group.'">'.$row->name_group.'</option>';
						}?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Customer</label>
				<div class="col-sm-3" id="custgroup">
					<select name="customer" class="form-control">
						<option value="" selected disabled>Choose a customer</option>
					</select>
				</div>
			</div>
			<?php $attr = $this->m_admin->get_product_attribute(array('A.id_product'=>$id_product));
			if (count($attr) > 0){
				$this->load->view($class.'/vw_sp_attribute');
			}else{
				$this->load->view($class.'/vw_sp_nonattribute');
			}?>			
			<div class="form-group">
				<label class="col-sm-4 control-label"></label>				
				<div class="col-sm-2">
					<button type="button" class="btn btn-white btn-info btn-bold ace-icon fa fa-floppy-o bigger-120 btnSubmit" data-rel="tooltip" data-placement="top" title="Save" onclick="ajaxform('<?php echo base_url(MODULE.'/'.$class.'/save_specific_price')?>','listsp',$('#form-ajax')[0])"> Save</button>	
					
				</div>
			</div>
		</div>
		<div id="detail" class="tab-pane">
			<div class="space-6"></div>
			<table class="df-tables table table-striped">
				<thead>
					<tr><th>#</th>
						<th>Group</th>
						<th>Customer</th>
						<th>Email</th>						
						<th>Specific Price</th>
						<th>Discount %</th>
						<th>Attribute</th>
						<th>Impact Price</th>
						<th>Until</th>
						<th>Add By</th>
						<th>Date Add</th>
						<th>Actions</th>
				</thead>
				<tbody id="listsp">
					<?php echo $this->m_content->table_list_sp($ceksp)?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
$("#chxdisc").change(function(){
	var boel = true;
	if (this.checked){
		boel = false;
	}
	$('input[name=specificdisc]').prop("readonly",boel);
});
</script>