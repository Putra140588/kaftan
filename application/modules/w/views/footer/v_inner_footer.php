 <div id="inner-footer">                
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 widget">
                            <h3><?php echo lang('custservice')?></h3>
                            <ul class="links">
                            	<?php 
                            		echo '<li><a href="'.base_url(FOMODULE.'/aboutus.html').'">'.lang('aboutus').' '.$this->session->userdata('company_name').'</a></li>
                        				  <li><a href="'.base_url(FOMODULE.'/help.html').'">'.lang('help').'</a></li>	                        			                                 
		                                  <li><a href="'.base_url(FOMODULE.'/contactus.html').'">'.lang('contactus').'</a></li>		                                 
		                                  <li><a href="'.base_url(FOMODULE.'/privacy-policy.html').'">'.lang('privacy').'</a></li>
		                                  <li><a href="'.base_url(FOMODULE.'/term-and-conditions.html').'">'.lang('term').'</a></li>';
                            	?>                            	
                            </ul>
                        </div>
                         <div class="col-md-3 col-sm-4 col-xs-12 widget">
                            <h3>MEMBER AREA</h3>                            
                            <ul class="links">
                            <?php 
                            	echo '<li><a href="'.base_url(FOMODULE.'/customer/my_account.html').'">'.lang('account').'</a></li>
	                                  <li><a href="'.base_url(FOMODULE.'/customer/my_order.html').'">'.lang('myorder').'</a></li>
	                                  <li><a href="'.base_url(FOMODULE.'/customer/my_account.html').'">'.lang('myaddress').'</a></li>
	                                  <li><a href="'.base_url(FOMODULE.'/customer/pay_confirm.html').'">'.lang('tf6').'</a></li>';
                            ?>
                                                               
                               
                            </ul>
                        </div> 
                        <div class="col-md-3 col-sm-4 col-xs-12 widget">
                            <h3><?php echo lang('paymethod')?></h3>
                            <ul class="pay-horizontal-list">
                            	<li><img src="<?php echo base_url('/assets/fo/images/payment/bca-2.png')?>"></li> 
                                <li><img src="<?php echo base_url('/assets/fo/images/payment/visa.png')?>"></li>
                                <li><img src="<?php echo base_url('/assets/fo/images/payment/master-card.png')?>"></li>
                                <li><img src="<?php echo base_url('/assets/fo/images/payment/paypal.png')?>"></li>
                                <li><img src="<?php echo base_url('/assets/fo/images/payment/discover.png')?>"></li>                                                              
                            </ul>
                             <h3><?php echo lang('couriershipp')?></h3>
                             <ul class="shipp-horizontal-list">
                                <li><img src="<?php echo base_url('/assets/fo/images/courier/jne.png')?>"></li> 
                                <li><img src="<?php echo base_url('/assets/fo/images/courier/tiki.png')?>"></li>  
                                <li><img src="<?php echo base_url('/assets/fo/images/courier/jnt.png')?>"></li>       
                                <li><img src="<?php echo base_url('/assets/fo/images/courier/pos.png')?>"></li>
                                <li><img src="<?php echo base_url('/assets/fo/images/courier/dhl.png')?>"></li>                                 
                                                                                                     
                            </ul>
                        </div>                                                              
                        <div class="clearfix visible-sm"></div>                        
                        <div class="col-md-3 col-sm-12 col-xs-12 widget">
                         <div class="md-margin"></div>
                           <a href="<?php echo base_url()?>"><img src="<?php echo base_url()?>assets/images/logo/<?php echo $this->session->userdata('logo_company');?>"></a>
							<p><?php echo $this->session->userdata('address_company')?></p>
                        </div>
                    </div>
                </div>            
            </div>