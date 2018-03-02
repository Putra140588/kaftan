<!-- FOOTER -->
<table style="width: 100%;	clear:both!important;">
	<tr>
		<td></td>
		<td class="container">
				<!-- content -->
				<div style="margin:0 auto;max-width:600px;padding:15px;display:block">
					<table style="width:100%">
					<tr>
					<td align="center">
						<!-- office address -->
							<table style="background-color: #ebebeb; width:100%">
								<tr>
									<td>								
										<table style="width:300px;float:left">
											<tr>
												<td style="padding: 15px;">										
													<h5>Hubungi kami:</h5>		
													<?php echo $this->session->userdata('address_company')?>	                
												</td>
											</tr>
										</table>
										
										<span class="clear"></span>	
									</td>
								</tr>
							</table><!-- /office address -->
							<p style="margin-top:20px">
								&copy; <?php echo $this->session->userdata('company_name')?>
							</p>
						</td>
					</tr>
				</table>
			</div><!-- /content -->				
		</td>
		<td></td>
	</tr>
</table><!-- /FOOTER -->
</body>
</html>