						<div class="tab-container clearfix">
                                 <?php $this->load->view('customer/v_left_tab')?>
                                 <div class="md-margin"></div>
                                  <div class="row">
					                   <div class="col-md-12 col-sm-12 col-xs-12">                                
					                         <ul id="products-tabs-list" class="tab-style-1 clearfix">
					                                    <li class="active"><a href="#statepay" data-toggle="tab" onclick="ajaxcall('<?php echo base_url(FOMODULE.'/customer/select_tab_order')?>','1','order-content')"><?php echo lang('statepay')?></a></li>
					                                    <li><a href="#stateorder" data-toggle="tab" onclick="ajaxcall('<?php echo base_url(FOMODULE.'/customer/select_tab_order')?>','2','order-content')"><?php echo lang('stateord')?></a></li>
					                                    <li><a href="#confreceived" data-toggle="tab" onclick="ajaxcall('<?php echo base_url(FOMODULE.'/customer/select_tab_order')?>','3','order-content')"><?php echo lang('confrcvd')?></a></li>
					                                    <li><a href="#listorder" data-toggle="tab" onclick="ajaxcall('<?php echo base_url(FOMODULE.'/customer/select_tab_order')?>','4','order-content')"><?php echo lang('listord')?></a></li>
					                           </ul>
					                            <div id="order-content">
					                                 <?php $this->load->view('customer/myorder/v_tab_statepay')?>
					                            </div>					               
					                                   
					                     </div>                          
					                </div>
                          </div>

