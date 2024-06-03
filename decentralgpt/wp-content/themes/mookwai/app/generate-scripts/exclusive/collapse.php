<?php

function interactCollapse($data)
{
  $setting = $data['Desktop'];
  $devices = null;
  $init_expand = null;
  $auto_fold = null;
  $duration = null;
  $delay = null;
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'initExpand':
        $init_expand = $item['custom'];
        break;
      case 'autoFold':
        $auto_fold = $item['custom'];
        break;
      case 'duration':
        $duration = $item['custom'];
        break;
      case 'delay':
        $delay = $item['custom'];
        break;
    }
  }

  $generate = '';
  $optionsArray = [];
  if ($devices) $optionsArray[] = "devices:{$devices}";
  if ($init_expand) $optionsArray[] = "initExpand:'{$init_expand}'";
  if ($auto_fold) $optionsArray[] = "autoFold:true";
  if ($duration) $optionsArray[] = "duration:{$duration}";
  if ($delay) $optionsArray[] = "delay:{$delay}";
  if (count($optionsArray)) {
    $generate .= ',{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}
