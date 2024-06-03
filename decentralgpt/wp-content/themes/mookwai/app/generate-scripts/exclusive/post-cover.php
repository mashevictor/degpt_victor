<?php

function interactPostCover($data)
{
  $setting = $data['Desktop'];
  $devices = "['Desktop', 'Tablet', 'Mobile']";
  $on_toggle = null;
  $ignore_first = null;
  $stay = null;
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'devices':
        $temp = $item['custom'];
        if ($temp) {
          $devices = "['" . implode("','", $temp) . "']";
        }
        break;
      case 'onToggle':
        $on_toggle = $item['custom'];
        break;
      case 'ignoreFirst':
        $ignore_first = $item['custom'];
        break;
      case 'stay':
        $stay = $item['custom'];
        break;
    }
  }

  $generate = '';
  $optionsArray = [];
  if ($devices) $optionsArray[] = "devices:{$devices}";
  if ($on_toggle) $optionsArray[] = "onToggle:'{$on_toggle}'";
  if ($on_toggle === 'hover' && $ignore_first) $optionsArray[] = "ignoreFirst:true";
  if ($stay) $optionsArray[] = "stay:$stay";
  if (count($optionsArray)) {
    $generate .= ',{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}
