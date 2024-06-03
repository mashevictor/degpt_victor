<?php

function borderStyles($setting, $preSetting, $prePreSetting, $globalColor)
{
  // Initialize
  $theStyles = '';
  $modeStyles = [];
  $foundBorderStyle = null;
  $foundWidth = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'borderStyle':
        $foundBorderStyle = $item;
        break;
      case 'borderWidth':
        $foundWidth = $item;
        break;
    }
  }
  $getColor = getInheritColor('borderColor', $setting, null, null, $globalColor);
  $theBorderStyle = getInheritValue('borderStyle', $setting, $preSetting, $prePreSetting);

  // Generate
  $important = isset($getColor['important']) ? ' !important' : '';
  $theStyles .= generateCommonStyles($foundBorderStyle, 'border-style', false);
  if (isset($theBorderStyle['value']) && $theBorderStyle['value'] !== 'none') {
    $theStyles .= generateSizingStyles($foundWidth, 'border-width', 'border-top-width', 'border-right-width', 'border-bottom-width', 'border-left-width', false);
    if ($getColor['value']) {
      $theColorValue = $getColor['value'];
      if ($getColor['type'] === 'solid') {
        $theStyles .= "border-color:$theColorValue$important;";
      } else if ($getColor['type'] === 'gradient') {
        $theStyles .= "border-image:$theColorValue 10$important;";
      }
    }
    $mode_colors = getModeColor('borderColor', $setting, $globalColor);
    if ($mode_colors) {
      foreach ($mode_colors as $mode) {
        $style = '';
        if ($mode['type'] === 'solid') {
          $style .= "border-color:" . $mode['value'] . $important . ";";
        } else if ($mode['type'] === 'gradient') {
          $style .= "border-image:" . $mode['value'] . $important . ";";
        }
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
