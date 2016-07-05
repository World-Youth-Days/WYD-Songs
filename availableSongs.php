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

$songIDs = "SELECT * FROM song";
$songRet = $db->query($songIDs);
$songIds = array();
while ($row = $songRet->fetchArray(SQLITE3_ASSOC)) $songIds[] = $row['word_id'];

$fromIDs = "SELECT * FROM ".$_GET['from'];
$fromRet = $db->query($fromIDs);
$fromIds = array();
while ($row = $fromRet->fetchArray(SQLITE3_ASSOC)) $fromIds[] = $row['word_id'];

$toIDs = "SELECT * FROM ".$_GET['to'];
$toRet = $db->query($toIDs);
$toIds = array();
while ($row = $toRet->fetchArray(SQLITE3_ASSOC)) $toIds[] = $row['word_id'];

$intersectIds = array_intersect($toIds, $fromIds, $songIds);

$tagsSQL = "SELECT * FROM tags WHERE flag='live'";
$tagsRet = $db->query($tagsSQL);
while ($tag = $tagsRet->fetchArray(SQLITE3_ASSOC)) {
    if($tag['tag_name']!="song" && $tag['tag_name'] != "reversible"){
        $tagIDs = "SELECT * FROM '".$tag['tag_name']."'";
        $tagRet = $db->query($tagIDs);
        $tagIds = array();
        while ($row = $tagRet->fetchArray(SQLITE3_ASSOC)) $tagIds[] = $row['word_id'];
        if(count(array_intersect($intersectIds, $tagIds))) {
            $song = $db->query("SELECT * FROM words WHERE id=".$tagIds[0])->fetchArray(SQLITE3_ASSOC);
            $output[] = ["0;".$tag['id'], substr($song['base'],3), substr($song['trans'], 3)];
//            echo $tag['tag_name']."<br>";
//            echo "<option value=''>".$song['base']."/".$song['trans']."</option>";
        }
    }
}

$fromIDs = "SELECT * FROM from_".substr($_GET['to'], 3);
$fromRet = $db->query($fromIDs);
$fromIds = array();
while ($row = $fromRet->fetchArray(SQLITE3_ASSOC)) $fromIds[] = $row['word_id'];

$toIDs = "SELECT * FROM to_".substr($_GET['from'], 5);
$toRet = $db->query($toIDs);
$toIds = array();
while ($row = $toRet->fetchArray(SQLITE3_ASSOC)) $toIds[] = $row['word_id'];

$intersectIds = array_intersect($toIds, $fromIds, $songIds);

$tagsSQL = "SELECT * FROM tags WHERE flag='live'";
$tagsRet = $db->query($tagsSQL);
while ($tag = $tagsRet->fetchArray(SQLITE3_ASSOC)) {
    if($tag['tag_name']!="song" && $tag['tag_name'] != "reversible"){
        $tagIDs = "SELECT * FROM '".$tag['tag_name']."'";
        $tagRet = $db->query($tagIDs);
        $tagIds = array();
        while ($row = $tagRet->fetchArray(SQLITE3_ASSOC)) $tagIds[] = $row['word_id'];
        if(count(array_intersect($intersectIds, $tagIds))) {
            $song = $db->query("SELECT * FROM words WHERE id=".$tagIds[0])->fetchArray(SQLITE3_ASSOC);
            $output[] = ["1;".$tag['id'], substr($song['trans'], 3), substr($song['base'], 3)];
//            echo $tag['tag_name']."<br>";
//            echo "<option value=''>".$song['base']."/".$song['trans']."</option>";
        }
    }
}

foreach ($output as $value) {
    echo "<option value='".$value[0]."'>".$value[1]." / ".$value[2]."</option>";
}
?>