<?php

/**
 * MooKwai theme options - Global styles content
 *
 * @package MooKwai
 */

defined('ABSPATH') || exit;

// Utils
include(MOOKWAI_PATH . '/app/generate-styles/utils.php');

// Page
include(MOOKWAI_PATH . '/app/generate-styles/page-setting/selection.php');

// Basic
include(MOOKWAI_PATH . '/app/generate-styles/basic/container.php');
include(MOOKWAI_PATH . '/app/generate-styles/basic/flex-item.php');
include(MOOKWAI_PATH . '/app/generate-styles/basic/pseudo.php');
include(MOOKWAI_PATH . '/app/generate-styles/basic/object-item.php');
include(MOOKWAI_PATH . '/app/generate-styles/basic/position.php');
include(MOOKWAI_PATH . '/app/generate-styles/basic/overflow.php');
include(MOOKWAI_PATH . '/app/generate-styles/basic/cursor.php');
include(MOOKWAI_PATH . '/app/generate-styles/basic/counter.php');

// Appearance
include(MOOKWAI_PATH . '/app/generate-styles/appearance/background.php');
include(MOOKWAI_PATH . '/app/generate-styles/appearance/border.php');
include(MOOKWAI_PATH . '/app/generate-styles/appearance/outline.php');
include(MOOKWAI_PATH . '/app/generate-styles/appearance/border-radius.php');
include(MOOKWAI_PATH . '/app/generate-styles/appearance/box-shadow.php');
include(MOOKWAI_PATH . '/app/generate-styles/appearance/clip.php');
include(MOOKWAI_PATH . '/app/generate-styles/appearance/svg.php');
include(MOOKWAI_PATH . '/app/generate-styles/appearance/opacity.php');

// Sizing
include(MOOKWAI_PATH . '/app/generate-styles/sizing/sizing.php');
include(MOOKWAI_PATH . '/app/generate-styles/sizing/spacing.php');

// Typography
include(MOOKWAI_PATH . '/app/generate-styles/typography/text.php');
include(MOOKWAI_PATH . '/app/generate-styles/typography/typography.php');

// Effect
include(MOOKWAI_PATH . '/app/generate-styles/effect/transform.php');
include(MOOKWAI_PATH . '/app/generate-styles/effect/transition.php');
include(MOOKWAI_PATH . '/app/generate-styles/effect/filter.php');

function generateGlobalBlocks($theDatas, $prefix, $globalColor)
{

  $preSelector = '';
  if ($prefix) {
    $preSelector = '.editor-styles-wrapper ';
  }

  $stylesReg = '';
  $stylesHover = '';
  $stylesHoverReg = '';
  $stylesHoverLg = '';
  $stylesHoverP = '';
  $stylesLg = '';
  $stylesP = '';
  $stylesMd = '';
  $stylesMdl = '';
  $stylesMdp = '';
  $stylesSm = '';
  $stylesSml = '';
  $stylesSmp = '';

  foreach ($theDatas as $key => $value) {
    foreach ($value as $item) {
      $blockID = $item['slug'];
      foreach ($item['elements'] as $ele => $eleValue) {
        $blockSelector = "{$preSelector}.{$blockID}";
        if ($ele !== 'root') {
          $thisSelector = $eleValue['selector'];
          if ($eleValue['isRoot']) {
            $blockSelector = "{$preSelector}{$thisSelector}.{$blockID}";
          } else {
            $blockSelector = "{$preSelector}.{$blockID} {$thisSelector}";
          }
        }
        foreach ($eleValue['style'] as $pseudoStyle) {
          $theResult = generateElementStyle($pseudoStyle, $blockSelector, $globalColor);
          $stylesReg .= $theResult['reg'];
          $stylesLg .= $theResult['lg'];
          $stylesP .= $theResult['regP'];
          $stylesMd .= $theResult['md'];
          $stylesMdl .= $theResult['mdl'];
          $stylesMdp .= $theResult['mdp'];
          $stylesSm .= $theResult['sm'];
          $stylesSml .= $theResult['sml'];
          $stylesSmp .= $theResult['smp'];
          $stylesHover .= $theResult['hover'];
          $stylesHoverReg .= $theResult['hoverReg'];
          $stylesHoverLg .= $theResult['hoverLg'];
          $stylesHoverP .= $theResult['hoverP'];
        }
      }
    }
  }

  if ($stylesHover) $stylesHover = "@media (any-hover:hover) and (min-width:901px){{$stylesHover}}";
  if ($stylesHoverReg) $stylesHoverReg = "@media (any-hover:hover) and (min-width:1201px){{$stylesHoverReg}}";
  if ($stylesHoverLg) $stylesHoverLg = "@media (any-hover:hover) and (min-width:1601px){{$stylesHoverLg}}";
  if ($stylesHoverP) $stylesHoverP = "@media (any-hover:hover) and (min-width:901px) and (orientation: portrait){{$stylesHoverP}}";
  if ($stylesLg) $stylesLg = "@media screen and (min-width:1601px){{$stylesLg}}";
  if ($stylesP) $stylesP = "@media screen and (max-width:1200px) and (orientation:portrait){{$stylesP}}";
  if ($stylesMd) $stylesMd = "@media screen and (max-width:1200px) and (orientation:landscape), screen and (max-width:900px) and (orientation:portrait){{$stylesMd}}";
  if ($stylesMdl) $stylesMdl = "@media screen and (max-width:1200px) and (orientation:landscape){{$stylesMdl}}";
  if ($stylesMdp) $stylesMdp = "@media screen and (max-width:900px) and (orientation:portrait){{$stylesMdp}}";
  if ($stylesSm) $stylesSm = "@media screen and (max-width:900px) and (orientation:landscape), screen and (max-width:600px) and (orientation:portrait){{$stylesSm}}";
  if ($stylesSml) $stylesSml = "@media screen and (max-width:900px) and (orientation:landscape){{$stylesSml}}";
  if ($stylesSmp) $stylesSmp = "@media screen and (max-width:600px) and (orientation:portrait){{$stylesSmp}}";

  $pageStyles = $stylesReg . $stylesLg . $stylesP . $stylesMd . $stylesMdl . $stylesMdp . $stylesSm . $stylesSml . $stylesSmp . $stylesHover . $stylesHoverReg . $stylesHoverLg . $stylesHoverP;
  return $pageStyles;
}

function generatePageBlocks($pageSetting, $theDatas, $theCustomCSS, $globalColor)
{
  $stylesReg = '';
  $stylesHover = '';
  $stylesHoverReg = '';
  $stylesHoverLg = '';
  $stylesHoverP = '';
  $stylesLg = '';
  $stylesP = '';
  $stylesMd = '';
  $stylesMdl = '';
  $stylesMdp = '';
  $stylesSm = '';
  $stylesSml = '';
  $stylesSmp = '';
  $stylesCustom = '';

  if (isset($pageSetting['styles']) && $pageSetting['styles']) {
    $pageStyles = $pageSetting['styles'];
    $pageBody = '';
    $pageText = '';
    $pageTextLink = '';
    $pageTextLinkHover = '';
    $pageSelection = '';
    if (count($pageStyles)) {
      foreach ($pageStyles as $item) {
        if ($item['enable']) {
          switch ($item['groupItem']) {
            case 'background':
              $page_body_style = backgroundStyles($item['settings'], null, null, $globalColor, true);
              $pageBody .= $page_body_style['theStyles'];
              break;
            case 'selection':
              $pageSelection .= pageSelection($item['settings'], $globalColor);
              break;
            case 'text':
              $page_text_style = textStyles($item['settings'], null, null, $globalColor);
              $pageText .= $page_text_style['theStyles'];
              break;
            case 'textLink':
              $page_textlink_style = textStyles($item['settings'], null, null, $globalColor);
              $pageTextLink .= $page_textlink_style['theStyles'];
              break;
            case 'textLinkHover':
              $page_textlinkhover_style = textStyles($item['settings'], null, null, $globalColor);
              $pageTextLinkHover .= $page_textlinkhover_style['theStyles'];
              break;
            case 'cursor':
              $cursorSettings = $item['settings'];
              $the_cursor = null;
              $the_custom_cursor = null;
              foreach($cursorSettings as $cursorSetting) {
                switch($cursorSetting['property']) {
                  case 'cursor':
                    $the_cursor = $cursorSetting['custom'];
                    break;
                  case 'customCursor':
                    $the_custom_cursor = $cursorSetting['custom'];
                    break;
                }
              }
              if($the_cursor === 'custom' && $the_custom_cursor) {
                $pageBody .= "cursor:$the_custom_cursor;";
              } else if ($the_cursor) {
                $pageBody .= "cursor:{$the_cursor};";
              }
              break;
          }
        }
      }
    }
    if ($pageBody && $pageText) {
      $stylesReg .= "body{{$pageBody}{$pageText}}";
    } else if ($pageBody) {
      $stylesReg .= "body{{$pageBody}}";
    } else if ($pageText) {
      $stylesReg .= "body{{$pageText}}";
    }
    if ($pageTextLink) {
      $stylesReg .= "a{{$pageTextLink}}";
    }
    if ($pageTextLinkHover) {
      $stylesReg .= "a:hover{{$pageTextLinkHover}}";
    }
    if ($pageSelection) {
      $stylesReg .= "::selection{{$pageSelection}}";
    }
  } else if (is_array($pageSetting)) {
    foreach ($pageSetting as $pageItem) {
      $pageBody = '';
      $pageText = '';
      $pageTextLink = '';
      $pageTextLinkHover = '';
      $pageSelection = '';
      $page_id = isset($pageItem['page_id']) ? $pageItem['page_id'] : '';
      $page_id = explode('//', $page_id);
      $page_slug = isset($page_id[1]) ? 'mk-template-' . $page_id[1] : null;
      $page_setting = isset($pageItem['page_setting']) ? $pageItem['page_setting'] : null;
      $page_styles = isset($page_setting['styles']) ? $page_setting['styles'] : null;
      if (isset($page_styles) && count($page_styles)) {
        foreach ($page_styles as $item) {
          if ($item['enable']) {
            switch ($item['groupItem']) {
              case 'background':
                $pageBody .= backgroundStyles($item['settings'], null, null, $globalColor, false);
                break;
              case 'selection':
                $pageSelection .= pageSelection($item['settings'], $globalColor);
                break;
              case 'text':
                $pageText .= textStyles($item['settings'], null, null, $globalColor);
                break;
              case 'textLink':
                $pageTextLink .= textStyles($item['settings'], null, null, $globalColor);
                break;
              case 'textLinkHover':
                $pageTextLinkHover .= textStyles($item['settings'], null, null, $globalColor);
                break;
            }
          }
        }
      }
      if ($pageBody && $pageText) {
        $stylesReg .= "body.{$page_slug}{{$pageBody}{$pageText}}";
      } else if ($pageBody) {
        $stylesReg .= "body.{$page_slug}{{$pageBody}}";
      } else if ($pageText) {
        $stylesReg .= "body.{$page_slug}{{$pageText}}";
      }
      if ($pageTextLink) {
        $stylesReg .= "body.{$page_slug} a{{$pageTextLink}}";
      }
      if ($pageTextLinkHover) {
        $stylesReg .= "body.{$page_slug} a:hover{{$pageTextLinkHover}}";
      }
      if ($pageSelection) {
        $stylesReg .= "body.{$page_slug} ::selection{{$pageSelection}}";
      }
    }
  }

  foreach ($theDatas as $theBlock) {
    $blockID = $theBlock['blockID'];
    foreach ($theBlock['elements'] as $key => $value) {
      $blockSelector = ".{$blockID}";
      if ($key !== 'root') {
        $thisSelector = $value['selector'];
        if (is_array($thisSelector)) {
          $blockSelector = [];
          foreach ($thisSelector as $item) {
            $blockSelector[] = ".{$blockID} {$item}";
          }
          // $blockSelector = implode(',', $newArray);
        } else {
          $blockSelector = ".{$blockID} {$thisSelector}";
        }
      }
      foreach ($value['style'] as $pseudoStyle) {
        $theResult = generateElementStyle($pseudoStyle, $blockSelector, $globalColor);
        $stylesReg .= $theResult['reg'];
        $stylesLg .= $theResult['lg'];
        $stylesP .= $theResult['regP'];
        $stylesMd .= $theResult['md'];
        $stylesMdl .= $theResult['mdl'];
        $stylesMdp .= $theResult['mdp'];
        $stylesSm .= $theResult['sm'];
        $stylesSml .= $theResult['sml'];
        $stylesSmp .= $theResult['smp'];
        $stylesHover .= $theResult['hover'];
        $stylesHoverReg .= $theResult['hoverReg'];
        $stylesHoverLg .= $theResult['hoverLg'];
        $stylesHoverP .= $theResult['hoverP'];
      }
    }
  }

  if ($theCustomCSS) {
    $theCustomCSS = str_replace(array("\r", "\n"), '', $theCustomCSS);
    $theCustomCSS = preg_replace('!\s+!', ' ', $theCustomCSS);
    $theCustomCSS = str_replace(array(" { ", " {", "{ "), '{', $theCustomCSS);
    $theCustomCSS = str_replace(array(" : ", " :", ": "), ':', $theCustomCSS);
    $theCustomCSS = preg_replace('!/\*.*?\*/!s', '', $theCustomCSS);
    $theCustomCSS = preg_replace('/\/\/.*$/m', '', $theCustomCSS);
    $stylesCustom .= $theCustomCSS;
  }
  if ($stylesHover) $stylesHover = "@media (any-hover:hover) and (min-width:901px){{$stylesHover}}";
  if ($stylesHoverReg) $stylesHoverReg = "@media (any-hover:hover) and (min-width:1201px){{$stylesHoverReg}}";
  if ($stylesHoverLg) $stylesHoverLg = "@media (any-hover:hover) and (min-width:1601px){{$stylesHoverLg}}";
  if ($stylesHoverP) $stylesHoverP = "@media (any-hover:hover) and (min-width:901px) and (orientation: portrait){{$stylesHoverP}}";
  if ($stylesLg) $stylesLg = "@media screen and (min-width:1601px){{$stylesLg}}";
  if ($stylesP) $stylesP = "@media screen and (max-width:1200px) and (orientation:portrait){{$stylesP}}";
  if ($stylesMd) $stylesMd = "@media screen and (max-width:1200px) and (orientation:landscape), screen and (max-width:900px) and (orientation:portrait){{$stylesMd}}";
  if ($stylesMdl) $stylesMdl = "@media screen and (max-width:1200px) and (orientation:landscape){{$stylesMdl}}";
  if ($stylesMdp) $stylesMdp = "@media screen and (max-width:900px) and (orientation:portrait){{$stylesMdp}}";
  if ($stylesSm) $stylesSm = "@media screen and (max-width:900px) and (orientation:landscape), screen and (max-width:600px) and (orientation:portrait){{$stylesSm}}";
  if ($stylesSml) $stylesSml = "@media screen and (max-width:900px) and (orientation:landscape){{$stylesSml}}";
  if ($stylesSmp) $stylesSmp = "@media screen and (max-width:600px) and (orientation:portrait){{$stylesSmp}}";

  $pageStyles = $stylesReg . $stylesLg . $stylesP . $stylesMd . $stylesMdl . $stylesMdp . $stylesSm . $stylesSml . $stylesSmp . $stylesHover . $stylesHoverReg . $stylesHoverLg . $stylesHoverP . $stylesCustom;
  return $pageStyles;
}

function generateElementStyle($data, $selector, $globalColor)
{
  $pseudoName = '';
  $basic = $data['basic'];
  $appearance = $data['appearance'];
  $typography = $data['typography'];
  $sizing = $data['sizing'];
  $effect = $data['effect'];
  if ($data['pseudo']) {
    $pseudoName = $data['pseudo'];
  }

  $propertyReg = '';
  $propertyLg = '';
  $propertyP = '';
  $propertyMd = '';
  $propertyMdl = '';
  $propertyMdp = '';
  $propertySm = '';
  $propertySml = '';
  $propertySmp = '';
  $propertyHover = '';
  $propertyHoverReg = '';
  $propertyHoverLg = '';
  $propertyHoverP = '';
  $modeReg = [];
  $modeMd = [];
  $modeSm = [];
  $modeHover = [];

  foreach ($basic as $item) {
    if ($item['enable']) {
      switch ($item['groupItem']) {
        case 'container':
          $propertyReg .= containerStyles($item['Desktop'], null, null);
          $propertyMd .= containerStyles($item['Tablet'], $item['Desktop'], null);
          $propertySm .= containerStyles($item['Mobile'], $item['Tablet'], $item['Desktop']);
          $propertyHover .= containerStyles($item['Hover'], $item['Desktop'], null);
          break;
        case 'flexItem':
          $propertyReg .= flexItemStyles($item['Desktop']);
          $propertyMd .= flexItemStyles($item['Tablet']);
          $propertySm .= flexItemStyles($item['Mobile']);
          $propertyHover .= flexItemStyles($item['Hover']);
          break;
        case 'pseudo':
          $propertyReg .= pseudoStyles($item['Desktop'], true);
          $propertyMd .= pseudoStyles($item['Tablet'], false);
          $propertySm .= pseudoStyles($item['Mobile'], false);
          $propertyHover .= pseudoStyles($item['Hover'], false);
          break;
        case 'objectItem':
          $propertyReg .= objectItemStyles($item['Desktop']);
          $propertyMd .= objectItemStyles($item['Tablet']);
          $propertySm .= objectItemStyles($item['Mobile']);
          $propertyHover .= objectItemStyles($item['Hover']);
          break;
        case 'position':
          $propertyReg .= positionStyles($item['Desktop'], null, null);
          $propertyMd .= positionStyles($item['Tablet'], $item['Desktop'], null);
          $propertySm .= positionStyles($item['Mobile'], $item['Tablet'], $item['Desktop']);
          $propertyHover .= positionStyles($item['Hover'], $item['Desktop'], null);
          break;
        case 'overflow':
          $propertyReg .= overflowStyles($item['Desktop']);
          $propertyMd .= overflowStyles($item['Tablet']);
          $propertySm .= overflowStyles($item['Mobile']);
          $propertyHover .= overflowStyles($item['Hover']);
          break;
        case 'cursor':
          $propertyReg .= cursorStyles($item['Desktop']);
          $propertyMd .= cursorStyles($item['Tablet']);
          $propertySm .= cursorStyles($item['Mobile']);
          $propertyHover .= cursorStyles($item['Hover']);
          break;
        case 'counter':
          $propertyReg .= counterStyles($item['Desktop']);
          $propertyMd .= counterStyles($item['Tablet']);
          $propertySm .= counterStyles($item['Mobile']);
          $propertyHover .= counterStyles($item['Hover']);
          break;
      }
    }
  }

  foreach ($sizing as $item) {
    if ($item['enable']) {
      switch ($item['groupItem']) {
        case 'sizing':
          $propertyReg .= sizeStyles($item['Desktop']);
          $propertyMd .= sizeStyles($item['Tablet']);
          $propertySm .= sizeStyles($item['Mobile']);
          $propertyHover .= sizeStyles($item['Hover']);
          break;
        case 'spacing':
          $propertyReg .= spacingStyles($item['Desktop']);
          $propertyMd .= spacingStyles($item['Tablet']);
          $propertySm .= spacingStyles($item['Mobile']);
          $propertyHover .= spacingStyles($item['Hover']);
          break;
      }
    }
  }

  foreach ($appearance as $item) {
    if ($item['enable']) {
      switch ($item['groupItem']) {
        case 'background':
          $backgroundReg = backgroundStyles($item['Desktop'], null, null, $globalColor, false);
          $backgroundTablet = backgroundStyles($item['Tablet'], $item['Desktop'], null, $globalColor, false);
          $backgroundMobile = backgroundStyles($item['Mobile'], $item['Tablet'], $item['Desktop'], $globalColor, false);
          $backgroundHover = backgroundStyles($item['Hover'], $item['Desktop'], null, $globalColor, false);
          $propertyReg .= $backgroundReg['theStyles'];
          $propertyMd .= $backgroundTablet['theStyles'];
          $propertySm .= $backgroundMobile['theStyles'];
          $propertyHover .= $backgroundHover['theStyles'];
          $modeReg = array_merge($modeReg, $backgroundReg['modeStyles']);
          $modeMd = array_merge($modeMd, $backgroundTablet['modeStyles']);
          $modeSm = array_merge($modeSm, $backgroundMobile['modeStyles']);
          $modeHover = array_merge($modeHover, $backgroundHover['modeStyles']);
          break;
        case 'border':
          $borderReg = borderStyles($item['Desktop'], null, null, $globalColor);
          $borderTablet = borderStyles($item['Tablet'], $item['Desktop'], null, $globalColor);
          $borderMobile = borderStyles($item['Mobile'], $item['Tablet'], $item['Desktop'], $globalColor);
          $borderHover = borderStyles($item['Hover'], $item['Desktop'], null, $globalColor);
          $propertyReg .= $borderReg['theStyles'];
          $propertyMd .= $borderTablet['theStyles'];
          $propertySm .= $borderMobile['theStyles'];
          $propertyHover .= $borderHover['theStyles'];
          $modeReg = array_merge($modeReg, $borderReg['modeStyles']);
          $modeMd = array_merge($modeMd, $borderTablet['modeStyles']);
          $modeSm = array_merge($modeSm, $borderMobile['modeStyles']);
          $modeHover = array_merge($modeHover, $borderHover['modeStyles']);
          break;
        case 'outline':
          $outlineReg = outlineStyles($item['Desktop'], null, null, $globalColor);
          $outlineTablet = outlineStyles($item['Tablet'], $item['Desktop'], null, $globalColor);
          $outlineMobile = outlineStyles($item['Mobile'], $item['Tablet'], $item['Desktop'], $globalColor);
          $outlineHover = outlineStyles($item['Hover'], $item['Desktop'], null, $globalColor);
          $propertyReg .= $outlineReg['theStyles'];
          $propertyMd .= $outlineTablet['theStyles'];
          $propertySm .= $outlineMobile['theStyles'];
          $propertyHover .= $outlineHover['theStyles'];
          $modeReg = array_merge($modeReg, $outlineReg['modeStyles']);
          $modeMd = array_merge($modeMd, $outlineTablet['modeStyles']);
          $modeSm = array_merge($modeSm, $outlineMobile['modeStyles']);
          $modeHover = array_merge($modeHover, $outlineHover['modeStyles']);
          break;
        case 'borderRadius':
          $propertyReg .= borderRadiusStyles($item['Desktop']);
          $propertyMd .= borderRadiusStyles($item['Tablet']);
          $propertySm .= borderRadiusStyles($item['Mobile']);
          $propertyHover .= borderRadiusStyles($item['Hover']);
          break;
        case 'boxShadow':
          $boxShadowReg = boxShadowStyles($item['Desktop'], null, null, $globalColor);
          $boxShadowTablet = boxShadowStyles($item['Tablet'], $item['Desktop'], null, $globalColor);
          $boxShadowMobile = boxShadowStyles($item['Mobile'], $item['Tablet'], $item['Desktop'], $globalColor);
          $boxShadowHover = boxShadowStyles($item['Hover'], $item['Desktop'], null, $globalColor);
          $propertyReg .= isset($boxShadowReg['theStyles']) ? $boxShadowReg['theStyles'] : '';
          $propertyMd .= isset($boxShadowTablet['theStyles']) ? $boxShadowTablet['theStyles'] : '';
          $propertySm .= isset($boxShadowMobile['theStyles']) ? $boxShadowMobile['theStyles'] : '';
          $propertyHover .= isset($boxShadowHover['theStyles']) ? $boxShadowHover['theStyles'] : '';
          $modeReg = isset($boxShadowReg['modeStyles']) ? array_merge($modeReg, $boxShadowReg['modeStyles']) : null;
          $modeMd = isset($boxShadowTablet['modeStyles']) ? array_merge($modeMd, $boxShadowTablet['modeStyles']) : null;
          $modeSm = isset($boxShadowMobile['modeStyles']) ? array_merge($modeSm, $boxShadowMobile['modeStyles']) : null;
          $modeHover = isset($boxShadowHover['modeStyles']) ? array_merge($modeHover, $boxShadowHover['modeStyles']) : null;
          break;
        case 'clip':
          $propertyReg .= clipStyles($item['Desktop']);
          $propertyMd .= clipStyles($item['Tablet']);
          $propertySm .= clipStyles($item['Mobile']);
          $propertyHover .= clipStyles($item['Hover']);
          break;
        case 'svg':
          $svgReg = svgStyles($item['Desktop'], null, null, $globalColor);
          $svgTablet = svgStyles($item['Tablet'], $item['Desktop'], null, $globalColor);
          $svgMobile = svgStyles($item['Mobile'], $item['Tablet'], $item['Desktop'], $globalColor);
          $svgHover = svgStyles($item['Hover'], $item['Desktop'], null, $globalColor);
          $propertyReg .= $svgReg['theStyles'];
          $propertyMd .= $svgTablet['theStyles'];
          $propertySm .= $svgMobile['theStyles'];
          $propertyHover .= $svgHover['theStyles'];
          $modeReg = array_merge($modeReg, $svgReg['modeStyles']);
          $modeMd = array_merge($modeMd, $svgTablet['modeStyles']);
          $modeSm = array_merge($modeSm, $svgMobile['modeStyles']);
          $modeHover = array_merge($modeHover, $svgHover['modeStyles']);
          break;
        case 'opacity':
          $propertyReg .= opacityStyles($item['Desktop']);
          $propertyMd .= opacityStyles($item['Tablet']);
          $propertySm .= opacityStyles($item['Mobile']);
          $propertyHover .= opacityStyles($item['Hover']);
          break;
      }
    }
  }

  foreach ($typography as $item) {
    if ($item['enable']) {
      switch ($item['groupItem']) {
        case 'text':
          $textReg = textStyles($item['Desktop'], null, null, $globalColor);
          $textTablet = textStyles($item['Tablet'], $item['Desktop'], null, $globalColor);
          $textMobile = textStyles($item['Mobile'], $item['Tablet'], $item['Desktop'], $globalColor);
          $textHover = textStyles($item['Hover'], $item['Desktop'], null, $globalColor);
          $propertyReg .= $textReg['theStyles'];
          $propertyMd .= $textTablet['theStyles'];
          $propertySm .= $textMobile['theStyles'];
          $propertyHover .= $textHover['theStyles'];
          $modeReg = array_merge($modeReg, $textReg['modeStyles']);
          $modeMd = array_merge($modeMd, $textTablet['modeStyles']);
          $modeSm = array_merge($modeSm, $textMobile['modeStyles']);
          $modeHover = array_merge($modeHover, $textHover['modeStyles']);
          break;
        case 'typography':
          $propertyReg .= typographyStyles($item['Desktop']);
          $propertyMd .= typographyStyles($item['Tablet']);
          $propertySm .= typographyStyles($item['Mobile']);
          $propertyHover .= typographyStyles($item['Hover']);
          break;
      }
    }
  }

  foreach ($effect as $item) {
    if ($item['enable']) {
      switch ($item['groupItem']) {
        case 'transform':
          $propertyReg .= transformStyles($item['Desktop'], $item['Tablet'], $item['Mobile'], $item['Hover']);
          $propertyMd .= transformStyles($item['Tablet'], $item['Desktop'], $item['Mobile'], $item['Hover']);
          $propertySm .= transformStyles($item['Mobile'], $item['Desktop'], $item['Tablet'], $item['Hover']);
          $propertyHover .= transformStyles($item['Hover'], $item['Mobile'], $item['Desktop'], $item['Tablet']);
          break;
        case 'transition':
          $propertyReg .= transitionStyles($item['Desktop'], null, null);
          $propertyMd .= transitionStyles($item['Tablet'], $item['Desktop'], null);
          $propertySm .= transitionStyles($item['Mobile'], $item['Tablet'], $item['Desktop']);
          $propertyHover .= transitionStyles($item['Hover'], $item['Desktop'], null);
          break;
        case 'filter':
          $propertyReg .= filterStyles($item['Desktop'], ['filter']);
          $propertyMd .= filterStyles($item['Tablet'], ['filter']);
          $propertySm .= filterStyles($item['Mobile'], ['filter']);
          $propertyHover .= filterStyles($item['Hover'], ['filter']);
          break;
        case 'backdropFilter':
          $propertyReg .= filterStyles($item['Desktop'], ['-webkit-backdrop-filter', 'backdrop-filter']);
          $propertyMd .= filterStyles($item['Tablet'], ['-webkit-backdrop-filter', 'backdrop-filter']);
          $propertySm .= filterStyles($item['Mobile'], ['-webkit-backdrop-filter', 'backdrop-filter']);
          $propertyHover .= filterStyles($item['Hover'], ['-webkit-backdrop-filter', 'backdrop-filter']);
          break;
      }
    }
  }
  $stylesReg = '';
  $stylesLg = '';
  $stylesP = '';
  $stylesMd = '';
  $stylesMdl = '';
  $stylesMdp = '';
  $stylesSm = '';
  $stylesSml = '';
  $stylesSmp = '';
  $stylesHover = '';
  $stylesHoverReg = '';
  $stylesHoverLg = '';
  $stylesHoverP = '';

  $generalSelector = generateSelector($selector, $pseudoName, '');
  $regSelector = $generalSelector['regSelector'];
  $regHoverSelector = $generalSelector['regHoverSelector'];
  if ($propertyReg) {
    $stylesReg .= "{$regSelector}{{$propertyReg}}";
  }
  if ($propertyHover) {
    $stylesHover .= "{$regHoverSelector}{{$propertyHover}}";
  }
  if ($propertyHoverReg) {
    $stylesHoverReg .= "{$regHoverSelector}{{$propertyHoverReg}}";
  }
  if ($propertyHoverLg) {
    $stylesHoverLg .= "{$regHoverSelector}{{$propertyHoverLg}}";
  }
  if ($propertyHoverP) {
    $stylesHoverP .= "{$regHoverSelector}{{$propertyHoverP}}";
  }
  if ($propertyLg) {
    $stylesLg .= "{$regSelector}{{$propertyLg}}";
  }
  if ($propertyP) {
    $stylesP .= "{$regSelector}{{$propertyP}}";
  }
  if ($propertyMd) {
    $stylesMd .= "{$regSelector}{{$propertyMd}}";
  }
  if ($propertyMdl) {
    $stylesMdl .= "{$regSelector}{{$propertyMdl}}";
  }
  if ($propertyMdp) {
    $stylesMdp .= "{$regSelector}{{$propertyMdp}}";
  }
  if ($propertySm) {
    $stylesSm .= "{$regSelector}{{$propertySm}}";
  }
  if ($propertySml) {
    $stylesSml .= "{$regSelector}{{$propertySml}}";
  }
  if ($propertySmp) {
    $stylesSmp .= "{$regSelector}{{$propertySmp}}";
  }

  if (count($modeReg)) {
    $newArray = mapModes($modeReg);
    foreach ($newArray as $item) {
      $mode_selector = '[data-mode="' . $item['slug'] . '"] ';
      $theSelector = generateSelector($selector, $pseudoName, $mode_selector);
      $theSelector = $theSelector['regSelector'];
      $stylesReg .= "{$theSelector}{" . $item['style'] . "}";
    }
  }
  if (isset($modeMd) && count($modeMd)) {
    $newArray = mapModes($modeMd);
    foreach ($newArray as $item) {
      $mode_selector = '[data-mode="' . $item['slug'] . '"] ';
      $theSelector = generateSelector($selector, $pseudoName, $mode_selector);
      $theSelector = $theSelector['regSelector'];
      $stylesMd .= "{$theSelector}{" . $item['style'] . "}";
    }
  }
  if (count($modeSm)) {
    $newArray = mapModes($modeSm);
    foreach ($newArray as $item) {
      $mode_selector = '[data-mode="' . $item['slug'] . '"] ';
      $theSelector = generateSelector($selector, $pseudoName, $mode_selector);
      $theSelector = $theSelector['regSelector'];
      $stylesSm .= "{$theSelector}{" . $item['style'] . "}";
    }
  }
  if (count($modeHover)) {
    $newArray = mapModes($modeHover);
    foreach ($newArray as $item) {
      $mode_selector = '[data-mode="' . $item['slug'] . '"] ';
      $theSelector = generateSelector($selector, $pseudoName, $mode_selector);
      $theSelector = $theSelector['regSelector'];
      $stylesHover .= "{$theSelector}{" . $item['style'] . "}";
    }
  }

  return array(
    'reg' => $stylesReg,
    'lg' => $stylesLg,
    'regP' => $stylesP,
    'md' => $stylesMd,
    'mdl' => $stylesMdl,
    'mdp' => $stylesMdp,
    'sm' => $stylesSm,
    'sml' => $stylesSml,
    'smp' => $stylesSmp,
    'hover' => $stylesHover,
    'hoverReg' => $stylesHoverReg,
    'hoverLg' => $stylesHoverLg,
    'hoverP' => $stylesHoverP,
  );
}

function generateSelector($selector, $pseudoName, $theMode)
{
  $regSelector = '';
  $regHoverSelector = '';
  if (is_array($selector)) {
    $regSelector = [];
    $regHoverSelector = [];
    foreach ($selector as $item) {
      $pseudo_array = explode(',', $pseudoName);
      foreach ($pseudo_array as $pseudo_item) {
        $regSelector[] = "{$item}{$pseudo_item}";
        $regHoverSelector[] = "{$item}{$pseudo_item}:hover";
      }
    }
    $regSelector = implode(',', $regSelector);
    $regHoverSelector = implode(',', $regHoverSelector);
  } else {
    $regSelector = [];
    $regHoverSelector = [];
    $pseudo_array = explode(',', $pseudoName);
    foreach ($pseudo_array as $pseudo_item) {
      $regSelector[] = "{$theMode}{$selector}{$pseudo_item}";
      $regHoverSelector[] = "{$theMode}{$selector}{$pseudo_item}:hover";
    }
    $regSelector = implode(',', $regSelector);
    $regHoverSelector = implode(',', $regHoverSelector);
  }
  return [
    'regSelector' => $regSelector,
    'regHoverSelector' => $regHoverSelector,
  ];
}

function mapModes($datas)
{
  $result = [];
  foreach ($datas as $item) {
    $found = array_search($item['slug'], array_column($result, 'slug'));
    if (is_numeric($found)) {
      $result[$found]['style'] = $result[$found]['style'] . $item['style'];
    } else {
      $result[] = $item;
    };
  }
  return $result;
}
