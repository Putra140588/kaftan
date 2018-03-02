<div class="form-group">
	<div class="col-sm-4 control-label">Meta Title</div>
	<div class="col-sm-8">
	<input type="text" placeholder="Meta Title" name="meta_title" class="col-xs-10 col-sm-5" value="<?php echo isset($meta_title) ? $meta_title : ''?>"/>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label"> Meta Description </label>
	<div class="col-sm-5">
		<textarea name="metadesc" id="metadesc" class="textareas"><?php echo isset($meta_description) ? $meta_description : ''?></textarea>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label"> Meta Keyword </label>
	<div class="col-sm-8">
		<input type="text" placeholder="Meta Keyword" name="metakey" class="col-xs-10 col-sm-5" value="<?php echo isset($meta_keywords) ? $meta_keywords : ''?>"/>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label"> Friendly URL </label>
	<div class="col-sm-8">
		<input type="text" placeholder="Friendly URL" name="friendlyurl" id="friendlyurl" class="col-xs-10 col-sm-5" value="<?php echo isset($permalink) ? $permalink : ''?>"/>		
	</div>	
</div>
<div class="form-group">
<label class="col-sm-4 control-label"></label>
	<div class="col-sm-5">
		<button class="btn btn-white btn-info" type="button" onclick="ajaxcall('<?php echo base_url(MODULE.'/product/generate_url')?>',$('#productname').val(),'friendlyurl')">Generate Friendly URL</button>
	</div>
</div>