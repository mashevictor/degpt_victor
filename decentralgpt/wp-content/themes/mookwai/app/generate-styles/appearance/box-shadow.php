<?php

function boxShadowStyles($setting, $preSetting, $prePreSetting, $globalColor)
{
  // Initialize
  $theStyles = '';
  $modeStyles = [];
  $foundType = null;
  $foundColor = null;
  $foundOffsetX = null;
  $foundOffsetY = null;
  $foundBlur = null;
  $foundSpread = null;
  $foundInset = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'type':
        $foundType = $item;
        break;
      case 'color':
        $foundColor = $item;
        break;
      case 'offsetX':
        $foundOffsetX = $item;
        break;
      case 'offsetY':
        $foundOffsetY = $item;
        break;
      case 'blur':
        $foundBlur = $item;
        break;
      case 'spread':
        $foundSpread = $item;
        break;
      case 'inset':
        $foundInset = $item;
        break;
    }
  }
  if ($foundType && ($foundType['custom'] === 'inherit' || $foundType['custom'] === 'initial' || $foundType['custom'] === 'unset')) {
    $theStyles .= generateCommonStyles($foundType, 'box-shadow', false);
    return $theStyles;
  }
  $theColor = getInheritColor('color', $setting, $preSetting, $prePreSetting, $globalColor);
  $theOffsetX = getInheritSize('offsetX', $setting, $preSetting, $prePreSetting);
  $theOffsetY = getInheritSize('offsetY', $setting, $preSetting, $prePreSetting);
  $theBlur = getInheritSize('blur', $setting, $preSetting, $prePreSetting);
  $theSpread = getInheritSize('spread', $setting, $preSetting, $prePreSetting);
  $theInset = getInheritValue('inset', $setting, $preSetting, $prePreSetting);

  // Generate
  if ($foundColor || $foundOffsetX || $foundOffsetY || $foundBlur || $foundSpread || $foundInset) {
    $offsetX = $theOffsetX ? $theOffsetX : 0;
    $offsetY = $theOffsetY ? $theOffsetY : 0;
    $blur = $theBlur ? $theBlur : 0;
    $spread = $theSpread ? $theSpread : 0;
    $inset = $theInset === 'true' ? 'inset ' : '';
    $important = isset($theColor['important']) ? ' !important' : '';
    if ($theColor['value']) {
      $color = $theColor['value'];
      $theStyles .= "box-shadow:$inset$offsetX $offsetY $blur $spread $color$important;";
    }
    $mode_colors = getModeColor('color', $setting, $globalColor);
    if ($mode_colors) {
      foreach ($mode_colors as $mode) {
        $style = '';
        $style .= "box-shadow:$inset$offsetX $offsetY $blur $spread " . $mode['value'] . $important . ";";
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
