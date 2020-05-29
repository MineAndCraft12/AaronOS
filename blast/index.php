<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <?php
            echo '<script defer src="script.js?ms='.round(microtime(true) * 1000).'"></script>';
        ?>
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