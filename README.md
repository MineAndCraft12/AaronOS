# AaronOS

Welcome to the AaronOS repository!

This is where all the open-source bits of aOS will reside, so the public can read it and even make pull requests if you wish.

AaronOS is usable at https://aaronos.dev/

If you wish to deploy AaronOS on your own server, you MUST read and follow the End User License Agreement at /eula.txt
By downloading or otherwise using this source code in any way, you agree to the terms listed in that file.
Upon hosting AaronOS, the first connection to the project (which performs first-time setup) will also prompt you with this file.

# **Important notice to existing server operators**
Updates were made on April 9, 2022 to address a security issue on the official AaronOS server.
All data stored on your personal AaronOS servers is still intact, but you will be logged out of your AaronOS profile after pulling this update.

You can still use the "Load a different aOS" button in Settings - Information to get back into your existing account.

Big changes are coming to the way AaronOS stores files in the future. Keep an eye out.

# **Setup**

## Prerequisites
Make sure you have PHP and a webserver like Apache or Nginx (The official aOS uses Apache).
You can install them by running the following in your terminal:

```sudo apt install -y Apache2 php```

## Setting Up
We need to open the ports aOS uses, grab aOS from Github, move it into the correct folder, than change the folder permissions so that Apache can work properly.

Note that this guide written for Ubuntu; you may need to make changes for another system.

First open the ports:

```sudo ufw allow "Apache Full"```

Make sure you're in your public HTML directory, then clone from Github.

```
cd /var/www/html
git clone https://github.com/MineAndCraft12/AaronOS
```

Give ownership of the public HTML directory to Apache and let Apache write to it.

``` 
sudo chown -R www-data /var/www/
sudo chmod -R 755 /var/www/html
```

IMPORTANT! It is absolutely vital that Apache2 is configured to honor .htaccess files!

Your Apache configuration file is likely located at /etc/apache2/apache2.conf

Scroll down quite a bit, and locate lines that look similar to this, for your public HTML directory:

```
<Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride None
        Require all granted
</Directory>
```

Make sure the AllowOverride option is set to "All", NOT "None".
This is incredibly important for the security of your users.

Start Apache.

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
 * Non-related files I keep on the server
 * Old, not-in-development versions of aOS

The main PHP file is /aosBeta.php

The main JavaScript file is /scriptBeta.js

The main CSS file is /styleBeta.css
