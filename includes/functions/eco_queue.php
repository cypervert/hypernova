<?php

function eco_que_process($user, &$planet, $time_left)
{
  global $sn_data, $lang;

  $que_types = $sn_data['groups']['ques'];
  foreach($que_types as $que_type_id => &$que_type_data)
  {
    $que_type_data['time_left'] = $time_left;
  }
  $que_types[QUE_STRUCTURES]['unit_list'] = $sn_data['groups']['build_allow'][$planet['planet_type']];

  $que = array();
  $built = array();
  $in_que = array();
  $in_que_abs = array();
  $xp = array();
  $query_string = '';
  $query = '';

  if($planet['que'])
  {
    $que_strings = explode(';', $planet['que']);
    foreach($que_strings as $que_item_string)
    {
      // skipping empty que lines
      if(!$que_item_string)
      {
        continue;
      }

      $que_item = explode(',', $que_item_string);

      // Skipping invalid negative values for time and unit_amount
      if($que_item[2] < 1 || $que_item[1] < 1)
      {
        continue;
      }

      $unit_id      = $que_item[0];
      $que_item = array(
        'ID'     => $que_item[0], // unit ID
        'AMOUNT' => $que_item[1], // unit amount
        'TIME'   => $que_item[2], // build time left (in seconds)
        'MODE'   => $que_item[3], // build/destroy
        'QUE'    => $que_item[4], // que ID
        'NAME'   => $lang['tech'][$unit_id],
        'STRING' => "{$que_item_string};",
      );

      $unit_data    = $sn_data[$unit_id];
      $unit_db_name = $unit_data['name'];

      $que_id    = $que_item['QUE'];
      $que_data  = &$que_types[$que_id];
      $unit_list = $que_data['unit_list'];

      if(!in_array($unit_id, $unit_list))
      {
        // Unit is in wrong que. It can't happens in normal circuimctances - hacked?
        // We will not proceed such units
        continue;
      }

      $time_left = $que_data['time_left'];

      $change = 0;
      switch($que_item['MODE'])
      {
        case BUILD_CREATE:
          $change = +1;
        break;

        case BUILD_DESTROY:
          $change = -1;
        break;
      }
      $item_change = $change * $que_item['AMOUNT'];

      $que_item['LEVEL'] = ($planet[$unit_db_name] ? $planet[$unit_db_name] : 0) + $item_change + $in_que[$unit_id];

      if($time_left === 0)
      {
        // There is no time left in this que. Skipping
        $query_string .= $que_item['STRING'];
        $que_amounts[$que_id] += $item_change;
        $in_que[$unit_id] += $item_change;
        $in_que_abs[$unit_id] += $que_item['AMOUNT'];
        $que[$que_id][] = $que_item;

        continue;
      }

      // $build_price = eco_get_unit_price($user, $planet, $unit_id, $que_item['MODE']);

      $build_time = $que_item['TIME'];
      $amount_to_build = min($que_item['AMOUNT'], floor($time_left / $build_time));
      if($amount_to_build)
      {
        $time_left -= $amount_to_build * $build_time;

        $que_item['AMOUNT'] -= $amount_to_build;

        $amount_to_build *= $change;
        $built[$unit_id] += $amount_to_build;

        if(in_array($unit_id, $sn_data['groups']['structures']))
        {
          $unit_level = ($planet[$unit_db_name] ? $planet[$unit_db_name] : 0) + $que['in_que'][$unit_id];
          $build_data = eco_get_build_data($user, $planet, $unit_id, $unit_level);
          $build_data = $build_data[$que_item['MODE']];
          $xp_incoming = 0;
          foreach($sn_data['groups']['resources_loot'] as $resource_id)
          {
            $xp_incoming += $build_data[$resource_id] * $amount_to_build;
          }

          $xp[RPG_STRUCTURE] += round(($xp_incoming > 0 ? $xp_incoming : 0)/1000);
        }

        $planet[$unit_db_name] += $amount_to_build;
        $query .= "`{$unit_db_name}` = `{$unit_db_name}` + '{$amount_to_build}',";
      }

      if($que_item['AMOUNT'] > 0)
      {
        $que_item['TIME'] -= $time_left;
        $time_left = 0;
        $que_item['STRING'] = "{$unit_id},{$que_item['AMOUNT']},{$que_item['TIME']},{$que_item['MODE']},{$que_item['QUE']};";
        $que_amounts[$que_id] += $item_change;
        $in_que[$unit_id] += $item_change;
        $in_que_abs[$unit_id] += $que_item['AMOUNT'];

        $query_string .= $que_item['STRING'];
        $que[$que_id][] = $que_item;
      }

      // now placing rest of time back to que element
      $que_data['time_left'] = $time_left;
    }
  }

  $planet['que'] = $query_string;
  $query .= "`que` = '{$query_string}'";

  return array(
    'que'     => $que,
    'built'   => $built,
    'xp'      => $xp,
    'amounts' => $que_amounts,
    'in_que'  => $in_que,
    'in_que_abs'  => $in_que_abs,
    'string'  => $query_string,
    'query'   => $query,
  );
}

function eco_que_add($user, &$planet, $que, $unit_id, $que_id, $unit_amount = 1, $build_mode = BUILD_CREATE)
{
  global $lang, $resource, $time_now, $sn_data;

  if (!eco_can_build_unit($user, $planet, $unit_id))
  {
    return $que;
  }

  if (eco_unit_busy($user, $planet, $que, $unit_id))
  {
    return $que;
  }

  $que_types = $sn_data['groups']['ques'];
  $que_types[QUE_STRUCTURES]['unit_list'] = $sn_data['groups']['build_allow'][$planet['planet_type']];

  $unit_list = false;
  foreach($que_types as $que_id => $que_data)
  {
    if(in_array($unit_id, $que_data['unit_list']))
    {
      $unit_list   = $que_data['unit_list'];
      break;
    }
  }

  if($unit_list === false)
  {
    // This is not queable item. Remove it from que
    return $que;
  }

  // Check if que is full
  if(count($que['que'][$que_id]) >= $que_types[$que_id]['length'])
  {
    return $que;
  }

  // We do not work with negaitve unit_amounts - hack or cheat
  if($unit_amount < 1)
  {
    return $que;
  }

  if($que === false)
  {
    $que = array();
  }

  switch($build_mode)
  {
    case BUILD_CREATE:
      $change = +1;
    break;

    case BUILD_DESTROY:
      $change = -1;
    break;
  }

  $unit_db_name = $sn_data[$unit_id]['name'];

  $unit_level = ($planet[$unit_db_name] ? $planet[$unit_db_name] : 0) + $que['in_que'][$unit_id];
  $build_data = eco_get_build_data($user, $planet, $unit_id, $unit_level);
  $unit_level += $change * $unit_amount;
  if($build_data['CAN'][$build_mode] >= $unit_amount && $unit_level >= 0)
  {
    $unit_time       = $build_data[$build_mode][RES_TIME];
    $que_item_string = "{$unit_id},{$unit_amount},{$unit_time},{$build_mode},{$que_id};";

    $que['que'][$que_id][] = array(
        'ID'     => $unit_id, // unit ID
        'AMOUNT' => $unit_amount, // unit amount
        'TIME'   => $unit_time, // build time left (in seconds)
        'MODE'   => $build_mode, // build/destroy
        'NAME'   => $lang['tech'][$unit_id],
        'QUE'    => $que_id, // que ID
        'STRING' => $que_item_string,
        'LEVEL'  => $unit_level
    );
    $que['in_que'][$unit_id] += $unit_amount * $change;
    $que['in_que_abs'][$unit_id] += $unit_amount;
    $que['amounts'][$que_id] += $unit_amount * $change;
    $que['string'] .= $que_item_string;
    $que['query'] = "`que` = '{$que['string']}'";

    $planet['que'] = $que['string'];
    foreach($sn_data['groups']['resources_loot'] as $resource_id)
    {
      $resource_db_name = $sn_data[$resource_id]['name'];
      $resource_change = $build_data[$build_mode][$resource_id] * $unit_amount;

      $planet[$resource_db_name] -= $resource_change;

      $que['query'] = "`$resource_db_name` = `$resource_db_name` - '{$resource_change}',{$que['query']}";
    }
    doquery("UPDATE {{planets}} SET {$que['query']} WHERE `id` = '{$planet['id']}' LIMIT 1;");
  }

  return $que;
}

function eco_que_trim($user, &$planet, &$que, $que_id, $que_item, &$resource_change)
{
  global $sn_data;

  $sn_data_groups_resources_loot = $sn_data['groups']['resources_loot'];

  $unit_id = $que_item['ID'];
  $build_mode = $que_item['MODE'];

  $build_data = eco_get_build_data($user, $planet, $unit_id, $que_item['LEVEL'] - $build_mode);

  $unit_amount = $que_item['AMOUNT'];
  foreach($sn_data_groups_resources_loot as $resource_id)
  {
    $resource_change[$resource_id] += $build_data[$build_mode][$resource_id] * $unit_amount;
  }
  $que['in_que'][$unit_id] -= $build_mode * $unit_amount;
  $que['in_que_abs'][$unit_id] -= $unit_amount;
  $que['amounts'][$que_id] -= $build_mode * $unit_amount;
}

function eco_que_clear($user, &$planet, $que, $que_id, $only_one = false)
{
  global $sn_data;

  $que_string = '';

//pdump($que,'Before');

  foreach($que['que'] as $que_data_id => &$que_data)
  {

    if($que_data_id == $que_id)
    {
      // This que is those we want to clear
      // ADD CHECK FOR CLEAREBILITY!
      if($only_one)
      {
        if (count($que_data) > 0)
        {
          eco_que_trim($user, &$planet, $que, $que_id, $que_data[count($que_data) - 1], &$resource_change);
          unset($que_data[count($que_data) - 1]);
        }
      }
      else
      {
        foreach($que_data as $que_item)
        {
          eco_que_trim($user, &$planet, $que, $que_id, $que_item, &$resource_change);
        }
        $que['que'][$que_id] = array();
      }

      foreach($que_data as $que_item)
      {
        $que_string .= $que_item['STRING'];
      }

      $que_query = '';
      foreach($resource_change as $resource_id => $resource_amount)
      {
        $resource_db_name = $sn_data[$resource_id]['name'];
        $planet[$resource_db_name] += $resource_amount;

        $que_query .= "`$resource_db_name` = `$resource_db_name` + '{$resource_amount}', ";
      }
      $que_query = "{$que_query}`que` = '{$que_string}'";
      $que['string'] = $que_string;
      $que['query'] = $que_query;
      $planet['que'] = $que_string;

//pdump($que,'Before update');
//die();
    }
    else
    {
      // This que just passed by
      foreach($que_data as $que_item)
      {
        $que_string .= $que_item['STRING'];
      }
    }
  }
//pdump($que,'After');
//die();
  doquery("UPDATE {{planets}} SET {$que['query']} WHERE `id` = '{$planet['id']}' LIMIT 1;");

  return $que;
}

?>