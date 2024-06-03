<?php

function pageSelection($setting, $globalColor)
{
  // Initialize
  $theStyles = '';

  // Get Datas
  $theBgColor = getInheritColor('background', $setting, null, null, $globalColor);
  $theTextColor = getInheritColor('text', $setting, null, null, $globalColor);

  // Generate
  if ($theBgColor['value']) {
    $colorValue = $theBgColor['value'];
    $theStyles .= "background-color:$colorValue;";
  }
  if ($theTextColor['value']) {
    $colorValue = $theTextColor['value'];
    $theStyles .= "color:$colorValue;";
  }

  return $theStyles;
}
