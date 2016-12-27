<?php
  require_once './parce_csv.php';

  $uploaddir = '../files/';
  $uploadfile = "../files/avito.csv";

  if (copy($_FILES['uploadfile']['tmp_name'], $uploadfile))
  {
    echo "<h3>Файл успешно загружен на сервер</h3>";
  }
  else { echo "<h3>Ошибка! Не удалось загрузить файл на сервер!</h3>"; exit; }

  startParse();
?>
