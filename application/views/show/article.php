
<?php

switch ($ring) {
  case 1:
    $ring = 'ring1.png'; break;

  case 2:
    $ring = 'ring2.png'; break;

  case 3:
    $ring = 'ring3.png'; break;

  default: break;
}

$ring_path = '/img/' . $ring;

$article_preview = 'class="article_preview" ';

if (isset($preview))
  $title = anchor('article/' . $id, $title, '');

?>
      <article>
        <div class="container">
          <div class="article_head">
            <div class="ring"> <img src="<?php echo $ring_path; ?>" alt="ring"/> </div>
            <div class="title"> <?php echo $title; ?> </div>

            <?php if ($admin === TRUE): ?>
              <div class="manage_article">
                <?php
                  echo '<a href="' . site_url("admin/article/$id") . '">Modifica</a>';
                ?>
              </div>
            <?php endif; ?>

            <div class="article_author"><?php echo $author; ?></div>
          </div>

          <div class="article_body">
            <?php echo $body; ?>
          </div>
          
          <?php
            if (isset($preview))
             echo anchor('article/' . $id, 'Continua...', $article_preview);
         ?>
        
          <div class="article_foot">
            <div class="right"><?php echo $date; ?></div>

            <?php echo print_tags($tags); ?>
          </div>

          <hr>

        </div>
      </article>
