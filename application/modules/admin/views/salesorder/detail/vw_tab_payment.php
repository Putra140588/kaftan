<div class="tabbable">
	<ul class="nav nav-tabs padding-18">
		<li class="active">
			<a href="javascript:void(0)" data-toggle="tab" onclick="ajaxcall('<?php echo base_url(MODULE.'/'.$class.'/view/'.$id_order)?>','inpay','paytrans')">Input Payment</a>
		</li>
		<li>
			<a href="javascript:void(0)" data-toggle="tab" onclick="ajaxcall('<?php echo base_url(MODULE.'/'.$class.'/view/'.$id_order)?>','refpay','paytrans')">Refund Payment</a>
		</li>
	</ul>
	<div class="tab-content padding-24">
		<div id="paytrans" class="tab pane active">
			<?php $this->load->view($class.'/detail/vw_input_payment')?>
		</div>
	</div>
</div>
<div class="widget-box transparent">
	<div class="widget-header widget-header-small">
		<h4 class="widget-title blue smaller">
			<i class="ace-icon fa fa-rss orange"></i>
				Data Payment
		</h4>													
	</div>
	<div class="widget-body">
		<div class="widget-main padding-8">
			<div id="datapay" class="profile-feed">
				<table class="table table-bordered table-hover">
					<thead>
						<tr><th>#</th>
							<th>Method</th>
							<th>Amount</th>
							<th>Paid</th>
							<th>Balanced</th>
							<th>Paypal Fee</th>
							<th>Status</th>
							<th>Date of Pay</th>
							<th>Date Add</th>
							<th>Add By</th>
							<th>Notes</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						$this->db->order_by('date_add_pay','desc');
						$sql = $this->m_admin->get_table('tb_order_pay','*',array('id_order'=>$id_order));
						if (count($sql) > 0){
							$no=1;
							foreach ($sql as $row){
								$total_amount = ($row->iso_code == 'IDR') ? number_format($row->total_amount,0,'.','.') : $row->total_amount;
								$total_pay = ($row->iso_code == 'IDR') ? number_format($row->total_pay,0,'.','.') : $row->total_pay;
								$total_balance = ($row->iso_code == 'IDR') ? number_format($row->total_balance,0,'.','.') : $row->total_balance;
								$total_fee = ($row->iso_code == 'IDR') ? number_format($row->paypal_fee_amount,0,'.','.') : $row->paypal_fee_amount;
								echo '<tr><td>'.$no++.'</td>
										  <td>'.$row->payment_method.'</td>
										  <td>'.$total_amount.'</td>
										  <td>'.$total_pay.'</td>
										  <td>'.$total_balance.'</td>
			 							  <td>'.$total_fee.'</td>
										  <td>'.$row->status_pay.'</td>
									      <td>'.short_date($row->m_date_add_pay).'</td>
										  <td>'.long_date_time($row->date_add_pay).'</td>
										  <td>'.$row->add_by_pay.'</td>
										  <td>'.$row->notes.'</td>
										  <td></td>
									 </tr>';
							}
						}
					?>
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>