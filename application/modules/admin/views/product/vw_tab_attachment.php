<div class="form-group">
	<div class="col-sm-4 control-label">Attachments</div>
	<div class="col-sm-3">
		<table class="table">
			<thead>
				<tr><th>#</th>
					<th class="center no-sort">
						<input type="checkbox" class="ace ace-checkbox-2" id="chkatchall"/>
						<span class="lbl"></span>
					</th>
					<th>Name</th>
					<th>File</th>					
			</thead>
			<tbody id="attachments">
				<?php 
				$id_product = isset($id_product) ? $id_product : '';
				$id_manufacture = isset($id_manufacture) ? $id_manufacture : 0;				
				echo $this->m_content->table_attachment($id_manufacture,$id_product);?>
			</tbody>
		</table>
	</div>
</div>
<script>
$("#chkatchall").on('click',function(e){
	if (this.checked){
		$(".rowattch").prop('checked',true);		
	}else{
		$(".rowattch").prop('checked',false);		
	}
})
</script>