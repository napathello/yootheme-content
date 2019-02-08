<?php

$el = $this->el('div', [

    'class' => [
        'content-element el-element'
    ]

]);

/**
 * Var
 */

$id = $props['field_content_id'];
$id_exclude = $props['field_exclude_id'];
$category = $props['field_content_category'];
$tags = $props['field_content_tag'];

//Grid
$grid_default = 'uk-child-width-1-'.$props['field_grid_default'];
$grid_small = 'uk-child-width-1-'.$props['field_grid_small'].'@s';
$grid_medium = 'uk-child-width-1-'.$props['field_grid_medium'].'@m';
$grid_large = 'uk-child-width-1-'.$props['field_grid_large'].'@l';;
$grid_xlarge = 'uk-child-width-1-'.$props['field_grid_xlarge'].'@xl';

$theme_grid = 'uk-grid uk-grid-small uk-grid-match '.$grid_default.' '.$grid_small.' '.$grid_medium.' '.$grid_large.' '.$grid_xlarge;
$theme_img_size = $props['field_image_size'];
$theme_img_show = $props['show_image'];
$theme_show_date = $props['show_date'];
$theme_show_cat = $props['show_category'];
$theme_show_author = $props['show_author'];
$theme_show_dotnav = $props['show_dot_nav'];

//Content
$theme_content_show = $props['show_content'];
$theme_content_count = $props['field_content_count'];
$theme_content_more = $props['field_content_more'];

//Utility
$truncate = $props['title_truncate'] == true ? 'uk-text-truncate' : '';
$style = $props['field_style_content'];
$sticky_content = $props['content_sticky'];

//Query Data
$args = array(
  'post_type' => 'post',
  'post_status' => 'publish',
  'posts_per_page' => $props['field_content_number'],
  'content_type' => $props['field_content_type'],
  'orderby' => $props['field_content_orderby'],
  'order' => $props['field_content_order'],
);

// From selected content ids
if ($props['field_content_type'] == 'id') {
    $args['post__in'] = ($id ? explode(',', $id) : null);
}

// From Exclude post from query_posts() wordpress content ids
if ($id_exclude) {
    $args['post__not_in'] = ($id_exclude  ? explode(',', $id_exclude) : null);
}


// From selected content categories
if ($props['field_content_type'] == 'category' && $category != '') {
    $category = explode(',', $category);
    $args['cat'] = $category;
}

// From selected content tags
if ($props['field_content_type'] == 'tags' && $tags != '') {
    $tags = explode(',', $tags);
    $args['tax_query'][] = array(
    'taxonomy' 	=> 'post_tag',
        'field'    	=> 'id',
    'terms'    	=> $tags,
        'operator' 	=> 'IN'
  );
}

$loop = new WP_Query($args);
?>

<?= $el($props, $attrs) ?>

  <?php if ($style == 'card') :?>

    <div class="<?php echo $theme_grid; ?> content-element-grid" uk-grid>

          <?php
            $item_index = 0;
            while ($loop->have_posts()) : $loop->the_post(); $item_index++;
            global $post;
            $id = $post->ID;

            $post_thumbnail_attr = array(
                'class' => 'uk-content-element-photo el-image attachment-'.$theme_img_size,
                'alt'   => get_the_title(),
                'title' => get_the_title(),
            );

          ?>

            <div class="el-item item-<?= $item_index ;?> <?= $item_index == 1 ? 'element-sticky' : ''; ?>">
                <div class="uk-card uk-card-default">
                 <?php if ($theme_img_show == true) :?>
                    <?php if (has_post_thumbnail()) : ?>
                      <div class="uk-card-media-top">
                        <a class="el-link" href="<?php the_permalink(); ?>"><?= the_post_thumbnail($theme_img_size, $post_thumbnail_attr);?>
                        </a>
                      </div>
                    <?php endif;?>
                  <?php endif;?>
                  <div class="uk-card-small uk-card-body">
                    <h5 class="uk-margin-remove-bottom el-title <?= $truncate;?>"><a class="el-link" href="<?php the_permalink(); ?>"><?= get_the_title();?></a></h5>
                    <?php if ($theme_show_date || $theme_show_cat) :?>
                      <span class="uk-text-meta el-meta uk-display-block">
                        <?php $theme_show_date == true ? the_time('d M Y') : ''; ?>
                        <?php if ($theme_show_cat == true) :?>
                          <?= __('Posted in ', 'yootheme'); the_category(', '); ?>
                        <?php endif;?>
                      </span>
                    <?php endif;?>
                    <?php if ($theme_show_author == true) :?>
                      <span class="uk-text-meta el-meta uk-display-block"> <?= __('By ', 'yootheme');?> <?php the_author(); ?> </span>
                    <?php endif;?>
                    <?php if ($theme_content_show == true) :?>
                      <p class="uk-text-small uk-margin-small-top"><?= wp_trim_words(wp_filter_nohtml_kses(get_the_content()), $theme_content_count, $theme_content_more);?></p>
                    <?php endif;?>
                </div>
              </div>
            </div>

          <?php
            endwhile;
            wp_reset_query();
          ?>

    </div>

  <?php elseif ($style == 'list-card') :?>

  <div class="content-element-grid">

    <?php
      $item_index = 0;
      while ($loop->have_posts()) : $loop->the_post(); $item_index++;
      global $post;
      $id = $post->ID;

      $post_thumbnail_attr = array(
          'class' => 'uk-content-element-photo el-image attachment-'.$theme_img_size,
          'alt'   => get_the_title(),
          'title' => get_the_title(),
          'uk-cover' => '',
      );

    ?>
    <div class="el-item item-<?= $item_index ;?> <?= $item_index == 1 ? 'element-sticky' : ''; ?>">
      <div class="uk-card uk-card-default uk-grid-collapse uk-margin" uk-grid>
      <?php if ($theme_img_show == true) :?>
        <?php if (has_post_thumbnail()) : ?>
          <div class="uk-card-media-left uk-cover-container uk-width-1-3@s">
              <a class="el-link uk-position-cover uk-position-z-index" href="<?php the_permalink(); ?>"></a>
              <?= the_post_thumbnail($theme_img_size, $post_thumbnail_attr);?>
              <canvas width="600" height="400"></canvas>
          </div>
        <?php endif;?>
      <?php endif;?>
          <div class="uk-width-2-3@s">
              <div class="uk-card-body">
                <h5 class="uk-margin-remove-bottom el-title <?= $truncate;?>"><a class="el-link" href="<?php the_permalink(); ?>"><?= get_the_title();?></a></h5>
                <?php if ($theme_show_date || $theme_show_cat) :?>
                  <span class="uk-text-meta el-meta uk-display-block">
                    <?php $theme_show_date == true ? the_time('d M Y') : ''; ?>
                    <?php if ($theme_show_cat == true) :?>
                      <?= __('Posted in ', 'yootheme'); the_category(', '); ?>
                    <?php endif;?>
                  </span>
                <?php endif;?>
                <?php if ($theme_show_author == true) :?>
                  <span class="uk-text-meta el-meta uk-display-block"> <?= __('By ', 'yootheme');?> <?php the_author(); ?> </span>
                <?php endif;?>
                <?php if ($theme_content_show == true) :?>
                  <p class="uk-text-small uk-margin-small-top"><?= wp_trim_words(wp_filter_nohtml_kses(get_the_content()), $theme_content_count, $theme_content_more);?></p>
                <?php endif;?>
              </div>
          </div>
      </div>
    </div>
    <?php
      endwhile;
      wp_reset_query();
    ?>

  </div>

  <?php elseif ($style == 'list') :?>

    <div class="content-element-grid uk-padding-small uk-card-default">

      <ul class="uk-list uk-list-large uk-list-divider uk-margin-remove">

        <?php
          $item_index = 0;
          while ($loop->have_posts()) : $loop->the_post(); $item_index++;
          global $post;
          $id = $post->ID;

          $post_thumbnail_attr = array(
              'class' => 'uk-content-element-photo el-image attachment-'.$theme_img_size,
              'alt'   => get_the_title(),
              'title' => get_the_title(),
          );

        ?>

          <li class="el-item item-<?= $item_index ;?> <?= $item_index == 1 ? 'element-sticky' : ''; ?>">
            <div class="uk-grid-small uk-flex-middle uk-height-match" uk-grid>
                <?php if ($theme_img_show == true) :?>
                  <?php if (has_post_thumbnail()) : ?>
                  <div class="uk-width-1-4">
                    <a class="el-link" href="<?php the_permalink(); ?>">
                      <?= the_post_thumbnail($theme_img_size, $post_thumbnail_attr);?>
                    </a>
                  </div>
                  <?php endif;?>
                <?php endif;?>
                <div class="uk-width-expand">
                    <h5 class="uk-margin-remove-bottom el-title <?= $truncate;?>"><a class="el-link" href="<?php the_permalink(); ?>"><?= get_the_title();?></a></h5>
                    <?php if ($theme_show_date || $theme_show_cat) :?>
                      <span class="uk-text-meta el-meta uk-display-block">
                        <?php $theme_show_date == true ? the_time('d M Y') : ''; ?>
                        <?php if ($theme_show_cat == true) :?>
                          <?= __('Posted in ', 'yootheme'); the_category(', '); ?>
                        <?php endif;?>
                      </span>
                    <?php endif;?>
                    <?php if ($theme_show_author == true) :?>
                      <span class="uk-text-meta el-meta uk-display-block"> <?= __('By ', 'yootheme');?> <?php the_author(); ?> </span>
                    <?php endif;?>
                    <?php if ($theme_content_show == true) :?>
                      <p class="uk-text-small uk-margin-small-top"><?= wp_trim_words(wp_filter_nohtml_kses(get_the_content()), $theme_content_count, $theme_content_more);?></p>
                    <?php endif;?>
                </div>
            </div>
          </li>

        <?php
          endwhile;
          wp_reset_query();
        ?>

      </ul>

    </div>

  <?php elseif ($style == 'list-sticky') :?>

    <ul class="content-element-grid uk-list-divider uk-list uk-list-large uk-card-default uk-margin-remove">

        <?php
          $item_index = 0;

          while ($loop->have_posts()) : $loop->the_post(); $item_index++;
          global $post;
          $id = $post->ID;
          $post_thumbnail_attr = array(
              'class' => 'uk-content-element-photo el-image attachment-'.$theme_img_size,
              'alt'   => get_the_title(),
              'title' => get_the_title(),
          );

        ?>


          <?php if ($item_index == 1) :?>

            <li class="el-item item-<?= $item_index ;?> <?= $item_index == 1 ? 'element-sticky' : ''; ?>">
              <div>
                  <?php if ($theme_img_show == true) :?>
                    <?php if (has_post_thumbnail()) : ?>
                    <div>
                      <a class="el-link" href="<?php the_permalink(); ?>">
                        <?= the_post_thumbnail($theme_img_size, $post_thumbnail_attr);?>
                      </a>
                    </div>
                    <?php endif;?>
                  <?php endif;?>
                  <div class="uk-padding-small uk-padding-remove-bottom">
                      <h4 class="uk-margin-remove-bottom el-title <?= $truncate;?>"><a class="el-link" href="<?php the_permalink(); ?>"><?= get_the_title();?></a></h5>
                      <?php if ($theme_show_date || $theme_show_cat) :?>
                        <span class="uk-text-meta el-meta uk-display-block">
                          <?php $theme_show_date == true ? the_time('d M Y') : ''; ?>
                          <?php if ($theme_show_cat == true) :?>
                            <?= __('Posted in ', 'yootheme'); the_category(', '); ?>
                          <?php endif;?>
                        </span>
                      <?php endif;?>
                      <?php if ($theme_show_author == true) :?>
                        <span class="uk-text-meta el-meta uk-display-block"> <?= __('By ', 'yootheme');?> <?php the_author(); ?> </span>
                      <?php endif;?>
                      <?php if ($theme_content_show == true || $sticky_content == true) :?>
                        <p class="uk-text-small uk-margin-small-top uk-margin-remove-bottom"><?= wp_trim_words(wp_filter_nohtml_kses(get_the_content()), $theme_content_count, $theme_content_more);?></p>
                      <?php endif;?>
                  </div>
              </div>
            </li>

          <?php else :?>

            <li class="el-item uk-padding-small <?= $loop->post_count !== $loop->current_post+1 ? 'uk-padding-remove-bottom' : '' ;?> item-<?= $item_index ;?> <?= $item_index == 1 ? 'element-sticky' : ''; ?>">
              <div class="uk-flex-middle uk-height-match" uk-grid>
                  <?php if ($theme_img_show == true) :?>
                    <?php if (has_post_thumbnail()) : ?>
                    <div class="uk-grid-small uk-width-1-4">
                      <a class="el-link" href="<?php the_permalink(); ?>">
                        <?= the_post_thumbnail($theme_img_size, $post_thumbnail_attr);?>
                      </a>
                    </div>
                    <?php endif;?>
                  <?php endif;?>
                  <div class="uk-width-expand">
                      <h5 class="uk-margin-remove-bottom el-title <?= $truncate;?>"><a class="el-link" href="<?php the_permalink(); ?>"><?= get_the_title();?></a></h5>
                      <?php if ($theme_show_date || $theme_show_cat) :?>
                        <span class="uk-text-meta el-meta uk-display-block">
                          <?php $theme_show_date == true ? the_time('d M Y') : ''; ?>
                          <?php if ($theme_show_cat == true) :?>
                            <?= __('Posted in ', 'yootheme'); the_category(', '); ?>
                          <?php endif;?>
                        </span>
                      <?php endif;?>
                      <?php if ($theme_show_author == true) :?>
                        <span class="uk-text-meta el-meta uk-display-block"> <?= __('By ', 'yootheme');?> <?php the_author(); ?> </span>
                      <?php endif;?>
                      <?php if ($theme_content_show == true) :?>
                        <p class="uk-text-small uk-margin-small-top"><?= wp_trim_words(wp_filter_nohtml_kses(get_the_content()), $theme_content_count, $theme_content_more);?></p>
                      <?php endif;?>
                  </div>
              </div>
            </li>

          <?php endif;?>

        <?php
          endwhile;
          wp_reset_query();
        ?>

    </ul>

  <?php elseif ($style == 'slider') :?>
    <div class="uk-slider" uk-slider>
      <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1">
        <ul class="uk-slider-items <?=$theme_grid;?>">
    <?php
      $item_index = 0;

      while ($loop->have_posts()) : $loop->the_post(); $item_index++;
      global $post;
      $id = $post->ID;
      $post_thumbnail_attr = array(
          'class' => 'uk-content-element-photo el-image attachment-'.$theme_img_size,
          'alt'   => get_the_title(),
          'title' => get_the_title(),
          'uk-cover' => '',
      );

    ?>
    <li>
      <div class="el-item item-<?= $item_index ;?> <?= $item_index == 1 ? 'element-sticky' : ''; ?>">
        <div class="uk-card uk-card-default uk-grid-collapse uk-margin" uk-grid>
        <?php if ($theme_img_show == true) :?>
          <?php if (has_post_thumbnail()) : ?>
            <div class="uk-card-media-left uk-cover-container uk-width-1-3@s">
                <a class="el-link uk-position-cover uk-position-z-index" href="<?php the_permalink(); ?>"></a>
                <?= the_post_thumbnail($theme_img_size, $post_thumbnail_attr);?>
                <canvas width="600" height="400"></canvas>
            </div>
          <?php endif;?>
        <?php endif;?>
            <div class="uk-width-2-3@s">
                <div class="uk-card-body">
                  <h5 class="uk-margin-remove-bottom el-title <?= $truncate;?>"><a class="el-link" href="<?php the_permalink(); ?>"><?= get_the_title();?></a></h5>
                  <?php if ($theme_show_date || $theme_show_cat) :?>
                    <span class="uk-text-meta el-meta uk-display-block">
                      <?php $theme_show_date == true ? the_time('d M Y') : ''; ?>
                      <?php if ($theme_show_cat == true) :?>
                        <?= __('Posted in ', 'yootheme'); the_category(', '); ?>
                      <?php endif;?>
                    </span>
                  <?php endif;?>
                  <?php if ($theme_show_author == true) :?>
                    <span class="uk-text-meta el-meta uk-display-block"> <?= __('By ', 'yootheme');?> <?php the_author(); ?> </span>
                  <?php endif;?>
                  <?php if ($theme_content_show == true) :?>
                    <p class="uk-text-small uk-margin-small-top"><?= wp_trim_words(wp_filter_nohtml_kses(get_the_content()), $theme_content_count, $theme_content_more);?></p>
                  <?php endif;?>
                </div>
            </div>
        </div>
      </div>
    </li>
    <?php
      endwhile;
      wp_reset_query();
    ?>
      </ul>
        <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
        <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>
        <?php if($theme_show_dotnav == true) :?>
          <ul class="uk-slider-nav uk-dotnav uk-position-large uk-position-bottom-right"></ul>
        <?php endif;?>
      </div>
    </div>

  <?php endif;?>

</div>
