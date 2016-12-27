<?php

  require_once("./config.php");
  $mysqli = new mysqli($host, $user, $password, $db);

  if ($mysqli->connect_errno) {
    echo 'Ошибка соединения с БД \n"';
    echo 'Номер ошибки '.$mysqli->connect_errno.'\n';
    echo 'Ошибка: '.$mysqli->connect_errno.'\n';
    $mysqli->close();
  }

  $sql = 'SELECT MAX(`number`) as `max` FROM `ads`';

  if (!$result = $mysqli->query($sql)) {
    echo "Ошибка выполнения запроса к БД \n";
    echo "Запрос :".$sql."\n";
    echo 'Номер ошибки '.$mysqli->connect_errno.'\n';
    echo 'Ошибка: '.$mysqli->connect_errno.'\n';
    $mysqli->close();
  }

  if ($result->num_rows === 0) {
    echo "Ответ на запрос пришел пустым";
    $mysqli->close();
  }

/*  while ($places = $result->fetch_assoc()) {
    echo $places['place']."<br>";
  }*/

  $mysqli->close();

  $max = $result->fetch_assoc();
  echo $max['max'] + 1;
?>
