<?php

/**
 * MooKwai template
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

function mookwai_template_html()
{
  global $_wp_current_template_id, $_wp_current_template_content, $wp_embed, $wp_query;

  if (!$_wp_current_template_content) {
    if (is_user_logged_in()) {
      return '<h1>' . esc_html__('No matching template found') . '</h1>';
    }
    return;
  }

  $content = $wp_embed->run_shortcode($_wp_current_template_content);
  $content = $wp_embed->autoembed($content);
  $content = shortcode_unautop($content);
  $content = do_shortcode($content);

  if (
    $_wp_current_template_id &&
    str_starts_with($_wp_current_template_id, get_stylesheet() . '//') &&
    is_singular() &&
    1 === $wp_query->post_count &&
    have_posts()
  ) {
    while (have_posts()) {
      the_post();
      $content = do_blocks($content);
    }
  } else {
    $content = do_blocks($content);
  }

  $content = wptexturize($content);
  $content = convert_smilies($content);
  $content = wp_filter_content_tags($content, 'template');
  $content = str_replace(']]>', ']]&gt;', $content);

  return '<div class="mk-page">' . $content . '</div>';
}

$template_html = mookwai_template_html();

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <?php wp_head(); ?>
  </head>

  <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <?php echo $template_html; ?>

    <?php wp_footer(); ?>
  </body>
</html>
