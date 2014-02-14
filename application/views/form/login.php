	<div id="static">

<?php echo form_open('login/check'); ?>

		<h1>Login</h1>
		<div class="login">
			<label for="name">Nome utente:</label>
			<?php echo form_input($user_name); ?>
		</div>
		<div class="login">
			<label for="password">Password:</label>
			<?php echo form_password($password); ?>
		</div>

		<div class="login">
			<?php echo form_submit('login', 'Log In'); ?>
		</div>

<?php echo form_close(); ?>
	
	</div>
