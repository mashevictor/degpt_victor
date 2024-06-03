<?php

function interactAnchor($data)
{
  $setting = $data['Desktop'];
  $devices = null;
  $target = null;
  $duration = null;
  $offset = null;
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'target':
        $target = $item['custom'];
        break;
      case 'duration':
        $duration = $item['custom'];
        break;
      case 'offset':
        $offset = $item['custom'];
        break;
      case 'devices':
        $devices = transformDevice($item['custom']);
        break;
    }
  }
  $generate = '';
  $optionsArray = [];
  if ($target) $optionsArray[] = "target:'{$target}'";
  if ($duration !== null) $optionsArray[] = "duration:{$duration}";
  if ($offset) $optionsArray[] = "offset:{$offset}";
  if ($devices) $optionsArray[] = "devices:{$devices}";
  if (count($optionsArray)) {
    $generate .= ',{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}