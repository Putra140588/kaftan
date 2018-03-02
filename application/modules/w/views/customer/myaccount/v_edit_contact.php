<form action="#" class="form-horizontal" method="post" action="<?php echo base_url(FOMODULE.'/customer/my_account/edit')?>">
               	<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
              		<fieldset>
              			<h2 class="sub-title">Ubah Kontak</h2>
              				<span class="validation-error"><?php echo form_error('gender'); ?></span>																	
									<div class="input-group">
										<span class="input-group-addon"><span class="input-icon input-icon-user"></span><span class="input-text"><?php echo lang('gender')?>&#42;</span></span>
										<div class="large-selectbox clearfix">
                                            <select id="gender" name="gender" class="selectbox">                                             
                                               <?php foreach ($this->m_client->get_gender() as $row) {
                                               		$select = ($row->id_gender == $this->session->userdata('id_gender')) ? 'selected' : '';
                                              	 echo '<option value="'.$row->id_gender.'#'.$row->name.'" '.$select.'>'.$row->name.'</option>';                                              															
												}?>	
                                            </select>                                           
                                        </div>                                        
									</div>
							<span class="validation-error"><?php echo form_error('firstname'); ?></span>
							<div class="input-group">
								<span class="input-group-addon"><span class="input-icon input-icon-user"></span><span class="input-text"><?php echo lang('firstname')?>&#42;</span></span>
								<input type="text" id="firstname" name="firstname" class="form-control input-lg" value="<?php echo $this->session->userdata('first_name_fo')?>" placeholder="<?php echo lang('firstname')?>">
							</div>
							<div class="input-group">
								<span class="input-group-addon"><span class="input-icon input-icon-user"></span><span class="input-text"><?php echo lang('lastname')?></span></span>
								<input type="text" id="lastname" name="lastname" class="form-control input-lg" value="<?php echo $this->session->userdata('last_name_fo')?>" placeholder="<?php echo lang('lastname')?>">
							</div>
							
							<?php $birthdate = $this->session->userdata('birthdate');
								  $xpl = explode("-", $birthdate);
							?>
							<div class="input-group">
								<span class="input-group-addon"><span class="input-icon input-icon-region"></span><span class="input-text"><?php echo lang('bdate')?>&#42;</span></span>
									<div class="large-selectbox clearfix">
                                            <select name="tgl" id="tgl" class="selectbox"> 
                                             <option value="" disabled><?php echo lang('date')?></option>                                              
												<?php for ($a=1; $a<=31; $a++) 
												{		
													$day = ($a >= 10) ? $a : '0'.$a;																										
													$select = ($xpl[2] == $day) ? 'selected' : '';							
													echo '<option value="'.$day.'" '.$select.'>'.$day.'</option>';
												}
												?>		
                                            </select>
                                            <select name="bln" id="bln" class="selectbox">	
                                            <option value="" disabled><?php echo lang('month')?></option>											
												<?php for ($b=1; $b<=12; $b++) 
												{
													$month = ($b >= 10) ? $b : '0'.$b;
													$select = ($xpl[1] == $month) ? 'selected' : '';
													echo '<option value="'.$month.'" '.$select.'>'.$month.'</option>';
												}
												?>
											</select>
											<select name="thn" id="thn" class="selectbox">			
												<option value="" disabled><?php echo lang('year')?></option>									
												<?php for ($year=1950; $year<=date('Y')+1; $year++) 
												{
													
													$select = ($xpl[0] == $year) ? 'selected' : '';
													echo '<option value="'.$year.'" '.$select.'>'.$year.'</option>';
												}
												?>
											</select> 											
                                        </div>
									</div>	
									<span class="validation-error"><?php echo form_error('phone'); ?></span>	
									<div class="input-group">
										<span class="input-group-addon"><span class="input-icon input-icon-phone"></span><span class="input-text"><?php echo lang('phone')?>&#42;</span></span>
										<input type="text" autocomplete="off" name="phone" class="form-control input-lg" value="<?php echo $this->session->userdata('phone')?>" placeholder="<?php echo lang('phone')?>">
									</div>
									<div class="input-group">
										<span class="input-group-addon"><span class="input-icon input-icon-email"></span><span class="input-text"><?php echo lang('email')?>&#42;</span></span>
										<input type="text" name="email" readonly class="form-control input-lg" value="<?php echo $this->session->userdata('email')?>" placeholder="<?php echo lang('email')?>">
									</div>
              		</fieldset>              		
              		<input type="submit" value="<?php echo lang('saveedit')?>" class="btn btn-custom-2">
              		<a href="<?php echo base_url(FOMODULE.'/customer/my_account.html')?>" class="btn btn-custom"><?php echo strtoupper(lang('cancel'))?></a>
              	</form>                   