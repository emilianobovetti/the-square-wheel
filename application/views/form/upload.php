
<?php
if (isset($error))
	echo $error;

echo form_open_multipart('admin/upload');

echo form_upload($upload_form);

echo form_submit('upload', 'Carica');

echo form_close();

if (isset($upload_data))
{
	echo '<div id="uploaded_file">', get_upload_link($upload_data['file_name']), '</div>';
}

?>
