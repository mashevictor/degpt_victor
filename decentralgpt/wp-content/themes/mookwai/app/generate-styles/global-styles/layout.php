<?php

function GlobalStylesLayout($setting, $preSelector)
{
  $regStyles = '';
  $lgStyles = '';
  $xlStyles = '';
  $xxlStyles = '';
  $regPStyles = '';
  $mdStyles = '';
  $mdpStyles = '';
  $miniStyles = '';
  $smStyles = '';
  $smlStyles = '';
  $smpStyles = '';

  if ($preSelector) $regStyles .= '.mk-container.mkd-empty .el-row{display:flex!important;}';
  if ($preSelector) return [
    'reg' => $regStyles,
    'lg' => $lgStyles,
    'xl' => $xlStyles,
    'xxl' => $xxlStyles,
    'mini' => $miniStyles,
    'regP' => $regPStyles,
    'md' => $mdStyles,
    'mdp' => $mdpStyles,
    'sm' => $smStyles,
    'sml' => $smlStyles,
    'smp' => $smpStyles,
  ];
  foreach ($setting as $container) {
    $containerSlug = $container['slug'];
    // Container
    $containerReg = '';
    $containerLg = '';
    $containerMini = '';
    $containerSmp = '';
    if ($container['align'] === 'center') {
      $containerReg .= 'margin-right:auto;margin-left:auto;';
    } else if ($container['align'] === 'right') {
      $containerReg .= 'margin-right:0;margin-left:auto;';
    }
    $sideSpaceReg = null;
    $sideSpaceLg = null;
    $sideSpaceMini = null;
    $sideSpacePhone = null;
    $maxWidth = null;
    if ($container['side'] && $container['side'] !== '0') {
      $getSide = $container['side'];
      if ($container['sideScale']) {
        $sideSpaceReg = round($getSide / 1440 * 100000) / 1000 . '%';
        $sideSpaceMini = round($getSide / 1440 * 900000) / 1000 . 'px';
        if (!$container['sideScaleOnLarge']) {
          $sideSpaceLg = round($getSide / 1440 * 1600000) / 1000 . 'px';
        }
      } else {
        $sideSpaceReg = $getSide . 'px';
      }
    }
    if ($container['maxWidth'] && $container['maxWidth'] !== 'auto') {
      $maxWidth = $container['maxWidth'] . 'px';
      if ($sideSpaceReg) {
        $maxWidth = "calc($maxWidth + var(--containerSide) * 2)";
      }
    }
    if ($container['phoneSide'] || $container['phoneSide'] === 0 || $container['phoneSide'] === '0') {
      $getPhoneSide = $container['phoneSide'];
      if ($container['phoneSide'] !== '0') {
        $sideSpacePhone = $getPhoneSide . 'px';
      } else {
        $sideSpacePhone = '0';
      }
    }
    if ($sideSpaceReg) {
      $containerReg .= "--containerSide:$sideSpaceReg;padding:0 var(--containerSide);";
    } else {
      $containerReg .= 'padding:0;';
    }
    if ($maxWidth) {
      $containerReg .= "max-width:$maxWidth;";
    }
    if ($sideSpaceLg) $containerLg .= "--containerSide:$sideSpaceLg;";
    if ($sideSpaceMini)
      $containerMini .= "--containerSide:$sideSpaceMini;";
    if ($sideSpacePhone || $sideSpacePhone === '0')
      $containerSmp .= "--containerSide:$sideSpacePhone;";
    if (!$sideSpaceReg) $containerSmp .= "padding:0 var(--containerSide);";

    if ($containerReg)
      $containerReg = ".$containerSlug{{$containerReg}}";
    if ($containerLg)
      $containerLg = ".$containerSlug{{$containerLg}}";
    if ($containerMini)
      $containerMini = ".$containerSlug{{$containerMini}}";
    if ($containerSmp)
      $containerSmp = ".$containerSlug{{$containerSmp}}";
    $regStyles .= $containerReg;
    $lgStyles .= $containerLg;
    $miniStyles .= $containerMini;
    $smpStyles .= $containerSmp;

    // Row
    $rowReg = '';
    $rowLg = '';
    $rowXl = '';
    $rowXxl = '';
    $rowMini = '';
    $rowRegP = '';
    $rowMd = '';
    $rowMdp = '';
    $rowSm = '';
    $rowSml = '';
    $rowSmp = '';
    $columnGapReg = null;
    $columnGapLg = null;
    $columnGapMini = null;
    $columnGapSm = null;
    $rowGapReg = null;
    $rowGapLg = null;
    $rowGapMini = null;
    $rowGapSm = null;
    if ($container['gapL'] && $container['gapL'] !== '0') {
      $getGapL = $container['gapL'];
      if ($container['gapScale']) {
        $columnGapReg = round($getGapL / 1440  * 100000) / 1000 . 'vw';
        $columnGapMini = round($getGapL / 1440  * 900000) / 1000 . 'px';
        if (!$container['gapScaleOnLarge']) {
          $columnGapLg = round($getGapL / 1440  * 1600000) / 1000 . 'px';
        }
      } else {
        $columnGapReg = $getGapL . 'px';
      }
    }
    if ($container['phoneGapL']) {
      if ($container['phoneGapL'] !== '0') {
        $columnGapSm = $container['phoneGapL'] . 'px';
      } else {
        $columnGapSm = '0';
      }
    }
    if ($container['gapP'] && $container['gapP'] !== '0') {
      $getGapP = $container['gapP'];
      if ($container['gapScale']) {
        $rowGapReg = round($getGapP / 1440  * 100000) / 1000 . 'vw';
        $rowGapMini = round($getGapP / 1440  * 900000) / 1000 . 'px';
        if (!$container['gapScaleOnLarge']) {
          $rowGapLg = round($getGapP / 1440  * 1600000) / 1000 . 'px';
        }
      } else {
        $rowGapReg = $getGapP . 'px';
      }
    }
    if ($container['phoneGapP']) {
      if ($container['phoneGapP'] !== '0') {
        $rowGapSm = $container['phoneGapP'] . 'px';
      } else {
        $rowGapSm = '0';
      }
    }

    if ($container['type'] === 'column') {
      $getColumns = $container['columns'];
      $getPhoneColumns = $container['phoneColumns'];
      $rowReg .= 'display:grid;';
      $rowReg .= "--columnCount:$getColumns;";
      $rowSmp .= "--columnCount:$getPhoneColumns;";
      $rowReg .= "--columnGap:$columnGapReg;--rowGap:$rowGapReg;";
      if ($columnGapLg) $rowLg .= "--columnGap:$columnGapLg;";
      if ($columnGapMini) $rowMini .= "--columnGap:$columnGapMini;";
      if ($columnGapSm) $rowSm .= "--columnGap:$columnGapSm;";
      if ($rowGapLg) $rowLg .= "--rowGap:$rowGapLg;";
      if ($rowGapMini) $rowMini .= "--rowGap:$rowGapMini;";
      if ($rowGapSm) $rowSm .= "--rowGap:$rowGapSm;";
      $rowReg .= "grid-template-columns:repeat(var(--columnCount),1fr);";
      $rowReg .= 'column-gap:var(--columnGap);row-gap:var(--rowGap);';
    } else if ($container['type'] === 'grid') {
      $getColumns = $container['columns'];
      $getPhoneColumns = $container['phoneColumns'];
      $rowReg .= 'display:grid;';
      $rowReg .= "--columnCount:$getColumns;";
      $rowSml .= "--columnCount:$getPhoneColumns;";
      $rowSmp .= "--columnCount:$getPhoneColumns;";
      $rowReg .= "--columnGap:$columnGapReg;--rowGap:$rowGapReg;";
      if ($columnGapLg) $rowLg .= "--columnGap:$columnGapLg;";
      if ($columnGapMini) $rowMini .= "--columnGap:$columnGapMini;";
      if ($columnGapSm) $rowSm .= "--columnGap:$columnGapSm;";
      if ($rowGapLg) $rowLg .= "--rowGap:$rowGapLg;";
      if ($rowGapMini) $rowMini .= "--rowGap:$rowGapMini;";
      if ($rowGapSm) $rowSm .= "--rowGap:$rowGapSm;";
      $rowHeightReg = 'auto';
      $rowHeightLg = null;
      $rowHeightMini = null;
      $rowHeightSm = null;
      if ((isset($container['height']) && $container['height']) && (isset($container['height']) && $container['height'] !== '0')) {
        $getHeight = $container['height'];
        if ($getHeight !== 'auto' && $getHeight !== '1fr') {
          $rowHeightReg = round($getHeight / 1440  * 100000) / 1000 . 'vw';
          $rowHeightLg = round($getHeight / 1440  * 1600000) / 1000 . 'px';
          $rowHeightMini = round($getHeight / 1440  * 900000) / 1000 . 'px';
        } else {
          $rowHeightReg = $getHeight;
        }
      }
      if (isset($container['phoneHeight']) && $container['phoneHeight']) {
        if ($container['phoneHeight'] !== '0') {
          if ($container['phoneHeight'] !== 'auto' && $container['phoneHeight'] !== '1fr') {
            $rowHeightSm = $container['phoneHeight'] . 'px';
          } else {
            $rowHeightSm = $container['phoneHeight'];
          }
        } else {
          $rowHeightSm = '0';
        }
      }
      $rowReg .= "--rowHeight:$rowHeightReg;";
      if ($rowHeightLg) $rowLg .= "--rowHeight:$rowHeightLg;";
      if ($rowHeightMini) $rowMini .= "--rowHeight:$rowHeightMini;";
      if ($rowHeightSm) $rowSm .= "--rowHeight:$rowHeightSm;";
      $rowReg .= 'grid-template-columns:repeat(var(--columnCount),1fr);grid-auto-rows:var(--rowHeight);';
      $rowReg .= 'column-gap:var(--columnGap);row-gap:var(--rowGap);';
    } else if ($container['type'] === 'cell') {
      $rowReg .= 'display:grid;';
      $cellWidthReg = $container['cellWidth'] . 'px';
      $rowReg .= "--cellWidth:$cellWidthReg;";
      if ((isset($container['cellWidthMd']) && $container['cellWidthMd']) || (isset($container['cellWidthMd']) && $container['cellWidthMd'] === 0)) {
        $cellWidthMd = $container['cellWidthMd'] . 'px';
        if ($container['cellWidthMd'] === '0') {
          $cellWidthMd = '100%';
        }
        $rowMd .= "--cellWidth:$cellWidthMd;";
      }
      if (isset($container['cellWidthPhone'])) {
        $cellWidthSmp = $container['cellWidthPhone'] . 'px';
        if ($container['cellWidthPhone'] === '0' || $container['cellWidthPhone'] === 0) {
          $cellWidthSmp = '100%';
        }
        $rowSmp .= "--cellWidth:$cellWidthSmp;";
      }
      $rowReg .= "grid-template-columns:repeat(auto-fill,minmax(var(--cellWidth),1fr));";
      $rowReg .= "--columnGap:{$columnGapReg};--rowGap:{$rowGapReg};column-gap:var(--columnGap);row-gap:var(--rowGap);";
      if ($columnGapLg) $rowLg .= "--columnGap:$columnGapLg;";
      if ($columnGapMini) $rowMini .= "--columnGap:$columnGapMini;";
      if ($columnGapSm) $rowSm .= "--columnGap:$columnGapSm;";
      if ($rowGapLg) $rowLg .= "--rowGap:$rowGapLg;";
      if ($rowGapMini) $rowMini .= "--rowGap:$rowGapMini;";
      if ($rowGapSm) $rowSm .= "--rowGap:$rowGapSm;";
      $rowReg .= 'grid-auto-flow:row dense;';
    } else if ($container['type'] === 'flex') {
      $rowReg .= 'display:flex;';
      $rowReg .= "--columnGap:{$columnGapReg};column-gap:var(--columnGap);--rowGap:{$rowGapReg};row-gap:var(--rowGap);";
      if ($columnGapLg) $rowLg .= "--columnGap:$columnGapLg;";
      if ($columnGapMini) $rowMini .= "--columnGap:$columnGapMini;";
      if ($columnGapSm) $rowSm .= "--columnGap:$columnGapSm;";
      if ($rowGapLg) $rowLg .= "--rowGap:$rowGapLg;";
      if ($rowGapMini) $rowMini .= "--rowGap:$rowGapMini;";
      if ($rowGapSm) $rowSm .= "--rowGap:$rowGapSm;";
      if ($container['flexDirection']) {
        $getValue = $container['flexDirection'];
        $rowReg .= "flex-direction:{$getValue};";
      }
      if ($container['justifyContent']) {
        $getValue = $container['justifyContent'];
        $rowReg .= "justify-content:{$getValue};";
      }
      if ($container['alignItems']) {
        $getValue = $container['alignItems'];
        $rowReg .= "align-items:{$getValue};";
      }
      if ($container['flexWrap']) {
        $getValue = $container['flexWrap'];
        $rowReg .= "flex-wrap:{$getValue};";
      }
    }
    if ($rowReg) $rowReg = ".$containerSlug .el-row{{$rowReg}}";
    // if ($container['type'] === 'grid' && !$container['height']) {
    //   $rowReg .= ".$containerSlug .el-row::before{content:'';width:0;padding-bottom:100%;grid-row:1/1;grid-column:1/1;}";
    //   $rowReg .= ".$containerSlug .el-row>.mk-column:first-of-type{grid-row:1/1;grid-column:1/1;}";
    // }

    if ($rowLg) $rowLg = ".$containerSlug .el-row{{$rowLg}}";
    if ($rowMini) $rowMini = ".$containerSlug .el-row{{$rowMini}}";
    if ($rowMd) $rowMd = ".$containerSlug .el-row{{$rowMd}}";
    if ($rowSm) $rowSm = ".$containerSlug .el-row{{$rowSm}}";
    if ($rowSml) $rowSml = ".$containerSlug .el-row{{$rowSml}}";
    if ($rowSmp) $rowSmp = ".$containerSlug .el-row{{$rowSmp}}";

    if ($container['type'] === 'column' || $container['type'] === 'grid') {
      $selector = ".$containerSlug .el-row";
      $rowReg .= generateLayoutColumn($container['columns'], $selector, null);
      $rowLg .= generateLayoutColumn($container['columns'], $selector, '-lg');
      $rowXl .= generateLayoutColumn($container['columns'], $selector, '-xl');
      $rowXxl .= generateLayoutColumn($container['columns'], $selector, '-xxl');
      $rowRegP .= generateLayoutColumn($container['columns'], $selector, '-p');
      $rowMd .= generateLayoutColumn($container['columns'], $selector, '-md');
      $rowMdp .= generateLayoutColumn($container['columns'], $selector, '-mdp');
      $rowSml .= generateLayoutColumn($container['columns'], $selector, '-sml');
      $rowSmp .= generateLayoutColumn($container['columns'], $selector, '-smp');
      if ($container['type'] === 'grid') {
        $rowReg .= generateLayoutRow($selector, null);
        $rowLg .= generateLayoutRow($selector, '-lg');
        $rowXl .= generateLayoutRow($selector, '-xl');
        $rowXxl .= generateLayoutRow($selector, '-xxl');
        $rowRegP .= generateLayoutRow($selector, '-p');
        $rowMd .= generateLayoutRow($selector, '-md');
        $rowMdp .= generateLayoutRow($selector, '-mdp');
        $rowSml .= generateLayoutRow($selector, '-sml');
        $rowSmp .= generateLayoutRow($selector, '-smp');
      }
    } else if ($container['type'] === 'flex') {
      $selector = ".$containerSlug .mk-column";
      $rowReg .= "$selector{flex:1 0 0%;width:auto;}";
    }

    $regStyles .= $rowReg;
    $lgStyles .= $rowLg;
    $xlStyles .= $rowXl;
    $xxlStyles .= $rowXxl;
    $regPStyles .= $rowRegP;
    $miniStyles .= $rowMini;
    $mdStyles .= $rowMd;
    $mdpStyles .= $rowMdp;
    $smStyles .= $rowSm;
    $smlStyles .= $rowSml;
    $smpStyles .= $rowSmp;
  }
  return [
    'reg' => $regStyles,
    'lg' => $lgStyles,
    'xl' => $xlStyles,
    'xxl' => $xxlStyles,
    'mini' => $miniStyles,
    'regP' => $regPStyles,
    'md' => $mdStyles,
    'mdp' => $mdpStyles,
    'sm' => $smStyles,
    'sml' => $smlStyles,
    'smp' => $smpStyles,
  ];
}

function generateLayoutColumn($columns, $selector, $res)
{
  $colNum = intval($columns);
  $suffix = '';
  if ($res) $suffix = $res;
  $theStyles = '';
  while ($colNum > 0) {
    $offset = $colNum - 1;
    $to = $colNum + 1;
    $theStyles .= "$selector .mk-col-{$colNum}{$suffix}{grid-column-end:span $colNum;}";
    $theStyles .= "$selector .mk-col-offset-{$offset}{$suffix}{grid-column-start:$colNum;}";
    $theStyles .= "$selector .mk-col-extend-{$colNum}{$suffix}{grid-column-end:$to;}";
    $colNum--;
  }
  return $theStyles;
}

function generateLayoutRow($selector, $res)
{
  $rowNum = 4;
  $suffix = '';
  if ($res) $suffix = $res;
  $theStyles = '';
  while ($rowNum > 0) {
    $offset = $rowNum - 1;
    $to = $rowNum + 1;
    $theStyles .= "$selector .mk-row-{$rowNum}{$suffix}{grid-row-end:span $rowNum;}";
    $theStyles .= "$selector .mk-row-offset-{$offset}{$suffix}{grid-row-start:$rowNum;}";
    $theStyles .= "$selector .mk-row-extend-{$rowNum}{$suffix}{grid-row-end:$to;}";
    $rowNum--;
  }
  return $theStyles;
}
