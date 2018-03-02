<?php $id_product = isset($id_product) ? $id_product : '';?>
<div class="form-group">
	<div class="col-sm-4 control-label">Video Count</div>
	<div class="col-sm-2">
			<select class="form-control" name="video" id="video" onchange="ajaxcall('<?php echo base_url(MODULE.'/product/video_count/'.$id_product)?>',this.value,'videorow')">
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
	<div class="col-sm-4 control-label">Video Row</div>
	<div class="col-sm-8">		
		<table class="df-tables table">
			<thead>
				<tr><th>#</th>
					<th class="center no-sort">
						<input type="checkbox" class="ace ace-checkbox-2" id="chkvidall"/>
						<span class="lbl"></span>
					</th>
					<th>Video Title</th>
					<th>Video URL</th>
					<th>Sort</th>
					<th>Active</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody id="videorow">			
				<?php								
				echo $this->m_content->table_video($id_product)?>
			</tbody>
		</table>		
	</div>
</div>
<div class="form-group">
	<div class="col-sm-4 control-label"></div>
	<div class="col-sm-5">*Checked video if you want to add new &amp; edit<br>
	*Example Video Url : <b>https://www.youtube.com/embed/WbixzrwUdOU</b></div>
</div>
<script>
$("#chkvidall").on('click',function(e){
	if (this.checked){
		$(".rowvid").prop('checked',true);		
	}else{
		$(".rowvid").prop('checked',false);		
	}
})
</script>