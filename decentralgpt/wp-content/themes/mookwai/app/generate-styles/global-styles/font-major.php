<?php

function generateFontMajor($preSelector, $setting)
{
  $reg = '';
  $regP = '';
  if ($preSelector) return [
    'reg' => $reg,
    'regP' => $regP,
  ];
  foreach ($setting as $item) {
    $reg .= generateMajorRes($item, false);
    $regP .= generateMajorRes($item, true);
  }
  return [
    'reg' => $reg,
    'regP' => $regP,
  ];
}

function generateMajorRes($setting, $isPortrait)
{
  $slug = $setting['slug'];
  $ratio = 1;
  if ($isPortrait) {
    $ratio = $setting['ratio'];
  }
  $theStyles = '';
  $fontSizeValue = $setting['fontSize'];
  $lineHeightValue = $setting['lineHeight'];
  $letterSpacingValue = $setting['letterSpacing'];
  $wordSpacingValue = $setting['wordSpacing'];
  $fontSize = round($fontSizeValue * $ratio / 1440 * 100000) / 1000 . 'vw';
  $lineHeight = round($lineHeightValue / $fontSizeValue * 1000) / 1000 . 'em';
  $letterSpacing = round($letterSpacingValue / $fontSizeValue * 1000) / 1000 . 'em';
  $wordSpacing = round($wordSpacingValue / $fontSizeValue * 1000) / 1000 . 'em';
  $theStyles .= "--$slug-s:$fontSize;";
  if (!$isPortrait) {
    $theStyles .= "--$slug-h:$lineHeight;";
    $theStyles .= "--$slug-l:$letterSpacing;";
    $theStyles .= "--$slug-w:$wordSpacing;";
  }
  return $theStyles;
}
