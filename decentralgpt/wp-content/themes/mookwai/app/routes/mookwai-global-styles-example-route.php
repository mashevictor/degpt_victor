<?php

/**
 * Enqueue theme assets
 *
 * @package MooKwai
 */

namespace MOOKWAI\App\Routes;

defined('ABSPATH') || exit;

use MOOKWAI\App\Traits\Singleton;

class MooKwai_Global_Styles_Example_Route
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
    register_rest_route('mk/v1', 'styles-example', array(
      'methods' => ['GET', 'POST'],
      'callback' => [$this, 'getResults'],
      'permission_callback' => '__return_true'
    ));
  }

  public function getResults($request)
  {
    if ($request->get_method() === 'GET') {
      return array(
        'layout' => get_option('mkopt_examples_layout') ? get_option('mkopt_examples_layout') : [],
        'colorPalette' => get_option('mkopt_examples_color') ? get_option('mkopt_examples_color') : array(
          "brand" => [],
          "neutral" => [],
          "hint" => [],
          "other" => [],
        ),
        'fontFamily' => get_option('mkopt_examples_fontFamily') ? get_option('mkopt_examples_fontFamily') : array(
          "customFont" => [],
          "googleFont" => [],
        ),
        'fontCommon' => get_option('mkopt_examples_fontCommon') ? get_option('mkopt_examples_fontCommon') : [],
        'fontMajor' => get_option('mkopt_examples_fontMajor') ? get_option('mkopt_examples_fontMajor') : [],
        'spacing' => get_option('mkopt_examples_spacing') ? get_option('mkopt_examples_spacing') : [],
        'base' => get_option('mkopt_examples_base') ? get_option('mkopt_examples_base') : array(
          "page" => [],
          "paragraph" => [],
          "heading" => [],
          "link" => [],
        ),
      );
    } elseif ($request->get_method() === 'POST') {
      return array(
        update_option('mkopt_examples_layout', $request->get_params()['examplesLayout']),
        update_option('mkopt_examples_color', $request->get_params()['examplesColorPalette']),
        update_option('mkopt_examples_fontFamily', $request->get_params()['examplesFontFamily']),
        update_option('mkopt_examples_fontCommon', $request->get_params()['examplesFontCommon']),
        update_option('mkopt_examples_fontMajor', $request->get_params()['examplesFontMajor']),
        update_option('mkopt_examples_spacing', $request->get_params()['examplesSpacing']),
        update_option('mkopt_examples_base', $request->get_params()['examplesBase']),
      );
    }
  }
}
