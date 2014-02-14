
<?php 
echo form_open('admin/publish'); 

if (isset($article_id))
	echo form_hidden('article_id', $article_id);

?>

	<div class ="clear">
		<div id="ring_select">Ring: <?php echo form_dropdown('ring', $ring_options, $selected_ring); ?> </div>
		<div id="title_select">Titolo: <?php echo form_input($title); ?> </div>
		<div id="body_edit"> <?php echo form_textarea($body); ?> </div>
		<div id="tag_edit"> <?php echo form_textarea($tags); ?> </div>
	</div>

	<div class="publish_button">
		<?php echo form_submit('publish', 'Pubblica'); ?>
	</div>

<?php echo form_close(); ?> 

<?php
if (isset($article_id))
{
	echo form_open('admin/delete');
	echo form_hidden('article_id', $article_id);
	echo '<div class="publish_button">' . form_submit('delete', 'Elimina') . '</div>';
	echo form_close();
}
?>

	<iframe src="<?php echo site_url('admin/upload'); ?>" 
		scrolling="no" id="upload">
	</iframe>
