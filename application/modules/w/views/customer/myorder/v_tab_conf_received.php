<?php 
	echo '<h3>'.$title.'</h3><p>'.lang('ondelivery').'</p>';
	echo '<div id="dataorder">';
		$data['sql'] = $this->m_client->get_order(array('A.order_received'=>0,'A.on_delivery'=>1,'A.id_customer'=>$this->session->userdata('id_customer')));
		$data['received_confirm'] = true;
		echo '<div id="historyord">';
			$this->load->view('customer/myorder/v_data_order',$data);
		echo '</div>';
	echo '</div>';
?>