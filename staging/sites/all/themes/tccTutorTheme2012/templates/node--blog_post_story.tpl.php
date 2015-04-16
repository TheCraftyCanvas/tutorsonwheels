<?php print $shareThisScriptsEmbed; ?>

<article id="article-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <div class="article-inner tccTutorTheme-blog-post">

    <?php print $unpublished; ?>

    <?php print render($title_prefix); ?>
    <?php if ($title || $display_submitted): ?>
      <header>
        <?php if ($title): ?>
          <h1<?php print $title_attributes; ?>>
            <a href="<?php print $node_url; ?>" rel="bookmark"><?php print $title; ?></a>
          </h1>
        <?php endif; ?>
        <?php if ($display_submitted): ?>
          <p class="submitted"><?php 
          $dateStart = strpos($submitted,"on ");
          $submitted = "Posted " . substr($submitted,$dateStart,strlen($submitted)-7);
          print $submitted;
          ?></p>
        <?php endif; ?>
      </header>
    <?php endif; ?>
    <?php print render($title_suffix); ?>

    <div<?php print $content_attributes; ?>>

    <?php print render($content['body']);?>

    <?php print render($content['field_tags']); ?>

    </div><!--/.article-content-->


    <?php if (!empty($content['links'])): ?>
      <nav class="clearfix"><?php print render($content['links']); ?></nav>
    <?php endif; ?>

    <?php print render($content['comments']); ?>

    <?php print $shareThisIconBarEmbed; ?>

  </div><!--/.tccTutorTheme-blog-post-->
</article>
