<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login - Alfred</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="html/css/login.css">
        <link rel="stylesheet" href="html/css/font-awesome.min.css">
    </head>
    <body>
        <div class="content">
            <div class="imgcontainer">
                <i class="fa fa-5x fa-cloud"></i>
            </div>

            <div class="container">
                <label><b>Username</b></label>
                <input type="text" placeholder="Enter Username" name="username">

                <label><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="password">

                <button type="submit">Login</button>                
            </div>
            <div class="container" style="background-color:#f1f1f1">
                <button type="button" class="cancelbtn">Cancel</button>
                <span class="psw">Forgot <a href="#todo">password?</a></span>
            </div>           
        </div>
        
        <script src="html/js/jquery-1.12.2.min.js"></script>
        <script src="html/js/login.js"></script>

    </body>
</html>
