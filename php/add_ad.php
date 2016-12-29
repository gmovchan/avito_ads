<?php
  require_once("./config.php");

  $number = $_POST['number'];
  $id_avito = $_POST['id_avito'];
  $date = date('d.m.Y');
  $link = $_POST['link'];
  $header = $_POST['header'];
  $price = $_POST['price'];
  $organization = $_POST['organization'];
  $name = $_POST['name'];
  $telephone_number = $_POST['telephone_number'];
  $address = $_POST['address'];
  $message = $_POST['message'];
  $text_ad = $_POST['text_ad'];
/*
  echo '$id_avito: '.$id_avito.'<br>';
  echo '$date: '.$date.'<br>';
  echo '$link: '.$link.'<br>';
  echo '$header: '.$header.'<br>';
  echo '$price: '.$price.'<br>';
  echo '$organization: '.$organization.'<br>';
  echo '$name: '.$name .'<br>';
  echo '$telephone_number: '.$telephone_number.'<br>';
  echo '$address: '.$address.'<br>';
  echo '$message: '.$message.'<br>';
  echo '$text_ad: '.$text_ad.'<br>';
*/
  print_r($_POST);

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
      /*printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqli->error);*/
      $mysqli->close();
  } else {
      /*printf("Текущий набор символов: %s\n", $mysqli->character_set_name());*/
  }

  /*используются подготавливаемые запросы для защиты от sql инъекций */
  $stmt = $mysqli->prepare("INSERT INTO ads (`id_avito`, `link`, `header`, `price`, `organization`,
  `name`, `telephone_number`, `address`, `message`, `text_ad`, `date`,
  `number`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param('ssssssssssss', $id_avito, $link, $header, $price, $organization, $name,
  $telephone_number, $address, $message, $text_ad, $date, $number);

  if (!$stmt->execute()) {
    echo 'Ошибка выполнения запроса \n"';
    echo "Номер_ошибки: " . $mysqli->errno . "<br>";
    echo "Ошибка: " . $mysqli->error . "<br>";
    $mysqli->close();
  } else {
    echo "SUCCESS!";
  }

  printf("%d строк вставлено.\n", $stmt->affected_rows);

  $stmt->close();

  $mysqli->close();

?>
