<?php

/**
 * MooKwai blocks
 * Site - Post link
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\MK_Blocks\Site;

use MOOKWAI\App\Traits\Singleton;

class Post_Link
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
    if (!isset($block->context['postId'])) {
      return '';
    }

    $post = get_post($block->context['postId']);

    $wrapper_attributes = '';
    // $post_ID = $block->context['postId'];
    $open_in_new = $attributes['openInNew'];
    if ($open_in_new) $wrapper_attributes .= ' target="_blank"';
    $wrapper_classes = ['mk-post-link'];
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
    $wrapper_attributes .= ' class="' . esc_attr(implode(' ', $wrapper_classes)) . '"';
    $custom_attrs = isset($attributes['htmlAttr']) ? $attributes['htmlAttr'] : [];
    $wrapper_attrs = [];
    if (!empty($custom_attrs)) {
      foreach ($custom_attrs as $item) {
        foreach ($item as $key => $value) {
          $wrapper_attrs[] = $key . '=' . $value;
        }
      }
    }
    $wrapper_attributes .= ' ' . esc_attr(implode(' ', $wrapper_attrs));

    return sprintf(
      '<a href="%1$s" %2$s>%3$s</a>',
      esc_url(get_the_permalink($post)),
      $wrapper_attributes,
      $content
    );
  }
}
