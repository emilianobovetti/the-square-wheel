
      <a href="<?php echo base_url(); ?>">
        <img class="left" id="banner" src="<?php echo site_url('img/banner.png'); ?>" alt="banner"/>
      </a>
      <nav>
        <ul>
          <li class="left"><a href="<?php echo base_url(); ?>">Home</a></li>
          <li class="left"><a href="<?php echo base_url('article'); ?>">Last</a></li>
          <li class="left"><a href="<?php echo base_url('site/about'); ?>">About</a></li>
          <li class="left"><a href="<?php echo base_url('site/contact'); ?>">Contact</a></li>
          <li class="left"><a href="<?php echo base_url('site/thanks'); ?>">Thanks</a></li>
          <li class="left"><a href="<?php echo base_url('login'); ?>">Log in</a></li>

          <?php if ($admin === TRUE): ?>
            <li class="right"><a href="<?php echo base_url('admin/logout'); ?>">Log out</a></li>
          <?php endif; ?>

        </ul>
      </nav>

    <?php if (isset($pagination) AND $pagination): ?>

      <div id="pagination">
      	<?php echo $pagination; ?>
      </div>

    <?php endif; ?>
