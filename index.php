<!DOCTYPE html>
<html>
    <head>
        <title>WYD Songs</title>
        <meta name="description" content="WYD Songs - the best songs app for the World Youth Day! By the guys behind WYD Dictionary." />
        <meta property="og:title" content="WYD Songs" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="http://wyd-dict.tk/songs" />
        <meta property="og:image" content="http://wyd-dict.tk/img/fb.png" />
        <meta property="og:description" content="WYD Songs - the best songs app for the World Youth Day! By the guys behind WYD Dictionary." />
        
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/javascript" src="Sortable.min.js"></script>
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
            <div class="left">
                <div class="title" id="title-left"></div>
                <div class="words" id="words-left">
                    --
                </div>
            </div>
            <div class="right">
                <div class="title" id="title-right"></div>
                <div class="words" id="words-right">
                    --
                </div>
            </div>
            <div id="nav-input"></div>
            <div id="nav">
                <div id="left-button" class="left-button">
                    wstecz
                </div>
                <div id="right-button" class="left-button">
                    dalej
                </div>
                <div id="fullscreen" class="right-button">
                    pełny ekran
                </div>
                <div id="exit" class="right-button">
                    zamknij
                </div>
            </div>
        </div>
        
        <div id="chooser">
            <img id="logo" src="img/logo.png" alt="WYD Songs">
            <div class="left" id="song-adder">
                <h2>Add songs / Dodaj pieśni</h2>
                <div id="languages-chooser">
                    Choose the songs' languages: / Wybierz języki pieśni:<br>
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
                    Choose a song and add it to the Playlist: / Wybierz pieśń i dodaj ją do Playlisty:<br>
                    <select id="select-song">
                        <option>--</option>
                    </select>
                </div>
                <div id="song-add-button-container">
                    <button id="song-add-button">Add/Dodaj</button>
                </div>
            </div>
            <div class="right">
                <h2>Playlist / Playlista</h2>
                <div id="playlist">
                    <ul id="playlist-body">
                        
                    </ul>
                </div>
                Add songs on the left! / Dodaj pieśni w menu po lewej!
            </div>
            <div id="start-container">
                <div id="start">START!</div>
            </div>
        </div>
    </body>
</html>
