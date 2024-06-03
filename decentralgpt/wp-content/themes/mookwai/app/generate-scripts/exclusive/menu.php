<?php

function interactMenu($data)
{
  $setting = $data['Desktop'];
  $devices = null;
  $event_type = null;
  $animation = null;
  $display = null;
  $offset = null;
  $duration = null;
  $delay = null;
  $ease = null;
  $custome_ease = null;
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'devices':
        $temp = $item['custom'];
        if ($temp) {
          $devices = "['" . implode("','", $temp) . "']";
        }
        break;
      case 'animation':
        $animation = $item['custom'];
        break;
      case 'eventType':
        $event_type = $item['custom'];
        break;
      case 'panelDisplay':
        $temp = $item['custom'];
        if ($temp && count($temp)) {
          $tempArray = [];
          foreach ($temp as $key => $value) {
            $tempArray[] = $key . ":'" . $value . "'";
          }
          $display = "{" . implode(",", $tempArray) . "}";
        }
        break;
      case 'offset':
        $offset = $item['custom'];
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
  if ($event_type) $optionsArray[] = "event:'{$event_type}'";
  if ($animation) $optionsArray[] = "animation:'{$animation}'";
  if ($display) $optionsArray[] = "display:{$display}";
  if ($offset) $optionsArray[] = "offset:'{$offset}'";
  if ($duration) $optionsArray[] = "duration:{$duration}";
  if ($delay) $optionsArray[] = "delay:{$delay}";
  if ($ease === 'custom' && $custome_ease) {
    $optionsArray[] = "ease:'{$custome_ease}'";
  } else if ($ease && $ease !== 'custom') {
    $optionsArray[] = "ease:'{$ease}'";
  }
  if (count($optionsArray)) {
    $generate .= ',{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}
