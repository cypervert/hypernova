<?php

$page_title .= ' - ' . $lang["eco_mrk_{$submode}"];

if(is_array($shipList))
{
  if($user['rpg_points'] < $rpg_cost)
  {
    $intError = MARKET_NO_DM;
  }

  $message .= $lang["eco_mrk_{$submode}_ships"] . '<ul>';
  $qry = '';
  $total = array();
  foreach($shipList as $shipID => $shipCount)
  {
    if(!$shipCount)
    {
      continue;
    }

    if($shipCount < 0)
    {
      $debug->warning('Hack Attempt', 'User supplied negative ship count on Black Market page', 307);
      $intError = MARKET_NEGATIVE_SHIPS;
      break;
    }

    if($mode == MARKET_SCRAPPER)
    {
      $amount = $planetrow[$sn_data[$shipID]['name']];
    }
    elseif($mode == MARKET_STOCKMAN)
    {
      $amount = $stock[$shipID];
    }

    if($amount < $shipCount)
    {
      $intError = $error_no_stock;
      break;
    }

    if(!in_array($shipID, $sn_data['groups']['fleet']))
    {
      $debug->warning('Hack Attempt', 'User supplied non-ship unit ID on Black Market page', 306);
      $intError = MARKET_NOT_A_SHIP;
      break;
    }

    $ship_db_name = $sn_data[$shipID]['name'];
    if($mode == MARKET_SCRAPPER)
    {
      $qry .= "`{$ship_db_name}` = `{$ship_db_name}` - {$shipCount},";
      $newrow[$ship_db_name] = $planetrow[$ship_db_name] - $shipCount;
      $newstock[$shipID] += $shipCount;
    }
    elseif($mode == MARKET_STOCKMAN)
    {
      $qry .= "`{$ship_db_name}` = `{$ship_db_name}` + {$shipCount},";
      $newrow[$ship_db_name] = $planetrow[$ship_db_name] + $shipCount;
      $newstock[$shipID] -= $shipCount;
    }

    $resTemp['metal'] = floor($pricelist[$shipID]['metal'] * $shipCount * $config_rpg_scrape_metal);
    $resTemp['crystal'] = floor($pricelist[$shipID]['crystal'] * $shipCount * $config_rpg_scrape_crystal);
    $resTemp['deuterium'] = floor($pricelist[$shipID]['deuterium'] * $shipCount * $config_rpg_scrape_deuterium);

    foreach($resTemp as $resID => $resCount)
    {
      $total[$resID] += $resCount;
    }

    $message .= "<li>{$lang['tech'][$shipID]}: {$shipCount}";
  }

  if($mode == MARKET_STOCKMAN)
  {
    foreach($total as $resID => $resCount)
    {
      if($planetrow[$resID] < $resCount)
      {
        $intError = MARKET_NO_RESOURCES;
        $debug->warning('Trying to use bug in s/h market', 'S/H Ship Market', 301);
        break;
      }
    }
  }

  $intError = ($intError == MARKET_DEAL) && (array_sum($total) <= 0) ? $error_zero_res : $intError;

  if($intError == MARKET_DEAL)
  {
    $message .= "</ul>";
    {
      $message .= $lang["eco_mrk_{$submode}_res"] . '<ul>';
      foreach($total as $resID => $resCount)
      {
        if(!$resCount)
        {
          continue;
        }

        if($mode == MARKET_SCRAPPER)
        {
          $newrow[$resID] = $planetrow[$resID] + $resCount;
          $qry .= "`{$resID}` = `{$resID}` + {$resCount},";
        }
        elseif($mode == MARKET_STOCKMAN)
        {
          $newrow[$resID] = $planetrow[$resID] - $resCount;
          $qry .= "`{$resID}` = `{$resID}` - {$resCount},";
        }

        $message .= "<li>" . $lang['sys_' . $resID] . ": {$resCount}";
      }
      $message .= "</ul>";
      doquery("UPDATE {{planets}} SET {$qry}`id`=`id` WHERE `id` = {$planetrow['id']} LIMIT 1;");

      $rpg_deduct = $rpg_cost;

      $planetrow = array_merge($planetrow, $newrow);
      $stock = $newstock;

      $config->db_saveItem('eco_stockman_fleet', sys_unit_arr2str($stock));
    }
    $message = parsetemplate(gettemplate('message_body'), array('title' => $page_title, 'mes' => "<div align=left>{$message}</div>"));
  }
  else
  {
    $message = parsetemplate(gettemplate('message_body'), array('title' => $lang['eco_mrk_error_title'], 'mes' => $lang['eco_mrk_errors'][$intError]));
    foreach($shipList as $shipID => $shipCount)
    {
      $data['ships'][$shipID] = max(0, intval($shipCount));
    }
  }
}

$template = gettemplate('market_fleet', true);
$template->assign_var('rpg_cost', $rpg_cost);

if(!$array) $array = array();
foreach($array as $key => $value)
{
  if($mode == MARKET_SCRAPPER)
  {
    $shipID = $value;
    $amount = $planetrow[$sn_data[$shipID]['name']];
  }
  elseif($mode == MARKET_STOCKMAN)
  {
    $shipID = $key;
    $amount = $value;
  }

  if($amount > 0)
  {
    $template->assign_block_vars('ships', array(
      'ID'        => $shipID,
      'COUNT'     => $amount,
      'NAME'      => $lang['tech'][$shipID],
      'METAL'     => floor($pricelist[$shipID]['metal'] * $config_rpg_scrape_metal),
      'CRYSTAL'   => floor($pricelist[$shipID]['crystal'] * $config_rpg_scrape_crystal),
      'DEUTERIUM' => floor($pricelist[$shipID]['deuterium'] * $config_rpg_scrape_deuterium),
      'AMOUNT'    => intval($data['ships'][$shipID]),
    ));
  }
}

?>