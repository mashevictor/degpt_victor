<?php

/**
 * MooKwai theme options - Global styles content
 *
 * @package MooKwai
 */

defined('ABSPATH') || exit;

require_once MOOKWAI_PATH . '/app/minify/src/Minify.php';
require_once MOOKWAI_PATH . '/app/minify/src/JS.php';


include(MOOKWAI_PATH . '/app/generate-scripts/utils.php');

include(MOOKWAI_PATH . '/app/generate-scripts/exclusive/collapse.php');
include(MOOKWAI_PATH . '/app/generate-scripts/exclusive/flip-card.php');
include(MOOKWAI_PATH . '/app/generate-scripts/exclusive/general.php');
include(MOOKWAI_PATH . '/app/generate-scripts/exclusive/horizontal.php');
include(MOOKWAI_PATH . '/app/generate-scripts/exclusive/lottie.php');
include(MOOKWAI_PATH . '/app/generate-scripts/exclusive/masonry.php');
include(MOOKWAI_PATH . '/app/generate-scripts/exclusive/marquee.php');
include(MOOKWAI_PATH . '/app/generate-scripts/exclusive/menu.php');
include(MOOKWAI_PATH . '/app/generate-scripts/exclusive/navigation.php');
include(MOOKWAI_PATH . '/app/generate-scripts/exclusive/post-cover.php');
include(MOOKWAI_PATH . '/app/generate-scripts/exclusive/search-form.php');
include(MOOKWAI_PATH . '/app/generate-scripts/exclusive/slider.php');
include(MOOKWAI_PATH . '/app/generate-scripts/exclusive/smoother.php');
include(MOOKWAI_PATH . '/app/generate-scripts/exclusive/table-of-content.php');
include(MOOKWAI_PATH . '/app/generate-scripts/exclusive/typewriter.php');

include(MOOKWAI_PATH . '/app/generate-scripts/presets/bouncing-item.php');
include(MOOKWAI_PATH . '/app/generate-scripts/presets/flipping-item.php');
include(MOOKWAI_PATH . '/app/generate-scripts/presets/drag-item.php');
include(MOOKWAI_PATH . '/app/generate-scripts/presets/wiggle-item.php');
include(MOOKWAI_PATH . '/app/generate-scripts/presets/anchor.php');

include(MOOKWAI_PATH . '/app/generate-scripts/events/general.php');

use MatthiasMullie\Minify;

function generateTemplateScript($theDatas, $newCustomJS)
{
  $generate = '';
  // $page_events = isset($newPageSetting['events']) ? $newPageSetting['events'] : [];
  // foreach ($page_events as $event) {
  //   $generate .= generateThePageScript($event);
  // }
  foreach ($theDatas as $theBlock) {
    $blockID = $theBlock['blockID'];
    foreach ($theBlock['elements'] as $key => $value) {
      $blockSelector = ".{$blockID}";
      if ($key !== 'root') {
        $thisSelector = $value['selector'];
        $blockSelector = ".{$blockID} {$thisSelector}";
      }
      foreach ($value['interact'] as $pseudoInteract) {
        $generate .= generateElementScript($pseudoInteract, $blockSelector);
      }
    }
  }
  $generate .= $newCustomJS;
  $js = new Minify\JS($generate);
  $compressedCode = $js->minify();
  if ($compressedCode) $compressedCode .= ';';
  return $compressedCode;
}

function generatePageBlocksScript($newPageSetting, $theDatas, $newCustomJS)
{
  $generate = '';
  $page_events = isset($newPageSetting['events']) ? $newPageSetting['events'] : [];
  foreach ($theDatas as $theBlock) {
    $blockID = $theBlock['blockID'];
    foreach ($theBlock['elements'] as $key => $value) {
      $blockSelector = ".{$blockID}";
      if ($key !== 'root') {
        $thisSelector = $value['selector'];
        $blockSelector = ".{$blockID} {$thisSelector}";
      }
      foreach ($value['interact'] as $pseudoInteract) {
        $generate .= generateElementScript($pseudoInteract, $blockSelector);
      }
    }
  }
  foreach ($page_events as $event) {
    $generate .= generateThePageScript($event);
  }
  $generate .= $newCustomJS;
  $js = new Minify\JS($generate);
  $compressedCode = $js->minify();
  if ($compressedCode) $compressedCode .= ';';
  return $compressedCode;
}

function generateThePageScript($data)
{
  $generate = '';
  switch ($data['groupItem']) {
    case 'load':
      $options = interactEventGeneral($data, ['loading', 'loadComplete'], true);
      if ($options) $generate .= 'new mke.PageLoad(' . $options . ');';
      break;
    case 'scroll':
      $options = interactEventGeneral($data, ['scrollUp', 'scrollDown'], true);
      $generate .= 'new mke.PageScroll(' . $options . ');';
      break;
    case 'mouseHover':
      $options = interactEventGeneral($data, ['mouseenter', 'mouseleave'], true);
      if ($options) $generate .= 'new mke.PageMouseHover(' . $options . ');';
      break;
    case 'mouseMove':
      $options = interactEventGeneral($data, ['mouseX', 'mouseY'], true);
      if ($options) $generate .= 'new mke.PageMouseMove(' . $options . ');';
      break;
  }
  return $generate;
}

function generateElementScript($data, $selector)
{
  $generate = '';
  $exclusiveData = isset($data['exclusive']) ? $data['exclusive'] : [];
  $presetsData = isset($data['presets']) ? $data['presets'] : [];
  $eventsData = isset($data['events']) ? $data['events'] : [];
  foreach ($exclusiveData as $item) {
    if ($item['enable']) {
      switch ($item['groupItem']) {
        case 'smoother':
          $options = interactSmoother($item);
          $generate .= 'new mke.Smoother("' . $selector . '"' . $options . ');';
          break;
        case 'carousel':
          $options = interactSlider($item);
          $generate .= 'new mke.Carousel("' . $selector . '"' . $options . ');';
          break;
        case 'slides':
          $options = interactSlider($item);
          $generate .= 'new mke.Slides("' . $selector . '"' . $options . ');';
          break;
        case 'sliderPagination':
          $options = interactSliderControls($item);
          if ($options) $generate .= 'new mke.SliderControls("' . $selector . '"' . $options . ');';
          break;
        case 'sliderToggles':
          $options = interactSliderControls($item);
          if ($options) $generate .= 'new mke.SliderControls("' . $selector . '"' . $options . ');';
          break;
        case 'tableOfContents':
          $options = interactTableOfContent($item);
          $generate .= 'new mke.TableOfContents("' . $selector . '"' . $options . ');';
          break;
        case 'postCover':
          $is_interactive = null;
          $the_setting = $item['Desktop'];
          $found = array_search('onToggle', array_column($the_setting, 'property'));
          if ($found || $found === 0) {
            $is_interactive = $the_setting[$found]['custom'];
          }
          if ($is_interactive && $is_interactive !== 'none') {
            $options = interactPostCover($item);
            $generate .= 'new mke.PostCover("' . $selector . '"' . $options . ');';
          }
          break;
          // case 'searchForm':
          //   $options = interactSearchForm($item);
          //   $generate .= 'new mke.SearchForm("' . $selector . '"' . $options . ');';
          //   break;
        case 'horizontal':
          $options = interactHorizontal($item, $exclusiveData, $selector);
          $generate .= 'new mke.Horizontal("' . $selector . '"' . $options . ');';
          break;
        case 'navigation':
          $options = interactNavigation($item);
          $generate .= 'new mke.Navigation("' . $selector . '"' . $options . ');';
          break;
        case 'reel':
          $options = interactGeneral($item);
          $generate .= 'new mke.Reel("' . $selector . '"' . $options . ');';
          break;
        case 'flipCard':
          $options = interactFlipCard($item);
          $generate .= 'new mke.FlipCard("' . $selector . '"' . $options . ');';
          break;
        case 'marquee':
          $options = interactMarquee($item);
          $generate .= 'new mke.Marquee("' . $selector . '"' . $options . ');';
          break;
        case 'typewriter':
          $options = interactTypewriter($item);
          $generate .= 'new mke.Typewriter("' . $selector . '"' . $options . ');';
          break;
        case 'masonry':
          $options = interactMasonry($item);
          $generate .= 'new mke.Masonry("' . $selector . '"' . $options . ');';
          break;
        case 'collapse':
          $options = interactCollapse($item);
          $generate .= 'new mke.Collapse("' . $selector . '"' . $options . ');';
          break;
        case 'menu':
          $is_interactive = null;
          $the_setting = $item['Desktop'];
          $found = array_search('isInteractive', array_column($the_setting, 'property'));
          if ($found || $found === 0) {
            $is_interactive = $the_setting[$found]['custom'];
          }
          if ($is_interactive) {
            $options = interactMenu($item);
            $generate .= 'new mke.Menu("' . $selector . '"' . $options . ');';
          }
          break;
        case 'lottie':
          $options = interactLottie($item);
          $generate .= 'new mke.Lottie("' . $selector . '"' . $options . ');';
          break;
      }
    }
  }
  foreach ($presetsData as $item) {
    if ($item['enable']) {
      switch ($item['groupItem']) {
        case 'bouncingItem':
          $options = interactBouncingItem($item);
          $generate .= 'new mke.BouncingItem("' . $selector . '"' . $options . ');';
          break;
        case 'flippingItem':
          $options = interactFlippingItem($item);
          $generate .= 'new mke.FlippingItem("' . $selector . '"' . $options . ');';
          break;
        case 'dragItem':
          $options = interactDragItem($item);
          $generate .= 'new mke.DragItem("' . $selector . '"' . $options . ');';
          break;
        case 'wiggleItem':
          $options = interactWiggleItem($item);
          $generate .= 'new mke.WiggleItem("' . $selector . '"' . $options . ');';
          break;
        case 'anchor':
          $options = interactAnchor($item);
          $generate .= 'new mke.Anchor("' . $selector . '"' . $options . ');';
          break;
      }
    }
  }
  foreach ($eventsData as $data) {
    switch ($data['groupItem']) {
      case 'scroll':
        $options = interactEventGeneral($data, ['viewIn', 'viewOut', 'backViewIn', 'backViewOut'], false);
        if ($options) $generate .= 'new mke.Scroll("' . $selector . '",' . $options . ');';
        break;
      case 'scrollScrub':
        $options = interactEventGeneral($data, null, false);
        if ($options) $generate .= 'new mke.ScrollScrub("' . $selector . '",' . $options . ');';
        break;
      case 'matrixScroll':
        $options = interactEventGeneral($data, ['viewIn', 'viewOut', 'backViewIn', 'backViewOut'], false);
        if ($options) $generate .= 'new mke.MatrixScroll("' . $selector . '",' . $options . ');';
        break;
      case 'mouseHover':
        $options = interactEventGeneral($data, ['mouseenter', 'mouseleave'], false);
        if ($options) $generate .= 'new mke.MouseHover("' . $selector . '",' . $options . ');';
        break;
      case 'mouseMove':
        $options = interactEventGeneral($data, ['mouseX', 'mouseY'], false);
        if ($options) $generate .= 'new mke.MouseMove("' . $selector . '",' . $options . ');';
        break;
      case 'click':
        $options = interactEventGeneral($data, ['click', 'clickAgain'], false);
        if ($options) $generate .= 'new mke.Click("' . $selector . '",' . $options . ');';
        break;
    }
  }
  return $generate;
}
