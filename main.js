/*!
 * cookie-monster - a simple cookie library
 * v0.3.0
 * https://github.com/jgallen23/cookie-monster
 * copyright Greg Allen 2014
 * MIT License
*/
var monster={set:function(a,b,c,d,e){var f=new Date,g="",h=typeof b,i="",j="";if(d=d||"/",c&&(f.setTime(f.getTime()+24*c*60*60*1e3),g="; expires="+f.toUTCString()),"object"===h&&"undefined"!==h){if(!("JSON"in window))throw"Bummer, your browser doesn't support JSON parsing.";i=encodeURIComponent(JSON.stringify({v:b}))}else i=encodeURIComponent(b);e&&(j="; secure"),document.cookie=a+"="+i+g+"; path="+d+j},get:function(a){for(var b=a+"=",c=document.cookie.split(";"),d="",e="",f={},g=0;g<c.length;g++){for(var h=c[g];" "==h.charAt(0);)h=h.substring(1,h.length);if(0===h.indexOf(b)){if(d=decodeURIComponent(h.substring(b.length,h.length)),e=d.substring(0,1),"{"==e)try{if(f=JSON.parse(d),"v"in f)return f.v}catch(i){return d}return"undefined"==d?void 0:d}}return null},remove:function(a){this.set(a,"",-1)},increment:function(a,b){var c=this.get(a)||0;this.set(a,parseInt(c,10)+1,b)},decrement:function(a,b){var c=this.get(a)||0;this.set(a,parseInt(c,10)-1,b)}};

var verses = [];
current = 0;
$(document).ready(function() {
    $("#select-from").change(function() {
        if($("#select-to").val()!="--") getAvailableSongs();
    });
    $("#select-to").change(function() {
        if($("#select-from").val()!="--") getAvailableSongs();
    });
    function getAvailableSongs() {
        $.get("availableSongs.php?from="+$("#select-from").val()+"&to="+$("#select-to").val(), function(data) {
            $("#select-song").html(data);
        });
    }
    $("#song-add-button").click(function() {
        if($("#select-song").val() != undefined && $("#select-song").val() != "--") {
            $("#playlist-body").append("<li class='playlist-elem' id='"+$("#select-song").val()+"'><span class='drag-handle'>☰</span>"+$("#select-song option:selected").text()+"<span class='playlist-remove'>X</span></li>");
        } else {
            alert("Choose languages and a song!\n\nWybierz języki i pieśń!");
        }
    });
    var list = document.getElementById('playlist-body');
    var sortable = new Sortable(list, {
        animation: 150,
        filter: '.playlist-remove',
        onFilter: function (evt) {
          var el = sortable.closest(evt.item); // get dragged item
          el && el.parentNode.removeChild(el);
        }
    });
    
    if (monster.get("playlist")==null) {
        monster.set("playlist", "");
        playlists = [];
    } 
    
    $("#save").click(function() {
        contents = "";
        $(".playlist-elem").each(function() {
            contents+=$(this).attr('id')+";";
        });
        contents = contents.substr(0, question.length-1);
        nameAns = prompt("Give the Playlist a nama: / Nazwij swoją Playlistę:")
        playlists.push([nameAns, contents]);
    })
    
    $("#start").click(function() {
        question = "";
        $(".playlist-elem").each(function() {
            question+=$(this).attr('id')+";";
        });
        question = question.substr(0, question.length-1);
        if (question != "") {

            //Go fullscreen
            var el = document.body;
            var requestMethod = el.requestFullScreen || el.webkitRequestFullScreen 
            || el.mozRequestFullScreen || el.msRequestFullScreen;
            if (requestMethod) {
              requestMethod.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") {
              var wscript = new ActiveXObject("WScript.Shell");
              if (wscript !== null) {
                wscript.SendKeys("{F11}");
              }
            }

            $.get("constructPlaylist.php?playlist="+question, function(data) {
                verses = JSON.parse(data);
                current = 0;
                $("#words-left").html(verses[0][0][0].replace(/\n/g,'<br/>'));
                $("#words-right").html(verses[0][0][1].replace(/\n/g,'<br/>'));
                $("#nav").show();
                $("#chooser").fadeOut("slow");
                navTimeout = setTimeout(function(){$("#nav").fadeOut()}, 5000);
            });
        } else {
            alert("Add songs to the Playlist using the menu on your left!\n\nDodaj pieśni do Playlisty używając menu po lewej!");
        }
    });
    
    $(document).keydown(function(e) {
        if((e.which == 37 ||e.which == 40) && current-1 >= 0) {
            current -= 1;
            $("#words-left").html(verses[0][current][0].replace(/\n/g,'<br/>'));
            $("#words-right").html(verses[0][current][1].replace(/\n/g,'<br/>'));
            $("#title-left").html(verses[1][current][0].replace(/\n/g,'<br/>'));
            $("#title-right").html(verses[1][current][1].replace(/\n/g,'<br/>'));
        } else if ((e.which == 39 || e.which == 32 || e.which == 40) && current+1<verses[0].length) {
            current += 1;
            $("#words-left").html(verses[0][current][0].replace(/\n/g,'<br/>'));
            $("#words-right").html(verses[0][current][1].replace(/\n/g,'<br/>'));
            $("#title-left").html(verses[1][current][0].replace(/\n/g,'<br/>'));
            $("#title-right").html(verses[1][current][1].replace(/\n/g,'<br/>'));
        } else if(e.which == 27) {
            $("#chooser").fadeIn("slow");
        }
    });
    $("#left-button").click(function() {
        if(current-1 >= 0) {
            current -= 1;
            $("#words-left").html(verses[0][current][0].replace(/\n/g,'<br/>'));
            $("#words-right").html(verses[0][current][1].replace(/\n/g,'<br/>'));
            $("#title-left").html(verses[1][current][0].replace(/\n/g,'<br/>'));
            $("#title-right").html(verses[1][current][1].replace(/\n/g,'<br/>'));
        }
    });
    $("#right-button").click(function() {
        if(current+1<verses[0].length) {
            current += 1;
            $("#words-left").html(verses[0][current][0].replace(/\n/g,'<br/>'));
            $("#words-right").html(verses[0][current][1].replace(/\n/g,'<br/>'));
            $("#title-left").html(verses[1][current][0].replace(/\n/g,'<br/>'));
            $("#title-right").html(verses[1][current][1].replace(/\n/g,'<br/>'));
        }
    });
    $("#exit").click(function() {
        if ((document.fullScreenElement && document.fullScreenElement !== null) ||    
        (!document.mozFullScreen && !document.webkitIsFullScreen)) {
         if (document.documentElement.requestFullScreen) {  
           document.documentElement.requestFullScreen();  
         } else if (document.documentElement.mozRequestFullScreen) {  
           document.documentElement.mozRequestFullScreen();  
         } else if (document.documentElement.webkitRequestFullScreen) {  
           document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);  
         }  
       } else {  
         if (document.cancelFullScreen) {  
           document.cancelFullScreen();  
         } else if (document.mozCancelFullScreen) {  
           document.mozCancelFullScreen();  
         } else if (document.webkitCancelFullScreen) {  
           document.webkitCancelFullScreen();  
         }  
       } 
       $("#chooser").fadeIn("slow");
    });
    $("#nav-input").mousemove(function() {
        clearTimeout(navTimeout);
        $("#nav").fadeIn()
        navTimeout = setTimeout(function(){$("#nav").fadeOut()}, 5000);
    });
});