<div class="page-header">
	<h1>Add Status</h1>
</div>
<div class="form-group required">
	<label class="col-sm-4 control-label">Status</label>
	<div class="col-sm-3">
		<select class="chosen-select form-control clear" name=codestatus data-placeholder="Choose a statusess...">
			<option value=""/>
			<?php foreach ($statuses as $row){
				echo '<option value="'.$row->code_status.'#'.$row->name_status.'">'.$row->name_status.'</option>';
			}?>
		</select>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">
		Date Add
	</label>
	<div class="col-sm-2">
		<div class="input-group">
			<input type="text" class="date-picker input-sm form-control clear" name="dateadd" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd"/>
			<span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>					
		</div>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">
		Notes
	</label>
	<div class="col-sm-4">
		<input type="text" placeholder="Notes" name="notes" class="col-xs-10 col-sm-12 clear"/>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label"></label>
	<div class="col-sm-2">
		<button class="btn btn-white btn-info btn-bold ace-icon fa fa-floppy-o bigger-120 btnSubmit" type="button" data-rel="tooltip" data-placement="top" title="" onclick="ajaxform('<?php echo base_url(MODULE.'/'.$class.'/save_status')?>','historystatus',$('#form-ajax')[0])" data-original-title="Save"> Save</button>
	</div>
</div>
<?php $this->load->view($class.'/detail/vw_statuses')?>
<script>$('.date-picker').datepicker({
	autoclose: true,
	todayHighlight: true
})</script>