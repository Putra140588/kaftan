							<div class="widget-box transparent">
											<div class="widget-header widget-header-large">
												<h3 class="widget-title grey lighter">
													<i class="ace-icon fa fa-leaf green"></i>
													<?php echo $name_title?>
												</h3>
												
											</div>

											<div class="widget-body">
												<div class="widget-main padding-24">
													<div class="row">
														<div class="col-sm-4">
															<div class="row">
																<div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
																	<b>Supplier</b>
																</div>
															</div>

															<div>
																<div class="space-6"></div>
																<h4><b><?php echo $name_supplier?></b></h4>
																<ul class="list-unstyled spaced">
																	<li>
																		<?php echo $address?>
																	</li>
																	<li>
																		<?php echo $city?>
																	</li>
																	<li>
																		<?php echo $phone?>
																	</li>																																
																</ul>
															</div>
														</div><!-- /.col -->

														<div class="col-sm-4">
															<div class="row">
																<div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
																	<b>Shipp To</b>
																</div>
															</div>

															<div>
																<div class="space-6"></div>
																<h4><b><?php echo $first_name.' '.$last_name?></b></h4>
																<ul class="list-unstyled  spaced">
																	<li>
																		<?php echo $address_branch?>
																	</li>
																	<li>
																		<?php echo $districts_name?>
																	</li>
																	<li>
																		<?php echo $cities_name?>
																	</li>
																	<li>
																		<?php echo $province_name?>
																	</li>																	
																</ul>
															</div>
														</div><!-- /.col -->
														<div class="col-sm-4">
															<div class="well">
																<table class="table table-striped">
																	<tbody>
																		<tr><td>Purchase Number :</td>
																			<td><?php echo isset($id_purchase_order) ? '<h4><b>'.$id_purchase_order.'</b></h4>' : 'N/A'?></td>
																		</tr>
																		<tr><td>Purchase Date :</td>
																			<td><?php echo long_date($purchase_date)?></td>
																		</tr>
																		<tr><td>Shipp Via :</td>
																			<td><?php echo $name_courier?></td>
																		</tr>
																		<tr><td>Tax :</td>
																			<td><?php echo $rate_tax?> %</td>
																		</tr>
																		<tr><td>Currency :</td>
																			<td><?php echo $name_currency?></td>
																		</tr>
																	</tbody>
																</table>
															</div>
														</div>
													</div><!-- /.row -->

													<div class="space"></div>

													<div>
														<table class="table table-striped table-bordered">
															<thead>
																<tr>
																	<th class="center">#</th>
																	<th>Product</th>
																	<th>Description</th>
																	<th>Qty</th>
																	<th>Unit Price</th>
																	<th>Amount</th>
																</tr>
															</thead>
															<tbody>
															<?php 
															$no=1;
															foreach ($podetail as $row){
																echo '<tr><td>'.$no++.'</td>
																		  <td>'.$row->name_product.'<br>'.$row->name_group.' '.$row->name_attribute.'</td>
																		  <td>'.$row->description_det.'</td>
																		  <td>'.$row->unit_qty.'</td>
																		  <td>'.$symbol.' '.number_format($row->unit_price,2,'.','.').'</td>
																		  <td>'.$symbol.' '.number_format($row->total_unit_price,2,'.','.').'</td>
																	</tr>';
															}
															?>					
																	<tr>
																		<td colspan=5><h4 class="pull-right">Sub Total :</h4></td>
																		<td><h4 class="center">
																				<span><?php echo $symbol.' '.number_format($total_price_extax,2,'.','.')?></span>
																			</h4>
																		</td>
																	</tr>	
																	<tr>
																		<td colspan=5><h4 class="pull-right">Tax :</h4></td>
																		<td><h4 class="center">
																				<span><?php echo $rate_tax?> %</span>
																			</h4>
																		</td>
																	</tr>
																	<tr>
																		<td colspan=5><h4 class="pull-right">Tax Value :</h4></td>
																		<td><h4 class="center">
																				<span><?php echo $symbol.' '.number_format($price_tax,2,'.','.')?></span>
																			</h4>
																		</td>
																	</tr>
																	<tr>
																		<td colspan=5><h4 class="pull-right">Total amount :</h4></td>
																		<td><h4 class="center">
																				<span class="red"><?php echo $symbol.' '.number_format($total_price_inctax,2,'.','.')?></span>
																			</h4>
																		</td>
																	</tr>									
															</tbody>
														</table>
													</div>

													<div class="hr hr8 hr-double hr-dotted"></div>																								
													<div class="row">															
													<div class="col-sm-7 pull-left"> Say : <b><?php echo terbilang($total_price_inctax)?></b></div>	
													</div>

													<div class="space-6"></div>
													<div class="well">
														Description : <p>
														<?php echo $description?>
													</div>
												</div>
											</div>
										</div>