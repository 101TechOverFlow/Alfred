
<nav>
    <ul class="top-nav">
        <li id="search"><i class="fa fa-search fa-2x"></i><input type="text" name="search" placeholder="Search..."/></li>
        <li><i id="music-icon" class="fa fa-volume-off fa-2x"></i>
        	<div id="music-player" class="dropdown-content jp-type-playlist">
        		<?php
        			include("header-dropdown/music-player.php");
        		?>
			</div>
        </li>                  
        <li id="notif"><i class="fa fa-bell fa-2x"></i>
        	<div class="dropdown-content">
			    <?php 
			    	include("header-dropdown/notifications.php");
			    ?>
			</div>
        </li>                  
        <li id="uploads">
        	<i class="fa fa-upload fa-2x"></i>
			<div class="dropdown-content">
			    <?php 
			    	include("header-dropdown/uploads.php");
			    ?>
			</div>

        </li>                  
        <li>
        	<i class="fa fa-cog fa-2x"></i>
        	<div class="dropdown-content" style="min-width:125px;">
			     <?php 
                    include("header-dropdown/settings.php");
                ?>    
            </div>
        </li>                  
    </ul> 
<nav>