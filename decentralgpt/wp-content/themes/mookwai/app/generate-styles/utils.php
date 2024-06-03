<?php

function getInheritValue($property, $setting, $preSetting, $prePreSetting)
{
  $value = null;
  $important = false;
  $found = null;
  if ($setting) {
    $found = array_search($property, array_column($setting, 'property'));
  }

  if ($found || $found === 0) {
    $value = $setting[$found]['custom'];
    $important = isset($setting[$found]['important']) && $setting[$found]['important'];
  }
  if (!$value && $preSetting) {
    $preColumn = array_column($preSetting, 'property');
    $found = array_search($property, $preColumn);
    if ($found || $found === 0) {
      $value = $preSetting[$found]['custom'];
      $important = isset($preSetting[$found]['important']) && $preSetting[$found]['important'];
    }
  }
  if (!$value && $prePreSetting) {
    $prePreColumn = array_column($prePreSetting, 'property');
    $found = array_search($property, $prePreColumn);
    if ($found || $found === 0) {
      $value = $prePreSetting[$found]['custom'];
      $important = isset($prePreSetting[$found]['important']) && $prePreSetting[$found]['important'];
    }
  }
  return ['value' => $value, 'important' => $important];
}

function getInheritSize($property, $setting, $preSetting, $prePreSetting)
{
  $theValue = null;
  if ($setting) {
    $theColumn = array_column($setting, 'property');
    $found = array_search($property, $theColumn);
    if ($found || $found === 0) {
      $theValue = $setting[$found]['custom'];
    }
  }
  if (!$theValue && $preSetting) {
    $preColumn = array_column($preSetting, 'property');
    $found = array_search($property, $preColumn);
    if ($found || $found === 0) {
      $theValue = $preSetting[$found]['custom'];
    }
  }
  if (!$theValue && $prePreSetting) {
    $prePreColumn = array_column($prePreSetting, 'property');
    $found = array_search($property, $prePreColumn);
    if ($found || $found === 0) {
      $theValue = $prePreSetting[$found]['custom'];
    }
  }
  if ($theValue) {
    $theValue = explode('@&', $theValue);
    if ($theValue[0] === 0 || $theValue[0] === '0') {
      $theValue = 0;
    } else if ($theValue[0]) {
      $theValue = implode('', $theValue);
    }
  }
  return $theValue;
}

function getInheritColor($property, $setting, $preSetting, $prePreSetting, $globalColor)
{
  $value = null;
  $field = null;
  $type = null;
  $important = false;
  if ($setting) {
    $theColumn = array_column($setting, 'property');
    $found = array_search($property, $theColumn);
    if (is_numeric($found)) {
      $field = $setting[$found]['field'];
      $value = $setting[$found][$field];
      $important = isset($setting[$found]['important']) && $setting[$found]['important'];
    }
  }
  if (!$value && $preSetting) {
    $theColumn = array_column($preSetting, 'property');
    $found = array_search($property, $theColumn);
    if (is_numeric($found)) {
      $field = $preSetting[$found]['field'];
      $value = $preSetting[$found][$field];
      $important = isset($preSetting[$found]['important']) && $preSetting[$found]['important'];
    }
  }
  if (!$value && $prePreSetting) {
    $theColumn = array_column($prePreSetting, 'property');
    $found = array_search($property, $theColumn);
    if (is_numeric($found)) {
      $field = $prePreSetting[$found]['field'];
      $value = $prePreSetting[$found][$field];
      $important = isset($prePreSetting[$found]['important']) && $prePreSetting[$found]['important'];
    }
  }
  if ($value && $field === 'global') {
    $theColumn = array_column($globalColor, 'slug');
    $found = array_search($value, $theColumn);
    $field = $found;
    if (is_numeric($found)) {
      $type = $globalColor[$found]['type'];
    }
    $value = "var(--$value)";
  } else if ($value && $field === 'custom') {
    $value_array = explode('@&', $value);
    $type = isset($value_array[1]) ? $value_array[1] : 'solid';
    $value = $value_array[0];
  }
  return ['value' => $value, 'field' => $field, 'type' => $type, 'important' => $important];
}

function getModeColor($property, $setting, $globalColor)
{
  if (!$setting) return null;
  $found = array_search($property, array_column($setting, 'property'));
  if (!is_numeric($found)) return null;
  $color_scheme = isset($setting[$found]['colorScheme']) ? $setting[$found]['colorScheme'] : null;
  if (!$color_scheme || !count($color_scheme)) return null;
  $result = [];
  foreach ($color_scheme as $item) {
    $type = null;
    $value = null;
    $field = $item['field'];
    $value = $item[$field];
    if ($field === 'global') {
      $found_global = array_search($value, array_column($globalColor, 'slug'));
      if (is_numeric($found_global)) {
        $type = $globalColor[$found_global]['type'] ? $globalColor[$found_global]['type'] : 'solid';
      }
      $value = "var(--$value)";
    } else {
      $value_array = explode('@&', $value);
      $type = isset($value_array[1]) ? $value_array[1] : 'solid';
      $value = $value_array[0];
    }
    $result[] = [
      'slug' => $item['slug'],
      'type' => $type,
      'field' => $field,
      'value' => $value,
    ];
  }
  return $result;
}

function generateCommonStyles($setting, $attr, $isImportant)
{
  $theResults = '';
  $important = '';
  if ($isImportant) {
    $important = '!important';
  } else if (isset($setting['important']) && $setting['important']) {
    $important = '!important';
  }
  if ($setting) {
    $theValue = $setting['custom'];
    if ($theValue || $theValue === 0 || $theValue === '0') {
      $theResults = "$attr:$theValue$important;";
    }
  }
  return $theResults;
}

function generateSizingStyles($setting, $unitedAttr, $firstAttr, $secondAttr, $thirdAttr, $fouthAttr, $isImportant)
{
  $theResults = '';
  $important = '';
  if ($isImportant) {
    $important = '!important';
  } else if (isset($setting['important']) && $setting['important']) {
    $important = '!important';
  }
  if ($setting) {
    $theField = $setting['field'];
    $theValue = isset($setting[$theField]) ? $setting[$theField] : null;
    $valueArray = explode('|', $theValue);
    $unitedValue = null;
    $firstValue = null;
    $secondValue = null;
    $thirdValue = null;
    $fouthValue = null;
    if ($theField === 'global') {
      if ((count($valueArray) > 1 && $valueArray[1] === 'united') || count($valueArray) === 1) {
        $unitedValue = "var(--$valueArray[0])";
      } else if (count($valueArray) === 4) {
        $firstValue = explode('@&', $valueArray[0]);
        $secondValue = explode('@&', $valueArray[1]);
        $thirdValue = explode('@&', $valueArray[2]);
        $fouthValue = explode('@&', $valueArray[3]);
        if (count($firstValue) > 1 || !$firstValue[0]) {
          $firstValue = null;
        } else {
          $firstValue = $firstValue[0];
          $firstValue = "var(--$firstValue)";
        }
        if (count($secondValue) > 1 || !$secondValue[0]) {
          $secondValue = null;
        } else {
          $secondValue = $secondValue[0];
          $secondValue = "var(--$secondValue)";
        }
        if (count($thirdValue) > 1 || !$thirdValue[0]) {
          $thirdValue = null;
        } else {
          $thirdValue = $thirdValue[0];
          $thirdValue = "var(--$thirdValue)";
        }
        if (count($fouthValue) > 1 || !$fouthValue[0]) {
          $fouthValue = null;
        } else {
          $fouthValue = $fouthValue[0];
          $fouthValue = "var(--$fouthValue)";
        }
      }
    } else if ($theField === 'custom') {
      if ((count($valueArray) > 1 && $valueArray[1] === 'united') || count($valueArray) === 1) {
        $unitedArray = explode('@&', $valueArray[0]);
        if ($unitedArray[0] === 0 || $unitedArray[0] === '0') {
          $unitedValue = 0;
        } else if ($unitedArray[0]) {
          $unitedValue = implode('', $unitedArray);
        }
      } else if (count($valueArray) === 4) {
        $firstArray = explode('@&', $valueArray[0]);
        $secondArray = explode('@&', $valueArray[1]);
        $thirdArray = explode('@&', $valueArray[2]);
        $fouthArray = explode('@&', $valueArray[3]);
        if (count($firstArray) > 2 || !strlen($firstArray[0])) {
          $firstValue = null;
        } else if ($firstArray[0] === 0 || $firstArray[0] === '0') {
          $firstValue = 0;
        } else if ($firstArray[0]) {
          $firstValue = implode('', $firstArray);
        }
        if (count($secondArray) > 2 || !strlen($secondArray[0])) {
          $secondValue = null;
        } else if ($secondArray[0] === 0 || $secondArray[0] === '0') {
          $secondValue = 0;
        } else if ($secondArray[0]) {
          $secondValue = implode('', $secondArray);
        }
        if (count($thirdArray) > 2 || !strlen($thirdArray[0])) {
          $thirdValue = null;
        } else if ($thirdArray[0] === 0 || $thirdArray[0] === '0') {
          $thirdValue = 0;
        } else if ($thirdArray[0]) {
          $thirdValue = implode('', $thirdArray);
        }
        if (count($fouthArray) > 2 || !strlen($fouthArray[0])) {
          $fouthValue = null;
        } else if ($fouthArray[0] === 0 || $fouthArray[0] === '0') {
          $fouthValue = 0;
        } else if ($fouthArray[0]) {
          $fouthValue = implode('', $fouthArray);
        }
      }
    }
    if (($unitedValue || $unitedValue === 0) && $unitedAttr) {
      $theResults .= "$unitedAttr:$unitedValue$important;";
    }
    if ($firstAttr && $secondAttr && $thirdAttr && $fouthAttr) {
      if (
        ($firstValue || $firstValue === 0) &&
        ($secondValue || $secondValue === 0) &&
        ($thirdValue || $thirdValue === 0) &&
        ($fouthValue || $fouthValue === 0) &&
        $unitedAttr
      ) {
        if (
          $firstValue === $thirdValue &&
          $secondValue === $fouthValue &&
          $firstValue === $secondValue
        ) {
          $theResults .= "$unitedAttr:$firstValue$important;";
        } else if (
          $firstValue === $thirdValue &&
          $secondValue === $fouthValue &&
          $firstValue !== $secondValue
        ) {
          $theResults .= "$unitedAttr:$firstValue $secondValue$important;";
        } else if (
          $firstValue !== $thirdValue &&
          $secondValue === $fouthValue
        ) {
          $theResults .= "$unitedAttr:$firstValue $secondValue $thirdValue$important;";
        } else {
          $theResults .= "$unitedAttr:$firstValue $secondValue $thirdValue $fouthValue$important;";
        }
      } else {
        if (($firstValue || $firstValue === 0) && $firstAttr) {
          $theResults .= "$firstAttr:$firstValue$important;";
        }
        if (($secondValue || $secondValue === 0) && $secondAttr) {
          $theResults .= "$secondAttr:$secondValue$important;";
        }
        if (($thirdValue || $thirdValue === 0) && $thirdAttr) {
          $theResults .= "$thirdAttr:$thirdValue$important;";
        }
        if (($fouthValue || $fouthValue === 0) && $fouthAttr) {
          $theResults .= "$fouthAttr:$fouthValue$important;";
        }
      }
    } else if ($firstAttr && $secondAttr && !$thirdAttr && !$fouthAttr) {
      if (
        ($firstValue || $firstValue === 0) &&
        ($secondValue || $secondValue === 0)
      ) {
        if (
          $firstValue === $secondValue
        ) {
          $theResults .= "$unitedAttr:$firstValue$important;";
        } else {
          $theResults .= "$unitedAttr:$firstValue $secondValue$important;";
        }
      } else {
        if (($firstValue || $firstValue === 0) && $firstAttr) {
          $theResults .= "$firstAttr:$firstValue$important;";
        }
        if (($secondValue || $secondValue === 0) && $secondAttr) {
          $theResults .= "$secondAttr:$secondValue$important;";
        }
      }
    }
  }
  return $theResults;
}

function generateColorStyles($setting, $globalColor, $property)
{
  $theStyles = '';
  if (!$setting) return $theStyles;
  $theField = $setting['field'];
  $important = isset($setting['important']) && $setting['important'] ? '!important' : '';
  if ($theField === 'global') {
    $theValue = $setting['global'];
    $theStyles .= "$property:var(--$theValue)$important;";
  } else if ($theField === 'custom') {
    $theValue = $setting['custom'];
    $theValue = explode('@&', $theValue)[0];
    $theStyles .= "$property:$theValue$important;";
  }
  return $theStyles;
}
