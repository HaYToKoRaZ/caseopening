<?php

  function Query($query, $variables = array(), $orderby = null, $format = 'ASC', $limit = null, $groupby = null) {
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("<p class='error'>Sorry, we were unable to connect to the database server.</p>");
    if ($variables) {
      $query .= ' WHERE ';
      $currentVar = 1;
      foreach ($variables as $key => $value) {
        if (is_array($value) && sizeof($value) > 1) {
          $i = 1;
          $query .= $key.' IN (';
          foreach ($value as $k => $v) {
            ++$i;
            $query .= mysqli_real_escape_string($db, $v['id']);
            if ($i <= sizeof($value)) {
              $query .= ', ';
            }
          }
          $query .= ')';
        } else {
          if (is_array($value)) {
            $value = $value[0]['id'];
          }
          ++$currentVar;
          if($key == 'search') {
            $query .= "`name` LIKE '%".mysqli_real_escape_string($db, $value)."%'";
          } else if($key == 'inventory') {
            if($value == 2) {
              $query .= "`status` = 1 AND `locked` = 0";
            } else {
              $locked = isPromo() || isAdmin() ? 1 : 0;
              $query .= "(`status` = 1 OR `status` = 2) AND `locked` = ".$locked;
            }
          } else if($key == 'today') {
            $query .= "DATE(`created`) = CURDATE()";
          } else if($key == 'validitems') {
            $query .= "`id` IN (".mysqli_real_escape_string($db, $value).")";
          } else if($key == 'validuseritems') {
            $query .= "`users_items`.`id` IN (".mysqli_real_escape_string($db, $value).")";
          } else if($key == 'minprice') {
            $query .= '`price` >= '.mysqli_real_escape_string($db, $value);
          } else if($key == 'maxprice') {
            $query .= '`price` < '.mysqli_real_escape_string($db, $value);
          } else if($key == 'usercases') {
            $query .= "`steamid` != 1";
          } else {
            $query .= "`".$key."` = '".mysqli_real_escape_string($db, $value)."'";
          }
          if ($currentVar <= sizeof($variables)) {
              $query .= ' AND ';
          }
        }
      }
    }
    if ($groupby) {
      $query .= ' GROUP BY '.$groupby;
    }
    if ($orderby) {
      $query .= ' ORDER BY '.$orderby.' '.$format;
    }
    if ($limit) {
      $query .= ' LIMIT '.$limit;
    };
    $db->set_charset("utf8");
    if(isSuperAdmin() && DEBUG) print_r($query);
    $resultsDB = $db->query($query);
    $resultsArray = array();
    if ($resultsDB) {
      while ($resultsDB->num_rows > 0 && $row = $resultsDB->fetch_assoc()) {
        array_push($resultsArray, $row);
      }
    }
    mysqli_close($db);
    return $resultsArray;
  }

  function Insert($table, $variables = array()) {
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("<p class='error'>Sorry, we were unable to connect to the database server.</p>");
    $query = 'INSERT INTO `'.$table.'` (';
    $currentVar = 1;
    foreach ($variables as $key => $value) {
      ++$currentVar;
      $query .= '`'.$key.'`';
      if ($currentVar <= sizeof($variables)) {
        $query .= ', ';
      }
    }
    $currentVar = 1;
    $query .= ') VALUES (';
    foreach ($variables as $key => $value) {
      ++$currentVar;
      $query .= "'".mysqli_real_escape_string($db, $value)."'";
      if ($currentVar <= sizeof($variables)) {
        $query .= ', ';
      }
    }
    $query .= ')';
    $db->set_charset("utf8");
    $results = $db->query($query);
    $resultsid = $db->insert_id;
    mysqli_close($db);
    return $resultsid;
  }

  function Update($table, $where, $variables = array(), $par = "'", $bal = null) {
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("<p class='error'>Sorry, we were unable to connect to the database server.</p>");
    $query = 'UPDATE `'.$table.'` SET ';
    $currentVar = 1;
    foreach ($variables as $key => $value) {
      ++$currentVar;
      $query .= $key.'='.$par.mysqli_real_escape_string($db, $value).$par;
      if ($currentVar <= sizeof($variables)) {
        $query .= ', ';
      }
    }
    if ($where) {
      $currentVar = 1;
      $query .= ' WHERE ';
      foreach ($where as $key => $value) {
        ++$currentVar;
        if($key == 'sell') {
          $query .= "(`status` = 1 OR `status` = 4)";
        } else {
          $query .= $key."='".mysqli_real_escape_string($db, $value)."'";
        }
        if ($currentVar <= sizeof($where)) {
          $query .= ' AND ';
        }
      }
      if($bal) $query .= ' AND `balance`-'.mysqli_real_escape_string($db, $bal).'>=0';
    }
    $db->set_charset("utf8");
    $db->query($query);
    $count = $db->affected_rows;
    mysqli_close($db);
    return $count;
  }

  function Delete($table, $where) {
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("<p class='error'>Sorry, we were unable to connect to the database server.</p>");
    $currentVar = 1;
    $query = 'DELETE FROM `'.$table.'`';
    if ($where) {
      $query .= ' WHERE ';
      foreach ($where as $key => $value) {
        ++$currentVar;$query .= "`".$key."` = '".mysqli_real_escape_string($db, $value)."'";
        if ($currentVar <= sizeof($where)) {
          $query .= ' AND ';
        }
      }
    }
    $db->query($query);
    $count = $db->affected_rows;
    mysqli_close($db);
    return $count;
  }
