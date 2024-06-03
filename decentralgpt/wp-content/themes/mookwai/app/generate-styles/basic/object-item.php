<?php

function objectItemStyles($setting)
{
  // Initialize
  $theStyles = '';
  $foundFit = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'objectFit':
        $foundFit = $item;
        break;
    }
  }

  // Generate
  $theStyles .= generateCommonStyles($foundFit, 'object-fit', false);

  return $theStyles;
}
