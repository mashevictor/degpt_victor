<?php

/**
 * Register mookwai settings route
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\App\Routes;

defined('ABSPATH') || exit;

use MOOKWAI\App\Traits\Singleton;

class MooKwai_Post_Settings_Route
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
    register_rest_route('mk/v1', 'mookwai-settings', array(
      'methods' => ['GET', 'POST'],
      'callback' => [$this, 'getResults'],
      'permission_callback' => '__return_true'
    ));
  }

  public function getResults($request)
  {
    if ($request->get_method() === 'GET') {
      global $wpdb;
      $table_name = $wpdb->prefix . 'mookwai_block_settings';
      $the_page_id = isset($_GET['page_id']) ? $_GET['page_id'] : null;
      if (!$the_page_id) return 'page not found.';
      $theQuery = $wpdb->prepare("SELECT * FROM $table_name WHERE page_id = %s LIMIT 10", array($_GET['page_id']));
      $datas = $wpdb->get_results($theQuery);

      $templatesQuery = $wpdb->prepare("SELECT page_id, color_scheme FROM $table_name WHERE the_type = %s LIMIT 10000", array('wp_template'));
      $templatesDatas = $wpdb->get_results($templatesQuery);

      $baseGeneral = (object)[];
      $baseLink = (object)[];
      $baseCursor = (object)[];

      if (get_option('mkopt_styles_base')) {
        $baseGeneral = isset(get_option('mkopt_styles_base')['general']) && get_option('mkopt_styles_base')['general'] ? get_option('mkopt_styles_base')['general'] : (object)[];
        $baseLink = isset(get_option('mkopt_styles_base')['link']) && get_option('mkopt_styles_base')['link'] ? get_option('mkopt_styles_base')['link'] : (object)[];
        $baseCursor = isset(get_option('mkopt_styles_base')['cursor']) && get_option('mkopt_styles_base')['cursor'] ? get_option('mkopt_styles_base')['cursor'] : (object)[];
      }

      $blocksQuery = $wpdb->prepare("SELECT * from $table_name WHERE the_type = 'mk_block' LIMIT 1000");
      $reuseableQuery = $wpdb->prepare("SELECT * from $table_name WHERE the_type = 'wp_reuseable_block' LIMIT 1000");
      $blocksData = $wpdb->get_results($blocksQuery);
      $reuseableData = $wpdb->get_results($reuseableQuery);
      $reuseableResult = array();
      foreach ($reuseableData as &$reuseable) {
        if (isset($reuseable->the_value)) {
          $reuseableResult = array_merge($reuseableResult, json_decode($reuseable->the_value, true));
        }
      }
      $blocksResult = array(
        'button' => [],
      );
      foreach ($blocksData as $item) {
        $theName = str_replace("mkblock_", "", $item->page_id);
        $blocksResult[$theName] = json_decode($item->the_value);
      }
      $globalStyles = array(
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
        'colorScheme' => get_option('mkopt_styles_colorScheme') ? get_option('mkopt_styles_colorScheme') : [
          array(
            "slug" => 'mode-default',
            "title" => '默认'
          )
        ],
        'base' => array(
          "general" => $baseGeneral,
          "link" => $baseLink,
          "cursor" => $baseCursor,
        ),
      );
      $iconLibraries = get_option('mkopt_icon_library') ? get_option('mkopt_icon_library') : [];
      $editorWidthOptions = get_option('mkopt_editor_width_options') ? get_option('mkopt_editor_width_options') : array(
        array('title' => '全屏', 'slug' => 'full', 'width' => false, 'side' => false, 'sidePhone' => false),
        array('title' => '自动宽度', 'slug' => 'autoWide', 'width' => false, 'side' => '64', 'sidePhone' => '16')
      );
      $editorWidthDefault = get_option('mkopt_editor_width_default') ? get_option('mkopt_editor_width_default') : 'full';
      $editorWidthCurrent = $editorWidthDefault;
      if (isset($datas[0])) {
        $editorWidthCurrent = get_post_meta($datas[0]->page_id) ? get_post_meta($datas[0]->page_id, '_mk_post_edit_width', true) : $editorWidthDefault;
      }
      if ($datas) {
        $inherit_scheme = '';
        if ($datas[0]->the_type === 'post') {
          $template_slug = get_post_meta($datas[0]->page_id, '_wp_page_template', true);
        }
        $postSettings = array(
          "pageID" => $datas[0]->page_id,
          "theType" => $datas[0]->the_type,
          "parts" => json_decode($datas[0]->parts),
          "color_scheme" => $datas[0]->color_scheme,
          "inherit_scheme" => $inherit_scheme,
          "custom_css" => $datas[0]->custom_css,
          "custom_js" => $datas[0]->custom_js,
          "import_file" => $datas[0]->import_file,
          "blocks_setting" => json_decode($datas[0]->the_value),
          "page_setting" => json_decode($datas[0]->page_setting),
          "globalStyles" => $globalStyles,
          "iconLibraries" => $iconLibraries,
          "globalBlocks" => $blocksResult,
          "theTemplates" => $templatesDatas,
          "editorWidthOptions" => $editorWidthOptions,
          "editorWidthDefault" => $editorWidthDefault,
          "editorWidthCurrent" => $editorWidthCurrent,
          "reuseableBlocks" => $reuseableResult,
        );
      } else {
        $postSettings = array(
          "pageID" => null,
          "theType" => null,
          "parts" => [],
          "color_scheme" => "",
          "inherit_scheme" => "",
          "custom_css" => "",
          "custom_js" => "",
          "import_file" => "",
          "blocks_setting" => [],
          "page_setting" => null,
          "globalStyles" => $globalStyles,
          "iconLibraries" => $iconLibraries,
          "globalBlocks" => $blocksResult,
          "theTemplates" => $templatesDatas,
          "editorWidthOptions" => $editorWidthOptions,
          "editorWidthDefault" => $editorWidthDefault,
          "editorWidthCurrent" => $editorWidthCurrent,
        );
      }

      return $postSettings;
    } elseif ($request->get_method() === 'POST') {
      include(MOOKWAI_PATH . '/app/generate-styles/generate-global.php');
      include(MOOKWAI_PATH . '/app/generate-styles/generate-blocks.php');
      include(MOOKWAI_PATH . '/app/generate-scripts/generate-global.php');
      include(MOOKWAI_PATH . '/app/generate-scripts/generate-blocks.php');

      global $wpdb;
      $table_name = $wpdb->prefix . 'mookwai_block_settings';
      $theUpadateDatas = $request->get_params()['datasArray'];
      $isUpdateGlobalStyles = $request->get_params()['updateGlobalStyles'];
      // $isUpdateGlobalBlocks = $request->get_params()['updateGlobalBlocks'];
      $theGlobalStylesDatas = $request->get_params()['globalStyles'];
      $theCustomIconLibraryDatas = $request->get_params()['customIconLibrary'];
      $theGlobalBlocksDatas = $request->get_params()['globalBlocks'];
      $updataEditorWidthOptions = $request->get_params()['editorWidthOptions'];
      $updataEditorWidthDefault = $request->get_params()['editorWidthDefault'];
      $updataEditorWidthCurrent = $request->get_params()['editorWidthCurrent'];

      $reuseableQuery = $wpdb->prepare("SELECT * from $table_name WHERE the_type = 'wp_reuseable_block' LIMIT 1000");
      $reuseableData = $wpdb->get_results($reuseableQuery);
      $theReuseableBlockDatas = array();
      foreach ($reuseableData as &$reuseable) {
        if (isset($reuseable->the_value)) {
          $theReuseableBlockDatas = array_merge($theReuseableBlockDatas, json_decode($reuseable->the_value, true));
        }
      }

      update_option('mkopt_editor_width_options', $updataEditorWidthOptions);
      update_option('mkopt_editor_width_default', $updataEditorWidthDefault);

      // 全局样式更新
      if ($isUpdateGlobalStyles) {
        update_option('mkopt_styles_layout', $theGlobalStylesDatas['layout']);
        update_option('mkopt_styles_color', $theGlobalStylesDatas['colorPalette']);
        update_option('mkopt_styles_fontFamily', $theGlobalStylesDatas['fontFamily']);
        update_option('mkopt_styles_fontCommon', $theGlobalStylesDatas['fontCommon']);
        update_option('mkopt_styles_fontMajor', $theGlobalStylesDatas['fontMajor']);
        update_option('mkopt_styles_spacing', $theGlobalStylesDatas['spacing']);
        update_option('mkopt_styles_colorScheme', $theGlobalStylesDatas['colorScheme']);
        update_option('mkopt_styles_base', $theGlobalStylesDatas['base']);
        foreach ($theGlobalBlocksDatas as $key => $value) {
          $pageId = "mkblock_$key";
          $blockUpdateDatas = array(
            'the_type' => 'mk_block',
            'page_id' => $pageId,
            'the_value' => json_encode($value),
            'parts' => [],
          );
          $blockQuery = $wpdb->prepare("SELECT * from $table_name WHERE page_id = %s", array($pageId));
          $blockResult = $wpdb->get_results($blockQuery);
          if ($blockResult) {
            $wpdb->update($table_name, $blockUpdateDatas, array('page_id' => $pageId));
          } else {
            $wpdb->insert($table_name, $blockUpdateDatas);
          }
        }

        $enable_lenis_smooth = get_option('mkopt_enable_lenis_smooth');
        $lenis_smooth_lerp = get_option('mkopt_lenis_smooth_lerp', '0.06');
        $lenis_lerp = false;
        if ($enable_lenis_smooth && $lenis_smooth_lerp) {
          $lenis_lerp = $lenis_smooth_lerp;
        }
        $contentGlobalStyles = generateGlobalStyles($theGlobalStylesDatas, $theGlobalBlocksDatas, $theReuseableBlockDatas, false);
        $contentGlobalStylesEditor = generateGlobalStyles($theGlobalStylesDatas, $theGlobalBlocksDatas, $theReuseableBlockDatas, true);
        $contentGlobalScripts = generateGlobalScripts($theGlobalBlocksDatas, $theReuseableBlockDatas, $lenis_lerp);

        $fileGlobalStylesPath = MOOKWAI_CONTENT_PATH . '/mk-scripts/mk-global.min.css';
        $fileGlobalStylesDir = dirname($fileGlobalStylesPath);
        if (!file_exists($fileGlobalStylesDir)) {
          mkdir($fileGlobalStylesDir, 0777, true);
        }
        $fileGlobalStyles = fopen($fileGlobalStylesPath, 'w');

        $fileGlobalStylesEditorPath = MOOKWAI_CONTENT_PATH . '/mk-scripts/mk-editor-global.min.css';
        $fileGlobalStylesEditor = fopen($fileGlobalStylesEditorPath, 'w');

        $fileGlobalScriptsPath = MOOKWAI_CONTENT_PATH . '/mk-scripts/mk-global.min.js';
        $fileGlobalScripts = fopen($fileGlobalScriptsPath, 'w');

        if (flock($fileGlobalStyles, LOCK_EX)) {
          fwrite($fileGlobalStyles, $contentGlobalStyles);
        }
        fclose($fileGlobalStyles);
        if (flock($fileGlobalStylesEditor, LOCK_EX)) {
          fwrite($fileGlobalStylesEditor, $contentGlobalStylesEditor);
        }
        fclose($fileGlobalStylesEditor);
        if (flock($fileGlobalScripts, LOCK_EX)) {
          fwrite($fileGlobalScripts, $contentGlobalScripts);
        }
        fclose($fileGlobalScripts);
      }

      // Icon Library
      update_option('mkopt_icon_library', $theCustomIconLibraryDatas);
      $decode_icon_library_datas = json_encode($theCustomIconLibraryDatas);
      $fileIconLibrary = fopen(MOOKWAI_CONTENT_PATH . '/mk-scripts/icon-library.json', 'w');
      if (flock($fileIconLibrary, LOCK_EX)) {
        fwrite($fileIconLibrary, $decode_icon_library_datas);
      }
      fclose($fileIconLibrary);

      $templateUpdate = false;
      $templateScripts = '';

      // 页面样式更新
      foreach ($theUpadateDatas as $itemData) {
        $newPageSetting = $itemData['pageSetting'];
        $newDatas = $itemData['data'];
        $newCustomCSS = $itemData['customCSS'];
        $newCustomJS = $itemData['customJS'];
        $import_file = isset($itemData['importFile']) ? $itemData['importFile'] : null;
        $updateDatas = array(
          'the_type' => $itemData['theType'],
          'page_id' => $itemData['pageID'],
          'the_value' => json_encode($newDatas),
          'parts' => json_encode($itemData['parts']),
          'color_scheme' => $itemData['colorScheme'],
          'custom_css' => $newCustomCSS,
          'custom_js' => $newCustomJS,
          'import_file' => $import_file,
          'page_setting' => json_encode($newPageSetting),
        );
        $theQuery = $wpdb->prepare("SELECT * from $table_name WHERE page_id = %s", array($updateDatas["page_id"]));
        $result = $wpdb->get_results($theQuery);
        if ($result) {
          $wpdb->update($table_name, $updateDatas, array('page_id' => $updateDatas["page_id"]));
        } else {
          $wpdb->insert($table_name, $updateDatas);
        }

        $getGlobalColor = $theGlobalStylesDatas['colorPalette'];
        $globalColor = [];
        foreach ($getGlobalColor as $key => $value) {
          foreach ($value as $item) {
            array_push($globalColor, $item);
          }
        }

        if ($updateDatas['the_type'] == 'post') {
          update_post_meta($updateDatas['page_id'], '_mk_post_edit_width', $updataEditorWidthCurrent);
          $fileName =  'mk-page-' . $updateDatas['page_id'];
          $folderYear = get_the_date('/Y', $updateDatas['page_id']);
          $folderMonth = get_the_date('/m', $updateDatas['page_id']);
          if (!is_dir(MOOKWAI_CONTENT_PATH . '/mk-scripts' . $folderYear . $folderMonth)) {
            mkdir(MOOKWAI_CONTENT_PATH . '/mk-scripts' . $folderYear . $folderMonth, 0777, true);
          }
          $pageStyles = generatePageBlocks($newPageSetting, $newDatas, $newCustomCSS, $globalColor);
          $pageScripts = generatePageBlocksScript($newPageSetting, $newDatas, $newCustomJS);
          $filePageStyle = fopen(MOOKWAI_CONTENT_PATH . '/mk-scripts/' . $folderYear . $folderMonth . '/' . $fileName . '.css', 'w');
          $filePageScript = fopen(MOOKWAI_CONTENT_PATH . '/mk-scripts/' . $folderYear . $folderMonth . '/' . $fileName . '.js', 'w');
          if (flock($filePageStyle, LOCK_EX)) {
            fwrite($filePageStyle, $pageStyles);
            fwrite($filePageScript, $pageScripts);
          }
          fclose($filePageStyle);
          fclose($filePageScript);
        } else if ($updateDatas['the_type'] == 'wp_template') {
          $template_name = '';
          $template_id_array = explode("//", $updateDatas['page_id']);
          if (count($template_id_array) > 1) {
            $template_name = $template_id_array[1];
          }
          $fileName =  'mk-template-' . $updateDatas['page_id'];
          if (!is_dir(MOOKWAI_CONTENT_PATH . '/mk-scripts/template/')) {
            mkdir(MOOKWAI_CONTENT_PATH . '/mk-scripts/template/', 0777, true);
          }
          $pageStyles = generatePageBlocks($newPageSetting, $newDatas, $newCustomCSS, $globalColor);
          $pageScripts = generatePageBlocksScript($newPageSetting, $newDatas, $newCustomJS);
          $filePageStyle = fopen(MOOKWAI_CONTENT_PATH . '/mk-scripts/template/mk-' . $template_name . '.css', 'w');
          $filePageScript = fopen(MOOKWAI_CONTENT_PATH . '/mk-scripts/template/mk-' . $template_name . '.js', 'w');
          if (flock($filePageStyle, LOCK_EX)) {
            fwrite($filePageStyle, $pageStyles);
            fwrite($filePageScript, $pageScripts);
          }
          fclose($filePageStyle);
          fclose($filePageScript);
        } else {
          $templateUpdate = true;
        }
      }

      // 模板样式更新
      if ($templateUpdate) {
        $templateTypes = array('wp_template_part', 'wp_reuseable_block');
        $partsResult = array();
        $partsCustomCSS = '';
        foreach ($templateTypes as $item) {
          $partsQuery = $wpdb->prepare("SELECT * from $table_name WHERE the_type = %s", array($item));
          $partsValue = $wpdb->get_results($partsQuery);
          foreach ($partsValue as $part) {
            $the_value = json_decode($part->the_value, true);
            $the_custom_css = $part->custom_css;
            $partsCustomCSS .= $the_custom_css;
            foreach ($the_value as $value) {
              $partsResult[] = $value;
            }
          }
        }
        $templatesQuery = $wpdb->prepare("SELECT page_id, page_setting FROM $table_name WHERE the_type = %s LIMIT 10000", array('wp_template'));
        $templatesDatas = $wpdb->get_results($templatesQuery);
        $templateArray = [];
        foreach ($templatesDatas as $template) {
          $itemData = array(
            "page_id" => $template->page_id,
            "page_setting" => json_decode($template->page_setting, true),
          );
          $templateArray[] = $itemData;
        }
        $templateStyles = generatePageBlocks($templateArray, $partsResult, $partsCustomCSS, $globalColor);
        $templateScripts .= generateTemplateScript($partsResult, $newCustomJS);
        $fileTemplateStyle = fopen(MOOKWAI_CONTENT_PATH . '/mk-scripts/mk-template.min.css', 'w');
        $fileTemplateScript = fopen(MOOKWAI_CONTENT_PATH . '/mk-scripts/mk-template.min.js', 'w');
        if (flock($fileTemplateStyle, LOCK_EX)) {
          fwrite($fileTemplateStyle, $templateStyles);
        }
        fclose($fileTemplateStyle);
        if (flock($fileTemplateScript, LOCK_EX)) {
          fwrite($fileTemplateScript, $templateScripts);
        }
        fclose($fileTemplateScript);
      }
    }
  }
}
