<?php echo form_open('login/check'); ?>

	<div class="clear">
		<h1>Login</h1>
		<p>Nome utente: <?php echo form_input($user_name); ?> </p>
		<p>Password: <?php echo form_password($password); ?> </p>
	</div>

	<?php echo form_submit('login', 'Log In');
echo form_close();
?> 
