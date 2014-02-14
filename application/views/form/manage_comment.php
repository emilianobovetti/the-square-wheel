
<?php
echo form_open('admin/comments'); 

echo form_hidden('comment_id', $id);

echo form_submit('delete', 'Elimina');

echo form_submit('edit', 'Modifica');

echo form_close();
?>
