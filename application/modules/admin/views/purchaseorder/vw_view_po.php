<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
					<div class="page-content">						
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<?php $this->load->view('vw_alert_notif')?>	
								<div class="center">
									<button class="btn btn-white btn-info btn-bold">
									<i class="ace-icon fa fa-print bigger-120 blue"></i>
									Print Out
									</button>
								</div>																							
								<?php $this->load->view($class.'/vw_summary')?>
								
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
