<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Letter extends CI_Controller{
	var $class;	
	var $datenow;
	var $addby;	
	var $id_employee;
	public function __construct(){
		parent::__construct();		
		$this->load->library('m_pdf');
		$this->load->model('m_admin');
		$this->load->model('w/m_client');
		$this->class = strtolower(__CLASS__);		
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');	
		$this->id_employee = $this->session->userdata('id_employee');
		$this->m_admin->maintenance();
	}
	function index(){
		echo '404-Page Not Found';die;
	}
	function orderslip($title='',$id=''){			
		if (!empty($id)){			
			$sql = $this->m_client->get_order(array('A.id_order'=>$id));
			if (count($sql) > 0){
				foreach ($sql as $row)
					foreach ($row as $key=>$val){
					$data[$key] = $val;
				}
				$data['detail'] = $this->m_client->get_order_detail(array('A.id_order'=>$id));
				$mpdf = $this->m_pdf->load('A4');
				if ($title == 'inv'){
					$pdf_title = 'Invoice-'.$id.'.pdf';					
					$body = $this->load->view('pdf/vw_invoice',$data,true);
					$mpdf->SetWatermarkText($row->payment_result);
					$mpdf->showWatermarkText = true;
				}else if ($title == 'deliv'){
					$pdf_title = 'Delivery-'.$id.'.pdf';
					$body = $this->load->view('pdf/vw_delivery',$data,true);
				}else{
					echo 'Not Found';die;
				}
				$data['title'] = $pdf_title;
				$header =$this->load->view('pdf/vw_header',$data,true);				
				$footer = $this->load->view('pdf/vw_footer',$data,true);
				$output = $header.$body.$footer;				
				//$mpdf->SetDisplayMode('fullpage');
				$mpdf->WriteHTML($output);
				ob_end_clean();
				$mpdf->Output($pdf_title,'I');
				//echo $output;
			}else{
				echo 'Not Found';
			}			
		}else{
			echo 'Error';
		}				
	}	
}
