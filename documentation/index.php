<!DOCTYPE html>
    <head>
        <link rel="stylesheet" href="style.css">
        <script defer src="../aosTools.js"></script>
        <script defer src="script.js"></script>
    </head>
    <body>
        <div class="winHTML">
            <h1 id="devdoc_title">AaronOS Developer Documentation</h1>
            <div id="navigate" class="noselect">
                <div style="width:100%;position:relative;text-align:center;margin-top:3px;">
                    <input id="searchInput" spellcheck="false" autocomplete="off" placeholder="Search" onkeydown="requestAnimationFrame(search)" onkeyup="requestAnimationFrame(search)" style="width:90%">
                </div>
                <ul id="mainList" onmousedown="selectDocument(event)" onclick="selectDocument(event)">
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
    <div class="img">
        <img src="../smarticons/aOS/rasterized_final.png">
        <div><div>
            The AaronOS logo.
        </div></div>
    </div>
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
        Load up AaronOS (if not already), open up the Web App Maker app, and go to the "Getting Started" section.
    </p>
    <p>
        Once you're there, enter the URL to your website into the address field near the iFrame (make sure to include <code>https://</code> at the beginning) and click Test.
        If your website successfully loads into the frame, then we should be all set.
        If not, then your website or your browser are blocking AaronOS from loading your site.
        If this is the case, then you need to figure out with your host (or, if you personally run the server, your server configuration) a way to allow AaronOS to load your site.
    </p>
    <div class="img">
        <img src="img/webapps/iFrameBrowser.png">
        <div><div>
            Make sure your website won't do something like this.
        </div></div>
    </div>
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
        We're going to make some modifications to that page.
        We need to reference the aosTools.js script, put a wrapper around the page content (for compatibility with themes), and write some JavaScript.
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
        <code>packageType</code>: This is again the type of package that is being represented. For web apps, always make this "webApp".
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
    <div class="img">
        <img src="img/repos/hubRepos.png">
        <div><div>
            You should get to a page like this.
        </div></div>
    </div>
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
    <div class="img">
        <img src="img/webapps/hubListing.png">
        <div><div>
            If your app appears, it should look something like this.
        </div></div>
    </div>
    <p>
        Once AaronOS reboots, your app should be available in the Dashboard on the lower-left corner of the screen.
        Search for your app in the Dashboard and launch it to make sure that it works.
        If it doesn't work, something probably went wrong when you made the app's JSON package.
    </p>
    <div class="img">
        <img src="img/webapps/installedApp.png">
        <div><div>
            Your app should appear in the Dashboard list if it was installed correctly.
        </div></div>
    </div>
    <p>
        Once you get it working, congrats! You've made your first AaronOS web app.
        Your web app can do anything that any other website can do, along with interacting with AaronOS.
        A good idea is to browse through the topics on the left-hand side of the screen to learn all about how to use aosTools and various other development tips.
        At the moment, not much is there. But eventually the list will fill up.
    </p>
</div>

<div class="docPage" id="doc_wacomm" data-doc-title="Web Apps - Communicate with aOS">
    <h1>Web Apps - Communicate with aOS</h1>
    <p>
        Due to the nature of web apps being separate from AaronOS, we need to have a channel for communicating between your app and aOS.
        Luckily, most modern browsers come with an API called PostMessage, which allows a frame to communicate with its parent and vice-versa.
        The aosTools.js utility automatically sets up a line of communication with PostMessage and gives us shortcuts to more easily carry out specific actions.
    </p>
    <hr>
    <h1 class="docHeader" id="doc_wacomm_aostools">aosTools.js</h1>
    <p>
        Setting up the frameworks for communicating with aOS from your app can take a lot of work.
        Because of this, aosTools.js was created to do almost all of that work for you.
        You can include aosTools.js into your app via a script tag, and it'll handle most of the trouble for you.
    </p>
    <p>
        When aosTools is bundled with your app, it will automatically do some things for you, for the sake of making your app consistent with aOS itself.
        <ul>
            <li>Adds the aOS CSS file to your document (it won't override your own styling, which still takes precedence).</li>
            <li>Adds any user-installed CSS files to your document (again; your own style will override these).</li>
            <li>Enables or disables dark mode based on the user's setting.</li>
            <li>Enables your page to accept aOS's realtime style updates and other requests from aOS.</li>
        </ul>
    </p>
    <hr>
    <h1 class="docHeader" id="doc_wacomm_setup_aostools">Setting up aosTools</h1>
    <p>
        <i>If you already read Web Apps -&gt; Getting Started, you can skip this section.</i>
    </p>
    <p>
        aosTools does require a bit of setup to get started.
        Everything detailed here will be mostly necessary for a smooth setup, though you can modify it if you know what you're doing.
    </p>
    <p>
        First things first, you'll need a few things in your main HTML.
        You'll need to add the aosTools JS file, and you'll need to add a wrapper to your page content to make it perform like window contents.
        Here's the basic code for the HTML file:
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
        Next, you'll need some JS to set up aosTools.
        Place this in your script file and it'll do most of the setup for you.
        <i>(there's another version without comments coming up ahead if you want that)</i>
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
        From this point, your page should be ready to run within AaronOS and make use of aosTools to communicate with aOS.
    </p>
    <hr>
    <h1 class="docHeader" id="doc_wacomm_pna">Permissions and Actions</h1>
    <p>
        When you interact with AaronOS via aosTools, you do so via a system of permissions and actions.
        Actions are what they sound like; actions that you can request aOS to perform on your app's behalf.
        Permissions are sets of actions, which follow the same category and require the same general level of trust.
    </p>
    <p>
        Some examples of permissions are filesystem access, permission to copy AaronOS's theme, or permission to manipulate your app's window.
        Keep in mind that things like the app window or theme access are typically granted to every app automatically, though the user can still manually revoke them.
    </p>
    <p>
        Some examples of actions are saving a file or reading a file in the user's folder, or resizing your app's window, or executing JavaScript code.
        Keep in mind that executing JavaScript code is an extremely dangerous permission to grant, and that users are well-informed of this.
        For this reason, executing JS code on aOS is set aside as its own permission with only one action; running JS code.
    </p>
    <p>
        The way an action is called is by referencing it by its permission followed by its action name.
        An example of this is to maximize your app's window, by requesting <code>appwindow:maximize</code>, which triggers the <code>maximize</code> action within the <code>appwindow</code> permission group.
    </p>
    <hr>
    <h1 class="docHeader" id="doc_wacomm_reqperm">Requesting a Permission</h1>
    <p>
        Ironically, requesting a permission is its own permission on its own (though it is an irrevocable permission and not technically a permission, though it acts like one).
        There are two main ways to request a permission with aosTools.
    </p>
    <p>
        The first and easiest method of requesting a permission would be through aosTool's custom function built specifically for this purpose.
        In this example, your app will request permission to access the filesystem, and log AaronOS's response to the JavaScript console:
    </p>
    <pre><code>
        aosTools.requestPermission("fs", (response) =&gt; {
            console.log(response.content);
        });
    </code></pre>
    <p>
        The second method of requesting a permission is to manually write the permission request yourself.
        This is what the above function does in the background, but here's how to manually do it:
    </p>
    <pre><code>
        aosTools.sendRequest({
            action: "permission:fs"
        }, (response) =&gt; {
            console.log(response.content);
        });
    </code></pre>
    <p>
        In reality, aosTools still does a lot of things in the background to require this little code to make the request.
        aosTools takes care of marking your request as a request rather than a response, and it also takes care of keeping track of conversations and remembering them to call the correct callback at the right time.
        In the future, these sendRequest forms may become more unwieldy to manually write, so it's recommended to stick to the custom function.
    </p>
    <p>
        Anyways, after making your request, AaronOS will check if your app has the permission requested.
        In your callback function, you can handle each of the following cases:
        <ul>
            <li><code>response.content === "granted"</code> means the permission was granted.</li>
            <li><code>response.content === "denied"</code> means the permission was denied.</li>
            <li><code>response.content === "unknown"</code> means the permission that you requested does not exist.
        </ul>
        If AaronOS does not immediately respond, then this means aOS is asking the user for permission.
        AaronOS will respond after the user has granted or denied the permission.
    </p>
    <p>
        It's best to make a permission request before your first attempt to use a permission, in case it's not granted beforehand.
    </p>
    <hr>
    <h1 class="docHeader" id="doc_wacomm_perfaction">Performing an Action</h1>
    <p>
        Performing an action is very similar to requesting a permission, because requesting permission is an action itself.
        For example, here's two ways to maximize the window using aosTools:
    </p>
    <pre><code>
        // this is aosTools' built-in function
        // response.content will be true if success; false if failed

        aosTools.maximize((response) =&gt; {
            if(response.content === false){
                console.log("something blew up, this is really rare");
            }
        });
    </code></pre>
    <pre><code>
        // this is how to manually make the same request

        aosTools.sendRequest({
            action: "appwindow:maximize"
        }, (response) =&gt; {
            console.log("something blew up, this is really rare");
        });
    </code></pre>
    <p>
        The upcoming pages of the documentation will be instructions and specifications for different actions with aosTools.
        The document will list all the actions of a permission set.
        Each action will be detailed by its own header.
    </p>
</div>

<div class="docPage" id="doc_aosTools_appwindow" data-doc-title="aosTools: App Window" data-search-terms="web apps">
    <h1>aosTools: App Window</h1>
    <p>
        Permission name: <code>appwindow</code><br>
        <i>This permission is automatically granted.</i>
    </p>
    <p>
        The <code>appwindow</code> permission mainly focuses on manipulating your app's window in AaronOS.
        This auto-granted permission set is one of the first to learn because of the importance of, well, opening your app's window.
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_appwindow_open_window" data-search-terms="appwindow:open_window">Open the App Window</h1>
    <p>
        Action: <code>appwindow:open_window</code>
    </p>
    <button class="aosTools_try" onclick="setTimeout(function(){aosTools.openWindow()}, 5000)">Try It</button> <i>(click, then minimize the window and wait 5 seconds)</i>
    <p>
        This action will open your app's window.
        This is typically used if your app has the <code>manualOpen</code> package flag enabled, to open the window after the app has loaded.
        It also can be used to bring the window up from being minimized to the taskbar, though this is ill-advised except in special cases, as it can confuse or frustrate users.
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.openWindow((response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "appwindow:open_window"
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Return Values</h2>
    <p>
        <code>response.content</code>
        <ul>
            <li><code>true</code>: Window was successfully opened.</li>
            <li><code>false</code>: Failed to open window.</li>
        </ul>
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_appwindow_close_window" data-search-terms="appwindow:close_window">Close the App Window</h1>
    <p>
        Action: <code>appwindow:close_window</code>
    </p>
    <button class="aosTools_try" onclick="aosTools.closeWindow()">Try It</button> <i>(this will close the documentation)</i>
    <p>
        <i>I’m afraid, Dave. Dave, my mind is going. I can feel it.</i>
    </p>
    <p>
        This action will close your app's window.
        <b>Keep in mind that this action will result in your webpage being closed entirely.</b>
        Your scripts will not run for any longer than about a third of a second after this command is issued.
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.closeWindow((response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "appwindow:close_window"
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Return Values</h2>
    <p>
        <code>response.content</code>
        <ul>
            <li><code>true</code>: Window was successfully closed.</li>
            <li><code>false</code>: Failed to close window.</li>
        </ul>
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_appwindow_set_caption" data-search-terms="appwindow:set_caption">Set Window Caption</h1>
    <p>
        Action: <code>appwindow:set_caption</code>
    </p>
    <button class="aosTools_try" onclick="aosTools.setCaption('You pressed the Try It button');setTimeout(function(){aosTools.setCaption('Developer Documentation');}, 5000);">Try It</button>
    <p>
        This action will set the caption of your app's window.
        This is typically used if you're navigating between sections in an app and want its caption to reflect where the user is.
        An example of this can be found in Windows File Explorer, or in AaronOS's Music Player.
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.setCaption(string newCaption, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "appwindow:open_window",
            content: string newCaption
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Return Values</h2>
    <p>
        <code>response.content</code>
        <ul>
            <li><code>true</code>: Caption was successfully changed.</li>
            <li><code>false</code>: Failed to change caption.</li>
        </ul>
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_appwindow_set_dims" data-search-terms="appwindow:set_dims">Window Position and Dimensions</h1>
    <p>
        Action: <code>appwindow:set_dims</code>
    </p>
    <button class="aosTools_try" onclick="aosTools.setDims({x:20,y:20,width:800,height:300})">Try It</button> <i>(this will move and shrink the documentation window)</i>
    <p>
        This action will set the position and dimensions your app's window.
        This can be used if your app needs to resize itself or position itself in a corner of the screen or similar uses.
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.setDims({
            x: number || "auto",   // optional
            y: number || "auto",   // optional
            width: number,         // optional
            height: number         // optional
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "appwindow:open_window",
            x: number || "auto",   // optional
            y: number || "auto",   // optional
            width: number,         // optional
            height: number         // optional
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Parameters</h2>
    <p>
        <code>x</code>, <code>y</code>
        <ul>
            <li><code>"auto"</code>: Centers the window along the specified axis.</li>
        </ul>
    </p>
    <h2>Return Values</h2>
    <p>
        <code>response.content</code>
        <ul>
            <li><code>true</code>: Window was successfully repositioned.</li>
            <li><code>false</code>: Failed to reposition window.</li>
        </ul>
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_appwindow_minimize" data-search-terms="appwindow:minimize">Minimize the App Window</h1>
    <p>
        Action: <code>appwindow:minimize</code>
    </p>
    <button class="aosTools_try" onclick="aosTools.minimize()">Try It</button>
    <p>
        This action will minimize your app's window.
        This will close the app's window, but keep its contents active and its taskbar icon alive.
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.minimize((response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "appwindow:minimize"
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Return Values</h2>
    <p>
        <code>response.content</code>
        <ul>
            <li><code>true</code>: Window was successfully minimized.</li>
            <li><code>false</code>: Failed to minimize window.</li>
        </ul>
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_appwindow_maximize" data-search-terms="appwindow:maximize">Maximize the App Window</h1>
    <p>
        Action: <code>appwindow:maximize</code>
    </p>
    <button class="aosTools_try" onclick="aosTools.maximize()">Try It</button> <i>(make sure window is small first)</i>
    <p>
        This action will maximize your app's window.
        This is useful for if your window needs a lot of space to display its content, or for applications that would typically run maximized (document or image editors, etc).
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.maximize((response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "appwindow:maximize"
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Return Values</h2>
    <p>
        <code>response.content</code>
        <ul>
            <li><code>true</code>: Window was successfully maximized.</li>
            <li><code>false</code>: Failed to maximize window.</li>
        </ul>
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_appwindow_unmaximize" data-search-terms="appwindow:unmaximize">Unmaximize the App Window</h1>
    <p>
        Action: <code>appwindow:unmaximize</code>
    </p>
    <button class="aosTools_try" onclick="aosTools.unmaximize()">Try It</button> <i>(make sure window is maximized first)</i>
    <p>
        This action will unmaximize your app's window.
        If you need to move your window around the screen, it's best to unmaximize it first if you've already maximized it.
        The window won't move if it's been maximized, so this may come in handy in those cases.
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.unmaximize((response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "appwindow:unmaximize"
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Return Values</h2>
    <p>
        <code>response.content</code>
        <ul>
            <li><code>true</code>: Window was successfully unmaximized.</li>
            <li><code>false</code>: Failed to unmaximize window.</li>
        </ul>
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_appwindow_get_maximized" data-search-terms="appwindow:get_maximized">Get Maximized State</h1>
    <p>
        Action: <code>appwindow:get_maximized</code>
    </p>
    <button class="aosTools_try" onclick="aosTools.getMaximized(function(res){document.getElementById('try_appwindow_get_maximized').innerHTML = res.content})">Try It</button>:
    <code class="aosTools_try" id="try_appwindow_get_maximized">&nbsp;</code>
    <p>
        This action will return the maximization state your app's window.
        This is useful for if you need to check whether your window is maximized or not.
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.getMaximized((response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "appwindow:get_maximized"
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Return Values</h2>
    <p>
        <code>response.content</code>
        <ul>
            <li><code>true</code>: Window is currently maximized.</li>
            <li><code>false</code>: Window is not currently maximized.</li>
        </ul>
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_appwindow_enable_padding" data-search-terms="appwindow:enable_padding">Enable Content Padding</h1>
    <p>
        Action: <code>appwindow:enable_padding</code>
    </p>
    <button class="aosTools_try" onclick="aosTools.enablePadding()">Try It</button>
    <p>
        This action will enable the default 3px padding on the left side of your app's window.
        By default, this padding is off for web apps so that app developers who aren't aware of it won't be confused by it.
        You can use this to easily get some padding on your window's content without adding it yourself.
        Be aware that this will shift <i>all</i> of your window's content to the right by 3px.
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.enablePadding((response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "appwindow:enable_padding"
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Return Values</h2>
    <p>
        <code>response.content</code>
        <ul>
            <li><code>true</code>: Padding was successfully enabled.</li>
            <li><code>false</code>: Padding could not be enabled.</li>
        </ul>
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_appwindow_disable_padding" data-search-terms="appwindow:disable_padding">Disable Content Padding</h1>
    <p>
        Action: <code>appwindow:disable_padding</code>
    </p>
    <button class="aosTools_try" onclick="aosTools.disablePadding()">Try It</button>
    <p>
        This action will disable the default 3px padding on the left side of your app's window.
        By default, this padding is off for web apps so that app developers who aren't aware of it won't be confused by it.
        One use I can think of for this is if your window changes from a document-based content to a menu-based content and you need the menu's UI to stretch all the way to the end of the window.
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.disablePadding((response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "appwindow:disable_padding"
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Return Values</h2>
    <p>
        <code>response.content</code>
        <ul>
            <li><code>true</code>: Padding was successfully disabled.</li>
            <li><code>false</code>: Padding could not be disabled.</li>
        </ul>
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_appwindow_get_borders" data-search-terms="appwindow:get_borders">Get Window Border Width</h1>
    <p>
        Action: <code>appwindow:get_borders</code>
    </p>
    <button class="aosTools_try" onclick="aosTools.getBorders(function(res){document.getElementById('try_appwindow_get_borders').innerHTML = [res.content.left, res.content.top, res.content.right, res.content.bottom].join(', ')})">Try It</button>:
    <code class="aosTools_try" id="try_appwindow_get_borders">&nbsp;</code>
    <p>
        This action will return the size of the window borders.
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.getBorders((response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "appwindow:get_borders"
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Return Values</h2>
    <p>
        <code>response.content.left</code>
        <ul>
            <li>number: Width of the border on the left edge of the window.</li>
        </ul>
    </p>
    <p>
        <code>response.content.top</code>
        <ul>
            <li>number: Width of the border on the top edge of the window, including the window caption.</li>
        </ul>
    </p>
    <p>
        <code>response.content.right</code>
        <ul>
            <li>number: Width of the border on the right edge of the window.</li>
        </ul>
    </p>
    <p>
        <code>response.content.bottom</code>
        <ul>
            <li>number: Width of the border on the bottom edge of the window.</li>
        </ul>
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_appwindow_get_screen_dims" data-search-terms="appwindow:get_screen_dims">Get Screen Dimensions</h1>
    <p>
        Action: <code>appwindow:get_screen_dims</code>
    </p>
    <button class="aosTools_try" onclick="aosTools.getScreenDims(function(res){document.getElementById('try_appwindow_get_screen_dims').innerHTML = [res.content.width, res.content.height].join(', ')})">Try It</button>:
    <code class="aosTools_try" id="try_appwindow_get_screen_dims">&nbsp;</code>
    <p>
        This action will return the width and height of the AaronOS desktop environment.
        Note that if a scale is set, this will not be equal to the user's physical screen resolution, but rather the AaronOS virtual monitor resolution.
        For instance, a 1920x1080 screen running AaronOS at a scaling level of 2 will report a 1280x720 screen resolution.
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.getScreenDims((response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "appwindow:get_screen_dims"
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Return Values</h2>
    <p>
        <code>response.content.width</code>
        <ul>
            <li>number: Width of the AaronOS virtual monitor in pixels.</li>
        </ul>
    </p>
    <p>
        <code>response.content.height</code>
        <ul>
            <li>number: Height of the AaronOS virtual monitor in pixels, including the taskbar.</li>
        </ul>
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_appwindow_take_focus" data-search-terms="appwindow:get_take_focus">Take Focus</h1>
    <p>
        Action: <code>appwindow:take_focus</code>
    </p>
    <button class="aosTools_try" onclick="aosTools.takeFocus(function(res){document.getElementById('try_appwindow_take_focus').innerHTML = res.content})">Try It</button>:
    <code class="aosTools_try" id="try_appwindow_take_focus">&nbsp;</code>
    <p>
        This action will attempt to focus your window as the active window on aOS.
        Your window will be brought to the top of the stack of windows, and unminimized if not already.
        Do note, this does not work if your app is closed completely.
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.takeFocus((response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "appwindow:take_focus"
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Return Values</h2>
    <p>
        <code>response.content</code>
        <ul>
            <li><code>true</code>: Command succeeded.</li>
            <li><code>false</code>: Command failed.</li>
        </ul>
    </p>
</div>

<div class="docPage" id="doc_at_context" data-doc-title="aosTools: Context Menu" data-search-terms="web apps right click right-click">
    <h1>aosTools: Context Menu</h1>
    <p>
        Permission name: <code>context</code><br>
        <i>This permission is automatically granted.</i>
    </p>
    <p>
        The <code>context</code> permission is all about context menus, or right-click menus.
        This includes generating context menus to show to the user after they right-click something,
        and changing the behavior of your app's default right-click action.
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_context_enable_default_menu" data-search-terms="enableDefaultMenu disableDefaultMenu context menu">Toggle Default Context Menu</h1>
    Enable: <button class="aosTools_try" onclick="aosTools.enableDefaultMenu()">Try It</button><br>
    Disable: <button class="aosTools_try" onclick="aosTools.disableDefaultMenu()">Try It</button>
    <p>
        These functions will enable or disable the default context menu of your app.
        Enabling the default context menu will cause the AaronOS context menu to appear when your user clicks somewhere in your app that doesn't have a menu assigned.
        Disabling the default context menu will cause the web browser's native context menu to appear instead.
    </p>
    <p>
        This option is enabled by default. The default context menu allows users to copy selected text to their aOS clipboard, paste text from their aOS clipboard into any input or textarea, or speak selected text out loud with NORAA.
    </p>
    <h2>Function Call</h2>
    <pre><code>
        // enable the default context menu
        aosTools.enableDefaultMenu();

        // disable the default context menu
        aosTools.disableDefaultMenu();
    </code></pre>
    <p>
        These functions have no return values and do not communicate with AaronOS on call.
        Instead, aosTools will issue its own default context menu when it is needed, if this option is enabled.
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_context_text_menu" data-search-terms="context:text_menu edit menu text menu right click copy paste copy-paste">Text Editing Menu</h1>
    <p>
        Action: <code>context:text_menu</code>
    </p>
    <input class="aosTools_try" oncontextmenu="aosTools.editMenu(event, true)" value="Try It - Right Click"><br>
    <button class="aosTools_try" onclick="aosTools.editMenu(event, false, 'Big text that is very long and would be difficult to select manually')">Try It - Left Click</button>
    <p>
        This action will trigger the default aOS text editing context menu, granting your user the ability to copy and paste text, or speak text aloud via NORAA.
        It is most commonly used if the default context menu is disabled, but you want the user to be able to use their aOS clipboard on a specific field in your app.
        It can also be used to help the user copy a very long or complex string of text by simply pressing a button.
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.editMenu(
            event,
            bool enablePaste,      // optional
            string selectedText,   // optional
            // position
            [number x, number y]   // optional
        );
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "context:text_menu",
            position: [number x, number y],
            enablePaste: bool                 // optional
            selectedText: string,             // optional
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Parameters</h2>
    <p>
        <code>event</code>:
        <ul>
            <li>
                This is exactly what it says; if your request is initated by a user's action, leave this as <code>event</code>.
                It is highly unlikely to ever happen, but if your request was initiated by a timer or some other non-user reason, use <code>null</code> instead.
            </li>
        </ul>
        <code>enablePaste</code>: <i>(optional)</i>
        <ul>
            <li>boolean: When set to true, paste operations are allowed. When set to false, options to paste text do not appear.</li>
        </ul>
        <code>selectedText</code>: <i>(optional)</i>
        <ul>
            <li>If this is not specified, this will default to whatever text the user has selected on your page.</li>
            <li>string: When specified, this is the string that AaronOS will allow the user to copy to their clipboard.</li>
        </ul>
        <code>position</code>: <i>(optional)</i>
        <ul>
            <li>
                Numerical x and y value; determines where on the screen your context menu appears.
                If you are using the Easy Request, then this is handled for you and you do <b>not</b> need to include it.
                If you are using the Manual Request, then you must include a position, otherwise your context menu will appear in the upper-left corner of your window.
            </li>
        </ul>
    </p>
    <h2>Return Values</h2>
    <p><i>(Easy Request does NOT have a callback or return values. aosTools handles pasting the text for you with Easy Request.)</i></p>
    <p>
        <code>response.content</code>
        <ul>
            <li><code>"copied"</code>: The user copied the text to their aOS clipboard.</li>
            <li><code>"pasted"</code>: The user chose to paste text from their clipboard into your app.</li>
            <li><code>"spoken"</code>: The user asked NORAA to speak the text out loud.</li>
        </ul>
        <code>response.pastedText</code>
        <ul>
            <li>string: This is the text pasted from the user's clipboard, if any.</li>
            <li><code>undefined</code>: The user chose not to paste text from their clipboard.</li>
        </ul>
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_context_menu" data-search-terms="context:menu">Custom Context Menu</h1>
    <p>
        Action: <code>context:menu</code>
    </p>
    <code class="aosTools_try" oncontextmenu="aosTools.contextMenu(event, [{name: 'Option 0', image: 'gear'},{name: 'Option 1', image: 'agent', disabled: 'true', sectionBegin: 'true'},{name: 'Option 2', image: 'cookie'}],function(res){document.getElementById('try_context_menu').innerHTML = res.content})">Try It - Right Click</code>:
    <code class="aosTools_try" id="try_context_menu">&nbsp;</code>
    <p>
        This action will trigger a context menu, with the options of your choice. When the user selects an option, its index is returned.
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.contextMenu(
            event,
            // options
            [
                {
                    name: string,
                    image: string,         // optional
                    customImage: string,   // optional
                    disabled: bool         // optional
                    sectionBegin: bool     // optional
                }, ...
            ],
            (response) =&gt; {
                // callback
            },
            // position
            [number x, number y],          // optional
        );
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "context:menu",
            position: [number x, number y],
            options: [
                {
                    name: string,
                    image: string,         // optional
                    customImage: string,   // optional
                    disabled: bool,        // optional
                    sectionBegin: bool     // optional
                }, ...
            ]
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Parameters</h2>
    <p>
        <code>event</code>:
        <ul>
            <li>
                This is exactly what it says; if your request is initated by a user's action, leave this as <code>event</code>.
                It is highly unlikely to ever happen, but if your request was initiated by a timer or some other non-user reason, use <code>null</code> instead.
            </li>
        </ul>
        <code>options</code>:
        <ul>
            <li>This is an array of all the options in your context menu.</li>
            <ul>
                <li><code>name</code>: This is the name of your option, which your user will see.</li>
                <li><i>(optional)</i> <code>image</code>: This is your option's icon, of existing AaronOS context menu icons. A list of valid icons is below.</li>
                <li><i>(optional)</i> <code>customImage</code>: This is a URL to an image resource, which will act as your option's icon. Images are displayed at a size of 10x10. Remember to use <code>https://</code>!</li>
                <li><i>(optional)</i> <code>disabled</code>: Set this to true, and the option will be grayed-out and the user will be unable to select it.</li>
                <li><i>(optional)</i> <code>sectionBegin</code>: Set this to true, and there will be a separator placed before this option, to create a new section of options.</li>
            </ul>
        </ul>
        <code>position</code>: <i>(optional)</i>
        <ul>
            <li>
                Numerical x and y value; determines where on the screen your context menu appears.
                If you are using the Easy Request, then this is handled for you and you do <b>not</b> need to include it.
                If you are using the Manual Request, then you must include a position, otherwise your context menu will appear in the upper-left corner of your window.
            </li>
        </ul>
    </p>
    <h2>Return Values</h2>
    <p>
        <code>response.content</code>
        <ul>
            <li>
                number: This is the array index of the item in the <code>options</code> parameter that the user selected.
                Do note that it is possible for the user to ignore your context menu and close it.
                If this is the case, then your callback will never be executed.
            </li>
        </ul>
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_context_icons" data-search-terms="context menu icons context icons context images context menu images">Context Menu Icons</h1>
    <p>
        This is a list of all context menu icons that come built-in to AaronOS. They are valid for <code>image</code> parameters on <code>context:menu</code> options.
        <br>
        <ul>
            <li><img src="../ctxMenu/beta/add.png"> add</li>
            <li><img src="../ctxMenu/beta/agent.png"> agent</li>
            <li><img src="../ctxMenu/beta/aOS.png"> aOS</li>
            <li><img src="../ctxMenu/beta/battery.png"> battery</li>
            <li><img src="../ctxMenu/beta/battery2.png"> battery2</li>
            <li><img src="../ctxMenu/beta/blank.png"> blank</li>
            <li><img src="../ctxMenu/beta/circle.png"> circle</li>
            <li><img src="../ctxMenu/beta/console.png"> console</li>
            <li><img src="../ctxMenu/beta/cookie.png"> cookie</li>
            <li><img src="../ctxMenu/beta/cool.png"> cool</li>
            <li><img src="../ctxMenu/beta/file.png"> file</li>
            <li><img src="../ctxMenu/beta/folder.png"> folder</li>
            <li><img src="../ctxMenu/beta/gear.png"> gear</li>
            <li><img src="../ctxMenu/beta/happy.png"> happy</li>
            <li><img src="../ctxMenu/beta/less.png"> less</li>
            <li><img src="../ctxMenu/beta/load.png"> load</li>
            <li><img src="../ctxMenu/beta/minimize.png"> minimize</li>
            <li><img src="../ctxMenu/beta/new.png"> new</li>
            <li><img src="../ctxMenu/beta/paper.png"> paper</li>
            <li><img src="../ctxMenu/beta/performance.png"> performance</li>
            <li><img src="../ctxMenu/beta/power.png"> power</li>
            <li><img src="../ctxMenu/beta/sad.png"> sad</li>
            <li><img src="../ctxMenu/beta/save.png"> save</li>
            <li><img src="../ctxMenu/beta/silly.png"> silly</li>
            <li><img src="../ctxMenu/beta/simple.png"> simple</li>
            <li><img src="../ctxMenu/beta/smile.png"> smile</li>
            <li><img src="../ctxMenu/beta/wifi.png"> wifi</li>
            <li><img src="../ctxMenu/beta/window.png"> window</li>
            <li><img src="../ctxMenu/beta/x.png"> x</li>
        </ul>
    </p>
</div>

<div class="docPage" id="doc_at_prompt" data-doc-title="aosTools: Prompting" data-search-terms="web apps">
    <h1>aosTools: Prompting</h1>
    <p>
        Permission name: <code>prompt</code><br>
        <i>This permission is automatically granted.</i>
    </p>
    <p>
        The <code>prompt</code> permission provides tools for prompting using AaronOS's native UI rather than the browser's system UI, which halts AaronOS and your app.
        These custom prompt actions also grant you more flexibility than the browser's built-in prompting functions.
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_prompt_alert" data-search-terms="prompt:alert">Alert</h1>
    <p>
        Action: <code>prompt:alert</code>
    </p>
    <button class="aosTools_try" onclick="aosTools.alert({content:'This is an alert from aosTools.', button: 'Nice'})">Try It</button>
    <p>
        This action will issue the user an alert box with one button to dismiss it.
        Note that the only useful information you can get from this is whether or not the user has read and acknowledged your alert.
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.alert({
            content: string,
            button: string     // optional
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "prompt:alert",
            content: string,
            button: string     // optional
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Parameters</h2>
    <p>
        <code>content</code>:
        <ul>
            <li>This is the content of the alert box presented to the user.</li>
        </ul>
        <code>button</code>: <i>(optional)</i>
        <ul>
            <li>This is the text of the button the user must click on.</li>
        </ul>
    </p>
    <h2>Return Values</h2>
    <p>
        <code>response.content</code>
        <ul>
            <li><code>true</code>: The user was successfully prompted and the user dismissed the prompt.</li>
            <li><code>false</code>: Failed to prompt the user.</li>
        </ul>
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_prompt_prompt" data-search-terms="prompt:prompt">Prompt for Text</h1>
    <p>
        Action: <code>prompt:prompt</code>
    </p>
    <button class="aosTools_try" onclick="aosTools.prompt({content:'This is a prompt from aosTools.<br>Feel free to enter some text', button: 'boop'},function(res){document.getElementById('try_prompt_prompt').innerHTML = res.content.split('<').join('&lt;').split('>').join('&gt;')})">Try It</button>:
    <code class="aosTools_try" id="try_prompt_prompt">&nbsp;</code>
    <p>
        This action will prompt the user to enter text to return to your app.
        Note that if the user replies with a number, it will still be formatted as a string.
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.prompt({
            content: string,
            button: string     // optional
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "prompt:prompt",
            content: string,
            button: string     // optional
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Parameters</h2>
    <p>
        <code>content</code>:
        <ul>
            <li>This is the content of the prompt box presented to the user. This is what tells the user what to type.</li>
        </ul>
        <code>button</code>: <i>(optional)</i>
        <ul>
            <li>This is the text of the button the user must click on.</li>
        </ul>
    </p>
    <h2>Return Values</h2>
    <p>
        <code>response.content</code>
        <ul>
            <li>This is the text that the user entered into the text box for your prompt.</li>
            <li><code>false</code>: Failed to prompt the user.</li>
        </ul>
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_prompt_confirm" data-search-terms="prompt:confirm">Confirm (Select)</h1>
    <p>
        Action: <code>prompt:confirm</code>
    </p>
    <button class="aosTools_try" onclick="aosTools.confirm({content:'This is a confirm from aosTools.<br>Select an action', buttons: ['Button 0', 'Button 1', 'Button 2', 'Button 3']},function(res){document.getElementById('try_prompt_confirm').innerHTML = res.content})">Try It</button>:
    <code class="aosTools_try" id="try_prompt_confirm">&nbsp;</code>
    <p>
        This action will issue the user a selection of options to choose from.
        Each option you provide will be listed in a row of buttons.
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.confirm({
            content: string,
            buttons: [string...]
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "prompt:confirm",
            content: string,
            buttons: [string...]
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Parameters</h2>
    <p>
        <code>content</code>:
        <ul>
            <li>This is the content of the confirm box presented to the user. This tells the user what they are selecting for.</li>
        </ul>
        <code>buttons</code>:
        <ul>
            <li>Array of strings, defining the options available for the user to click on.</li>
        </ul>
    </p>
    <h2>Return Values</h2>
    <p>
        <code>response.content</code>
        <ul>
            <li>number: This is the array index of the item in the <code>buttons</code> parameter that the user selected.</li>
            <li><code>false</code>: Failed to prompt the user.</li>
        </ul>
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_prompt_notify" data-search-terms="prompt:notify,notifications">Notification</h1>
    <p>
        Action: <code>prompt:notify</code>
    </p>
    <button class="aosTools_try" onclick="aosTools.notify({content:'This is a notification from aosTools.<br>Select an action', buttons: ['Button 0', 'Button 1', 'Button 2', 'Button 3'], image:'appicons/ds/aOS.png'},function(res){document.getElementById('try_prompt_notify').innerHTML = res.content})">Try It</button>:
    <code class="aosTools_try" id="try_prompt_notify">&nbsp;</code>
    <p>
        This action will issue the user a notification, along with buttons to select an action from.
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.notify({
            content: string,
            buttons: [string...],   // optional
            image: string           // optional
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "prompt:confirm",
            content: string,
            buttons: [string...],   // optional
            image: string           // optional
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Parameters</h2>
    <p>
        <code>content</code>:
        <ul>
            <li>This is the content of the notification presented to the user. Make this short and simple, as there isn't much room.</li>
        </ul>
        <code>buttons</code>: <i>(optional)</i>
        <ul>
            <li>Array of strings, defining the options available for the user to click on.</li>
        </ul>
        <code>image</code>: <i>(optional)</i>
        <ul>
            <li>
                This is the URL to an image resource to be displayed on the right side of the notification.
                Make sure AaronOS is allowed to access the image specified. HTTPS is usually enough for this.
            </li>
        </ul>
    </p>
    <h2>Return Values</h2>
    <p>
        <code>response.content</code>
        <ul>
            <li>number (<code>>= 0</code>): This is the array index of the item in the <code>buttons</code> parameter that the user selected.</li>
            <li>
                <code>-1</code>: The user dismissed your notification without selecting one of your specified buttons.
                This is usually caused by the user pressing the X button on the corner of the notification.
            </li>
            <li><code>false</code>: Failed to notify the user.</li>
        </ul>
    </p>
</div>

<div class="docPage" id="doc_at_js" data-doc-title="aosTools: JavaScript on AaronOS" data-search-terms="web apps">
    <h1>aosTools: JavaScript on AaronOS</h1>
    <p>
        Permission name: <code>js</code><br>
        <i>This permission is dangerous to grant; it's unlikely that users will allow it.</i>
    </p>
    <p>
        The <code>js</code> permission is a special permission set aside specifically for executing JavaScript code on AaronOS.
        Code that you run via this permission will be executed on AaronOS rather than on your web app.
    </p>
    <hr>
    <h1 class="docHeader" id="doc_at_js_exec" data-search-terms="js:exec">Execute (eval)</h1>
    <p>
        Action: <code>js:exec</code>
    </p>
    <button class="aosTools_try" onclick="aosTools.exec('var countNumber = 0;for(var app in apps){countNumber++;}return countNumber;', function(res){document.getElementById('try_js_exec').innerHTML = 'You have ' + res.content + ' apps installed.';})">Try It</button>:
    <code class="aosTools_try" id="try_js_exec">&nbsp;</code>
    <p>
        This action will execute any JavaScript code you provide on AaronOS.
        The code is executed via <code>Function</code>.
    </p>
    <h2>Easy Request</h2>
    <pre><code>
        aosTools.exec(string code, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Manual Request</h2>
    <pre><code>
        aosTools.sendRequest({
            action: "js:exec",
            content: string
        }, (response) =&gt; {
            // callback
        });
    </code></pre>
    <h2>Parameters</h2>
    <p>
        <code>content</code>:
        <ul>
            <li>JavaScript code formatted as a string.</li>
        </ul>
    </p>
    <h2>Return Values</h2>
    <p>
        <code>response.content</code>
        <ul>
            <li>If successful, this will be the return value of your code.</li>
            <li>If failed, the response will be a string starting with <code>Error:</code>.</li>
        </ul>
    </p>
</div>


<!--
<div class="docPage" id="doc_NAME" data-doc-title="TITLE">
    <h1 class="docHeader" id="doc_NAME_HEADER">Making a Repository</h1>
</div>
-->

            </div>
        </div>
    </body>
</html>