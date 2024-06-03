<?php

function textStyles($setting, $preSetting, $prePreSetting, $globalColor)
{
  $theStyles = '';
  $modeStyles = [];
  $foundFamily = null;
  $foundWeight = null;
  $foundStyle = null;
  $foundSpec = null;
  $foundColor = null;
  // $foundIndent = null;
  // $foundAlign = null;
  $foundTransform = null;

  $foundStrokeColor = null;
  $foundStrokeWidth = null;
  $foundShadowColor = null;
  $foundShadowX = null;
  $foundShadowY = null;
  $foundShadowBlur = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'fontFamily':
        $foundFamily = $item;
        break;
      case 'fontWeight':
        $foundWeight = $item;
        break;
      case 'fontStyle':
        $foundStyle = $item;
        break;
      case 'fontSpec':
        $foundSpec = $item;
        break;
      case 'color':
        $foundColor = $item;
        break;
        // case 'textIndent':
        //   $foundIndent = $item;
        //   break;
        // case 'textAlign':
        //   $foundAlign = $item;
        //   break;
      case 'textTransform':
        $foundTransform = $item;
        break;
      case 'strokeColor':
        $foundStrokeColor = $item;
        break;
      case 'strokeWidth':
        $foundStrokeWidth = $item;
        break;
      case 'shadowColor':
        $foundShadowColor = $item;
        break;
      case 'shadowX':
        $foundShadowX = $item;
        break;
      case 'shadowY':
        $foundShadowY = $item;
        break;
      case 'shadowBlur':
        $foundShadowBlur = $item;
        break;
    }
  }
  $theColor = getInheritColor('color', $setting, $preSetting, $prePreSetting, $globalColor);
  $theStyles .= generateFamilys($foundFamily);
  $theStyles .= generateCommonStyles($foundWeight, 'font-weight', false);
  $theStyles .= generateCommonStyles($foundStyle, 'font-style', false);
  $theStyles .= generateTypographyFontSpec($foundSpec);

  $color_important = isset($theColor['important']) && $theColor['important'] ? ' !important' : '';

  if ($foundColor) {
    if ($theColor['value']) {
      $colorValue = null;
      $colorValue = $theColor['value'];
      if ($theColor['type'] === 'solid') {
        $theStyles .= "color:$colorValue$color_important;";
      }
    }
  }
  $mode_color = getModeColor('color', $setting, $globalColor);
  if ($mode_color) {
    foreach ($mode_color as $mode) {
      $style = '';
      $style .= "color:" . $mode['value'] . $color_important . ";";
      $modeStyles[] = [
        'slug' => $mode['slug'],
        'style' => $style,
      ];
    }
  }
  // $theStyles .= generateSizingStyles($foundIndent, 'text-indent', null, null, null, null, false);
  // $theStyles .= generateCommonStyles($foundAlign, 'text-align', false);
  $theStyles .= generateTypographyDecoration($setting);
  $theStyles .= generateCommonStyles($foundTransform, 'text-transform', false);

  // stroke
  $isStroke = getInheritValue(
    'strokeEnable',
    $setting,
    $preSetting,
    $prePreSetting
  );
  $theStrokeColor = getInheritColor('strokeColor', $setting, $preSetting, $prePreSetting, $globalColor);
  $theStrokeWidth = getInheritSize('strokeWidth', $setting, $preSetting, $prePreSetting);
  if (isset($isStroke['value']) && $isStroke['value']) {
    $widthValue = $theStrokeWidth ? $theStrokeWidth : '1px';
    $colorValue = $theStrokeColor['value'];
    if (($foundStrokeColor || $foundStrokeWidth) && $theStrokeColor) {
      $theStyles .= "-webkit-text-stroke:$widthValue $colorValue;";
      $theStyles .= "text-stroke:$widthValue $colorValue;";
    }
    $mode_stroke = getModeColor('strokeColor', $setting, $globalColor);
    if ($mode_stroke) {
      foreach ($mode_stroke as $mode) {
        $style = '';
        $style .= "-webkit-text-stroke:" . $widthValue . ' ' . $mode['value'] . ";";
        $style .= "text-stroke:" . $widthValue . ' ' . $mode['value'] . ";";
        $modeStyles[] = [
          'slug' => $mode['slug'],
          'style' => $style,
        ];
      }
    }
  }

  // shadow
  $isShadow = getInheritValue(
    'shadowEnable',
    $setting,
    $preSetting,
    $prePreSetting
  );
  $theShadowColor = getInheritColor('shadowColor', $setting, $preSetting, $prePreSetting, $globalColor);
  $theShadowX = getInheritSize('shadowX', $setting, $preSetting, $prePreSetting);
  $theShadowY = getInheritSize('shadowY', $setting, $preSetting, $prePreSetting);
  $theShadowBlur = getInheritSize('shadowBlur', $setting, $preSetting, $prePreSetting);

  // Generate
  if (isset($isShadow['value']) && $isShadow['value']) {
    $offsetX = $theShadowX ? $theShadowX : 0;
    $offsetY = $theShadowY ? $theShadowY : 0;
    $blur = $theShadowBlur ? $theShadowBlur : 0;
    if ($foundShadowColor || $foundShadowX || $foundShadowY || $foundShadowBlur) {
      if ($theShadowColor['value']) {
        $color = $theShadowColor['value'];
        $theStyles .= "text-shadow:$offsetX $offsetY $blur $color;";
      }
    }
    $mode_shadow = getModeColor('shadowColor', $setting, $globalColor);
    if ($mode_shadow) {
      foreach ($mode_shadow as $mode) {
        $color = $mode['value'];
        $style = "text-shadow:$offsetX $offsetY $blur $color;";
        $modeStyles[] = [
          'slug' => $mode['slug'],
          'style' => $style,
        ];
      }
    }
  }

  return [
    'theStyles' => $theStyles,
    'modeStyles' => $modeStyles,
  ];
}


function generateFamilys($setting)
{
  $data = '';
  $important = '';
  if ($setting && $setting['custom']) {
    if (count($setting['custom'])) {
      if(isset($setting['important']) && $setting['important']) {
        $important = '!important;';
      }
      $data = 'font-family:';
      for ($i = 0; $i < count($setting['custom']); $i++) {
        $isHasSpace = str_contains($setting['custom'][$i], ' ');
        $value = $setting['custom'][$i];
        if ($i < count($setting['custom']) - 1) {
          if ($isHasSpace) {
            $data .= "'$value',";
          } else {
            $data .= "$value,";
          }
        } else {
          if ($isHasSpace) {
            $data .= "'$value'";
          } else {
            $data .= "$value";
          }
        }
      }
      $data .= $important . ';';
    }
  }
  return $data;
}

function generateTypographyFontSpec($setting)
{
  $theStyles = '';
  if (!$setting) return $theStyles;
  $theField = $setting['field'];
  $important = isset($setting['important']) && $setting['important'] ? ' !important' : '';
  if ($theField === 'global') {
    $theValue = $setting['global'];
    if ($theValue) {
      $fontSize = "var(--{$theValue}-s)";
      $lineHeight = "var(--{$theValue}-h)";
      $letterSpacing = "var(--{$theValue}-l)";
      $wordSpacing = "var(--{$theValue}-w)";
      $theStyles .= "font-size:$fontSize$important;";
      $theStyles .= "line-height:$lineHeight$important;";
      $theStyles .= "letter-spacing:$letterSpacing$important;";
      $theStyles .= "word-spacing:$wordSpacing$important;";
    }
  } else {
    $theValue = $setting['custom'];
    $fontSize = explode('@&', $theValue['fontSize']);
    $lineHeight = explode('@&', $theValue['lineHeight']);
    $letterSpacing = explode('@&', $theValue['letterSpacing']);
    $wordSpacing = explode('@&', $theValue['wordSpacing']);
    if ($fontSize[0]) {
      $fontSize = implode('', $fontSize);
      $theStyles .= "font-size:$fontSize$important;";
    } else if ($fontSize[0] === 0 || $fontSize[0] === '0') {
      $theStyles .= "font-size:0$important;";
    }
    if ($lineHeight[0]) {
      $lineHeight = implode('', $lineHeight);
      $theStyles .= "line-height:$lineHeight$important;";
    } else if ($lineHeight[0] === 0 || $lineHeight[0] === '0') {
      $theStyles .= "line-height:0$important;";
    }
    if ($letterSpacing[0]) {
      $letterSpacing = implode('', $letterSpacing);
      $theStyles .= "letter-spacing:$letterSpacing$important;";
    } else if ($letterSpacing[0] === 0 || $letterSpacing[0] === '0') {
      $theStyles .= "letter-spacing:0$important;";
    }
    if ($wordSpacing[0]) {
      $wordSpacing = implode('', $wordSpacing);
      $theStyles .= "word-spacing:$wordSpacing$important;";
    } else if ($wordSpacing[0] === 0 || $wordSpacing[0] === '0') {
      $theStyles .= "word-spacing:0$important;";
    }
  }
  return $theStyles;
}

function generateTypographyColor($setting, $globalColor)
{
  $theStyles = '';
  if (!$setting) return $theStyles;
  $theField = $setting['field'];
  if ($theField === 'global') {
    $theValue = $setting['global'];
    $theStyles .= "color:var(--$theValue);";
  } else if ($theField === 'custom') {
    $theValue = $setting['custom'];
    $theStyles .= "color:$theValue;";
  }
  return $theStyles;
}

function generateTypographyDecoration($setting)
{
  $theStyles = '';
  $theColumn = array_column($setting, 'property');
  $indDeco = array_search('textDecoration', $theColumn);
  if (!$indDeco && $indDeco !== 0) return $theStyles;
  $foundDecoration = $setting[$indDeco];
  if (!$foundDecoration) return $theStyles;
  $theStyles .= generateCommonStyles($foundDecoration, 'text-decoration', false);
  $indStyle = array_search('textDecorationStyle', $theColumn);
  $foundStyle = $indStyle ? $setting[$indStyle] : null;
  $indThickness = array_search('textDecorationThickness', $theColumn);
  $foundThickness = $indThickness ? $setting[$indThickness] : null;
  $indOffset = array_search('textUnderlineOffset', $theColumn);
  $foundOffset = $indOffset ? $setting[$indOffset] : null;
  $indSkipInk = array_search('textDecorationSkipInk', $theColumn);
  $foundSkipInk = $indSkipInk ? $setting[$indSkipInk] : null;
  $theStyles .= generateCommonStyles($foundStyle, 'text-decoration-style', false);
  $theStyles .= generateSizingStyles($foundThickness, 'text-decoration-thickness', null, null, null, null, false);
  $theStyles .= generateSizingStyles($foundOffset, 'text-underline-offset', null, null, null, null, false);
  if ($foundSkipInk && $foundSkipInk['custom'] === false) {
    $important = isset($foundSkipInk['important']) && $foundSkipInk['important'] ? ' !important' : '';
    $theStyles .= "text-decoration-skip-ink:none$important;";
  }
  return $theStyles;
}
