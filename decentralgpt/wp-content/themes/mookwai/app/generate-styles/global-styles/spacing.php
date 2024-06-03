<?php

function GlobalStylesSpacing($preSelector, $datas)
{
  $stylesReg = '';
  $stylesLg = '';
  $stylesMini = '';
  if ($preSelector) return [
    'reg' => $stylesReg,
    'lg' => $stylesLg,
    'mini' => $stylesMini,
  ];
  foreach ($datas as $item) {
    $slug = $item['slug'];
    $value = $item['value'];
    $valueReg = round($value / 1440 * 100000) / 1000 . 'vw';
    $valueLg = round($value / 1440 * 1600000) / 1000 . 'px';
    $valueMini = round($value / 1440 * 900000) / 1000 . 'px';
    $stylesReg .= "--$slug:$valueReg;";
    $stylesLg .= "--$slug:$valueLg;";
    $stylesMini .= "--$slug:$valueMini;";
  }
  return [
    'reg' => $stylesReg,
    'lg' => $stylesLg,
    'mini' => $stylesMini,
  ];
}
