<?php						 					  
	echo '<h3>'.$title.'</h3>';
		$data['sql'] = $this->m_client->get_order(array('A.id_customer'=>$this->session->userdata('id_customer')));
		$data['received_confirm'] = false;
		echo '<div id="historyord">';
			$this->load->view('customer/myorder/v_data_order',$data);
		echo '</div>';	
?>