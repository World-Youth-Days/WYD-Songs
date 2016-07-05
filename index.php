<!DOCTYPE html>
<html>
    <head>
        <title>WYD Songs</title>
        <meta name="description" content="WYD Songs - the best songs app for the World Youth Day! By the guys behind WYD Dictionary." />
        <meta property="og:title" content="WYD Songs" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="http://wyd-dict.tk/" />
        <meta property="og:image" content="http://wyd-dict.tk/img/fb.png" />
        <meta property="og:description" content="WYD Songs - the best songs app for the World Youth Day! By the guys behind WYD Dictionary." />
        
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/javascript" src="jquery-1.12.2.min.js"></script>
        <script type="text/javascript" src="main.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link rel="stylesheet" href="main.css" />
    </head>
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
    
    $sql = "SELECT * FROM song";
    $ret = $db->query($sql);
    $songIds = array();
    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $songIds[] = $row['word_id'];
    }
    ?>
    <body>
        <div id="ticker">
            
        </div>
        
        <div id="chooser">
            <h1>WYD Songs</h1>
            <div id="languages-chooser">
                <select id="select-from">
                    <option>--</option>
                    <?php
                    $sql = "SELECT * FROM tags WHERE flag='from' OR flag='to'";
                    $ret = $db->query($sql);
                    while ($tag = $ret->fetchArray(SQLITE3_ASSOC)) {
                        $sqlIDs = "SELECT * FROM ".$tag['tag_name'];
                        $tagRet = $db->query($sqlIDs);
                        $tagIds = array();
                        while ($retID = $tagRet->fetchArray(SQLITE3_ASSOC)) $tagIds[] = $retID['word_id'];
                        if(count(array_intersect($songIds, $tagIds))) {
                            echo "<option value='from_".substr($tag['tag_name'], strlen($tag['tag_name'])-2)."'>".substr($tag['tag_name'], strlen($tag['tag_name'])-2)."</option>";
                        }
                    }
                    ?>
                </select>
                <select id="select-to">
                    <option>--</option>
                    <?php
                        while ($tag = $ret->fetchArray(SQLITE3_ASSOC)) {
                        $sqlIDs = "SELECT * FROM ".$tag['tag_name'];
                        $tagRet = $db->query($sqlIDs);
                        $tagIds = array();
                        while ($retID = $tagRet->fetchArray(SQLITE3_ASSOC)) $tagIds[] = $retID['word_id'];
                        if(count(array_intersect($songIds, $tagIds))) {
                            echo "<option value='to_".substr($tag['tag_name'], strlen($tag['tag_name'])-2)."'>".substr($tag['tag_name'], strlen($tag['tag_name'])-2)."</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div id="songs-chooser">
                <select id="select-song">
                    <option>--</option>
                </select>
            </div>
            <div id="song-add-button-container">
                <button id="song-add-button">Add/Dodaj</button>
            </div>
        </div>
    </body>
</html>
