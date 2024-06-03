<?php

/**
 * MooKwai blocks
 * Site - Pagination next
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\MK_Blocks\Site;

use MOOKWAI\App\Traits\Singleton;

use WP_Query;

class Pagination_Next
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

    $nextContent = '';

    $classes = ['page-numbers', 'el-paginext'];
    if (isset($attributes['blockID'])) {
      $classes[] = $attributes['blockID'];
    }
    $wrapper_classes = 'class="' . esc_attr(implode(' ', $classes)) . '"';

    global $wp_query;
    if (isset($block->context['query']['inherit']) && $block->context['query']['inherit']) {
      global $paged;
      $nextContent = $this->mk_next_post_link($paged, $wp_query->max_num_pages, $content, $wrapper_classes);
    } else {
      $block_query = new WP_Query(build_query_vars_from_query_block($block, $page));
      $custom_query_max_pages = (int) $block_query->max_num_pages;
      $prev_wp_query = $wp_query;
      $wp_query      = $block_query;
      if ($custom_query_max_pages && $custom_query_max_pages !== $page) {
        $nextContent = sprintf(
          '<a href="%1$s" %2$s>%3$s</a>',
          esc_url(add_query_arg($page_key, $page + 1)),
          $wrapper_classes,
          $content
        );
      }
      wp_reset_postdata();
      $wp_query = $prev_wp_query;
    }
    return $nextContent;
  }
  public function mk_next_post_link($paged, $max_page, $content, $classes)
  {
    if ($paged == $max_page || $max_page == 1) {
      return '';
    }
    return sprintf(
      '<a href="%1$s" %2$s>%3$s</a>',
      next_posts($max_page, false),
      $classes,
      $content
    );
  }
}
