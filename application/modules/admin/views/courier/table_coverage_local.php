								<div class="space-6"></div>
								<table class="display wrap sc-tables" cellspacing="0" width="100%" value="<?php echo base_url('admin/'.$class.'/zone_coverage_local/'.$id_cities.'/'.$id_courier.'/1')?>">
									<thead>
						            <tr>
							            <th>#</th>					            									
										<th class="center no-sort">
											<input type="checkbox" class="ace ace-checkbox-2" id="chkcostall"/>
											<span class="lbl"></span>
										</th>
										<th>ID</th>																											
										<th>Zone</th>	
										<th class="no-sort">
											<label>
												<input type="checkbox" class="ace ace-checkbox-2" id="costweight"/>
												<span class="lbl"> Weight</span>
											</label> 
										</th>	
										<th class="no-sort">
											<label>
												<input type="checkbox" class="ace ace-checkbox-2" id="costprice"/>
												<span class="lbl"> Price (<?php echo $_SESSION['symbol']?>)</span>
											</label> 
										</th>																																																																											
									</tr>
			       				 </thead>
			       				 <thead>
									<tr>
										<td></td>
										<td></td>
										<td><input type="text" id="2" class="search-input"></td>
										<td><input type="text" id="3" class="search-input"></td>	
										<td><input type="text" id="4" class="search-input"></td>	
										<td><input type="text" id="5" class="search-input"></td>																														
									</tr>
								</thead>	
								</table>
								<div class="space-10"></div>
								<button class="btn btn-warning btn-sm" id="costedit"><i class="fa fa-edit"></i> Save Edit</button>
								<button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure delete country zone coverage?')"><i class="fa fa-trash"></i> Delete</button>	
<script type="text/javascript">
$(document).ready(function(){		
	loaddatatable(".sc-tables");	
	$("#costedit").click(function(){
		$('input[name="edtbtn"]').val(true);
	});	
});
$("#chkcostall").on('click',function(e){
	if (this.checked){
		$(".rowcost1").prop('checked',true);
	}else{
		$(".rowcost1").prop('checked',false);
	}
})
$("#costweight").on('click',function(e){
	var val = document.getElementById('cwf1').value;	
	if (this.checked){		
		$(".costwght").val(val);//mengisi pada dari nilai urut yang pertama
	}else{
		$(".costwght").val('');
	}
})
$("#costprice").on('click',function(e){
	var val = document.getElementById('cprc1').value;	
	if (this.checked){		
		$(".costprce").val(val);//mengisi pada dari nilai urut yang pertama
	}else{
		$(".costprce").val('');
	}
})
</script>  