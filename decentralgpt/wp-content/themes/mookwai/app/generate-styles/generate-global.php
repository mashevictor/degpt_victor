<?php

include(MOOKWAI_PATH . '/app/generate-styles/global-styles/utils.php');
include(MOOKWAI_PATH . '/app/generate-styles/global-styles/color.php');
include(MOOKWAI_PATH . '/app/generate-styles/global-styles/font-common.php');
include(MOOKWAI_PATH . '/app/generate-styles/global-styles/font-major.php');
include(MOOKWAI_PATH . '/app/generate-styles/global-styles/spacing.php');
include(MOOKWAI_PATH . '/app/generate-styles/global-styles/layout.php');

include(MOOKWAI_PATH . '/app/generate-styles/global-styles/body.php');
include(MOOKWAI_PATH . '/app/generate-styles/global-styles/selection.php');
include(MOOKWAI_PATH . '/app/generate-styles/global-styles/paragraph.php');
include(MOOKWAI_PATH . '/app/generate-styles/global-styles/heading.php');
include(MOOKWAI_PATH . '/app/generate-styles/global-styles/link.php');
include(MOOKWAI_PATH . '/app/generate-styles/global-styles/reset-elements.php');
include(MOOKWAI_PATH . '/app/generate-styles/global-styles/basic-elements.php');
include(MOOKWAI_PATH . '/app/generate-styles/global-styles/font-family.php');
include(MOOKWAI_PATH . '/app/generate-styles/global-styles/google-font.php');

function generateGlobalStyles($globalStyles, $globalBlock, $reuseableBlock, $prefix)
{
  $preSelector = '';
  if ($prefix) {
    $preSelector = '.editor-styles-wrapper ';
  }
  $stylesImport = '';
  $stylesReg = '';
  $stylesLg = '';
  $stylesXl = '';
  $stylesXxl = '';
  $stylesMini = '';
  $stylesRegP = '';
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
  $stylesDesktop = '';
  $stylesTablet = '';
  $stylesMobile = '';

  $rootStylesReg = '';
  $rootStylesLg = '';
  $rootStylesMini = '';
  $rootStylesRegP = '';
  $rootStylesMd = '';
  $rootStylesSm = '';
  foreach ($globalStyles as $key => $value) {
    switch ($key) {
      case 'colorPalette':
        $rootStylesReg .= GlobalStylesColor($preSelector, $value);
        break;
      case 'fontCommon':
        $fontCommonStyles = generateFontCommon($preSelector, $value);
        $rootStylesReg .= $fontCommonStyles['reg'];
        $rootStylesLg .= $fontCommonStyles['lg'];
        $rootStylesMd .= $fontCommonStyles['md'];
        $rootStylesSm .= $fontCommonStyles['sm'];
        break;
      case 'fontMajor':
        $fontMajorStyles = generateFontMajor($preSelector, $value);
        $rootStylesReg .= $fontMajorStyles['reg'];
        $rootStylesRegP .= $fontMajorStyles['regP'];
        break;
      case 'spacing':
        $spacingStyles = GlobalStylesSpacing($preSelector, $value);
        $rootStylesReg .= $spacingStyles['reg'];
        $rootStylesLg .= $spacingStyles['lg'];
        $rootStylesMini .= $spacingStyles['mini'];
        break;
    }
  }

  // root
  $rootStylesReg = ":root{font-size:0.694vw;{$rootStylesReg}}";
  if ($rootStylesLg) $rootStylesLg = ":root{{$rootStylesLg}}";
  if ($rootStylesMini) $rootStylesMini = ":root{{$rootStylesMini}}";
  if ($rootStylesRegP) $rootStylesRegP = ":root{{$rootStylesRegP}}";
  if ($rootStylesMd) $rootStylesMd = ":root{{$rootStylesMd}}";
  if ($rootStylesSm) $rootStylesSm = ":root{{$rootStylesSm}}";

  $stylesReg .= $rootStylesReg;
  $stylesLg .= $rootStylesLg;
  $stylesMini .= $rootStylesMini;
  $stylesRegP .= $rootStylesRegP;
  $stylesMd .= $rootStylesMd;
  $stylesSm .= $rootStylesSm;

  $colorSchemeData = $globalStyles['colorScheme'];

  // body
  $baseData = $globalStyles['base'];
  $layoutData = $globalStyles['layout'];
  $bodyStyles = generateBody($baseData, $preSelector, $colorSchemeData);
  $stylesReg .= $bodyStyles['reg'];
  $stylesLg .= $bodyStyles['lg'];
  $stylesMd .= $bodyStyles['md'];

  // Selection
  $stylesReg .= generateSelection($colorSchemeData, $preSelector);

  // Font family
  $fontFamilyData = $globalStyles['fontFamily'];
  $stylesReg .= GlobalStylesFontFamily($preSelector, $fontFamilyData);
  $stylesImport .= GlobalStylesGoogleFont($preSelector, $fontFamilyData);

  // reset elements
  $stylesReg .= generateResetElements($preSelector);

  // Paragraph
  $stylesReg .= generateParagraph($baseData, $preSelector);

  // Heading 
  $stylesReg .= generateHeading($baseData, $preSelector);

  // Link
  $linkStyles = generateLink($baseData, $preSelector, $colorSchemeData);
  $stylesReg .= $linkStyles['reg'];
  $stylesHover .= $linkStyles['hover'];

  // Layout
  $layoutStyles = GlobalStylesLayout($layoutData, $preSelector);
  $stylesReg .= $layoutStyles['reg'];
  $stylesLg .= $layoutStyles['lg'];
  $stylesXl .= $layoutStyles['xl'];
  $stylesXxl .= $layoutStyles['xxl'];
  $stylesMini .= $layoutStyles['mini'];
  $stylesRegP .= $layoutStyles['regP'];
  $stylesMd .= $layoutStyles['md'];
  $stylesMdp .= $layoutStyles['mdp'];
  $stylesSm .= $layoutStyles['sm'];
  $stylesSml .= $layoutStyles['sml'];
  $stylesSmp .= $layoutStyles['smp'];

  // basic elements
  $basicStyles = generateBasicElements($preSelector, $colorSchemeData);
  $stylesReg .= $basicStyles['reg'];
  $stylesMd .= $basicStyles['md'];
  $stylesSm .= $basicStyles['sm'];
  $stylesDesktop .= $basicStyles['desktop'];
  $stylesTablet .= $basicStyles['tablet'];
  $stylesMobile .= $basicStyles['mobile'];

  $getGlobalColor = $globalStyles['colorPalette'];
  $globalColor = [];
  foreach ($getGlobalColor as $key => $value) {
    foreach ($value as $item) {
      array_push($globalColor, $item);
    }
  }

  // $stylesReg .= ".wp-site-blocks{overflow-x:hidden;}";
  $stylesReg .= ".mk-container .el-row .mk-column.mk-offset-none{grid-column-start:auto!important;grid-row-start:auto!important;}";
  $stylesLg .= ".mk-container .el-row .mk-column.mk-offset-none-lg{grid-column-start:auto!important;grid-row-start:auto!important;}";
  $stylesXl .= ".mk-container .el-row .mk-column.mk-offset-none-xl{grid-column-start:auto!important;grid-row-start:auto!important;}";
  $stylesXxl .= ".mk-container .el-row .mk-column.mk-offset-none-xxl{grid-column-start:auto!important;grid-row-start:auto!important;}";
  $stylesRegP .= ".mk-container .el-row .mk-column.mk-offset-none-p{grid-column-start:auto!important;grid-row-start:auto!important;}";
  $stylesMd .= ".mk-container .el-row .mk-column.mk-offset-none-md{grid-column-start:auto!important;grid-row-start:auto!important;}";
  $stylesMdp .= ".mk-container .el-row .mk-column.mk-offset-none-mdp{grid-column-start:auto!important;grid-row-start:auto!important;}";
  $stylesSml .= ".mk-container .el-row .mk-column.mk-offset-none-sml{grid-column-start:auto!important;grid-row-start:auto!important;}";
  $stylesSmp .= ".mk-container .el-row .mk-column.mk-offset-none-smp{grid-column-start:auto!important;grid-row-start:auto!important;}";

  if (!$preSelector) {
    foreach ($globalBlock as $key => $value) {
      foreach ($value as $item) {
        $blockID = $item['slug'];
        foreach ($item['elements'] as $ele => $eleValue) {
          $blockSelector = "{$preSelector}.{$blockID}";
          if ($ele !== 'root') {
            $thisSelector = $eleValue['selector'];
            if (is_array($thisSelector)) {
              $blockSelector = [];
              if ($eleValue['isRoot']) {
                foreach ($thisSelector as $item) {
                  $blockSelector[] = "{$preSelector}{$item}.{$blockID}";
                }
              } else {
                foreach ($thisSelector as $item) {
                  $blockSelector[] = "{$preSelector}.{$blockID} {$item}";
                }
              }
            } else {
              $blockSelector = '';
              if ($eleValue['isRoot']) {
                $blockSelector = "{$preSelector}{$thisSelector}.{$blockID}";
              } else {
                $blockSelector = "{$preSelector}.{$blockID} {$thisSelector}";
              }
            }
          }
          foreach ($eleValue['style'] as $pseudoStyle) {
            $theResult = generateElementStyle($pseudoStyle, $blockSelector, $globalColor);
            $stylesReg .= $theResult['reg'];
            $stylesLg .= $theResult['lg'];
            $stylesRegP .= $theResult['regP'];
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
    if(isset($reuseableBlock) && count($reuseableBlock)) {
      foreach($reuseableBlock as $item) {
        foreach ($item['elements'] as $ele => $eleValue) {
          $blockID = $item['blockID'];
          $blockSelector = "{$preSelector}.{$blockID}";
          if ($ele !== 'root') {
            $thisSelector = $eleValue['selector'];
            if (is_array($thisSelector)) {
              $blockSelector = [];
              if ($eleValue['isRoot']) {
                foreach ($thisSelector as $item) {
                  $blockSelector[] = "{$preSelector}{$item}.{$blockID}";
                }
              } else {
                foreach ($thisSelector as $item) {
                  $blockSelector[] = "{$preSelector}.{$blockID} {$item}";
                }
              }
            } else {
              $blockSelector = '';
              if ($eleValue['isRoot']) {
                $blockSelector = "{$preSelector}{$thisSelector}.{$blockID}";
              } else {
                $blockSelector = "{$preSelector}.{$blockID} {$thisSelector}";
              }
            }
          }
          foreach ($eleValue['style'] as $pseudoStyle) {
            $theResult = generateElementStyle($pseudoStyle, $blockSelector, $globalColor);
            $stylesReg .= $theResult['reg'];
            $stylesLg .= $theResult['lg'];
            $stylesRegP .= $theResult['regP'];
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
  }


  if ($stylesLg) {
    $stylesLg = "@media screen and (min-width:1601px){{$stylesLg}}";
  }

  if ($stylesXl) {
    $stylesXl = "@media screen and (min-width:1801px){{$stylesXl}}";
  }

  if ($stylesXxl) {
    $stylesXxl = "@media screen and (min-width:2201px){{$stylesXxl}}";
  }

  if ($stylesRegP) {
    $stylesRegP = "@media screen and (max-width: 1200px) and (orientation: portrait){{$stylesRegP}}";
  }

  if ($stylesDesktop) {
    $stylesDesktop = "@media screen and (min-width:1201px){{$stylesDesktop}}";
  }

  if ($stylesMini) {
    $stylesMini = "@media screen and (max-width:900px){{$stylesMini}}";
  }

  if ($stylesMd) {
    $stylesMd = "@media screen and (max-width: 1200px) and (orientation: landscape), screen and (max-width: 900px) and (orientation: portrait){{$stylesMd}}";
  }

  if ($stylesMdl) {
    $stylesMdl = "@media screen and (max-width: 1200px) and (orientation: landscape){{$stylesMdl}}";
  }

  if ($stylesMdp) {
    $stylesMdp = "@media screen and (max-width: 900px) and (orientation: portrait){{$stylesMdp}}";
  }

  if ($stylesTablet) {
    $stylesTablet = "@media screen and (max-width: 1200px) and (min-width: 901px) and (orientation: landscape), screen and (max-width: 900px) and (min-width: 601px) and (orientation: portrait){{$stylesTablet}}";
  }

  if ($stylesSm || $stylesMobile) {
    $stylesSm = "@media screen and (max-width:900px) and (orientation:landscape), screen and (max-width:600px) and (orientation:portrait){{$stylesSm}{$stylesMobile}}";
  }

  if ($stylesSml) {
    $stylesSml = "@media screen and (max-width: 900px) and (orientation: landscape){{$stylesSml}}";
  }

  if ($stylesSmp) {
    $stylesSmp = "@media screen and (max-width:600px) and (orientation:portrait){{$stylesSmp}}";
  }

  if ($stylesHover) {
    $stylesHover = "@media (any-hover:hover) and (min-width:901px){{$stylesHover}}";
  }

  if ($stylesHoverReg) {
    $stylesHoverReg = "@media (any-hover:hover) and (min-width:1201px){{$stylesHoverReg}}";
  }

  if ($stylesHoverLg) {
    $stylesHoverLg = "@media (any-hover:hover) and (min-width:1601px){{$stylesHoverLg}}";
  }

  if ($stylesHoverP) {
    $stylesHoverP = "@media (any-hover:hover) and (min-width:901px) and (orientation: portrait){{$stylesHoverP}}";
  }

  $theStyles = $stylesImport . $stylesReg . $stylesLg . $stylesXl . $stylesXxl . $stylesRegP . $stylesDesktop . $stylesMini . $stylesMd . $stylesMdl . $stylesMdp . $stylesTablet . $stylesSm . $stylesSml . $stylesSmp . $stylesHover . $stylesHoverReg . $stylesHoverLg . $stylesHoverP;
  return $theStyles;
}
