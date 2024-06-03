<?php

function interactWiggleItem($data)
{
  $setting = $data['Desktop'];
  $devices = null;
  $type = null;
  $duration = null;
  $wiggles = null;
  $transform = null;
  $power = null;
  $interval = null;
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'type':
        $type = $item['custom'];
        break;
      case 'devices':
        $devices = transformDevice($item['custom']);
        break;
      case 'duration':
        $duration = $item['custom'];
        break;
      case 'wiggles':
        $wiggles = $item['custom'];
        break;
      case 'transform':
        $transform = $item['custom'];
        break;
      case 'power':
        $power = $item['custom'];
        break;
      case 'interval':
        $interval = $item['custom'];
        break;
    }
  }
  $generate = '';
  $optionsArray = [];
  if ($devices) $optionsArray[] = "devices:{$devices}";
  if ($type) $optionsArray[] = "type:'{$type}'";
  if ($wiggles) $optionsArray[] = "wiggles:{$wiggles}";
  if ($duration) $optionsArray[] = "duration:{$duration}";
  if ($transform) $optionsArray[] = "transform:'{$transform}'";
  if ($power) $optionsArray[] = "power:{$power}";
  if ($interval) $optionsArray[] = "interval:{$interval}";
  if (count($optionsArray)) {
    $generate .= ',{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}