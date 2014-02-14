
      <div id="insert_comment">
        <?php
          echo form_open('comment/publish'); 

          echo form_hidden('article_id', $article_id);
        ?>
        
        <div class="title">Commenta</div>
        <div id="comment_author">Nome: <?php echo form_input($author); ?></div>
        <div id="comment_body"><?php echo form_textarea($body); ?></div>
        
        <?php
          echo $recaptcha_html;
          
          echo form_submit('publish', 'Pubblica');

          echo form_close();
        ?>

      </div>
