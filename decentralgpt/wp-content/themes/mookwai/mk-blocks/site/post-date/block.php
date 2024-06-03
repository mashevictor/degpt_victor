<?php

/**
 * MooKwai blocks
 * Site - Post date
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\MK_Blocks\Site;

use MOOKWAI\App\Traits\Singleton;

class Post_Date
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
    $the_format = '';
    if (isset($attributes['format']) && $attributes['format'] && $attributes['format'] !== 'inherit') {
      $the_format = $attributes['format'];
    }
    $post_ID = $block->context['postId'];
    $formatted_date = get_the_date($the_format, $post_ID);
    $unformatted_date = esc_attr(get_the_date('c', $post_ID));

    if (isset($attributes['displayType']) && 'modified' === $attributes['displayType']) {
      if (get_the_modified_date('Ymdhi', $post_ID) > get_the_date('Ymdhi', $post_ID)) {
        $formatted_date = get_the_modified_date($the_format, $post_ID);
        $unformatted_date = esc_attr(get_the_modified_date('c', $post_ID));
        $classes[] = 'wp-block-post-date__modified-date';
      }
    }

    $classes = array('mk-entry-date');
    if (isset($attributes['globalSlug']) && $attributes['globalSlug']) $classes[] = $attributes['globalSlug'];
    if ($attributes['blockID']) $classes[] = $attributes['blockID'];

    $wrapper_classes = 'class="' . implode(' ', $classes) . '"';

    if (isset($attributes['isLink']) && $attributes['isLink']) {
      $formatted_date = sprintf('<a href="%1s">%2s</a>', get_the_permalink($post_ID), $formatted_date);
    }

    return sprintf(
      '<div %1$s><time datetime="%2$s">%3$s</time></div>',
      $wrapper_classes,
      $unformatted_date,
      $formatted_date
    );
  }
}
