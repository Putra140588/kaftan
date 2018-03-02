<?php $this->load->view('email_temp/v_header_office');?>
<!-- BODY -->
<table style="width:100%">
	<tr>
		<td></td>
		<td class="container" bgcolor="#FFFFFF">
		<div style="margin:0 auto;max-width:600px;padding:15px;display:block">
			<table>
				<tr>
					<td>
						<h3>Hi, <?php echo $name?></h3>
						
						<p style="font-size:17px;"><?php echo sprintf(lang('etext1'),tab_title('tab_title'))?></p>
						<p></p>						
						<p style="padding:15px;background-color:#ECF8FF;margin-bottom:15px">
						<?php $link = '<a href="'.base_url(FOMODULE.'/account/activasi/'.$id_customer.'/'.$encode).'" title="'.lang('etitle').'">Aktivasi&raquo;';
						echo sprintf(lang('etext2'),tab_title('tab_title'),$link)?> </a> 
						</p>				
						<p><?php echo lang('etext3')?></p>									
					</td>
				</tr>
			</table>
		</div><!-- /content -->							
		</td>
		<td></td>
	</tr>
</table><!-- /BODY -->
<?php $this->load->view('email_temp/v_footer_office');?>

