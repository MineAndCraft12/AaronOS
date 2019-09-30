<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <script defer src="script.js"></script>
    </head>
    <body>
        <canvas
            id="cnv"
            onmousedown="blast.mouseDown(event); canvasElement.focus()"
            onmouseup="blast.mouseUp(event); return false;"
            onmousemove="blast.mouseMove(event); return false;"
        ></canvas>
    </body>
</html>