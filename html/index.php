<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Alfred</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="html/css/design.css">        
        <link rel="stylesheet" href="html/css/jplayer.css">        
        <link rel="stylesheet" href="html/css/overlays.css">        
        <link rel="stylesheet" href="html/css/colors.css">
        <link rel="stylesheet" href="html/css/font-awesome.min.css">
    </head>
    <body>
          <?php 
            //include("templates/overlays.php");
          ?>
          <div id="header">
            <?php
              include("templates/header.php");
            ?>
          </div>
          <div id="wrapper">
            <div id="side-menu">
                <a onclick="javascript:_ROUTE.loadModule('files');" style="top:5px;" class="green" id="about">Fichiers<i class="fa fa-cloud"></i></a>
                <a onclick="javascript:_ROUTE.loadModule('music');" style="top:60px;" class="blue" id="blog">Musique<i class="fa fa-music"></i></a>
                <a onclick="javascript:_ROUTE.loadModule('movies');" style="top:115px;" class="orange" id="blog">Films<i class="fa fa-film"></i></a>
                <a onclick="javascript:_ROUTE.loadModule('photos');" style="top:170px;" class="purple" id="blog">Photos<i class="fa fa-photo"></i></a>
                <a onclick="javascript:_ROUTE.loadModule('books');" style="top:225px;" class="yellow" id="blog">Livres<i class="fa fa-book"></i></a>
                <a onclick="javascript:_ROUTE.loadModule('trash');" style="bottom:5px;" class="gray" id="contact">Corbeille<i class="fa fa-trash"></i></a>
            </div>
            <div id="side-panel">              
            </div>
            <div id="content">
              <div class="header">
                <div class="breadcrumb"></div>
                <div class="menu-action">
                                         
                </div>
              </div>
                <div class="content">
                    <ul class="list-items"></ul>
                </div>
            </div>
          </div>
        
        
        <script type="text/javascript" src="html/js/jquery-1.12.2.min.js"></script>
        <script type="text/javascript" src="html/js/route.core.js"></script>
        <script type="text/javascript" src="html/js/templates.core.js"></script>
        <script type="text/javascript" src="html/js/modules/files.module.js"></script>  
        <script type="text/javascript" src="html/js/index.js"></script>
        <script type="text/javascript" src="html/js/functions.js"></script>

        <!-- TEMPLATES -->
        <script type="text/template" data-template="file" src="html/templates/files/file.php"></script>
    </body>
</html>
