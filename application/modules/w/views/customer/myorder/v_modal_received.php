 									<div class="modal-dialog">
                                        <div class="modal-content">
                                        <form id="formmodal" action="<?php echo base_url(FOMODULE.'/customer/save_received#dataorder')?>">
                                        <input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
                                        <input type="hidden" name="id_order" value="<?php echo $id_order?>">
                                         	<div class="modal-body">                                        		
                                         		<div class="no-content-comment" style="text-align:center">
                                         			<?php $this->load->view('v_alert_boxes_modal')?>
						                            <h2><i class="fa fa-question"></i></h2>						                             
						                            <h4><?php echo lang('konfreceive')?></h4>
						                            <div class="sm-margin"></div>
						                            <p><span style="color:#7c807e"><?php echo lang('descrecv')?></span></p>						                          
						                        </div>
                                         	</div>                                  
                                            <div class="modal-footer">
                                           	 	<button type="button" class="btn btn-custom" data-dismiss="modal"><?php echo lang('no')?></button>  
                                           	 	<button class="btn btn-custom-2"><?php echo lang('yes')?></button>                                      
                                            </div>
                                            </form> 
                                        </div>
                                    </div> 				