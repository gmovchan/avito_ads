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
  /*ответ не выводится, чтобы не ломать JSON объект*/
  if (!$mysqli->set_charset("utf8")) {
      /*printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqli->error);*/
      $mysqli->close();
  } else {
      /*printf("Текущий набор символов: %s\n", $mysqli->character_set_name());*/
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

  /*все что далее надо перепистаь на подготавливаемые запросы для защиты от sql
  инъекций*/

  $sql = "SELECT * FROM `ads` WHERE `$column` = '$data'";

  if (!$result = $mysqli->query($sql)) {
    echo "Ошибка выполнения запроса к БД \n";
    echo "Запрос :".$sql."\n";
    echo "Номер_ошибки: " . $mysqli->errno . "<br>";
    echo "Ошибка: " . $mysqli->error . "<br>";
    $mysqli->close();
  }

  $number = 'none';

  if ($result->num_rows === 0) {
    $repeat = false;
  } else {
    /*если есть повторы, то ищуются номера повторяющихся записей, чтобы потом передать их
    на фронтэнд*/
    $repeat = true;
    $number = array();
    /*если записей несколько, то они превращаются в строку и перечисляются через запятую*/
    while ($a = $result->fetch_assoc()) {
      $number[] = $a['number'];
    }
    $number = implode(', ', $number);
  }

  if ($repeat) {
    echo json_encode(array('repeat' => "true", 'data' => $data, 'number' => $number));
  } else {
    echo json_encode(array('repeat' => "false", 'data' => $data, 'number' => $number));
  }

  $mysqli->close();

?>
