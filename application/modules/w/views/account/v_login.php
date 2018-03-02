<section id="content">
        	<div id="breadcrumb-container">
        		<div class="container">
					<ul class="breadcrumb">
						<li><a href="<?php echo base_url()?>">Home</a></li>
						<li class="active"><?php echo lang('login')?></li>
					</ul>
        		</div>
        	</div>
        	<div class="container">
        		<div class="row">
        			<div class="col-md-12">
						<header class="content-title">
							<h1 class="title"><?php echo lang('logacc')?></h1>
                            <div class="md-margin"></div><!-- space -->
						</header>     
						<?php $this->load->view('v_alert_boxes')?>   			
						   <div class="row">							   	
							   	<div class="col-md-6 col-sm-6 col-xs-12">					   		
							   	<h2><?php echo lang('reg_acc')?></h2>							   		
							   	<p><?php echo lang('regdesc')?></p>
                                <div class="md-margin"></div>
							   	<a href="<?php echo base_url(FOMODULE.'/account/register.html')?>" class="btn btn-custom-2"><?php echo lang('btncreate')?></a>
                                <div class="lg-margin"></div>
							   	</div>
							   	<div class="col-md-6 col-sm-6 col-xs-12">					   		
							   		<h2><?php echo lang('readyreg')?></h2>
							   		<p><?php echo lang('readydesc')?></p>
							   		<div class="xs-margin"></div>							   		
									<form id="login-form" class="form-horizontal" method="post" action="<?php echo base_url(FOMODULE.'/account/login.html')?>">
									<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
										<span class="validation-error"><?php echo form_error('email'); ?></span>	
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="input-icon input-icon-email"></span><span class="input-text"><?php echo lang('email')?>&#42;</span></span>
                                            <input type="text" name="email" class="form-control input-lg" value="<?php echo set_value('email')?>" placeholder="<?php echo lang('email')?>">
                                        </div>
                                        <span class="validation-error"><?php echo form_error('password'); ?></span>
                                         <div class="input-group xs-margin">
                                            <span class="input-group-addon"><span class="input-icon input-icon-password"></span><span class="input-text"><?php echo lang('password')?>&#42;</span></span>
                                            <input type="password" name="password" class="form-control input-lg" value="<?php echo set_value('password')?>" placeholder="<?php echo lang('password')?>">
                                        </div>
                                    <span class="help-block text-right"><a href="#"><?php echo lang('forgot')?></a></span>
                                    <button class="btn btn-custom-2"><?php echo strtoupper(lang('login'))?></button>
                                    </form>
                                    <div class="sm-margin"></div>
							   	</div>						   	
						   </div>								   
        			</div>
        		</div>
			</div>        
        </section>