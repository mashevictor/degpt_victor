<?php

/**
 * MooKwai blocks
 * Site - Pagination number
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\MK_Blocks\Site;

use MOOKWAI\App\Traits\Singleton;

use WP_Query;

class Pagination_Number
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
    $max_page = isset($block->context['query']['pages']) ? (int) $block->context['query']['pages'] : 0;

    $content = '';
    global $wp_query;
    if (isset($block->context['query']['inherit']) && $block->context['query']['inherit']) {
      $total         = !$max_page || $max_page > $wp_query->max_num_pages ? $wp_query->max_num_pages : $max_page;
      $paginate_args = array(
        'prev_next' => false,
        'total'     => $total,
      );
      $content = paginate_links($paginate_args);
      if ($max_page > $wp_query->max_num_pages) {
        $max_page = $wp_query->max_num_pages;
      }
      global $paged;
    } else {
      $block_query = new WP_Query(build_query_vars_from_query_block($block, $page));
      $custom_query_max_pages = (int) $block_query->max_num_pages;
      $prev_wp_query = $wp_query;
      $wp_query      = $block_query;
      $total         = !$max_page || $max_page > $wp_query->max_num_pages ? $wp_query->max_num_pages : $max_page;
      $paginate_args = array(
        'base'      => '%_%',
        'format'    => "?$page_key=%#%",
        'current'   => max(1, $page),
        'total'     => $total,
        'prev_next' => false,
      );
      if (1 !== $page) {
        $paginate_args['add_args'] = array('cst' => '');
      }
      $paged = empty($_GET['paged']) ? null : (int) $_GET['paged'];
      if ($paged) {
        $paginate_args['add_args'] = array('paged' => $paged);
      }
      $content = paginate_links($paginate_args);
      wp_reset_postdata();
      $wp_query = $prev_wp_query;
    }
    if (empty($content)) {
      return '';
    }
    $classes = ['el-paginum'];
    if (isset($attributes['blockID'])) {
      $classes[] = $attributes['blockID'];
    }
    $wrapper_attributes = 'class="' . esc_attr(implode(' ', $classes)) . '"';

    return sprintf(
      '<div %1$s>%2$s</div>',
      $wrapper_attributes,
      $content
    );
  }
}
