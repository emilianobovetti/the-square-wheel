
<?php echo form_open('admin/edit_comment'); 

echo form_hidden('comment_id', $comment_id);

?>

	<h1>Modifica commento</h1>
	<div>Autore: <?php echo form_input($author); ?></div>
	<div>Body: <?php echo form_textarea($body); ?></div>

	<?php echo form_submit('edit', 'Modifica');
echo form_close();
?> 