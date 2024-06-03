<?php

function globalCommonStyles($setting, $property, $attr, $isImportant)
{
  $getSetting = isset($setting[$property]) ? $setting[$property] : null;
  if (!$getSetting) return;
  $important = '';
  if ($isImportant) $important = '!important';
  $theStyles = "$attr:{$getSetting}{$important};";
  return $theStyles;
}

function globalColorStyles($setting, $property, $attr, $isImportant)
{
  $getSetting = isset($setting[$property]) ? $setting[$property] : null;
  if (!$getSetting) return;
  $important = '';
  if ($isImportant) $important = '!important';
  $theStyles = "$attr:var(--$getSetting)$important;";
  return $theStyles;
}

function globalSpacingStyles($setting, $property, $attr, $isImportant)
{
  $getSetting = isset($setting[$property]) ? $setting[$property] : null;
  if (!$getSetting) return;
  $important = '';
  if ($isImportant) $important = '!important';
  $theStyles = "$attr:var(--$getSetting)$important;";
  return $theStyles;
}

function globalFontSpecStyles($setting, $property)
{
  $fontSpec = isset($setting[$property]) ? $setting[$property] : null;
  if (!$fontSpec) return;
  $theStyles = '';
  $theStyles .= "font-size:var(--$fontSpec-s);";
  $theStyles .= "line-height:var(--$fontSpec-h);";
  $theStyles .= "letter-spacing:var(--$fontSpec-l);";
  $theStyles .= "word-spacing:var(--$fontSpec-w);";
  return $theStyles;
}

function globalFontFamilyStyles($setting, $property)
{
  $fontFamily = isset($setting[$property]) ? $setting[$property] : null;
  if (!$fontFamily || !count($fontFamily)) return;
  $theStyles = 'font-family:';
  for ($i = 0; $i < count($fontFamily); $i++) {
    $isHasSpace = str_contains($fontFamily[$i], ' ');
    $value = $fontFamily[$i];
    if ($i < count($fontFamily) - 1) {
      if ($isHasSpace) {
        $theStyles .= "'$value',";
      } else {
        $theStyles .= "$value,";
      }
    } else {
      if ($isHasSpace) {
        $theStyles .= "'$value'";
      } else {
        $theStyles .= "$value";
      }
    }
  }
  $theStyles .= ';';
  return $theStyles;
}

function globalDecorationStyles($setting, $preFix)
{
  $textDecorationName = 'textDecoration';
  $textDecorationStyleName = 'textDecorationStyle';
  $textDecorationThicknessName = 'textDecorationThickness';
  $textUnderlineOffsetName = 'textUnderlineOffset';
  $textDecorationSkipInkName = 'textDecorationSkipInk';
  if ($preFix) {
    $textDecorationName = "{$preFix}TextDecoration";
    $textDecorationStyleName = "{$preFix}TextDecorationStyle";
    $textDecorationThicknessName = "{$preFix}TextDecorationThickness";
    $textUnderlineOffsetName = "{$preFix}TextUnderlineOffset";
    $textDecorationSkipInkName = "{$preFix}TextDecorationSkipInk";
  }
  $textDecoration = isset($setting[$textDecorationName]) ? $setting[$textDecorationName] : null;
  if (!$textDecoration) return;
  $theStyles = '';
  $theStyles .= "text-decoration:$textDecoration;";
  $theStyles .= globalCommonStyles($setting, $textDecorationStyleName, 'text-decoration-style', false);
  $theStyles .= globalSizingStyles($setting, $textDecorationThicknessName, 'text-decoration-thickness', false);
  $theStyles .= globalSizingStyles($setting, $textUnderlineOffsetName, 'text-underline-offset', false);
  $textDecorationSkipInk = isset($setting[$textDecorationSkipInkName]) ? $setting[$textDecorationSkipInkName] : null;
  if ($textDecorationSkipInk === false) {
    $theStyles .= 'text-decoration-skip-ink:none;';
  }
  return $theStyles;
}

function globalSizingStyles($setting, $property, $attr, $isImportant)
{
  if (!$setting) return;
  $getSetting = isset($setting[$property]) ? $setting[$property] : null;
  if (!$getSetting) return;
  $important = '';
  if ($isImportant) $important = '!important';
  $value = explode('@&', $getSetting);
  $value = implode('', $value);
  $theStyles = "$attr:{$value}{$important};";
  return $theStyles;
}
