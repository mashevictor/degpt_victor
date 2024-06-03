<?php

function svgStyles($setting, $preSetting, $prePreSetting, $globalColor)
{
  // Initialize
  $theStyles = '';
  $modeStyles = [];
  $foundFill = null;
  $foundStroke = null;
  $foundWidth = null;
  $foundLineCap = null;
  $foundLineJoin = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'fill':
        $foundFill = $item;
        break;
      case 'stroke':
        $foundStroke = $item;
        break;
      case 'strokeWidth':
        $foundWidth = $item;
        break;
      case 'strokeLinecap':
        $foundLineCap = $item;
        break;
      case 'strokeLinejoin':
        $foundLineJoin = $item;
        break;
    }
  }

  // Generate
  $theStyles .= generateColorStyles($foundFill, $globalColor, 'fill');
  $theStyles .= generateColorStyles($foundStroke, $globalColor, 'stroke');
  $theStyles .= generateSizingStyles($foundWidth, 'stroke-width', null, null, null, null, false);
  $theStyles .= generateCommonStyles($foundLineCap, 'stroke-linecap', false);
  $theStyles .= generateCommonStyles($foundLineJoin, 'stroke-linejoin', false);

  $fill_important = isset($foundFill['important']) ? ' !important' : '';
  $stroke_important = isset($foundFill['important']) ? ' !important' : '';
  $mode_fill = getModeColor('fill', $setting, $globalColor);
  if ($mode_fill) {
    foreach ($mode_fill as $mode) {
      $style = '';
      $style .= "fill:" . $mode['value'] . $fill_important . ";";
      $modeStyles[] = [
        'slug' => $mode['slug'],
        'style' => $style,
      ];
    }
  }
  $mode_stroke = getModeColor('stroke', $setting, $globalColor);
  if ($mode_stroke) {
    foreach ($mode_stroke as $mode) {
      $style = '';
      $style .= "stroke:" . $mode['value'] . $stroke_important . ";";
      $modeStyles[] = [
        'slug' => $mode['slug'],
        'style' => $style,
      ];
    }
  }

  return [
    'theStyles' => $theStyles,
    'modeStyles' => $modeStyles,
  ];
}
