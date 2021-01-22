<?php
    header('Access-Control-Allow-Origin', '*');
    $logInfo = json_encode($_POST);
    if(!is_dir('errors')){
        mkdir('errors');
    }
    if(!file_exists('errors/issues.json')){
        file_put_contents('errors/issues.json', '{"hrefs": {}}');
    }
    $totalLogs = json_decode(file_get_contents('errors/issues.json'), true);
    if(!property_exists($totalLogs['hrefs'], $_POST['href'])){
        $totalLogs['hrefs'][$_POST['href']] = (object)[];
        $totalLogs['hrefs'][$_POST['href']]['timestamps'] = [];
        $totalLogs['hrefs'][$_POST['href']]['blasts'] = [];
        $totalLogs['hrefs'][$_POST['href']]['loadingElements'] = [];
        $totalLogs['hrefs'][$_POST['href']]['cpStringExists'] = [];
        $totalLogs['hrefs'][$_POST['href']]['cpStrings'] = [];
        $totalLogs['hrefs'][$_POST['href']]['dashboardNames'] = [];
        $totalLogs['hrefs'][$_POST['href']]['dashboardDescs'] = [];
    }

    array_push($totalLogs['hrefs'][$_POST['href']]['timestamps'], strval(time()));

    if(!in_array($_POST['blast'], $totalLogs['hrefs'][$_POST['href']]['blasts'])){
        array_push($totalLogs['hrefs'][$_POST['href']]['blasts'], $_POST['blast']);
    }

    if(!in_array($_POST['loadingElement'], $totalLogs['hrefs'][$_POST['href']]['loadingElements'])){
        array_push($totalLogs['hrefs'][$_POST['href']]['loadingElements'], $_POST['loadingElement']);
    }

    if(!in_array($_POST['copyrightExists'], $totalLogs['hrefs'][$_POST['href']]['cpStringExists'])){
        array_push($totalLogs['hrefs'][$_POST['href']]['cpStringExists'], $_POST['copyrightExists']);
    }

    if(!in_array($_POST['copyrightString'], $totalLogs['hrefs'][$_POST['href']]['cpStrings'])){
        array_push($totalLogs['hrefs'][$_POST['href']]['cpStrings'], $_POST['copyrightString']);
    }

    if(!in_array($_POST['dashboardName'], $totalLogs['hrefs'][$_POST['href']]['dashboardNames'])){
        array_push($totalLogs['hrefs'][$_POST['href']]['dashboardNames'], $_POST['dashboardName']);
    }

    if(!in_array($_POST['dashboardDescStr'], $totalLogs['hrefs'][$_POST['href']]['dashboardDescs'])){
        array_push($totalLogs['hrefs'][$_POST['href']]['dashboardDescs'], $_POST['dashboardDescStr']);
    }

    file_put_contents('errors/issues.json', json_encode($totalLogs));

    //file_put_contents('errors/error_'.strval(time()).'.txt', $logInfo);
?>