<?php
   class MyDB extends SQLite3
   {
      function __construct()
      {
         $this->open('confustion.db');
      }
   }
   $db = new MyDB();
   if(!$db){
      echo $db->lastErrorMsg();
   } else {
      echo "Opened database successfully\n";
   }
/*CREATE TABLE
   
   $sql =<<<EOF
      CREATE TABLE UserCode1
      (ID INTEGER,
      Code TEXT NOT NULL,
      Answer TEXT NOT NULL,
      Type TEXT NOT NULL,
      Pair INTEGER NOT NULL);
EOF;
*/
$sql =<<<EOF
      INSERT INTO UserCode1 (ID, Code, Answer, Type, Pair) SELECT ID,Code,Answer,Type,Pair FROM Code ORDER BY ID ;
EOF;
   $ret = $db->exec($sql);
   if(!$ret){
      echo $db->lastErrorMsg();
   } else {
      echo "Table created successf  ully\n";
   }

   $db->close();


?>