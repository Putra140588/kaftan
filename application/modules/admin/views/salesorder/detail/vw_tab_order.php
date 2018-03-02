<div class="row">
	<div class="col-xs-12 col-md-12">
		<?php $this->load->view($class.'/detail/vw_info_order')?>
	</div>		
</div>
<div class="hr hr-8 dotted"></div>
<div id="listproduct">
	<?php $this->load->view($class.'/detail/vw_list_product')?>
</div>
<?php $this->load->view($class.'/detail/vw_info_shipp_pay')?>