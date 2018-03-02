<input type="hidden" name="attribute" value="1">
<div class="form-group">
	<label class="col-sm-4 control-label">Product Attribute</label>
	<div class="col-sm-8">
		<table class="table">
			<thead>
				<tr><th>#</th>
					<th class="center no-sort">
						<input type="checkbox" class="ace ace-checkbox-2" id="chkspall"/>
						<span class="lbl"></span>
					</th>
					<th>Available From</th>
					<th>Specific Price</th>
					<th>Discount %</th>					
					<th>Attribute</th>
					<th>Impact Price</th>
			</thead>
			<tbody>
				<?php $sql = $this->m_admin->get_product_attribute(array('A.id_product'=>$id_product));
				$no=1;
				foreach ($sql as $row){
					$final_price = isset($final_price) ? $final_price : '';
					echo '<tr><td>'.$no.'</td>
							  <td><center><label><input type="checkbox" class="ace ace-checkbox-2 rowsp" name="checsp[]" value="'.$row->id_product_attribute.'"><span class="lbl"></span></label></center></td>
							  <td><div class="input-group">
									<input type="text" class="date-picker input-sm form-control" name="datasp['.$row->id_product_attribute.'][date_from]" placeholder="Date From" data-date-format="yyyy-mm-dd"/>
									<span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>					
								</div>								 
								<div class="input-group">
									<input type="text" class="date-picker input-sm form-control" name="datasp['.$row->id_product_attribute.'][date_to]" placeholder="Date To" data-date-format="yyyy-mm-dd"/>
									<span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>					
								</div>
							  </td>
							  <td><input type="text" name="datasp['.$row->id_product_attribute.'][price_sp]" placeholder="'.$_SESSION['symbol'].'" class="col-sm-8 clear" id="pricesp'.$no.'" onkeypress="return decimals(event,this.id)" value="'.$final_price.'"></td>
							  <td><input type="text" name="datasp['.$row->id_product_attribute.'][disc_sp]" placeholder="%" class="col-sm-8 clear" id="discsp'.$no.'" onkeypress="return decimals(event,this.id)"></td>
							  <td>'.$row->name_group.' '.$row->name.'</td>
							  <td>'.$row->symbol.' '.number_format($row->price_impact,2).'</td>
						</tr>';
					$no++;
				}?>
			</tbody>
		</table>
	</div>
</div>
<script>
$("#chkspall").on('click',function(e){
	if (this.checked){
		$(".rowsp").prop('checked',true);		
	}else{
		$(".rowsp").prop('checked',false);		
	}
})
</script>