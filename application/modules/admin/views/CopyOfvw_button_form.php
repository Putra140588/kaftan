<div class="widget-header">
<h4 class="widget-title lighter"><?php echo $page_title?><?php echo isset($sub_title) ? ' <small><i class="ace-icon fa fa-angle-double-right"></i> '.$sub_title.'</small>' : '';?></h4>	
	<div class="widget-toolbar">															
		<button type="submit" class="btn btn-white btn-info btn-bold ace-icon fa fa-floppy-o bigger-120 btnSubmit" data-rel="tooltip" data-placement="top" title="Save"></button>	
		<?php 
		$id_form = (isset($id_form)) ? 'formmodal' : 'form-ajax';
		if ( $id_form =='formmodal'){?>	
		<button class="btn btn-white btn-danger btn-bold ace-icon fa fa-times bigger-120" data-rel="tooltip" data-placement="top" title="Cancel" type="button" data-dismiss="modal"></button>																													
		<?php }else{?>
		<button class="btn btn-white btn-danger btn-bold ace-icon fa fa-times bigger-120" data-rel="tooltip" data-placement="top" title="Cancel" type="button" onclick="javascript:window.location.href='<?php echo base_url(MODULE.'/'.$class)?>'"></button>
		<?php }?>
		<div class="space-4"></div>																								
	</div>
</div>