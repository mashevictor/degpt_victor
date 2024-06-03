<?php

/**
 * MooKwai blocks
 * Site - Post title
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\MK_Blocks\Site;

use MOOKWAI\App\Traits\Singleton;

class Post_Title
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

    $post  = get_post($block->context['postId']);
    $title = get_the_title($post);

    if (!$title) {
      return '';
    }

    $tag_name = 'h4';
    if (isset($attributes['tagName'])) {
      $tag_name = $attributes['tagName'];
    }

    if ($attributes['isLink']) {
      $open_in_new = $attributes['openInNew'];
      $rel_value = $attributes['linkRel'];
      $rel_attrs = '';
      if (isset($rel_value) && !empty($rel_value)) {
        $rel_value = implode(' ', $rel_value);
        $rel_attrs = ' rel="' . $rel_value . '"';
      }
      $target = '';
      if ($open_in_new) {
        $target = ' target="_blank"';
      }
      $title = sprintf(
        '<a href="%1$s"%2$s%3$s>%4$s</a>',
        get_the_permalink($post),
        $target,
        $rel_attrs,
        $title
      );
    }

    $wrapper_attributes = '';
    $wrapper_classes = ['mk-entry-title'];
    if (isset($attributes['isRestrict']) && $attributes['isRestrict']) {
      $wrapper_classes[] = 'ellipsis';
    }
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
      $title
    );
  }
}
