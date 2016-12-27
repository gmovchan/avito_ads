<?php
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

  print_r($_POST);
?>
