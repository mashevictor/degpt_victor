<?php

function interactBouncingItem($data)
{
  $setting = $data['Desktop'];
  $devices = null;
  $power = null;
  $duration = null;
  $inner = null;
  $inner_power = null;
  $inner_duration = null;
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
      case 'inner':
        $inner = $item['custom'];
        break;
      case 'innerPower':
        $inner_power = $item['custom'];
        break;
      case 'innerDuration':
        $inner_duration = $item['custom'];
        break;
    }
  }

  $generate = '';
  $optionsArray = [];
  if ($devices) $optionsArray[] = "devices:{$devices}";
  if ($power) $optionsArray[] = "power:{$power}";
  if ($duration) $optionsArray[] = "duration:{$duration}";
  if ($inner) $optionsArray[] = "inner:true";
  if ($inner_power) $optionsArray[] = "innerPower:{$inner_power}";
  if ($inner_duration) $optionsArray[] = "innerDuration:{$inner_duration}";
  if (count($optionsArray)) {
    $generate .= ',{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}
