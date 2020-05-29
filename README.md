# AaronOS

Welcome to the AaronOS repository!

This is where all the open-source bits of aOS will reside, so the public can read it and even make pull requests if you wish.

AaronOS is usable at https://aaronos.dev/

If you wish to deploy AaronOS on your own server, you MUST read and follow the End User License Agreement at /eula.txt
By downloading or otherwise using this source code in any way, you agree to the terms listed in that file.
Upon hosting AaronOS, the first connection to the project (which performs first-time setup) will also prompt you with this file.

# **Setup**

## Prerequisites
Make sure you have PHP and a webserver like Apache or Nginx (The official aOS uses Apache) You can install them by running the following in your terminal:

```sudo apt install -y Apache2 php```

## Actually doing it
What we are gonna do is open the ports aOS uses, grab aOS from github, move it into the correct folder, than change the folder permissions so that the userfiles php code works

Note that this guide is for Ubuntu only, if you are using something else then only use this guide as a general outline

First open the ports

```sudo ufw allow "Apache Full"```

Make sure you are in the right directory, than download the aOS files

```
cd
git clone https://github.com/MineAndCraft12/AaronOS
```
Copy into the correct folder

```sudo cp -r AaronOS/* /var/www/html/```

Give ownership of directory to Apache and let Apache write to it

``` 
sudo chown -R www-data /var/www/
sudo chmod -R 755 /var/www/html
```

Start Apache

```sudo systemctl start apache2```

# Privacy

The official AaronOS Privacy Policy can be found at /privacy.txt

# What You Get

You may be wondering, what is and isn't included here?

Included:
 * All currently-used code in aOS
 * Any graphics or other assets that aOS uses

Not Included:
 * Graphics not related to aOS
 * Non-related file I keep on the server
 * User files (duh)
 * Old, not-in-development versions of aOS

The main PHP file is /aosBeta.php

The main JavaScript file is /scriptBeta.js

<<<<<<< HEAD
The main CSS file is /styleBeta.css
=======
The main CSS file is /styleBeta.css
>>>>>>> upstream/master
