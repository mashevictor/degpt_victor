<?php

/**
 * MooKwai blocks
 * Site - Pagination previous
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\MK_Blocks\Site;

use MOOKWAI\App\Traits\Singleton;

use WP_Query;

class Pagination_Previous
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
    $page_key = isset($block->context['queryId']) ? 'query-' . $block->context['queryId'] . '-page' : 'query-page';
    $page = empty($_GET[$page_key]) ? 1 : (int) $_GET[$page_key];

    $prevContent = '';

    $classes = ['page-numbers', 'el-pagiprev'];
    if (isset($attributes['blockID'])) {
      $classes[] = $attributes['blockID'];
    }
    $wrapper_classes = 'class="' . esc_attr(implode(' ', $classes)) . '"';

    global $wp_query;
    if (isset($block->context['query']['inherit']) && $block->context['query']['inherit']) {
      global $paged;
      $prevContent = $this->mk_previous_post_link($paged, $content, $wrapper_classes);
    } else {
      $block_query = new WP_Query(build_query_vars_from_query_block($block, $page));
      $prev_wp_query = $wp_query;
      $wp_query      = $block_query;
      if (1 !== $page) {
        $prevContent = sprintf(
          '<a href="%1$s" %2$s>%3$s</a>',
          esc_url(add_query_arg($page_key, $page - 1)),
          $wrapper_classes,
          $content
        );
      }
      wp_reset_postdata();
      $wp_query = $prev_wp_query;
    }
    return $prevContent;
  }
  public function mk_previous_post_link($paged, $content, $classes)
  {
    if (!$paged) {
      return '';
    }
    return sprintf(
      '<a href="%1$s" %2$s>%3$s</a>',
      previous_posts(false),
      $classes,
      $content
    );
  }
}
