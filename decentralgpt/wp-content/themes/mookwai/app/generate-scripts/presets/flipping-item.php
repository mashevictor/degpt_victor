<?php

function interactFlippingItem($data)
{
  $setting = $data['Desktop'];
  $devices = null;
  $power = null;
  $duration = null;
  $reversed = null;
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'devices':
        $temp = $item['custom'];
        if ($temp) {
          $devices = "['" . implode("','", $temp) . "']";
        }
        break;
      case 'power':
        $power = $item['custom'];
        break;
      case 'duration':
        $duration = $item['custom'];
        break;
      case 'reversed':
        $reversed = $item['custom'];
        break;
    }
  }

  $generate = '';
  $optionsArray = [];
  if ($devices) $optionsArray[] = "devices:{$devices}";
  if ($power) $optionsArray[] = "power:{$power}";
  if ($duration) $optionsArray[] = "duration:{$duration}";
  if ($reversed) $optionsArray[] = "reversed:true";
  if (count($optionsArray)) {
    $generate .= ',{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}
