						<div class="form-group required">
							<label class="col-sm-4 control-label">Label Move</label>
							<div class="col-sm-6">
							 <?php echo $this->m_content->chosen_label_movement($id_label_movement)?>
							</div>							
						</div>	
						<div class="form-group required">
							<label class="col-sm-4 control-label">Qty</label>
							<div class="col-sm-6">
								<input type="text" name="qty_input" class="col-sm-3" placeholder="Qty" required>								
							</div>							
						</div>
						<div class="form-group required">
							<label class="col-sm-4 control-label">Warehouse</label>
							<div class="col-sm-3">
								<select name="warehouse" required class="form-control clear" onchange="ajaxcall('<?php echo base_url(MODULE.'/stockmgm/choose_warehouse')?>',this.value,'whlocfrom')">
									<option value="" selected disabled>--</option>
									<?php $wh = $this->m_admin->get_warehouse();
									foreach ($wh as $v){
										echo '<option value="'.$v->id_warehouse.'">'.$v->name_warehouse.'</option>';
									}?>	
								</select>	
								<?php 
								//use for stock transfer
								if ($id_label_movement == 3){
									echo 'From <select name="warehouse_to" required class="form-control clear" onchange="ajaxcall(\''.base_url(MODULE.'/stockmgm/choose_warehouse').'\',this.value,\'whlocto\')">
											<option value="" selected disabled>--</option>';
									foreach ($wh as $v){
										echo'<option value="'.$v->id_warehouse.'">'.$v->name_warehouse.'</option>';
									}
									echo'</select> To';
								}?>				
							</div>							
						</div>
						<div class="form-group required">
							<label class="col-sm-4 control-label">Location</label>
							<div class="col-sm-3">
								<select name="location" required class="form-control clear" id="whlocfrom">
									<option value="" selected disabled>--</option>
								</select>	
								<?php if ($id_label_movement == 3){
					 echo 'From <select name="location_to" class="form-control clear" id="whlocto" required>
									<option value="" selected disabled>--</option>
							  </select> To';
								}?>								
							</div>							
						</div>
						<input type="hidden" name="attribute" value="0">	