<?php

/**
 * Register icon library route
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\App\Routes;

defined('ABSPATH') || exit;

use MOOKWAI\App\Traits\Singleton;

class MooKwai_Icon_Library_Route
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
    register_rest_route('mk/v1', 'icon-library', array(
      'methods' => ['GET', 'POST'],
      'callback' => [$this, 'getResults'],
      'permission_callback' => '__return_true'
    ));
  }

  public function getResults($request)
  {
    if ($request->get_method() === 'GET') {
      $library_datas = get_option('mkopt_icon_library') ? get_option('mkopt_icon_library') : [];
      return $library_datas;
    } elseif ($request->get_method() === 'POST') {
      $updata_library_datas = $request->get_params();
      update_option('mkopt_icon_library', $updata_library_datas);
    }
  }
}
