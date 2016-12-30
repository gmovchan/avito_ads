<?php

  header("Content-Type: text/html; charset=utf-8");
  date_default_timezone_set("Europe/Moscow");

  if ($_GET['action']) {
    require_once("./config.php");
    $mysqli = new mysqli($host, $user, $password, $db);

    if ($mysqli->connect_errno) {
      echo 'Ошибка соединения с БД <br>"';
      sql_error();
    }

    /*принудительно установил кодировку UTF-8 потому что скрипт почему-то отдавал
    строки для БД в кодировке СР-1251*/
    if (!$mysqli->set_charset("utf8")) {
        /*printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqli->error);*/
        $mysqli->close();
    } else {
        /*printf("Текущий набор символов: %s\n", $mysqli->character_set_name());*/
    }
  } else {
    exit('Ошибка. Необходимо передать тип действия в ссылке. ?action=create/parse');
  }

  switch ($_GET['action']) {
    case 'parse':
      startParse($mysqli);
      break;

    case 'create':
      startCreate($mysqli);
      break;

    default:
      # code...
      break;
  }


  function startParse($mysqli) {

    $handle = fopen('php://memory', 'w+');
    fwrite($handle, iconv('CP1251', 'UTF-8', file_get_contents('../files/avito.csv')));
    rewind($handle);

    $date = date("d.m.Y");

    $count_update = $count_insert = 0;

    while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
      $number = $data[1];
      $id_avito = $data[2];


      /*не все строки являются объявлениеми, поэтому пропускаю те, где не стоит артикул*/
      if (is_numeric($number) && $id_avito) {

        $link = $data[3];
        $header = $data[4];
        $price = $data[5];
        $organization = $data[6];
        $name = $data[7];
        $telephone_number = $data[8];
        $address = $data[9];
        $message = $data[10];
        $text_ad = $data[11];

        /*не у каждой позиции проставлена дата, потому что в оригинальной таблице есть
        объединенные ячейки с датой*/
        if ($data[0]) {
          $date = $data[0];
        }

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
            $count_insert++;
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
            $count_update++;
          }
        }
      }
    }
    echo "Добавлено новых позиций: ".$count_insert."<br>Обновлено старых позиций: ".$count_update;
    $mysqli->close();
  }

  function sql_error() {
    echo "Номер_ошибки: " . $mysqli->errno . "<br>";
    echo "Ошибка: " . $mysqli->error . "<br>";
    $mysqli->close();
  }

  function startCreate($mysqli) {

    echo "Начинается генерация нового документа<br>";

    $headers_csv = array('Дата','№','№ объявления','Ссылка','Заголовок','Цена',
  'Название фирмы','Имя','Телефон','Адрес','ПИСЬМО да/нет','Текст');

    $file = '../files/avito_ads_from_db.csv';

    $handle = fopen($file, 'w');

    $sql = "SELECT `date`, `number`, `id_avito`, `link`, `header`, `price`, `organization`,
     `name`, `telephone_number`, `address`, `message`, `text_ad` FROM `ads`";

    if (!$result = $mysqli->query($sql)) {
      echo "Ошибка: Наш запрос не удался и вот почему: <br>";
      echo "Запрос: " . $sql . "<br>";
      sql_error();
    }

    $avito_ads_from_db = array();
    /*добавил первым массивом - массив с заголовком таблицы*/
    $avito_ads_from_db[] = $headers_csv;

    while ($a = $result->fetch_assoc()) {
      $avito_ads_from_db[] = $a;
    }

  foreach ($avito_ads_from_db as $key => $value) {

    foreach ($value as $key1 => $value1) {

      $avito_ads_from_db[$key][$key1] = iconv('UTF-8', 'CP1251', $avito_ads_from_db[$key][$key1]);
      $value1 = 0;
    }
  }

    foreach ($avito_ads_from_db as $key) {
      fputcsv ($handle, $key);
    }

    fclose($handle);

    echo "Документ создан<br>";

    $mysqli->close();
    download_file_csv($file);
  }

  function download_file_csv($file) {
    echo "Начинается скачивание файла...<br>";
    if (file_exists($file)) {
      if (ob_get_level()) {
        ob_end_clean();
      }
      header('Content-Description: File Transfer');
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename='.basename($file));
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: '.filesize($file));

      readfile($file);
      exit;
    }
  }


?>
