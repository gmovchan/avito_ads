<?php
  require_once("./config.php");
  $mysqli = new mysqli($host, $user, $password, $db);

  if ($mysqli->connect_errno) {
    echo 'Ошибка соединения с БД \n"';
    echo 'Номер ошибки '.$mysqli->connect_errno.'\n';
    echo 'Ошибка: '.$mysqli->connect_errno.'\n';
    $mysqli->close();
  }

  $sql = "SELECT * FROM `address`";

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

  while ($places = $result->fetch_assoc()) {
    echo $places['place']."<br>";
  }
  $mysqli->close();

  function sql_error() {
    echo "Ошибка: Наш запрос не удался и вот почему: <br>";
    echo "Запрос: " . $sql . "<br>";
    echo "Номер_ошибки: " . $mysqli->errno . "<br>";
    echo "Ошибка: " . $mysqli->error . "<br>";
  }
?>
