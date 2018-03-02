						<div class="form-group required">
							<label class="col-sm-4 control-label">Label Move</label>
							<div class="col-sm-6">
							<?php echo $this->m_content->chosen_label_movement($id_label_movement)?>
							</div>							
						</div>				
						<div class="form-group required">
							<label class="col-sm-4 control-label">Stock</label>
							<div class="col-sm-8">
								<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th class="center no-sort">
											<input type="checkbox" class="ace ace-checkbox-2" id="chklblall"/>
											<span class="lbl"></span>
										</th>										
										<th>Qty</th>
										<th>Group</th>																					
										<th>Attribute</th>											
										<th>Warehouse</th>
										<th>Location</th>																				
									</tr>
								</thead>
								<tbody>
									<?php echo $this->m_content->table_stock_attribute($sql,$id_label_movement)?>
								</tbody>
							</table>
							</div>							
						</div>
						<input type="hidden" name="attribute" value="1">		
<script>
$("#chklblall").on('click',function(e){
	if (this.checked){
		$(".rowlbl").prop('checked',true);		
	}else{
		$(".rowlbl").prop('checked',false);		
	}
})
</script>						