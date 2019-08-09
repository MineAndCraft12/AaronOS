<!DOCTYPE html>
    <head>
        <link rel="stylesheet" href="style.css">
        <script defer src="../aosTools.js"></script>
        <script defer src="script.js"></script>
    </head>
    <body>
        <div class="winHTML">
            <h1>Documentation</h1>
            <div id="navigate">
                <ul id="mainList" onclick="selectDocument(event)">
                    <!--
                    <li>List Item</li>
                    <li>List Item Folder</li>
                    <ul>
                        <li>Folder List Item</li>
                        <li>Folder List Item</li>
                    </ul>
                    -->
                </ul>
            </div>
            <div id="content">

<div class="docPage" id="doc_welcome" data-doc-title="Welcome">
    <h1>Welcome to the AaronOS Developer Documentation.</h1>
    <p><i>This documentation is not yet finished, so please mind the dust.</i></p>
    <p>The documentation is mostly meant for those writing web apps for use in AaronOS.</p>
    <p>Select a topic on the left to get started.</p>
    <p><i>This document assumes a basic level of web development experience. Please contact Aaron to let him know if something needs more detail.</i></p>
</div>

<div class="docPage" id="doc_webapps" data-doc-title="Web Apps">
    <h1>Web Apps</h1>
    <p>
        In this documentation, you will learn how to make your own web app and get it working in AaronOS.
        This document is a work-in-progress and is incomplete.
    </p>
    <hr>
    <h1 class="docHeader" id="doc_webapps_what_is">What is a Web App?</h1>
    <p>
        You're probably familiar with the term Web App, but if not, here's a quick explanation.
    </p>
    <p>
        A web app is a program you can run on your computer that uses a web browser to render its UI.
        In many cases, these web apps will have a large chunk of their UI exist on the web (one example of this is the Quora app).
        In the case of AaronOS, because installing native apps can mean a security risk to users, most 3rd-party apps will be web apps.
        Sometimes even official AaronOS apps work as web apps, because it grants a performance boost or to make development easier.
    </p>
    <p>
        Web apps for AaronOS are actually their own completely separate webpages, which look like AaronOS UI.
        Because of this, anyone can host their own app for aOS, and get it added to a repository so that users can install it on aOS.
        We'll get into repositories later, but for now let's build the basics of a web app.
    </p>
    <hr>
    <h1 class="docHeader" id="doc_webapps_prereq">Prerequisites</h1>
    <p>
        Unfortunately, this guide is going to start with a bit of prerequisites.
        To host and use your own app, you're going to need a web server with HTTPS, on a domain aOS can access.
    </p>
    <p>
        Most free hosts should do the trick as long as they provide HTTPS.
        Many of you interested in this will likely already have a host or site of some sort.
        If not, you'll need one to make and use your app.
    </p>
    <p>
        First we need to test if your host allows AaronOS to load it in a frame.
        Load up AaronOS (if not already), open up the JavaScript Console app, paste the following code, and then run it.
    </p>
    <pre><code>
        openapp(apps.iFrameBrowser, "dsktp");
    </code></pre>
    <p>
        That code should open the hidden iFrame Browser app.
        Once the app is open, enter the URL to your website into the address field (make sure to include <code>https://</code> at the beginning) and click Go.
        If your website successfully loads into the frame, then we should be all set.
        If not, then your website or your browser are blocking AaronOS from loading your site. It's more likely to be the website.
        If this is the case, then you need to figure out with your host (or, if you personally run the server, your server configuration) a way to allow AaronOS to load your site.
    </p>
    <p>
        One more prerequisite is some basic knowledge of HTML, CSS, and JavaScript. If something needs explaining, don't hesitate to ask for more explanation. I might have missed something.
    </p>
    <hr>
    <h1 class="docHeader" id="doc_webapps_getting_started">Getting Started</h1>
    <p>
        There are three essential things that hold an AaronOS web app together.
        <ul>
            <li>HTML Webpage</li>
            <li>JavaScript Code</li>
            <li>aosTools.js <i>(technically you don't need this, but it makes life much easier)</i></li>
        </ul>
    </p>
    <p>
        First of all, we need a file structure. This is simply an example structure; you can do whatever you want.
        <ul>
            <li>your_app/ <i>(name this whatever you want - this will be the folder AaronOS requests)</i></li>
            <ul>
                <li>index.html <i>(the main UI page of your app - can also be .php)</i></li>
                <li>script.js <i>(the main script of your app)</i></li>
                <li>style.css <i>(the main stylesheet of your app)</i></li>
            </ul>
        </ul>
    </p>
    <p>
        You're most likely already familiar with what the basic HTML skeleton page looks like; something like this:
    </p>
    <pre><code>
    &lt;!DOCTYPE html&gt;
    &lt;html&gt;
        &lt;head&gt;
            &lt;title&gt;Page Title&lt;/title&gt;
            &lt;link rel="stylesheet" href="style.css"&gt;
            &lt;script src="script.js"&gt;&lt;/script&gt;
        &lt;/head&gt;
        &lt;body&gt;
            &lt;p&gt;Page content&lt;/p&gt;
        &lt;/body&gt;
    &lt;/html&gt;
    </code></pre>
    <p>
        We're going to make some modifications to that page. We need to reference the aosTools.js script, put a wrapper around the page content (for compatibility with themes), and write some JavaScript.
    </p>
    <p>
        First, here's what our index.html should look like:
    </p>
    <pre><code>
    &lt;!DOCTYPE html&gt;
    &lt;html&gt;
        &lt;head&gt;
            &lt;title&gt;Page Title&lt;/title&gt;
            &lt;link rel="stylesheet" href="style.css"&gt;
            &lt;!-- aosTools.js is grabbed from aaronos.dev --&gt;
            &lt;script defer src="https://aaronos.dev/AaronOS/aosTools.js"&gt;&lt;/script&gt;
            &lt;script defer src="script.js"&gt;&lt;/script&gt;
        &lt;/head&gt;
        &lt;body&gt;
            &lt;!-- theme compatibility wrapper --&gt;
            &lt;div class="winHTML"&gt;
                &lt;p&gt;Page content&lt;/p&gt;
                &lt;!-- a test button is an easy way to see if theme support is working --&gt;
                &lt;button&gt;Button&lt;/button&gt;
            &lt;/div&gt;
        &lt;/body&gt;
    &lt;/html&gt;
    </code></pre>
    <p>
        Next, here's what our script.js should look like: <i>(there's a version without comments coming up)</i>
    </p>
    <pre><code>
        /*
            connectListener is called when your webpage successfully connects to AaronOS.
            You can also add a listener for if your webpage cannot connect to aOS.
                This usually only happens when your page is being loaded outside of aOS.
                That listener's name is:
                window.aosTools_connectFailListener
        */
        window.aosTools_connectListener = function(){
            /*
                This tells AaronOS to open the app window.
                If you need to do extra things before you want the user to see the UI,
                    then do that first, and THEN run the line of code below.
            */
            aosTools.openWindow();
        }

        /*
            Since we don't know if your script loaded before aosTools.js did,
                we need to check if aosTools was already set up.
        */
        if(typeof aosTools === "object"){
            /*
                If the test above works, then we need to have aosTools.js initialize itself.
            */
            aosTools.testConnection();
        }
        /*
            if aosTools.js hasn't initialized yet,
                then it will run the above line of code on its own once it's ready.
        */
    </code></pre>
    <p>
        If you don't want all those comments, here's the uncommented code:
    </p>
    <pre><code>
        window.aosTools_connectListener = function(){
            aosTools.openWindow();
        }

        if(typeof aosTools === "object"){
            aosTools.testConnection();
        }
    </code></pre>
    <p>
        Next, you'll need to add your app to a repository to load it into AaronOS.
        If you don't have one, then open the Repositories document on the left side to find out how to make one.
    </p>
</div>

<div class="docPage" id="doc_repo" data-doc-title="Repositories">
    <h1>Repositories</h1>
    <p>
        A repository is a collection of apps, styles, scripts, and other utilities that users can install to AaronOS.
        This guide will show you what a repository typically looks like, and what the app information files look like as well.
        If all goes well, you should be able to load your own app into AaronOS by the end of this document.
    </p>
    <hr>
    <h1 class="docHeader" id="doc_repo_making_a_repo">Making a Repository</h1>
    <p>
        This section will focus on what is required for a repository, and will show you how to add a web app to the repository.
    </p>
    <p>
        First order of business is that we need a file to store our repository in.
        It's a good idea to make a separate repository for your released apps, and apps you're testing on.
        For now, we're going to make a testing repository.
    </p>
    <p>
        You can name your repository file anything you want as long as AaronOS can parse its contents as valid JSON.
        My recommendation would be something like <code>repository.json</code>, and I also recommend keeping your repository and its apps in its own folder.
        This way your server won't become cluttered with JSON files.
    </p>
    <p>
        First, I'm going to show you what a basic repository looks like.
        Then I'll go over what each entry does.
        Here's the code for your <code>repository.json</code>:
    </p>
    <pre><code>
        {
            "repoName": "My Testing Repository",
            "repoID": "your_name_here_or_something_unique",
            "repoAliases": ["alternate", "search", "terms"],
            "repoVersion": "1.0",
            "packages": {

            }
        }
    </code></pre>
    <p>
        <code>repoName</code>: This is the name of your repository as it appears to your users.
    </p>
    <p>
        <code>repoID</code>: This is the ID of your repository.
        AaronOS uses this ID in several integral functions to tell repositories apart.
        Make sure this is a unique term that no one else will use. It's best to stick to letters, numbers, and underscores.
        <b style="color:#F00">MAKE SURE THIS IS UNIQUE AND UNLIKELY TO ACCIDENTALLY CAUSE CONFLICTS, OR BIG BAD WILL HAPPEN.</b>
    </p>
    <p>
        <code>repoAliases</code>: This is a list of alternate phrases that users can search for to find your repository.
        For instance, a game developer might use an alias of "games", or a suite of office tools might use an alias like "office".
    </p>
    <p>
        <code>repoVersion</code>: This is the format that your repository follows. At the moment, there's only a version 1.0 of the repository syntax.
        However, if more syntaxes are added in the future, this line lets AaronOS know to use the old syntax with your repository.
    </p>
    <p>
        <code>packages</code>: This is where all of your web apps, stylesheets, and scripts will go.
    </p>
    <hr>
    <h1 class="docHeader" id="doc_repo_webapp_package">Web App Package Syntax</h1>
    <p>
        You're most likely here to figure out how to add a web app to your repository and load it into AaronOS. That's what this section will cover.
    </p>
    <p>
        Before you can add your app to a repository, you need to make sure that your app has a webpage and that AaronOS can access your site.
        If you haven't already, open up the Web Apps documentation on the left-hand side to find out how.
    </p>
    <p>
        For a web app, we're going to need a new JSON file to store the information of your app.
        Some of this information will be mirrored in your repository so that aOS can check for updates without loading your entire app.
        I recommend placing all of your apps' JSON files either within their app's own directory, or in a folder next to your repository.
        You can name this file whatever you want, but make sure it's named such that you know what it is.
    </p>
    <p>
        Below I will show you what this file should look like; then I'll tell you what each of the values mean.
    </p>
    <pre><code>
        {
            "name": "My Web App",
            "id": "unique_id_for_my_app",
            "version": "1.0",
            "abbreviation": "App",
            "appType": "webApp",
            "homeURL": "https://yourwebsite.com/myWebApp/index.html",
            "icon": {
                "foreground": "https://yourwebsite.com/myWebApp/repoIcon.png",
                "backgroundColor": "#303947",
                "backgroundBorder": {
                    "thickness": 2,
                    "color": "#252F3A"
                }
            },
            "windowSize": [500, 400],
            "manualOpen": 1
        }
    </code></pre>
    <p>
        <code>name</code>: This is the name of your web app as it appears to your users in AaronOS.
    </p>
    <p>
        <code>id</code>: This is the ID of your app. Make sure this file is the same between this JSON file and your repository's reference to this file (we'll look at that later).
        Also make sure that no other app or utility in your repository uses the same ID. It's best to stick to letters, numbers, and underscores.
    </p>
    <p>
        <code>version</code>: This is the version of your app's repository listing.
        Increment this number here and in your repository every time you change this listing, to ensure your users get the update.
    </p>
    <p>
        <code>abbreviation</code>: This is an abbreviation between one and three letters, for use in the Dashboard as a three-letter search shortcut.
        Users will be able to search for your three-letter abbreviation rather than the entire name of your app.
    </p>
    <p>
        <code>appType</code>: This is the type of item that this entry is formatted to be. For a web app, always make sure this says "webApp".
    </p>
    <p>
        <code>homeURL</code>: This is the URL to the webpage that AaronOS will load in your app window.
        Make sure to triple-check this for typos or no one will be able to use your app.
    </p>
    <p>
        <code>icon</code>: Repository apps use AaronOS's Smart Icons.
        The most important part here is "foreground" -- this is the front of the icon that people see when browsing the store and using your app.
        The other bits should be self explanatory; but since Smart Icons aren't the same as normal icons, I'll explain briefly what all of that is.
        Smart Icons are icons that conform to a shape specified by the user of AaronOS. This can range anywhere from circles to teardrops to squares.
        The values provided above will help your app blend in best with AaronOS's built-in icons, but you can change this to anything you want.
        Keep in mind that users can disable the background, or override your background color. Make sure your logo is readable in the case that this happens.
    </p>
    <p>
        <code>windowSize</code>: This is the width and height of your window when AaronOS first opens it. Try not to get too tiny or too huge with these values.
    </p>
    <p>
        <code>manualOpen</code>: This tells AaronOS whether to immediately open your app once launched, or to wait for your app to open the window itself.
        Set this value to <code>1</code> if you want to manually open your app window with aosTools.
        Set this value to <code>0</code> if you want AaronOS to do this itself, or if your app doesn't use aosTools.
        <b style="color:#F00">If your app does not use aosTools to manually open the app window, set this value to 0! Otherwise, your app will never open when users try to launch it.</b>
    </p>
    <hr>
    <h1 class="docHeader" id="doc_repo_webapp_repo">Web App Repo Syntax</h1>
    <p>
        Now that you have an app package JSON file, we need to add an entry to your repository that will link back to this file.
        If you remember the <code>repository.json</code> file we made earlier (though you may have named it something else), it had an empty space for packages to go.
        Now it's time to add our new package.
    </p>
    <p>
        Again, I'm going to give you the code, and then explain below what each of the values mean.
    </p>
    <pre><code>
        {
            "repoName": "My Testing Repository",
            "repoID": "your_name_here_or_something_unique",
            "repoAliases": ["alternate", "search", "terms"],
            "repoVersion": "1.0",
            "packages": {

                "unique_id_for_my_app": {
                    "packageName": "My Web App",
                    "packageID": "unique_id_for_my_app",
                    "packageAliases": ["test", "first", "mine"],
                    "version": "1.0",
                    "packageType": "webApp",
                    "installURL": "https://yourwebsite.com/myWebApp/package.json",
                    "icon": {
                        "foreground": "https://yourwebsite.com/myWebApp/repoIcon.png",
                        "backgroundColor": "#303947",
                        "backgroundBorder": {
                            "thickness": 2,
                            "color": "#252F3A"
                        }
                    },
                    "description": "This is my first app ever! :D"
                }

            }
        }
    </code></pre>
    <p>
        <code>"unique_id_for_my_app": {</code>: This is the ID of your web app. Make sure that it matches the packageID a couple lines down.
        Make sure that it also matches the ID in your package's JSON file.
    </p>
    <p>
        <code>packageName</code>: This is the name of your web app as it appears to your users in AaronOS. It's a good idea to make this match the name in your package's JSON file.
    </p>
    <p>
        <code>packageID</code>: This is, again, the ID of your web app. Again, make sure that this matches the one a couple lines above and in your app's JSON file.
    </p>
    <p>
        <code>packageAliases</code>: This is a list of alternate terms that users can search for to find your app in the aOS Hub.
        If Google Chrome were on the Hub, it would likely use an alias like "browser" or "internet".
        If Halo were on the Hub, it would likely use aliases like "game" and "fps".
    </p>
    <p>
        <code>version</code>: This is the version number of your app's package JSON file.
        Make sure this matches the version number in your JSON file, or your app will always claim that it needs an update.
        Whenever you update your app's JSON file, remember to increment the version number there and here.
    </p>
    <p>
        <code>packageType</code>: This is again the type of package that is being represented. For web apps, always make this "webApps".
    </p>
    <p>
        <code>installURL</code>: This will be the URL to your web app's package JSON file; the file that we wrote up a few moments before this one.
        Make sure to triple-check this for typos, or AaronOS won't be able to install your app.
    </p>
    <p>
        <code>icon</code>: This is the Smart Icon that your app uses in its Hub listing.
        It's a good idea to make this match the icon in your package file, but it's not strictly necessary to do so.
    </p>
    <p>
        <code>description</code>: This is the description of your app on its Hub listing.
        You can make this as long as you need, but try not to make it too long, or people will skip reading it.
    </p>
    <hr>
    <h1 class="docHeader" id="doc_repo_loading">Loading your Repository</h1>
    <p>
        Now that you've followed the above tutorials and gotten your repository and app ready, we can add them to aOS.
    </p>
    <p>
        If you haven't already, open up AaronOS.
        Open the "aOS Hub" app, and in the top-right corner there should be a button that says "Repositories".
        Click that button, and the Hub should bring you to a list of your installed repositories.
    </p>
    <p>
        Scroll to the bottom and there should be a text box.
        Enter the URL to your <code>repository.json</code> file into that text box, then click "Add Repository".
        If your repository isn't added to the list, then you probably misspelled it, or something went wrong when you make the repository's JSON file.
    </p>
    <p>
        Now, click on the "All" category and search for your app's title in the search box in the upper-right corner.
        If all has gone well, your app should appear in the list of apps.
        If it isn't there, then something probably went wrong when you added the app to your repository's JSON file.
        If it's there, go ahead and click "Install" on your app, and then restart AaronOS.
    </p>
    <p>
        Once AaronOS reboots, your app should be available in the Dashboard on the lower-left corner of the screen.
        Search for your app in the Dashboard and launch it to make sure that it works.
        If it doesn't work, something probably went wrong when you made the app's JSON package.
    </p>
    <p>
        Once you get it working, congrats! You've made your first AaronOS web app.
        Your web app can do anything that any other website can do, along with interacting with AaronOS.
        A good idea is to browse through the topics on the left-hand side of the screen to learn all about how to use aosTools and various other development tips.
        At the moment, not much is there. But eventually the list will fill up.
    </p>
</div>

            </div>
        </div>
    </body>
</html>