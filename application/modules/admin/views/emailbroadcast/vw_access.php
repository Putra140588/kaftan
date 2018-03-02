<div class="main-content">
	<div class="main-content-inner">
		<?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">								
						<input type="hidden" name="email_modul_code" value="<?php echo $email_modul_code?>">		
						<input type="hidden" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">		
						<div class="widget-box transparent">
							 <?php $this->load->view('vw_header_title')?>		
							<div class="widget-body">
								<div class="widget-main padding-6 no-padding-left no-padding-right">
									<div class="space-6"></div>
									<?php $select='';?>	
									<table class="display wrap dt-tables" cellspacing="0" width="100%" value="<?php echo base_url('admin/'.$class.'/get_emp/'.$email_modul_code)?>">
										<thead>
							            <tr>
								            <th>#</th>	
								            <th class="no-sort center">
								            	<label><input type="checkbox" class="ace" id="active-all" value="<?php echo $email_modul_code?>" onclick="ajaxcheck('<?php echo base_url(MODULE.'/'.$class.'/active/1')?>',this.value,this)"><span class="lbl"></span>
								            </th>				            									
											<th>Employee</th>
											<th>Email</th>																																														
																																																																														
										</tr>
					       				 </thead>
					       				 <thead>
											<tr>
												<td></td>
												<td></td>
												<td><input type="text" id="1" class="search-input"></td>
												<td><input type="text" id="2" class="search-input"></td>																																																							
																																												
											</tr>
										</thead>	
									</table>				
								</div>
							</div>
						</div>																	
				  </form>
			 </div>
		  </div>
	  </div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){		
	loaddatatable(".dt-tables");		
});
$("#active-all").on('click',function(e){
	if (this.checked){
		$(".active-row").prop('checked',true);
	}else{
		$(".active-row").prop('checked',false);
	}
})
</script> 