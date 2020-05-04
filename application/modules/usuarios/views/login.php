<?php
$this->load->view('admin/header', array( 'no_visible_elements' => true )); ?>

			<div class="row-fluid">
				<div class="span12 center login-header">
					<h2>Proages</h2>
				</div><!--/span-->
			</div><!--/row-->
			
			<div class="row-fluid">
				<div class="well span5 center login-box">
					<div class="alert alert-info">
						Porfavor ingresa con tu usuario y contraseña
					</div>
                    
                    
                    <?php // Return Message error ?>
            
					<?php $validation = validation_errors(); ?>
                    
                    <?php if( !empty( $validation ) ): ?>
                    <div class="alert alert-error">
                          <button type="button" class="close" data-dismiss="alert">×</button>
                          <strong>Error: </strong> <?php  echo $validation; // Show Dinamical message error ?>
                    </div>
                    <?php endif; ?>
                    
                    
                    <?php // Show Messages ?>
            
					<?php if( isset( $message['type'] ) ): ?>
                       
                       
                        <?php if( $message['type'] == true ): ?>
                            <div class="alert alert-success">
                                  <button type="button" class="close" data-dismiss="alert">×</button>
                                  <img src="<?php echo base_url() ?>images/true.png" width="20" height="20" />
                                  <strong>Correcto: </strong> <?php  echo $message['message']; // Show Dinamical message Success ?>
                            </div>
                        <?php endif; ?>
                       
                        
                        <?php if( $message['type'] == false ): ?>
                            <div class="alert alert-error">
                                  <button type="button" class="close" data-dismiss="alert">×</button>
                                  <img src="<?php echo base_url() ?>images/false.png" width="20" height="20" />
                                  <strong>Error: </strong> <?php  echo $message['message']; // Show Dinamical message error ?>
                            </div>
                        <?php endif; ?>
                    
                    
                    
                    <?php endif; ?>
                    
                    
                    
					<form id="login" class="form-horizontal" action="<?php echo base_url() ?>usuarios/login.html" method="post">
						<fieldset>
							<div class="input-prepend" title="Username" data-rel="tooltip">
								<span class="add-on"><i class="icon-user"></i></span><input autofocus class="input-large span10 required" name="username" id="username" type="text" value="admin" />
							</div>
							<div class="clearfix"></div>

							<div class="input-prepend" title="Password" data-rel="tooltip">
								<span class="add-on"><i class="icon-lock"></i></span><input class="input-large span10 required" name="password" id="password" type="password"/>
							</div>
							<div class="clearfix"></div>

							<div class="input-prepend">
								<!--<label class="remember" for="remember"><input type="checkbox" id="remember" />Remember me</label>-->
							</div>
							<div class="clearfix"></div>

							<p  id="actions-buttons-forms"class="center span5">
							<button type="submit" class="btn btn-primary">Login</button>
							</p>
						</fieldset>
					</form>
				</div><!--/span-->
			</div><!--/row-->
<?php $this->load->view('admin/footer'); ?>
