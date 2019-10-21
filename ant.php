<!DOCTYPE html>
<html>
<head>
    <style>
        html{
            width:100%;
            height:100%;
        }
        body{
            font-family:sans-serif;
            width:100%;
            height:100%;
            margin:0;
        }
        #antcnv{
            width:calc(100% - 300px);
            height:100%;
            display:block;
            position:absolute;
            right:0;
            background:#7F7F7F;
        }
        #controls{
            width:284px;
            height:calc(100% - 16px);
            padding:8px;
        }
        #input{
            width:250px;
            height:100px;
        }
    </style>
</head>
<body>
    <canvas id="antcnv"></canvas>
    <div id="controls">
        AaronOS Ant<br><br>
        <textarea id="input"></textarea><br><br>
        <button disabled>Random Inputs</button><br><br>
        <button onclick="run('slow');">Run Slow</button><br> <!-- 1 step per frame -->
        <button onclick="run('fast');">Run Fast</button><br> <!-- 10 steps per frame -->
        <button onclick="run('faster');">Run Faster</button><br> <!-- 50 steps per frame -->
        <button onclick="run('fastest')">Run Fastest</button><br> <!-- as many as possile per frame -->
        <button onclick="run()">Run Paused</button><br><br>

        <button onclick="pause();rendering = 0;">Pause</button><br>
        <button onclick="step();if(!rendering){render();}">Forward Step</button><br><br>
        <button disabled>Play Slow</button><br>
        <button disabled>Play Fast</button><br>
        <button disabled>Play Faster</button><br>
        <button disabled>Play Fastest</button>
    </div>
    <script defer>
        function getId(target){
            return document.getElementById(target);
        }

        getId("input").value = "" +
            "#FFF:right\n" +
            "#F00:left\n" +
            "#0F0:left\n" +
            "#0FF:right\n" +
            "#FF0:right\n" +
            "#F0F:right\n" +
            "#7F7F7F:left\n" +
            "#7F0000:right\n" +
            "#007F00:left\n" +
            "#00F:right\n" +
            "#7F7F00:left\n" +
            "#7F007F:left";
        
        var tree = [];

        var animFrameID = null;
        var intervalID = null;

        var size = [0, 0];
        var canvasElement = getId("antcnv");
        var canvas = canvasElement.getContext("2d");

        function run(type){
            pause();

            // parse input text.
            // Format:
            //  color:direction
            //  color:direction

            tree = getId("input").value.split("\n");
            for(var i in tree){
                tree[i] = tree[i].split(":");
            }

            // set up canvas and field
            size = [window.innerWidth - 300, window.innerHeight];
            canvasElement.width = size[0];
            canvasElement.height = size[1];
            canvasElement.style.width = size[0] + "px";
            canvasElement.style.height = size[1] + "px";
            canvas.clearRect(0, 0, size[0], size[1]);
            pos = [Math.floor(size[0] / 2), Math.floor(size[1] / 2)];
            currDirection = 0;
            changes = [];
            field = [];
            for(var i = 0; i < size[0]; i++){
                field[i] = [];
                for(var j = 0; j < size[1]; j++){
                    field[i][j] = tree[0][0];
                }
            }

            // set intervals and such
            rendering = 1;
            switch(type){
                case "slow":
                    animFrameID = requestAnimationFrame(doFrameStep);
                    render();
                    break;
                case "fast":
                    animFrameID = requestAnimationFrame(doFastStep);
                    render();
                    break;
                case "faster":
                    animFrameID = requestAnimationFrame(doFasterStep);
                    render();
                    break;
                case "fastest":
                    animFrameID = requestAnimationFrame(doFastestStep);
                    render();
                    break;
                default:
                    rendering = 0;
                    step();
                    render();
            }
        }

        function doFrameStep(){
            step();
            animFrameID = requestAnimationFrame(doFrameStep);
        }

        function doFastStep(){
            for(var i = 0; i < 10; i++){
                step();
            }
            animFrameID = requestAnimationFrame(doFastStep);
        }

        function doFasterStep(){
            for(var i = 0; i < 50; i++){
                step();
            }
            animFrameID = requestAnimationFrame(doFasterStep);
        }

        function doFastestStep(){
            var firstPerf = performance.now();
            while(performance.now() < firstPerf + 16){
                step();
            }
            animFrameID = requestAnimationFrame(doFastestStep);
        }

        function pause(){
            if(animFrameID){
                cancelAnimationFrame(animFrameID);
                animFrameID = null;
            }
            if(intervalID){
                clearInterval(intervalID);
            }
        }

        var pos = [0, 0];
        var field = [];

        var currDirection = 0;
        var directions = [
            function(){ // up
                pos[1] -= 1;
            },
            function(){ // right
                pos[0] += 1;
            },
            function(){ // down
                pos[1] += 1;
            },
            function(){ // left
                pos[0] -= 1;
            }
        ];

        function turn(direc){
            switch(direc){
                case "left":
                    currDirection -= 1;
                    break;
                case "right":
                    currDirection += 1;
                    break;
                default:

            }
            if(currDirection >= directions.length){
                currDirection -= directions.length;
            }else if(currDirection < 0){
                currDirection += directions.length;
            }
        }

        var totalSteps = 0;
        function step(){
            for(var i = 0; i < tree.length; i++){
                if(tree[i][0] === field[pos[0]][pos[1]]){
                    if(i !== tree.length - 1){
                        field[pos[0]][pos[1]] = tree[i + 1][0];
                        changes.push([pos[0], pos[1], tree[i + 1][0]]);
                        turn(tree[i + 1][1]);
                    }else{
                        field[pos[0]][pos[1]] = tree[0][0];
                        changes.push([pos[0], pos[1], tree[0][0]]);
                        turn(tree[0][1]);
                    }
                    directions[currDirection]();
                    break;
                }
            }
            totalSteps++;
        }
        
        var lastChanges = [];
        var changes = [];

        var rendering = 0;
        function render(){
            for(var i in changes){
                canvas.fillStyle = changes[i][2];
                canvas.fillRect(changes[i][0], changes[i][1], 1, 1);
            }
            if(rendering){
                requestAnimationFrame(render);
            }else{
                lastChanges = JSON.parse(JSON.stringify(changes));
            }
            changes = [];
        }
    </script>
</body>
</html>