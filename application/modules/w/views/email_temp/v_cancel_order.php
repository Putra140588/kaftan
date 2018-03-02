<?php $this->load->view('email_temp/v_header_office');?>
<table style="width:100%">
	<tr>
		<td></td>
		<td class="container" bgcolor="#FFFFFF">
		<div style="margin:0 auto;max-width:600px;padding:15px;display:block">
			<table>
				<tr>
					<td>
						<h3>Hi, <?php echo $name?></h3>
						<p style="font-size:17px;"><?php echo sprintf(lang('ecncl1'),$id_order)?></p>
						<p></p>						
						<p style="padding:15px;background-color:#ECF8FF;margin-bottom:15px">
							<?php echo lang('ecncl2')?> 
						</p>		
						<p><a href="<?php echo base_url(FOMODULE)?>"><?php echo lang('reorder')?></a></p>														
					</td>
				</tr>
			</table>
		</div>					
		</td>
		<td></td>
	</tr>
</table>
<?php $this->load->view('email_temp/v_footer_office');?>

