<!DOCTYPE html>
<html>
    <head>
        <title>~/workspace/<?php echo $_GET['d']; ?></title>
        <style>
            body{
                font-family:monospace;
                line-height:17px;
            }
            a{
                text-decoration:none;
                background-repeat:no-repeat;
                padding-left:21px;
                padding-bottom:1em;
                margin-bottom:-1em;
            }
            .the_loader_icon{
                background-image:url(/icon16/progress.gif);
            }
            a:focus{
                background-image:url(/icon16/progress.gif) !important;
            }
        </style>
    </head>
    <body>
        <?php
            $dirstr = $_GET['d'];
            if(!is_dir($dirstr)){
                exit('Invalid directory');
            }
            
            $filetypes = array(
                'txt', 'css', 'html', 'htm', 'php', 'js', 'json', 'md', 'markdown', 'cfg', 'conf', 'java', 'ini', 'log', 'pdf', 'lnk', 'gemrc', 'bashrc', 'bash_aliases', 'bash_history', 'bash_logout', 'gitignore', 'gitconfig',
                'apk', 'appx', 'deb', 'rpm', 'zip', 'gz', 'git', 'ext2', 'ext3', 'ext4', 'ext4dev', 'jar', 'fat', 'vfat', 'cramfs', 'minix', 'msdos', 'nfs', 'ntfs',
                'png', 'jpg', 'gif', 'ico', 'ttf', 'woff', 'svg', 'otf', 'eot',
                'mp4', 'webm', 'wmv', 'wav', 'mp3', 'ogg',
                'sb2', 'swf', 'bin', 'exe', 'sh', 'pid', 'sessions', 'bat', 'class'
            );
            
            foreach(scandir($dirstr) as $file) {
                if('.' === $file) continue;
                if('..' === $file){
                    echo '<a href="/getDir.php?d='.$dirstr.'/.." class="the_loader_icon"><i>..</i></a><br>';
                } else {
                    if(is_dir($dirstr.'/'.$file)){
                        echo '<a href="/getDir.php?d='.$dirstr.'/'.$file.'" style="background-image:url(/icon16/folder.png)"><i>'.$file.'</i></a><br>';
                    } else {
                        $lowstr = strtolower(substr($file, strrpos($file, '.') + 1, strlen($file) - strrpos($file, '.')));
                        if(array_search($lowstr, $filetypes) !== false){
                            echo '<a href="'.$dirstr.'/'.$file.'" style="background-image:url(icon16/'.$lowstr.'.png);">'.$file.'</a><br>';
                        }else{
                            echo '<a href="'.$dirstr.'/'.$file.'" style="background-image:url(icon16/file.png);">'.$file.'</a><br>';
                        }
                    }
                }
            }
        ?>
    </body>
    <script defer>
        setTimeout(function(){
            document.getElementsByClassName('the_loader_icon')[0].className = 'folder_icon';
        }, 1000);
    </script>
</html>