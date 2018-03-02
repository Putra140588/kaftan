<div class="tab-container clearfix">
      <?php $this->load->view('customer/v_left_tab')?>
      <div class="md-margin"></div>
     <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">   
               <?php if (empty($id)){?>        		
				 <div class="panel-group custom-accordion sm-accordion" id="collapse2">
                       <?php $this->load->view('customer/myaccount/v_info_contact')?>
                       <?php $this->load->view('customer/myaccount/v_address')?>
               	 </div>	
               <?php }else{?>
               		<?php $this->load->view('customer/myaccount/v_edit_contact')?>
               <?php }?>	              
               </div>
    </div>	 
</div>

