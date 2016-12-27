<?php
  header("Content-Type: text/html; charset=utf-8");
  date_default_timezone_set("Europe/Moscow");
  startParse();
  function startParse() {
    require_once("./config.php");
    $mysqli = new mysqli($host, $user, $password, $db);

    if ($mysqli->connect_errno) {
      echo 'Ошибка соединения с БД <br>"';
      sql_error();
    }

    //принудительно установил кодировку UTF-8 потому что скрипт почему-то отдавал
    //строки для БД в кодировке СР-1251
    if (!$mysqli->set_charset("utf8")) {
        printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqli->error);
        $mysqli->close();
    } else {
        printf("Текущий набор символов: %s\n", $mysqli->character_set_name());
    }

    $handle = fopen('php://memory', 'w+');
    fwrite($handle, iconv('CP1251', 'UTF-8', file_get_contents('../files/avito.csv')));
    rewind($handle);

    $date = date("d.m.Y");

    while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
      $number = $data[1];
      $id_avito = $data[2];


      //не все строки являются объявлениеми, поэтому пропускаю те, где не стоит артикул
      if (is_numeric($number) && $id_avito) {

        $link = $data[3];
        $header = $data[4];
        echo mb_detect_encoding($header);
        $price = $data[5];
        $organization = $data[6];
        $name = $data[7];
        $telephone_number = $data[8];
        $address = $data[9];
        $message = $data[10];
        $text_ad = $data[11];

        //не у каждой позиции проставлена дата, потому что в оригинальной таблице есть
        //объединенные ячейки с датой
        if ($data[0]) {
          $date = $data[0];
        }
/*
        echo $number . " / " . $id_avito  . " / " . $link . " / " . $header . " / " . $price
        . " / " . $organization . " / " . $name . " / " . $telephone_number . " / "
        . $address . " / " . $message . " / " . $text_ad . "<br>";
*/
        $sql = "SELECT * FROM `ads` WHERE `number` = $number";

        if (!$result = $mysqli->query($sql)) {
          echo "Ошибка: Наш запрос не удался и вот почему: <br>";
          echo "Запрос: " . $sql . "<br>";
          sql_error();
        }

        if ($result->num_rows === 0) {
          $sql = "INSERT INTO ads (`id_avito`, `link`, `header`, `price`, `organization`,
          `name`, `telephone_number`, `address`, `message`, `text_ad`, `date`,
          `number`) VALUES ('$id_avito', '$link', '$header', '$price', '$organization', '$name',
          '$telephone_number', '$address', '$message', '$text_ad', '$date', '$number')";

          if (!$result = $mysqli->query($sql)) {
              echo "Извините, возникла проблема в работе сайта.";
              sql_error();
          } else {
            echo "В БД добавлена позиция номер: ".$number."<br>";
          }
        } else {
          $sql = "UPDATE ads SET `id_avito` = '$id_avito', `link` = '$link', `header` = '$header',
           `price` = '$price', `organization` = '$organization', `name` = '$name',
           `telephone_number` = '$telephone_number', `address` = '$address',
           `message` = '$message', `text_ad` = '$text_ad', `date` = '$date'
           WHERE `number` = $number";

          if (!$result = $mysqli->query($sql)) {
              echo "Извините, возникла проблема в работе сайта.";
              echo "Ошибка: Наш запрос не удался и вот почему: <br>";
              echo "Запрос: " . $sql . "<br>";
              sql_error();
          } else {
            echo "В БД обновлена позиция номер: ".$number."<br>";
          }
        }
      }
    }
    $mysqli->close();
  }

  function sql_error() {
    echo "Номер_ошибки: " . $mysqli->errno . "<br>";
    echo "Ошибка: " . $mysqli->error . "<br>";
  }
?>
