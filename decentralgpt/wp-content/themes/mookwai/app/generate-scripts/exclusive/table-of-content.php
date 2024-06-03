<?php

function interactTableOfContent($data)
{
  $setting = $data['Desktop'];
  $first_level = null;
  $second_level = null;
  $include = null;
  $exclude = null;
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'firstLevel':
        $first_level = $item['custom'];
        break;
      case 'secondLevel':
        $second_level = $item['custom'];
        break;
      case 'include':
        $include = $item['custom'];
        break;
      case 'exclude':
        $exclude = $item['custom'];
        break;
    }
  }

  $generate = '';
  $optionsArray = [];
  if ($first_level) $optionsArray[] = "firstLevel:'{$first_level}'";
  if ($second_level) $optionsArray[] = "secondLevel:'{$second_level}'";
  if ($include) {
    $includeString = $include;
    $includeArray = explode(',', $includeString);
    $includeArray = array_map(function ($className) {
      return '.' . $className;
    }, $includeArray);
    $jsonString = json_encode($includeArray);
    $optionsArray[] = "include:{$jsonString}";
  }
  if ($exclude) {
    $excludeString = $exclude;
    $excludeArray = explode(',', $excludeString);
    $excludeArray = array_map(function ($className) {
      return '.' . $className;
    }, $excludeArray);
    $jsonString = json_encode($excludeArray);
    $optionsArray[] = "exclude:{$jsonString}";
  }
  if (count($optionsArray)) {
    $generate .= ',{';
    $generate .= implode(',', $optionsArray);
    $generate .= '}';
  }
  return $generate;
}
