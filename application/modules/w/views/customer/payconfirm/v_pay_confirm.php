        <section id="content">
        	<div id="breadcrumb-container">
        		<div class="container">
					<ul class="breadcrumb">
						<li><a href="index.html">Home</a></li>
						<li class="active"><?php echo lang('tf6')?></li>
					</ul>
        		</div>
        	</div>
        	<div class="container">
        		<div class="row">
        			<div class="col-md-12">
						<header class="content-title">
							<h1 class="title"><?php echo lang('tf6')?></h1>			
							<p style="text-align:center"><?php echo sprintf(lang('text2'),$this->session->userdata('company_name'))?><br>
								<?php echo lang('payconfirm1')?>								
							</p>				
						</header>
						<?php $this->load->view('v_alert_boxes')?>
        				<div class="xs-margin"></div><!-- space -->
						<form action="#" id="register-form" class="form-horizontal" method="post" action="<?php echo base_url(FOMODULE.'/customer/pay-confirm')?>">
						<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
        				<div class="row">        
        					<div class="col-md-4 col-sm-4 col-xs-12"></div>	        								
							<div class="col-md-5 col-sm-5 col-xs-12">									
									<fieldset>																	
									<span class="validation-error"><?php echo form_error('id_order'); ?></span>
									<div class="input-group">
										<span class="input-group-addon"><span class="input-text"><?php echo lang('text4')?>&#42;</span></span>
										<input type="text" id="id_order" name="id_order" class="form-control input-lg" value="<?php echo set_value('id_order')?>" placeholder="<?php echo lang('text4')?>">
									</div>										
									<span class="validation-error"><?php echo form_error('bankdest'); ?></span>
									<div class="input-group">
										<span class="input-group-addon"><span class="input-text"><?php echo lang('bankdest')?>&#42;</span></span>
										<div class="large-selectbox clearfix">
                                            <select id="bankdest" name="bankdest" class="selectbox">
                                             <option value="" selected disabled><?php echo lang('bankdest')?></option>
                                               <?php foreach ($paymethod as $row) {?>												
													<option value="<?php echo $row->id_payment_method?>" <?php echo set_select('bankdest',$row->id_payment_method)?>><?php echo $row->method_name;?></option>
												<?php }?>	
                                            </select>                                           
                                        </div>   
									</div>
									<span class="validation-error"><?php echo form_error('bankfrom'); ?></span>
									<div class="input-group">
										<span class="input-group-addon"><span class="input-text"><?php echo lang('bankfrom')?>&#42;</span></span>
										<input type="text" id="bankfrom" name="bankfrom" class="form-control input-lg" value="<?php echo set_value('bankfrom')?>" placeholder="<?php echo lang('bankfrom')?>">
									</div>	
									<span class="validation-error"><?php echo form_error('rekeningfrom'); ?></span>	
									<div class="input-group">
										<span class="input-group-addon"><span class="input-text"><?php echo lang('rekeningfrom')?>&#42;</span></span>
										<input type="text" autocomplete="off" name="rekeningfrom" class="form-control input-lg" value="<?php echo set_value('rekeningfrom')?>" placeholder="<?php echo lang('rekeningfrom')?>">
									</div>	
									<span class="validation-error"><?php echo form_error('transfmethod'); ?></span>
									<div class="input-group">
										<span class="input-group-addon"><span class="input-text"><?php echo lang('transfmethod')?>&#42;</span></span>
										<div class="large-selectbox clearfix">
                                            <select id="transfmethod" name="transfmethod" class="selectbox">
                                             <option value="" selected disabled><?php echo lang('transfmethod')?></option>
                                               <?php foreach ($transmethod as $row) {?>												
													<option value="<?php echo $row->id_method_transfer?>" <?php echo set_select('transfmethod',$row->id_method_transfer)?>><?php echo $row->name_method_transfer;?></option>
												<?php }?>	
                                            </select>                                           
                                        </div>   
									</div>	
									<span class="validation-error"><?php echo form_error('amountrans'); ?></span>	
									<div class="input-group">
										<span class="input-group-addon"><span class="input-text"><?php echo lang('amountrans')?>&#42;</span></span>
										<input type="text" autocomplete="off" id="amountrans" name="amountrans" class="form-control input-lg" onkeypress="return decimals(event,this.id)" value="<?php echo set_value('amountrans')?>" placeholder="<?php echo $this->session->userdata('symbol_fo')?>">
									</div>	
									<span class="validation-error"><?php echo form_error('transdate'); ?></span>	
									<div class="input-group">
										<span class="input-group-addon"><span class="input-text"><?php echo lang('transdate')?>&#42;</span></span>
										<input type="text" autocomplete="off" name="transdate" class="form-control input-lg date-picker" value="<?php echo set_value('transdate')?>" placeholder="<?php echo lang('transdate')?>">
									</div>	
									</fieldset>					
									<input type="submit" value="<?php echo lang('confirmbtn')?>" class="btn btn-custom-2 md-margin">        																								
								</div>        						
        							
        				</div>						
						
        				</form>
        			</div>
        		</div>
			</div>       
        </section>
        
        