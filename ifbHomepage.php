<!DOCTYPE html>
<html>
    <head>
        <title>iFrame Browser Homepage</title>
        <style>body{font-family:sans-serif;}</style>
    </head>
    <body>
        <h1>iFrame Browser</h1>
        <p>This app is intended for testing purposes. Its primary function is testing whether or not a webpage can load within an AaronOS application.</p>
        <p>
            Some common reasons a webpage might not be able to load:
            <ul>
                <li>AaronOS is loaded over HTTPS, but your webpage may have attempted to load over HTTP.</li>
                <li>Your webpage may forbid loading in a frame due to a restrictive X-Frame-Options header.</li>
                <li>Your browser or webpage may have rejected the request for some other reason -- check your Developer Tools console.</li>
            </ul>
        </p>
        <p>
            If you are attempting to view a webpage that forbids access, you can try to include a <i><b>trusted</b></i> proxy service's address with the Settings button.
            Be aware that if you use this option, any information that you view or provide on the webpage can possibly be seen by the service.
        </p>
    </body>
</html>