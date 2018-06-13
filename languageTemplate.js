// Copy-paste this into your BootScript file.
// This is configured for ENGLISH.
// Change these two vars to make a different language.
var newLanguage = 'en';
var newLanguageName = 'English';

// setting up, ignore this
languagepacks[newLanguage] = newLanguageName;

// from this point on, you can change the strings to fit your language.
// I will provide comments where necessary, to help show what a certain line means.


// aOS System

langContent[newLanguage] = {
    aOS: {
        framesPerSecond: 'FPS',
        cpuUsage: 'CPU',
        failedVarTry: 'failed', // lowercase
        fatalError1: 'You found an error! ',
        fatalError2: 'Error in',
        fatalError3: 'Module',
        fatalError4: 'at', // lowercase
        fatalError5: 'Send error report to the developer?',
        errorReport: 'Failed to save the report. The OS has either failed to initialize or crucial components have been deleted. Please email mineandcraft12@gmail.com with the details of your issue if you would like it fixed.'
    },
    appNames: {
        startMenu: "aOS Dashboard",
        nora: "NORAA",
        taskManager: "Task Manager",
        jsConsole: "JavaScript Console",
        bash: "Psuedo-Bash Terminal",
        cpuMon: "CPU Monitor",
        prompt: "Application Prompt",
        settings: "Settings",
        iconMaker: "Desktop Icon Maker",
        windowTest: "Window Test Application",
        testTwo: "Test App 2",
        ragdoll: "Rag Doll",
        notepad: "Text Editor",
        properties: "Properties Viewer",
        files: "File Manager",
        changelog: "Changelog",
        flashCards: "Flash Cards",
        pngSave: "PNG Saver",
        canvasGame: "Canvas Video Games",
        internet: "The Internet",
        aerotest: "Windowblur Test",
        savemaster: "SaveMaster",
        mouserecord: "Mouse Recorder",
        ti: "TI-83+ Simulator",
        appAPI: "aOS API",
        appmaker: "App Maker",
        calculator: "Calculator",
        search: "Search",
        image: "aOSimg Editor",
        changecalc: "Change Calculator",
        messaging: "Messaging",
        camera: "Camera",
        help: "aOS Help",
        musicVis: "Music Visualiser",
        perfMonitor: "Performance Monitor",
        mathway: "Mathway",
        appsbrowser: "Apps Browser",
        indycar: "Indycar",
        housegame: "House Game",
        simon: "Simon",
        postit: "Sticky Note",
        bootScript: "Boot Script",
        bugCentral: "Bug Central",
        rdp: "Remote Desktop Host",
        rdpViewer: "Remote Desktop Viewer",
        graph: "Function Grapher",
        extDebug: "External Debug",
        mouseControl: "Alternate Mouse Control",
        onlineDebug: "Online Debug Connection",
        fileBin: "File Binary",
        magnifier: "Magnifier",
        jana: "Jana",
        cookieClicker: "Cookie Clicker"
    },
    startMenu: {
        power: 'Power',
        taskManager: 'Task Manager',
        jsConsole: 'JavaScript Console',
        settings: 'Settings',
        files: 'Files',
        allApps: 'All Apps',
        aosHelp: 'aOS Help',
        search: 'Search',
        shutDown: 'Shut Down',
        restart: 'Restart'
    },
    ctxMenu: {
        settings: 'Settings',
        jsConsole: 'JavaScript Console',
        screenResolution: 'Change Screen Resolution',
        desktopBackground: 'Change Desktop Background',
        addIcon: 'Add Icon',
        speak: 'Speak',
        taskbarSettings: 'Taskbar Settings',
        openApp: 'Open',
        moveIcon: 'Move Icon',
        showApp: 'Show',
        hideApp: 'Hide',
        closeApp: 'Close',
        fold: 'Fold',
        fullscreen: 'Toggle Fullscreen',
        stayOnTop: 'Stay On Top',
        stopOnTop: 'Stop Staying On Top',
        copyText: 'Copy',
        pasteText: 'Paste'
    },
    jsConsole: {
        caption: 'Javascript Console',
        runCode: 'Run Code',
        input: 'Input'
    },
    prompt: {
        caption: 'Application Prompt',
        genericAlert: 'This app is used for alerts and prompts in aOS apps.',
        ok: 'OK',
        alertText: 'wants to tell you', // lowercase
        alertUnnamed: 'Alert from an anonymous app',
        confirmText: 'wants a choice from you', // lowercase
        confirmUnnamed: 'Pick a choice for an anonymous app',
        promptText: 'wants some info from you', // lowercase
        promptUnnamed: 'Enter some info for an anonymous app',
    },
    notepad: {
        caption: 'Text Editor',
        // these are all buttons...
        save: 'Save',
        load: 'Load',
        file: 'File',
        tools: 'Tools'
    }
};

// Settings App

apps.settings.language[newLanguage] = {
    valuesMayBeOutdated: 'All values below are from the time the Settings app was opened.',
    bgImgURL: 'Background Image URL',
    imgTile: 'Images tile to cover the screen.',
    performance: 'Performance',
    dbgLevel: 'Debug Level',
    dbgExplain: 'Sets how verbose aOS is in its actions. The different levels determine how often console messages appear and why.',
    perfModeOn: 'Running in Performance Mode',
    perfModeTog: 'If aOS is consistently running at a low FPS, try using',
    perfModeDesc: 'Performance Mode attempts to raise framerate by lowering the CPU usage of some system functions',
    info: 'Info',
    cookies: 'By using this site you are accepting the small cookie the filesystem relies on and that all files you or your aOS apps generate will be saved on the aOS server for your convenience (and, mostly, for technical reasons).',
    networkOn: 'Network Online',
    batteryLevel: 'Battery Level',
    batteryDesc: 'If the amount above is -100, then your computer either has no battery or the battery could not be found.',
};