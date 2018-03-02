 								<div class="modal-dialog">
                                        <div class="modal-content">
                                         	<div class="modal-body">     
                                         		<div class="no-content-comment" style="text-align:center">
						                            <h2><i class="<?php echo $icon?>"></i></h2>
						                             <h1><?php echo strtoupper($notif)?></h1>
						                            <h4><?php echo $coment?></h4>
						                        </div>
                                         	</div>                                  
                                            <div class="modal-footer">
                                           	 	<button type="button" class="btn btn-custom" data-dismiss="modal"><?php echo strtoupper(lang('close'))?></button>  
                                           	 	 <a href="<?php echo base_url(FOMODULE.'/checkout/proses')?>" class="btn btn-custom-2"><?php echo lang('checkout')?></a>                                         
                                            </div>
                                        </div>
                                    </div> 				