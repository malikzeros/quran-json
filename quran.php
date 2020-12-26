<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quran";
for($i=1;$i<=114;$i++){
try {
  $conn = new PDO(
    "mysql:host=$servername;dbname=$dbname", 
    $username, 
    $password,
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $jsondata = json_decode(file_get_contents($i.".json"));
  foreach ($jsondata as $surah) {
    echo $surah->number."\n";
    $sql = "INSERT INTO surah (number, name,id_name, name_latin,number_of_ayah) VALUES (".$surah->number.", '".addslashes($surah->name)."', '".addslashes($surah->translations->id->name)."', '".addslashes($surah->name_latin)."',".$surah->number_of_ayah.")";
    $conn->exec($sql);
    foreach ($surah->text as $text) {
      $sql = "INSERT INTO ayah (number, text) VALUES (".$surah->number.", '".addslashes($text)."')";
      $conn->exec($sql);
    }
    foreach ($surah->translations->id->text as $text) {
      $sql = "INSERT INTO translate (number, text) VALUES (".$surah->number.", '".addslashes($text)."')";
      $conn->exec($sql);
    }
    foreach ($surah->tafsir->id->kemenag->text as $text) {
      $sql = "INSERT INTO tafsir (number, text) VALUES (".$surah->number.", '".addslashes($text)."')";
      $conn->exec($sql);
    }
  }
} catch (PDOException $e) {
  echo $sql . "\n" . $e->getMessage();
}
$conn = null;
}