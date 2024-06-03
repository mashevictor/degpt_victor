<?php

function generateResetElements($preSelector)
{
  $stylesReg = '';
  $stylesReg .= "{$preSelector}*{position:relative;margin:0;overflow-wrap:break-word;}";
  $stylesReg .= "{$preSelector}*,{$preSelector}*::before,{$preSelector}*::after{box-sizing:border-box;}";
  // $stylesReg .= "{$preSelector}p,{$preSelector}button,{$preSelector}cite,{$preSelector}ul,{$preSelector}ol,{$preSelector}li,{$preSelector}pre,{$preSelector}code,{$preSelector}td{margin:0;font-family:inherit;font-size:inherit;line-height:inherit;letter-spacing:inherit;word-spacing:inherit;font-weight:inherit;font-style:inherit;color:inherit;}";
  $stylesReg .= "{$preSelector}ul,{$preSelector}ol,{$preSelector}li{margin:0;-webkit-padding-start:0;padding-inline-start:0;list-style:none;}";
  $stylesReg .= "{$preSelector}blockquote{margin:0;}{$preSelector}figure{margin:0;}{$preSelector}img{display:block;max-width:100%;height:auto;}{$preSelector}cite{font-size:.7em;}";
  $stylesReg .= "{$preSelector}button{background:none;border:none;border-radius:0;outline:0;-webkit-appearance:none;}";
  return $stylesReg;
}
