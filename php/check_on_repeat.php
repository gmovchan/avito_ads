<?php
  header("Content-Type: text/html; charset=utf-8");
  $data = $_GET["data"];
  $type = $_GET["type"];

  require_once("./config.php");
  $mysqli = new mysqli($host, $user, $password, $db);

  if ($mysqli->connect_errno) {
    echo 'Ошибка соединения с БД \n"';
    echo "Номер_ошибки: " . $mysqli->errno . "<br>";
    echo "Ошибка: " . $mysqli->error . "<br>";
    $mysqli->close();
  }

  /*принудительно установил кодировку UTF-8 потому что иначе MYSQL не понимала
  со словами на русском языке*/
  if (!$mysqli->set_charset("utf8")) {
//      printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqli->error);
      $mysqli->close();
  } else {
//      printf("Текущий набор символов: %s\n", $mysqli->character_set_name());
  }

  switch ($type) {
    case 'id_avito':
      $column = 'id_avito';
      break;

    case 'telephone_number':
      $column = 'telephone_number';
      break;

    case 'organization':
      $column = 'organization';
      break;

    default:
      # code...
      break;
  }

  $sql = "SELECT * FROM `ads` WHERE `$column` = '$data'";

  if (!$result = $mysqli->query($sql)) {
    echo "Ошибка выполнения запроса к БД \n";
    echo "Запрос :".$sql."\n";
    echo "Номер_ошибки: " . $mysqli->errno . "<br>";
    echo "Ошибка: " . $mysqli->error . "<br>";
    $mysqli->close();
  }

  if ($result->num_rows === 0) {
    $repeat = false;
  } else {
    $repeat = true;
  }

  $mysqli->close();

  if ($repeat) {
    echo json_encode(array('repeat' => "true", 'data' => $data));
  } else {
    echo json_encode(array('repeat' => "false", 'data' => $data));
  }

?>
