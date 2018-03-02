<?php 
$id_product = isset($id_product) ? $id_product : '';
if ($stock == true){
	echo '<div class="alert alert-warning">Stock Or Specific Price this product already exist, delete Stock Or Specific Price first for add product attribute!</div>';
}else{?>
<div class="form-group">
	<label class="col-sm-4 control-label">Group</label>
	<div class="col-sm-3">
		<select name="groupattr" class="form-control" onchange="ajaxcall('<?php echo base_url(MODULE.'/product/show_attribute')?>',this.value,'attributes')">
			<option value="" selected disabled>Choose a group</option>
			<?php foreach ($groupattr as $row){
				echo '<option value="'.$row->id_attribute_group.'">'.$row->name_group.'</option>';
			}?>
		</select>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">Attribute</label>
	<div class="col-sm-8">		
		<table class="df-tables table">
			<thead>
				<tr><th>#</th>
					<th class="center no-sort">
						<input type="checkbox" class="ace ace-checkbox-2" id="chkattrall"/>
						<span class="lbl"></span>
					</th>
					<th>Attribute</th>
					<th>Impact Price</th>
					<th>Sort</th>
					<th>Active</th>									
					<th>Default</th>	
				</tr>
			</thead>
			<tbody id="attributes">			
				
			</tbody>
		</table>		
		*Checked attribute for add new product attribute
	</div>
</div>

<div class="form-group">
	<div class="col-sm-4 control-label">Product Attribute</div>
	<div class="col-sm-8">
		<table class="df-tables table table-striped">
			<thead>
				<tr><th>#</th>			
					<th>Group</th>		
					<th>Attribute</th>
					<th>Impact Price</th>
					<th>Sort</th>
					<th>Active</th>	
					<th>Default</th>
					<th>Add By</th>
					<th>Date Add</th>
					<th>Actions</th>								
				</tr>
			</thead>
			<tbody id="prodattr">			
				<?php								
				echo $this->m_content->table_product_attribute($id_product)?>
			</tbody>
		</table>	
		Notes :<br>
		1. If You delete product attribute, then stock is also deleted<br>
		2. Select only one default attribute
	</div>
</div>
<?php }?>
<script>
$("#chkattrall").on('click',function(e){
	if (this.checked){
		$(".rowattr").prop('checked',true);		
	}else{
		$(".rowattr").prop('checked',false);		
	}
})
</script>