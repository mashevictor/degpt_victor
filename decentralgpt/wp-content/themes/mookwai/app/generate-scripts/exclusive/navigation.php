<?php

function interactNavigation($data)
{
  $setting = $data['Desktop'];
  $devices = null;
  $type = null;
  $direction = null;
  $duration = null;
  $delay = null;
  $ease = null;
  $custome_ease = null;
  $is_mask = null;
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'devices':
        $value = $item['custom'];
        $devices = "[";
        if ($value) $devices .= "'" . implode("','", $value) . "'";
        $devices .= "]";
        break;
      case 'toggleAnimation':
        $type = $item['custom'];
        break;
      case 'direction':
        $direction = $item['custom'];
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
      case 'isMask':
        $is_mask = $item['custom'];
        break;
      case 'panelDisplay':
        $temp = $item['custom'];
        if ($temp && count($temp)) {
          $tempArray = [];
          foreach ($temp as $key => $value) {
            $tempArray[] = $key . ":'" . $value . "'";
          }
          $panelDisplay = "{" . implode(",", $tempArray) . "}";
        }
        break;
      case 'toggleDisplay':
        $temp = $item['custom'];
        if ($temp && count($temp)) {
          $tempArray = [];
          foreach ($temp as $key => $value) {
            $tempArray[] = $key . ":'" . $value . "'";
          }
          $toggleDisplay = "{" . implode(",", $tempArray) . "}";
        }
        break;
    }
  }

  $generate = '';
  $optionsArray = [];
  if ($devices) $optionsArray[] = "devices:{$devices}";
  if ($panelDisplay) $optionsArray[] = "panelDisplay:{$panelDisplay}";
  if ($toggleDisplay) $optionsArray[] = "toggleDisplay:{$toggleDisplay}";
  if ($type) $optionsArray[] = "type:'{$type}'";
  if ($direction) $optionsArray[] = "direction:'{$direction}'";
  if ($duration) $optionsArray[] = "duration:{$duration}";
  if ($delay) $optionsArray[] = "delay:{$delay}";
  if ($ease === 'custom' && $custome_ease) {
    $optionsArray[] = "ease:'{$custome_ease}'";
  } else if ($ease) {
    $optionsArray[] = "ease:'{$ease}'";
  }
  if ($is_mask) $optionsArray[] = "isMask:true";
  if (count($optionsArray)) {
    $generate .= ',{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}
