<?php
class MyDB extends SQLite3
{
   function __construct()
   {
      $this->open('dictionary.db');
   }
}

$db = new MyDB();

if(!$db){
   echo $db->lastErrorMsg();
} else {
   #echo "Opened database successfully\n";
}

$output = array();
$output[] = ["", ""];

$toGet = explode(";", $_GET['playlist']);
for ($i=0; $i < count($toGet); $i+=2) {
    $tagName = $db->query("SELECT * FROM tags WHERE id=".$toGet[$i+1])->fetchArray(SQLITE3_ASSOC)['tag_name'];
    $versesIdsRet = $db->query("SELECT * FROM '".$tagName."'");
    $getVersesSQL = "SELECT * FROM words WHERE ";
    while ($row = $versesIdsRet->fetchArray(SQLITE3_ASSOC)) {
        $getVersesSQL .= "id=".$row['word_id']." OR ";
    }
    $getVersesSQL = substr($getVersesSQL, 0, strlen($getVersesSQL)-4)." ORDER BY base";
    //echo $getVersesSQL;
    $getVersesRet = $db->query($getVersesSQL);
    $getVersesRet->fetchArray(SQLITE3_ASSOC);
    while ($row = $getVersesRet->fetchArray(SQLITE3_ASSOC)) {
        if($toGet[$i]) {
            $output[] = [substr($row['trans'],3), substr($row['base'],3)];
        } else {
            $output[] = [substr($row['base'],3), substr($row['trans'],3)];
        }
    }
    $output[] = ["", ""];
}

echo json_encode($output);
?>