<?php

/**
 * MooKwai blocks
 * Site - Post content
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\MK_Blocks\Site;

use MOOKWAI\App\Traits\Singleton;

class Post_Content
{

  use Singleton;

  protected function __construct()
  {
    $this->setup_hooks();
  }

  protected function setup_hooks()
  {
    add_action('init', [$this, 'registerBlock']);
  }

  public function registerBlock()
  {
    register_block_type_from_metadata(__DIR__, array(
      'render_callback' => [$this, 'outputData']
    ));
  }

  public function outputData($attributes, $content, $block)
  {
    static $seen_ids = array();

    if (!isset($block->context['postId'])) {
      return '';
    }

    $post_id = $block->context['postId'];

    if (isset($seen_ids[$post_id])) {
      $is_debug = WP_DEBUG && WP_DEBUG_DISPLAY;

      return $is_debug ?
        __('[block rendering halted]') :
        '';
    }

    $seen_ids[$post_id] = true;
    if (!in_the_loop() && have_posts()) {
      the_post();
    }

    $content = get_the_content();
    if (has_block('core/nextpage')) {
      $content .= wp_link_pages(array('echo' => 0));
    }

    $content = apply_filters('the_content', str_replace(']]>', ']]&gt;', $content));
    unset($seen_ids[$post_id]);

    if (empty($content)) {
      return '';
    }

    $tag_name = 'div';
    if (isset($attributes['tagName'])) {
      $tag_name = $attributes['tagName'];
    }

    $wrapper_attributes = '';
    $wrapper_classes = ['mk-entry-content'];
    if (isset($attributes['globalSlug']) && $attributes['globalSlug']) {
      $wrapper_classes[] = $attributes['globalSlug'];
    }
    if (isset($attributes['blockID']) && $attributes['blockID']) {
      $wrapper_classes[] = $attributes['blockID'];
    }
    $custom_classes = isset($attributes['cssClass']) ? $attributes['cssClass'] : [];
    if (!empty($custom_classes)) {
      foreach ($custom_classes as $item) {
        $wrapper_classes[] = $item;
      }
    }
    $wrapper_attributes .= 'class="' . esc_attr(implode(' ', $wrapper_classes)) . '"';
    $custom_attrs = isset($attributes['htmlAttr']) ? $attributes['htmlAttr'] : [];
    $wrapper_attrs = [];
    if (isset($attributes['anchor']) && $attributes['anchor']) {
      $wrapper_attrs[] = 'id=' . $attributes['anchor'];
    }
    if (!empty($custom_attrs)) {
      foreach ($custom_attrs as $item) {
        foreach ($item as $key => $value) {
          $wrapper_attrs[] = $key . '=' . $value;
        }
      }
    }
    $wrapper_attributes .= ' ' . esc_attr(implode(' ', $wrapper_attrs));

    return sprintf(
      '<%1$s %2$s>%3$s</%1$s>',
      $tag_name,
      $wrapper_attributes,
      $content
    );
  }
}
