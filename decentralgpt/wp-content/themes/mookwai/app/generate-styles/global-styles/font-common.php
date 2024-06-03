<?php

function generateFontCommon($preSelector, $setting)
{
  $reg = '';
  $lg = '';
  $md = '';
  $sm = '';
  if ($preSelector) return [
    'reg' => $reg,
    'lg' => $lg,
    'md' => $md,
    'sm' => $sm,
  ];
  foreach ($setting as $item) {
    $reg .= generateRes($item['reg'], null, null, $item['slug']);
    $lg .= generateRes($item['lg'], $item['reg'], null, $item['slug']);
    $md .= generateRes($item['md'], $item['reg'], null, $item['slug']);
    $sm .= generateRes($item['sm'], $item['md'], $item['reg'], $item['slug']);
  }
  return [
    'reg' => $reg,
    'lg' => $lg,
    'md' => $md,
    'sm' => $sm,
  ];
}

function generateRes($current, $pre, $prePre, $slug)
{
  $theStyles = '';
  $fontSize = generateValue($current, $pre, $prePre, 'fontSize');
  $lineHeight = generateValue($current, $pre, $prePre, 'lineHeight');
  $letterSpacing = generateValue($current, $pre, $prePre, 'letterSpacing');
  $wordSpacing = generateValue($current, $pre, $prePre, 'wordSpacing');
  if ($fontSize) {
    $theStyles .= "--$slug-s:$fontSize;";
  }
  if ($lineHeight) {
    $theStyles .= "--$slug-h:$lineHeight;";
  }
  if ($letterSpacing) {
    $theStyles .= "--$slug-l:$letterSpacing;";
  }
  if ($wordSpacing) {
    $theStyles .= "--$slug-w:$wordSpacing;";
  }
  return $theStyles;
}

function generateValue($current, $pre, $prePre, $key)
{
  $result = '';
  if (!isset($current[$key]) || !$current[$key]) return $result;
  if (!$pre && !$prePre) {
    $result = explode('@&', $current[$key]);
    if ($result[0]) {
      if ($result[0] !== '0') {
        $result = implode('', $result);
      } else if ($result[0] === '0') {
        $result = '0';
      }
    } else {
      $result = '0';
    }
  } else if ($pre && !$prePre) {
    if ($current[$key] !== $pre[$key]) {
      $result = explode('@&', $current[$key]);
      if ($result[0]) {
        if ($result[0] !== '0') {
          $result = implode('', $result);
        } else if ($result[0] === '0') {
          $result = '0';
        }
      } else {
        return null;
      }
    }
  } else if ($pre && $prePre) {
    $currentArray = explode('@&', $current[$key]);
    $preArray = explode('@&', $pre[$key]);
    $prePreArray = explode('@&', $prePre[$key]);
    if (!$currentArray[0]) {
      return null;
    } else if ($currentArray[0] && $preArray[0]) {
      if (implode('', $currentArray) === implode('', $preArray)) return null;
    } else if ($currentArray[0] && !$preArray[0] && $prePreArray[0]) {
      if (implode('', $currentArray) === implode('', $prePreArray)) return null;
    }
    if ($currentArray[0]) {
      if ($currentArray[0] !== '0') {
        $result = implode('', $currentArray);
      } else {
        $result = '0';
      }
    } else {
      return null;
    }
  }
  return $result;
}
