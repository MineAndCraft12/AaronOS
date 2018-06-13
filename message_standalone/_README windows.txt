AaronOS Messenger from aOS B0.7.1
README file

=====

FILE TREE:
    aOSmessage: (everything)
        messages: (folder containing all messages sent on messenger)
            m0.txt (first message in server - NEVER delete this message)
            m[number].txt (message sent by a user)
                {
                "i": "User's ID number",
                "n": "User's Username",
                "c": "Message Content",
                "t": "Time Message Was Sent",
                "l": "ID Number of Message"
                }
        passw: (folder containing user passwords)
            u_[number].txt ([number] is user's ID number, password is bcrypt hashed)
        users: (folder containing user names)
            u_[number].txt ([number] is user's ID number)
        aosMessage.php (MAIN WEBPAGE, LINK USERS HERE - feel free to change the name if you need to
        hr.png (used for <hr> element)
        loadMessage.php (loads a message)
        messagePing.wav (notification sound when user is away - feel free to replace it with your own)
        messages.txt (current number of existing messages, automatically updated)
        saveMessage.php (saves a message)
        setUsername.php (sets a user's name and password when they first sign up)
        users.txt (current number of users, automatically updated)
    _README.txt (pretty heccin obvious what this is)

=====

INSTALLATION:
    Simply place all of the files in  into a directory.
    The webpage users interact with is aosMessage.php
    You can re-name that file if you wish.
    
    In theory, it should work right out of the box.
    Please contact mineandcraft12@gmail.com if it does not. Send error messages if you can.

=====

MODIFYING MESSAGES:
    Sometimes users will post bad things in chat, or post with a bad username.
    
    If you need to modify a message, here is how to do that:
        Find the message in question.
        Highlight from the right side of its username to beyond its left side.
            Secret invisible text should be revealed.
        Find the file m[number] in the messages folder.
            The number should be in the secret invisible text.
            You may need to count ahead a few messages, to find the right one.
        Find "c":"(content)" within that message.
        Now you can modify the (content) as you see fit.
            Make sure to never include a double-quote character within the message.
        Find "n":"(username)" within that message.
        Now you can modify the (username) as you see fit.
            Make sure to never include a double-quote character within the name.


MODIFYING USERNAMES:
    If you need to set an admin account or modify a user's name, here you go.
    
    Find a message sent by the user.
    Highlight from the right side of its username to beyond its left side.
        Secret invisible text should be revealed.
    Find the file u_[number] in the users folder.
        The number should be in the secret invisible text.
    Now you can modify the username as you see fit.
        If you are making a user an admin, simply add "{ADMIN}" at the beginning of their name.


CLEARING THE MESSAGES:
    Sometimes the messageboard will get out of control, or you need to do some housekeeping.
    
    Make sure that users are unable to post new messages (temporarily stop server, or however you want).
    Delete all the files from the messages folder, EXCEPT for m0.txt
    Modify messages.txt and set its content to 0.
    Make sure your users are able to post new messages again! (restart server, or however you did).
    It's a good idea to post a message yourself, letting users know that the messages were cleared.


CHANGING NOTIFICATION SOUND:
    To change the notification sound, you can do this.
    
    Get a hold of a .wav sound file you want to use.
    Overwrite the messagePing.wav file with your file.
    Make sure its name remains as messagePing.wav


RETRIEVING USER PASSWORDS:
    There's several reasons one would need to retrieve their password.
    
    Perhaps the user has lost their password, and you need to get it back for them.
    Perhaps you need to get into the user's account to fix something for them.
    Perhaps you need to verify that the encryption algorythm is working correctly.
    The process is difficult to explain in plain text, though.
    I posted a detailed video on how to retrieve a user's password here...   https://www.youtube.com/watch?v=dQw4w9WgXcQ