			<div class="form-group">
				<label class="col-sm-4 control-label">Available From</label>
				<div class="col-sm-2">
					<div class="input-group">
						<input type="text" class="date-picker input-sm form-control" name="spfrom" placeholder="Date From" data-date-format="yyyy-mm-dd"/>
						<span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>					
					</div>
				</div>				
				<div class="col-sm-2">
					<div class="input-group">
						<input type="text" class="date-picker input-sm form-control" name="spto" placeholder="Date To" data-date-format="yyyy-mm-dd"/>
						<span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>					
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label"> Specific Price </label>
				<div class="col-sm-2">
					<input type="text" placeholder="<?php echo $_SESSION['symbol']?>" name="specificprice" class="col-xs-10 col-sm-5"  value="<?php echo isset($final_price) ? $final_price : ''?>" id="sprice" onkeypress="return decimals(event,this.id)"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label"> Discount </label>
				<div class="col-sm-2">
					<input type="text" placeholder="%" name="specificdisc" readonly class="col-xs-10 col-sm-5"  id="spdisc" onkeypress="return decimals(event,this.id)"/>
				</div>				
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label"> Use Discount </label>				
				<div class="col-sm-2 checkbox">
					<label>
						<input class="ace" name="chx-disc" type="checkbox" id="chxdisc">
					<span class="lbl"> </span>
					</label>
				</div>
			</div>
			<input type="hidden" name="attribute" value="0">