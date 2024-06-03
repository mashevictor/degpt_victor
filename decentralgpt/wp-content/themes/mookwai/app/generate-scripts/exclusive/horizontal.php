<?php

function interactHorizontal($data, $exclusiveData, $selector)
{
  $setting = $data['Desktop'];
  $start = null;
  $stay_start = null;
  $stay_end = null;
  $scrub = null;
  $speed = null;
  $snap = null;
  $devices = null;
  $the_childs = [];
  $child_animations = [];
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'start':
        $start = $item['custom'];
        break;
      case 'stayStart':
        $stay_start = $item['custom'];
        break;
      case 'stayEnd':
        $stay_end = $item['custom'];
        break;
      case 'smooth':
        $scrub = $item['custom'];
        break;
      case 'speed':
        $speed = $item['custom'];
        break;
      case 'snap':
        $snap = $item['custom'];
        break;
      case 'devices':
        $temp = $item['custom'];
        if ($temp && count($temp)) {
          $devices = "['" . implode("','", $temp) . "']";
        }
        break;
      case 'childAnimations':
        $the_childs = $item['custom'];
        break;
    }
  }

  if (count($the_childs)) {
    $theColumn = array_column($exclusiveData, 'groupItem');
    foreach ($the_childs as $aniID) {
      $found = array_search($aniID, $theColumn);
      if ($found || $found === 0) {
        $child_array = [];
        $child_setting = $exclusiveData[$found]['Desktop'];
        $enable_scrub = false;
        $smooth = false;
        $child_array = [];
        foreach ($child_setting as $item) {
          switch ($item['property']) {
            case 'childElement':
              $child_array[] = "container:'" . $item['custom'] . "'";
              break;
            case 'start':
              $start = $item['custom'];
              $start = explode('@&|||@&', $start)[0];
              $start = str_replace("::selector", "document.querySelector('{$selector}')?", $start);
              if (str_starts_with($start, '()') || str_starts_with($start, 'function')) {
                $child_array[] = "start:{$start}";
              } else {
                $child_array[] = "start:'{$start}'";
              }
              break;
            case 'end':
              $end = $item['custom'];
              $end = explode('@&|||@&', $end)[0];
              $end = str_replace("::selector", "document.querySelector('{$selector}')?", $end);
              if (str_starts_with($end, '()') || str_starts_with($end, 'function')) {
                $child_array[] = "end:{$end}";
              } else {
                $child_array[] = "end:'{$end}'";
              }
              break;
            case 'toggleClass':
              $child_array[] = "toggleClass:'" . $item['custom'] . "'";
              break;
            case 'enableScrub':
              if ($item['custom']) {
                $enable_scrub = true;
              }
              break;
            case 'smooth':
              if ($item['custom']) {
                $smooth = $item['custom'];
              }
              break;
            case 'markers':
              if ($item['custom']) {
                $child_array[] = "markers:true";
              }
              break;
            // case 'viewIn':
            //   $view_in_opt = transformChildTriggerValue($item['custom']);
            //   if ($view_in_opt) $child_array[] = "viewIn:" . $view_in_opt;
            //   break;
            // case 'viewOut':
            //   $view_out_opt = transformChildTriggerValue($item['custom']);
            //   if ($view_out_opt) $child_array[] = "viewOut:" . $view_out_opt;
            //   break;
          }
        }
        if ($enable_scrub) {
          if ($smooth) {
            $child_array[] = "scrub:" . $smooth . "";
          } else {
            $child_array[] = "scrub:true";
          }
        }
        if (count($child_array)) {
          $child_opt = '{';
          $child_opt .= implode(',', $child_array);
          $child_opt .= '}';
          $child_animations[] = $child_opt;
        }
      }
    }
  }

  $generate = '';
  $optionsArray = [];
  if ($devices) $optionsArray[] = "devices:{$devices}";
  if ($start) $optionsArray[] = "start:'{$start}'";
  if ($stay_start) $optionsArray[] = "stayStart:{$stay_start}";
  if ($stay_end) $optionsArray[] = "stayEnd:{$stay_end}";
  if ($scrub) $optionsArray[] = "scrub:{$scrub}";
  if ($speed) $optionsArray[] = "speed:{$speed}";
  if ($snap) $optionsArray[] = "snap:true";
  if (count($child_animations)) {
    $test = implode(',', $child_animations);
    $optionsArray[] = "childAnimations:[{$test}]";
  }
  if (count($optionsArray)) {
    $generate .= ',{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}
