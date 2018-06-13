<!DOCTYPE html>
<html>
    <head>
        <title>aOS Password Check</title>
        <script defer>
            var googlePlay = "";
            if(window.location.href.indexOf('GooglePlay=true') > -1){
                googlePlay = '?GooglePlay=true';
            }
            function checkPassword(){
                document.cookie = 'password=' + document.getElementById('password').value + "; Max-Age:315576000";
                window.location = 'aosBeta.php' + googlePlay;
            }
            function skipPassword(){
                if(confirm("Are you sure you want to create a new account and potentially lose all the data associated with your old one?")){
                    document.cookie = 'keyword=; Max-Age=-99999999';
                    window.location = 'aosBeta.php' + googlePlay;
                }
            }
        </script>
        <?php
            echo '<link rel="stylesheet" href="/askPassword.css?ms='.time().'">';
        ?>
    </head>
    <body>
        <?php
            if(isset($_COOKIE['keyword'])){
                if(file_exists('USERFILES/'.$_COOKIE['keyword'].'/APP_STN_SETTING_BACKGROUND.txt')){
                    echo '<div id="background" style="background-image:url('.file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/APP_STN_SETTING_BACKGROUND.txt').');"></div>';
                }else{
                    echo '<div id="background"></div>';
                }
            }else{
                echo '<div id="background"></div>';
            }
        ?>
        <div id="card">
            <h1>AaronOS</h1>
            <hr>
            Your account is set to require a password to log in.<br><br>
            OS ID: <?php echo $_COOKIE['keyword'] ?><br><br>
            Please enter the password to your account below, to log in:<br>
            <input type="password" onkeypress="if(event.keyCode === 13){checkPassword();}" id="password"> <button onclick="checkPassword()">Log In</button><br><br>
            Or, if you don't have an account yet, <button onclick="skipPassword()">Create a New One</button>.<br><br>
        </div>
    </body>
    <script defer>
        document.getElementById('password').focus();
    </script>
</html>