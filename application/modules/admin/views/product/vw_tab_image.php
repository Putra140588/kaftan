<?php $id_product = isset($id_product) ? $id_product : '';?>
<div class="form-group">
	<label class="col-sm-4 control-label">Image Count</label>
	<div class="col-sm-2">
			<select class="form-control" name="count" id="count" onchange="ajaxcall('<?php echo base_url(MODULE.'/product/image_count/'.$id_product)?>',this.value,'imagerow')">				
				<option value="" selected disabled>Choose a Count</option>				
			<?php 
				for($i=1; $i <= 10; $i++){					
					echo '<option value="'.$i.'">'.$i.'</option>';
				}
			?>
		</select>												
	</div>											
</div>	
<div class="form-group">
	<div class="col-sm-4 control-label">Images Row</div>
	<div class="col-sm-5">		
		<table class="df-tables table">
			<thead>
				<tr><th>#</th>
					<th class="center no-sort">
						<input type="checkbox" class="ace ace-checkbox-2" id="chkimgall"/>
						<span class="lbl"></span>
					</th>
					<th>Images</th>
					<th>Sort</th>
					<th>Active</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody id="imagerow">			
				<?php								
				echo $this->m_content->table_image($id_product)?>
			</tbody>
		</table>		
	</div>
</div>
<div class="form-group">
	<div class="col-sm-4 control-label"></div>
	<div class="col-sm-5">*Checked images if you want to add new &amp; edit</div>
</div>
<script>
$("#chkimgall").on('click',function(e){
	if (this.checked){
		$(".rowimg").prop('checked',true);		
	}else{
		$(".rowimg").prop('checked',false);		
	}
})

</script>