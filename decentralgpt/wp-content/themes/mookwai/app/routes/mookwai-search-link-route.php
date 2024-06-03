<?php

/**
 * Register search link route
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\App\Routes;

defined('ABSPATH') || exit;

use MOOKWAI\App\Traits\Singleton;
use WP_REST_SERVER;
use WP_Query;

class MooKwai_Search_Link_Route
{

  use Singleton;

  protected function __construct()
  {
    $this->setup_hooks();
  }

  protected function setup_hooks()
  {
    add_action('rest_api_init', [$this, 'registerRoute']);
  }

  public function registerRoute()
  {
    register_rest_route('mk/v1', 'links', array(
      'methods' => WP_REST_SERVER::READABLE,
      'callback' => [$this, 'getResults'],
      'permission_callback' => '__return_true'
    ));
  }

  public function getResults($request)
  {
    $theDatas = new WP_Query(array(
      'post_type' => 'any',
      's' => sanitize_text_field($request['keyword']),
      'posts_per_page' => 10
    ));
    $theResults = array();
    while ($theDatas->have_posts()) {
      $theDatas->the_post();
      $get_blocks = parse_blocks(get_the_content());
      $content = '';
      foreach ($get_blocks as $block) {
        $content .= render_block($block);
      }
      $theResults[] = array(
        'id' => get_the_ID(),
        'type' => get_post_type(),
        'title' => get_the_title(),
        'content' => $content,
        'permalink' => get_the_permalink()
      );
    }
    return $theResults;
  }
}
