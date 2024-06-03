<?php

/**
 * Register theme settings route
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\App\Routes;

defined('ABSPATH') || exit;

use MOOKWAI\App\Traits\Singleton;

class MooKwai_Dashboard_Route
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
    register_rest_route('mk/v1', 'mookwai-dashboard', array(
      'methods' => ['GET', 'POST'],
      'callback' => [$this, 'getResults'],
      'permission_callback' => '__return_true'
    ));
  }

  public function getResults($request)
  {
    if ($request->get_method() === 'GET') {
      $theme = wp_get_theme()->parent();
      if (!$theme) $theme = wp_get_theme();
      $theme_version = $theme->get('Version');

      // Customize
      $adminBar = get_option('mkopt_show_admin_bar', 1);
      $adminAccess = get_option('mkopt_admin_access');
      $svgUpload = get_option('mkopt_svg_upload');
      $enqueueDefaultStyles = get_option('mkopt_enqueue_default_styles', 1);
      $enqueueExtentionScripts = get_option('mkopt_enqueue_extention_scripts', 1);
      $enqueueJquery = get_option('mkopt_enqueue_jquery');
      $enqueueThree = get_option('mkopt_enqueue_three');
      $enqueueBarba = get_option('mkopt_enqueue_barba');
      $enqueueSpline = get_option('mkopt_enqueue_spline', 1);
      $iosMobileWebApp = get_option('mkopt_ios_mobile_app');
      $themeColor = get_option('mkopt_theme_color', '');
      $favicon = get_option('mkopt_favicon', '');
      $faviconL = get_option('mkopt_favicon_l', '');
      $import_styles_data = get_option('mkopt_import_styles');
      $import_scripts_data = get_option('mkopt_import_scripts');
      $importStyles = array();
      $importScripts = array();
      if ($import_styles_data && is_array($import_styles_data)) {
        $importStyles = get_option('mkopt_import_styles');
      }
      if ($import_scripts_data && is_array($import_scripts_data)) {
      $importScripts = get_option('mkopt_import_scripts');
      }
      $custom_head = get_option('mkopt_custom_head', '');
      $custom_body_attributes = get_option('mkopt_custom_body_attributes', '');
      $enable_lenis_smooth = get_option('mkopt_enable_lenis_smooth');
      $lenis_smooth_lerp = get_option('mkopt_lenis_smooth_lerp', '0.06');
      
      // Integrate
      $analyticsGoogle = get_option('mkopt_analytics_google_uid');
      $analyticsBaidu = get_option('mkopt_analytics_baidu_src');
      $enqueueWooCommerce = get_option('mkopt_enqueue_woocommerce');

      return array(
        'environment' => array(
          'themeVersion' => $theme_version,
        ),
        'customize' => array(
          'adminBar' => $adminBar,
          'adminAccess' => $adminAccess,
          'svgUpload' => $svgUpload,
          'enqueueDefaultStyles' => $enqueueDefaultStyles,
          'enqueueExtentionScripts' => $enqueueExtentionScripts,
          'enqueueJquery' => $enqueueJquery,
          'enqueueThree' => $enqueueThree,
          'enqueueBarba' => $enqueueBarba,
          'enqueueSpline' => $enqueueSpline,
          'iosMobileWebApp' => $iosMobileWebApp,
          'themeColor' => $themeColor,
          'favicon' => $favicon,
          'faviconL' => $faviconL,
          'importStyles' => $importStyles,
          'importScripts' => $importScripts,
          'customHead' => $custom_head,
          'bodyAttributes' => $custom_body_attributes,
          'enableLenisSmooth' => $enable_lenis_smooth,
          'lenisSmoothLerp' => $lenis_smooth_lerp,
        ),
        'integrate' => array(
          'analyticsGoogle' => $analyticsGoogle,
          'analyticsBaidu' => $analyticsBaidu,
          'enqueueWooCommerce' => $enqueueWooCommerce,
        ),
      );
    } elseif ($request->get_method() === 'POST') {
      // Customize
      $customize_datas = $request->get_params()['customize'];
      $data_admin_bar = $customize_datas['adminBar'] ? 1 : '';
      $data_admin_access = $customize_datas['adminAccess'] ? 1 : '';
      $data_svg_upload = $customize_datas['svgUpload'] ? 1 : '';
      $data_enqueue_default_styles = $customize_datas['enqueueDefaultStyles'] ? 1 : '';
      $data_enqueue_extention_scripts = $customize_datas['enqueueExtentionScripts'] ? 1 : '';
      $data_enqueue_jquery = $customize_datas['enqueueJquery'] ? 1 : '';
      $data_enqueue_three = $customize_datas['enqueueThree'] ? 1 : '';
      $data_enqueue_barba = $customize_datas['enqueueBarba'] ? 1 : '';
      $data_enqueue_spline = $customize_datas['enqueueSpline'] ? 1 : '';
      $data_ios_mobile_app = $customize_datas['iosMobileWebApp'] ? 1 : '';
      update_option('mkopt_show_admin_bar', $data_admin_bar);
      update_option('mkopt_admin_access', $data_admin_access);
      update_option('mkopt_svg_upload', $data_svg_upload);
      update_option('mkopt_enqueue_default_styles', $data_enqueue_default_styles);
      update_option('mkopt_enqueue_extention_scripts', $data_enqueue_extention_scripts);
      update_option('mkopt_enqueue_jquery', $data_enqueue_jquery);
      update_option('mkopt_enqueue_three', $data_enqueue_three);
      update_option('mkopt_enqueue_barba', $data_enqueue_barba);
      update_option('mkopt_enqueue_spline', $data_enqueue_spline);
      update_option('mkopt_ios_mobile_app', $data_ios_mobile_app);
      update_option('mkopt_theme_color', $customize_datas['themeColor']);
      update_option('mkopt_favicon', $customize_datas['favicon']);
      update_option('mkopt_favicon_l', $customize_datas['faviconL']);
      update_option('mkopt_import_styles', $customize_datas['importStyles']);
      update_option('mkopt_import_scripts', $customize_datas['importScripts']);
      update_option('mkopt_custom_head', $customize_datas['customHead']);
      update_option('mkopt_custom_body_attributes', $customize_datas['bodyAttributes']);
      update_option('mkopt_enable_lenis_smooth', $customize_datas['enableLenisSmooth']);
      update_option('mkopt_lenis_smooth_lerp', $customize_datas['lenisSmoothLerp']);

      // Integrate
      $integrate_datas = $request->get_params()['integrate'];
      update_option('mkopt_analytics_google_uid', $integrate_datas['analyticsGoogle']);
      update_option('mkopt_analytics_baidu_src', $integrate_datas['analyticsBaidu']);
      update_option('mkopt_enqueue_woocommerce', $integrate_datas['enqueueWooCommerce']);
    }
  }
}