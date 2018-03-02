        <section id="content">
        	<div id="breadcrumb-container">
        		<div class="container">
					<ul class="breadcrumb">
						<li><a href="index.html">Home</a></li>
						<li class="active"><?php echo lang('reg_acc')?></li>
					</ul>
        		</div>
        	</div>
        	<div class="container">
        		<div class="row">
        			<div class="col-md-12">
						<header class="content-title">
							<h1 class="title"><?php echo lang('reg_acc')?></h1>							
						</header>
						<?php $this->load->view('v_alert_boxes')?>
        				<div class="xs-margin"></div><!-- space -->
						<form action="#" id="register-form" class="form-horizontal" method="post" action="<?php echo base_url(FOMODULE.'/account/register')?>">
						<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
        				<div class="row">        					
							<div class="col-md-6 col-sm-6 col-xs-12">									
									<fieldset>
									<h2 class="sub-title"><?php echo strtoupper(lang('personal'))?></h2>
									<span class="validation-error"><?php echo form_error('gender'); ?></span>																	
									<div class="input-group">
										<span class="input-group-addon"><span class="input-icon input-icon-user"></span><span class="input-text"><?php echo lang('gender')?>&#42;</span></span>
										<div class="large-selectbox clearfix">
                                            <select id="gender" name="gender" class="selectbox">
                                             <option value="" selected disabled><?php echo lang('gender')?></option>
                                               <?php foreach ($gender as $row) {?>												
												<option value="<?php echo $row->id_gender?>" <?php echo set_select('gender',$row->id_gender)?>><?php echo $row->name;?></option>
												<?php }?>	
                                            </select>                                           
                                        </div>                                        
									</div>
									<span class="validation-error"><?php echo form_error('firstname'); ?></span>
									<div class="input-group">
										<span class="input-group-addon"><span class="input-icon input-icon-user"></span><span class="input-text"><?php echo lang('firstname')?>&#42;</span></span>
										<input type="text" id="firstname" name="firstname" class="form-control input-lg" value="<?php echo set_value('firstname')?>" placeholder="<?php echo lang('firstname')?>">
									</div>
								
									<div class="input-group">
										<span class="input-group-addon"><span class="input-icon input-icon-user"></span><span class="input-text"><?php echo lang('lastname')?></span></span>
										<input type="text" id="lastname" name="lastname" class="form-control input-lg" value="<?php echo set_value('lastname')?>" placeholder="<?php echo lang('lastname')?>">
									</div>
									<span class="validation-error"><?php echo form_error('email'); ?></span>
									<div class="input-group">
										<span class="input-group-addon"><span class="input-icon input-icon-email"></span><span class="input-text"><?php echo lang('email')?>&#42;</span></span>
										<input type="text" name="email" class="form-control input-lg" value="<?php echo set_value('email')?>" placeholder="<?php echo lang('email')?>">
									</div>
									<span class="validation-error"><?php echo form_error('tgl'); ?></span>
									<span class="validation-error"><?php echo form_error('bln'); ?></span>
									<span class="validation-error"><?php echo form_error('thn'); ?></span>
									<div class="input-group">
										<span class="input-group-addon"><span class="input-icon input-icon-region"></span><span class="input-text"><?php echo lang('bdate')?>&#42;</span></span>
										<div class="large-selectbox clearfix">
                                            <select name="tgl" id="tgl" class="selectbox">
                                               <option value="" selected disabled><?php echo lang('date')?></option>
												<?php for ($a=1; $a<=31; $a++) 
												{											
													echo "<option value='$a' '".set_select('tgl',$a)."'>$a</option>";
												}
												?>		
                                            </select>
                                            <select name="bln" id="bln" class="selectbox">
												<option value="" selected disabled><?php echo lang('month')?></option>
												<?php for ($b=1; $b<=12; $b++) 
												{
													echo "<option value='$b' '".set_select('bln',$b)."'>".months()[$b]."</option>";
												}
												?>
											</select>
											<select name="thn" id="thn" class="selectbox">
												<option value="" selected disabled><?php echo lang('year')?></option>
												<?php for ($c=1950; $c<=date('Y')+1; $c++) 
												{
													echo "<option value='$c' '".set_select('thn',$c)."'>$c</option>";
												}
												?>
											</select> 											
                                        </div>
									</div>	
									<span class="validation-error"><?php echo form_error('phone'); ?></span>	
									<div class="input-group">
										<span class="input-group-addon"><span class="input-icon input-icon-phone"></span><span class="input-text"><?php echo lang('phone')?>&#42;</span></span>
										<input type="text" autocomplete="off" name="phone" class="form-control input-lg" value="<?php echo set_value('phone')?>" placeholder="<?php echo lang('phone')?>">
									</div>	
									
									</fieldset>																									
								</div>
        						
        						<div class="col-md-6 col-sm-6 col-xs-12">
        						<fieldset>
									<h2 class="sub-title"><?php echo lang('youpass')?></h2>
									<span class="validation-error"><?php echo form_error('password'); ?></span>	
									<div class="input-group">
										<span class="input-group-addon"><span class="input-icon input-icon-password"></span><span class="input-text"><?php echo lang('password')?>&#42;</span></span>
										<input type="password" name="password" class="form-control input-lg" value="<?php echo set_value('password')?>" placeholder="<?php echo lang('password')?>">
									</div>
									<span class="validation-error"><?php echo form_error('repeatpass'); ?></span>	
									<div class="input-group">
										<span class="input-group-addon"><span class="input-icon input-icon-password"></span><span class="input-text"><?php echo lang('repeatpass')?>&#42;</span></span>
										<input type="password" name="repeatpass" class="form-control input-lg" value="<?php echo set_value('repeatpass')?>" placeholder="<?php echo lang('repeatpass')?>">
									</div>									
									</fieldset>	
        						</div>        					
        				</div>						
						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<fieldset class="half-margin">			
									<span class="validation-error"><?php echo form_error('policy'); ?></span>							
									<div class="input-group custom-checkbox">
									<?php									
									$checked = (empty(form_error('policy'))) ? 'checked' : ''?>
									 <input type="checkbox" name="policy" <?php echo $checked?> <?php echo set_checkbox('policy')?>> 
									 <span class="checbox-container">
										 <i class="fa fa-check"></i>
									 </span>
										 <?php echo lang('policy').' <a href="'.base_url(FOMODULE.'/privacy-policy.html').'">'.lang('privacy').'</a>';?>									 
									</div>
								</fieldset>								
								<input type="submit" value="<?php echo lang('subacc')?>" class="btn btn-custom-2 md-margin">
							</div>
						</div>
        				</form>
        			</div>
        		</div>
			</div>       
        </section>
        
        