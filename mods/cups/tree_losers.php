<?php
// ClanSphere 2009 - www.clansphere.net
// $Id$

# Overwrite global settings by using the following array
$cs_main = array('init_sql' => true, 'init_tpl' => false, 'init_mod' => true);

chdir('../../');

require_once 'system/core/functions.php';

cs_init($cs_main);
@error_reporting(E_ALL);

chdir('mods/cups/');

$cups_id = (int) $_GET['id'];

$cup = cs_sql_select(__FILE__, 'cups', 'cups_teams, cups_name, cups_system', 'cups_id = ' . $cups_id);
$losers = cs_sql_count(__FILE__,'cupsquads','cups_id = '.$cups_id) - $cup['cups_teams'] / 2;
// calc number of matches
$n=2;
while ( $losers >= $n ) $n *= 2;
$count_matches = $n;
$rounds = strlen(decbin($count_matches));

// Calc-Defs
include 'tree_inc.php';

$round = 0;
$run = 0;

if($losers > 1) {
  
  // select all losermatches and store in $cupmatches[rounds]
  $cupmatches = array();
  $rounds_loop = $rounds;
  $tables = 'cupmatches cm LEFT JOIN ';
  $tables .= $cup['cups_system'] == 'users' ? '{pre}_users u1 ON u1.users_id = cm.squad1_id LEFT JOIN {pre}_users u2 ON u2.users_id = cm.squad2_id' :
    '{pre}_squads sq1 ON sq1.squads_id = cm.squad1_id LEFT JOIN {pre}_squads sq2 ON sq2.squads_id = cm.squad2_id';
  $cells = $cup['cups_system'] == 'users' ? 'u1.users_nick AS team1_name, u1.users_id AS team1_id, u2.users_nick AS team2_name, u2.users_id AS team2_id' :
    'sq1.squads_name AS team1_name, cm.squad1_id AS team1_id, sq2.squads_name AS team2_name, cm.squad2_id AS team2_id';
  $cells .= ', cm.cupmatches_winner AS cupmatches_winner, cm.cupmatches_accepted1 AS cupmatches_accepted1';
  $cells .= ', cm.cupmatches_accepted2 AS cupmatches_accepted2, cm.cupmatches_tree_order AS cupmatches_tree_order';
  while ($rounds_loop > 1) {
    $where = 'cm.cups_id = ' . $cups_id . ' AND cm.cupmatches_round = '. $rounds_loop . ' AND cupmatches_loserbracket = 1';
    $temp = cs_sql_select(__FILE__, $tables, $cells, $where, 'cm.cupmatches_tree_order',0,0);
    // bringe das array in die richtige reihenfolge (nur f�r die erste runde wichtig)
    if ($rounds_loop == $rounds) {
      if (!empty($temp)) {
      foreach ($temp as $tmatch)
        $cupmatches[$rounds_loop][$tmatch['cupmatches_tree_order']] = $tmatch;
      }
      $matches_in_round = pow(2, $rounds_loop-1);
      for ($z=0; $z < $matches_in_round; $z++) {
        if (!isset($cupmatches[$rounds_loop][$z]))
          $cupmatches[$rounds_loop][$z] = FALSE;
      }
    }
    $rounds_loop--;
  }
  
  // create image
  $rounds_loop = $rounds;
  for ($i = 0; $i < $count_matches; $i++) {
    $i2 = $i + 1;
    $round_2 = floor($round / 2);
    if (!empty($round)) {
      $currheight += (pow(2, $round - 1) - 0.5) * $entityheight;
      $currheight += (pow(2, $round - 2))       * $yspace_enemies;
      $currheight += (pow(2, $round - 2) - 0.5) * $yspace_normal;
    }
    
    imagefilledrectangle ($img, $currwidth, $currheight, $currwidth + $entitywidth, $currheight + $entityheight, $col_team_bg);
    $string = '';
    if (empty($round) AND $cupmatches[$rounds_loop][$i] !== FALSE)
      $string = $cupmatches[$rounds_loop][$i]['team1_name'] = (empty($cupmatches[$rounds_loop][$i]['team1_name']) AND !empty($cupmatches[$rounds_loop][$i]['team1_id'])) ? '? ID:'.$cupmatches[$rounds_loop][$i]['team1_id'] : $cupmatches[$rounds_loop][$i]['team1_name'];
    elseif (!empty($cupmatches[$rounds_loop+1][$run]['cupmatches_winner'])) {
      $cond = $cupmatches[$rounds_loop+1][$run]['cupmatches_winner'] == $cupmatches[$rounds_loop+1][$run]['team1_id'];
      $string = $cond ? $cupmatches[$rounds_loop+1][$run]['team1_name'] : $cupmatches[$rounds_loop+1][$run]['team2_name'];
      if (empty($cupmatches[$rounds_loop+1][$run]['cupmatches_accepted1']) || empty($cupmatches[$rounds_loop+1][$run]['cupmatches_accepted2'])) $string = '(' . $string . ')';
    }
    $string = mb_convert_encoding($string, "ISO-8859-1", $cs_main['charset']);
    if (!empty($string)) imagestring($img, $font_match, $currwidth + 10, $currheight + $entity_font_height, $string, $col_team_font);

    $run++;
    if ($i2 == $count_matches) break;
    if (empty($round))
        $currheight += empty($round) ? $entityheight + $yspace_enemies : ($round + 1) * $entityheight + $yspace_enemies * $round + $round * $entityheight_2;
    else {
        $currheight += pow(2, $round)     * $entityheight;
        $currheight += pow(2, $round - 1) * $yspace_enemies;
        $currheight += pow(2, $round - 1) * $yspace_normal;
    }
    
    imagefilledrectangle ($img, $currwidth, $currheight, $currwidth + $entitywidth, $currheight + $entityheight, $col_team_bg);
    $string = '';
    if (empty($round) AND $cupmatches[$rounds_loop][$i] !== FALSE)
      $string = $cupmatches[$rounds_loop][$i]['team2_name'] = (empty($cupmatches[$rounds_loop][$i]['team2_name']) AND !empty($cupmatches[$rounds_loop][$i]['team2_id'])) ? '? ID:'.$cupmatches[$rounds_loop][$i]['team2_id'] : $cupmatches[$rounds_loop][$i]['team2_name'];
    elseif (!empty($cupmatches[$rounds_loop+1][$run]['cupmatches_winner'])) {
      $cond = $cupmatches[$rounds_loop+1][$run]['cupmatches_winner'] == $cupmatches[$rounds_loop+1][$run]['team1_id'];
      $string = $cond ? $cupmatches[$rounds_loop+1][$run]['team1_name'] : $cupmatches[$rounds_loop+1][$run]['team2_name'];
      if (empty($cupmatches[$rounds_loop+1][$run]['cupmatches_accepted1']) || empty($cupmatches[$rounds_loop+1][$run]['cupmatches_accepted2'])) $string = '(' . $string . ')';
    }
    $string = mb_convert_encoding($string, "ISO-8859-1", $cs_main['charset']);
    if (!empty($string)) imagestring($img, $font_match, $currwidth + 10, $currheight + $entity_font_height, $string, $col_team_font);

    if (empty($round))
        $currheight += $entityheight + $yspace_normal;
    else {
      $currheight += (pow(2, $round - 1) + 0.5) * $entityheight;
      $currheight +=  pow(2, $round - 2)        * $yspace_enemies;
      $currheight += (pow(2, $round - 2) + 0.5) * $yspace_normal;
      
    }
    $run++;
    if ($i2 >= $max) {
      $currheight = $space_top;
      $currwidth += $entitywidth + $xspace;
      $nexthalf /= 2;
      $max += $nexthalf;
      $round++;
      $run = 0;
      $rounds_loop--;
    }
  }
}

header ('Content-type: image/png');
imagepng($img);
imagedestroy($img);