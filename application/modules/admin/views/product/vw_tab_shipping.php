<div class="form-group">
	<label class="col-sm-4 control-label"> Length </label>
	<div class="col-sm-2">
		<input type="text" placeholder="cm" name="length" class="col-xs-10 col-sm-5" value="<?php echo isset($length) ? $length : ''?>" id="length" onkeypress="return decimals(event,this.id)"/>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label"> Width </label>
	<div class="col-sm-2">
		<input type="text" placeholder="cm" name="width" class="col-xs-10 col-sm-5" value="<?php echo isset($width) ? $width : ''?>" id="width" onkeypress="return decimals(event,this.id)"/>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label"> Heigth </label>
	<div class="col-sm-2">
		<input type="text" placeholder="cm" name="height" class="col-xs-10 col-sm-5" value="<?php echo isset($height) ? $height : ''?>" id="height" onkeypress="return decimals(event,this.id)"/>
	</div>
</div>
<div class="form-group required">
	<label class="col-sm-4 control-label"> Weight </label>
	<div class="col-sm-2">
		<input type="text" placeholder="kg" name="weight" class="col-xs-10 col-sm-5" value="<?php echo isset($weight) ? $weight : ''?>" id="weight" onkeypress="return decimals(event,this.id)" required/>
	</div>
</div>