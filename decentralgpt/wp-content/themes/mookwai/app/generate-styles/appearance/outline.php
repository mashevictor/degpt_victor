<?php

function outlineStyles($setting, $preSetting, $prePreSetting, $globalColor)
{
  // Initialize
  $theStyles = '';
  $modeStyles = [];
  $foundOutlineStyle = null;
  $foundWidth = null;
  $foundOffset = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'outlineStyle':
        $foundOutlineStyle = $item;
        break;
      case 'outlineWidth':
        $foundWidth = $item;
        break;
      case 'outlineOffset':
        $foundOffset = $item;
        break;
    }
  }
  $getColor = getInheritColor('outlineColor', $setting, null, null, $globalColor);
  $theOutlineStyle = getInheritValue('outlineStyle', $setting, $preSetting, $prePreSetting);

  // Generate
  $theStyles .= generateCommonStyles($foundOutlineStyle, 'outline-style', false);
  if ($theOutlineStyle !== 'none') {
    $important = isset($getColor['important']) ? ' !important' : '';
    $theStyles .= generateSizingStyles($foundWidth, 'outline-width', null, null, null, null, false);
    if ($getColor['value']) {
      $theColorValue = $getColor['value'];
      if ($getColor['type'] === 'solid') {
        $theStyles .= "outline-color:$theColorValue$important;";
      }
    }
    $theStyles .= generateSizingStyles($foundOffset, 'outline-offset', null, null, null, null, false);

    $mode_colors = getModeColor('outlineColor', $setting, $globalColor);
    if ($mode_colors) {
      foreach ($mode_colors as $mode) {
        $style = '';
        $style .= "outline-color:" . $mode['value'] . $important . ";";
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
