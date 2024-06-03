<?php

/**
 * Enqueue theme assets
 *
 * @package MooKwai
 */

namespace MOOKWAI\App\Routes;

defined('ABSPATH') || exit;

use MOOKWAI\App\Traits\Singleton;

class MooKwai_Global_Styles_Route
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
    register_rest_route('mk/v1', 'styles', array(
      'methods' => ['GET', 'POST'],
      'callback' => [$this, 'getResults'],
      'permission_callback' => '__return_true'
    ));
  }

  public function getResults($request)
  {
    if ($request->get_method() === 'GET') {
      $baseGeneral = (object)[];
      $baseParagraph = (object)[];
      $baseHeading = (object)[];
      $baseLink = (object)[];

      global $wpdb;
      $table_name = $wpdb->prefix . 'mookwai_block_settings';
      $theQuery = $wpdb->prepare("SELECT * from $table_name WHERE the_type = 'mk_block' LIMIT 1000");
      $datas = $wpdb->get_results($theQuery);
      $blocksResult = array(
        'button' => [],
        'navigation' => [],
        'postList' => [],
        'carousel' => [],
      );
      foreach ($datas as $item) {
        $theName = str_replace("block//", "", $item->page_id);
        $blocksResult[$theName] = json_decode($item->the_value);
      }

      if (get_option('mkopt_styles_base')) {
        $baseGeneral = isset(get_option('mkopt_styles_base')['general']) ? get_option('mkopt_styles_base')['general'] : (object)[];
        $baseParagraph = isset(get_option('mkopt_styles_base')['paragraph']) ? get_option('mkopt_styles_base')['paragraph'] : (object)[];
        $baseHeading = isset(get_option('mkopt_styles_base')['heading']) ? get_option('mkopt_styles_base')['heading'] : (object)[];
        $baseLink = isset(get_option('mkopt_styles_base')['link']) ? get_option('mkopt_styles_base')['link'] : (object)[];
      }
      return array(
        'layout' => get_option('mkopt_styles_layout') ? get_option('mkopt_styles_layout') : [],
        'colorPalette' => get_option('mkopt_styles_color') ? get_option('mkopt_styles_color') : array(
          "brand" => [],
          "neutral" => [],
          "hint" => [],
          "other" => [],
        ),
        'fontFamily' => get_option('mkopt_styles_fontFamily') ? get_option('mkopt_styles_fontFamily') : array(
          "customFont" => [],
          "googleFont" => [],
        ),
        'fontCommon' => get_option('mkopt_styles_fontCommon') ? get_option('mkopt_styles_fontCommon') : [],
        'fontMajor' => get_option('mkopt_styles_fontMajor') ? get_option('mkopt_styles_fontMajor') : [],
        'spacing' => get_option('mkopt_styles_spacing') ? get_option('mkopt_styles_spacing') : [],
        'base' => array(
          "general" => $baseGeneral,
          "paragraph" => $baseParagraph,
          "heading" => $baseHeading,
          "link" => $baseLink,
        ),
        'colorScheme' => get_option('mkopt_styles_colorScheme') ? get_option('mkopt_styles_colorScheme') : [
          array(
            "slug" => 'mode-default',
            "title" => '默认'
          )
        ],
        'blocks' => $blocksResult
      );
    } elseif ($request->get_method() === 'POST') {
      global $wpdb;
      $table_name = $wpdb->prefix . 'mookwai_block_settings';
      $updates = $request->get_params()['stylesBlocks'];
      foreach ($updates as $key => $value) {
        $updateDatas = array(
          'the_type' => 'mk_block',
          'page_id' => "block//{$key}",
          'the_value' => json_encode($request->get_params()['stylesBlocks'][$key]),
        );
        $theQuery = $wpdb->prepare("SELECT * from $table_name WHERE page_id = %s", array($updateDatas["page_id"]));
        $result = $wpdb->get_results($theQuery);
        if ($result) {
          $wpdb->update($table_name, $updateDatas, array('page_id' => $updateDatas["page_id"]));
        } else {
          $wpdb->insert($table_name, $updateDatas);
        }
      }
      update_option('mkopt_styles_layout', $request->get_params()['stylesLayout']);
      update_option('mkopt_styles_color', $request->get_params()['stylesColorPalette']);
      update_option('mkopt_styles_fontFamily', $request->get_params()['stylesFontFamily']);
      update_option('mkopt_styles_fontCommon', $request->get_params()['stylesFontCommon']);
      update_option('mkopt_styles_fontMajor', $request->get_params()['stylesFontMajor']);
      update_option('mkopt_styles_spacing', $request->get_params()['stylesSpacing']);
      update_option('mkopt_styles_base', $request->get_params()['stylesBase']);
    }
  }
}
