<div class="main-content">
				<div class="main-content-inner">
				<?php $this->load->view('vw_header_index')?>
					<div class="page-content">						
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<?php $this->load->view('vw_alert_notif')?>																								
																
								<div class="row ">
									<div class="col-md-4 col-xs-8 col-sm-11 pull-right">
										<?php $data = array('min'=>0,'max'=>9);$this->load->view('vw_date_range',$data)?>
									</div>
									<div class="btn-export"></div>								
								</div>														
								<hr>
								<div class="space-6"></div>
								<table class="display wrap dt-tables" cellspacing="0" width="100%" value="<?php echo base_url('admin/'.$class.'/get_records')?>">
									<thead>
						            <tr>
							            <th>#</th>					            									
										<th>ID Order</th>																											
										<th>Account Name</th>	
										<th>Amount Transfer</th>
										<th>Bank From</th>
										<th>Method Payment</th>
										<th>Method Transfer</th>
										<th>Date Transfer</th>
										<th>Status</th>																																																												
										<th>Date Add</th>																			
										<th class="no-sort">Actions</th>																																																																			
									</tr>
			       				 </thead>
			       				 <thead>
									<tr>
										<td></td>
										<td><input type="text" id="1" class="search-input"></td>
										<td><input type="text" id="2" class="search-input"></td>
										<td><input type="text" id="3" class="search-input"></td>
										<td><input type="text" id="4" class="search-input"></td>	
										<td><input type="text" id="5" class="search-input"></td>	
										<td><input type="text" id="6" class="search-input"></td>
										<td><input type="text" id="7" class="search-input"></td>
										<td><input type="text" id="8" class="search-input"></td>	
										<td><input type="text" id="9" class="search-input"></td>																																														
										<td></td>																																
									</tr>
								</thead>	
								</table>
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
<script type="text/javascript">
$(document).ready(function(){		
	loaddatatable(".dt-tables");		
});
</script>  