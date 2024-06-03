<?php

function interactFlipCard($data)
{
  $setting = $data['Desktop'];
  $devices = null;
  $direction = null;
  $reverse = null;
  $duration = null;
  $delay = null;
  $ease = null;
  $custome_ease = null;
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'devices':
        $value = $item['custom'];
        $devices = "[";
        if ($value) $devices .= "'" . implode("','", $value) . "'";
        $devices .= "]";
        break;
      case 'direction':
        $direction = $item['custom'];
        break;
      case 'reverse':
        $reverse = $item['custom'];
        break;
      case 'duration':
        $duration = $item['custom'];
        break;
      case 'delay':
        $delay = $item['custom'];
        break;
      case 'ease':
        $ease = $item['custom'];
        break;
      case 'customEase':
        $custome_ease = $item['custom'];
        break;
    }
  }

  $generate = '';
  $optionsArray = [];
  if ($devices) $optionsArray[] = "devices:{$devices}";
  if ($direction) $optionsArray[] = "direction:'{$direction}'";
  if ($reverse) $optionsArray[] = "reverse:true";
  if ($duration) $optionsArray[] = "duration:{$duration}";
  if ($delay) $optionsArray[] = "delay:{$delay}";
  if ($ease === 'custom' && $custome_ease) {
    $optionsArray[] = "ease:'{$custome_ease}'";
  } else if ($ease) {
    $optionsArray[] = "ease:'{$ease}'";
  }
  if (count($optionsArray)) {
    $generate .= ',{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}
