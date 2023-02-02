<?php
    $config = require "config.php";
    require "misc/tools.php";

    if (!isset($_REQUEST["q"]))
    {
        echo "<p>Пример API запроса: <a href=\"./api.php?q=GNU&p=2&t=0\">./api.php?q=GNU&p=2&t=0</a></p>
        <br/>
        <p>\"q\" это запрос в поиск</p>
        <p>\"p\" номер страницы результатов (первая страница 0)</p>
        <p>\"t\" тип поиска (0=google, 10=yandex, 1=форумы, 2=видео)</p>
        <br/>
        <p>Результаты будут в формате JSON.</p>
        <p>API поддерживает как запросы POST, так и GET.</p>";

        die();
    }

    $query = $_REQUEST["q"];
    $query_encoded = urlencode($query);
    $page = isset($_REQUEST["p"]) ? (int) $_REQUEST["p"] : 0;
    $type = isset($_REQUEST["t"]) ? (int) $_REQUEST["t"] : 0;

    $results = array();

    switch ($type)
    {
        case 0:
            require "engines/google_mobile/text.php";
            $results = get_text_results($query, $page);
            break;
        case 10:
            require "engines/yandex/text.php";
            $results = get_text_results($query, $page);
            break;
        case 1:
            require "engines/crowdview/forums.php";
            $results = get_text_results($query_encoded, $page);
            break;
        case 2:
            require "engines/brave/video.php";
            $results = get_video_results($query_encoded);
            break;
        case 3:
            if ($config->disable_bittorent_search)
                $results = array("error" => "disabled");
            else
            {
                require "engines/bittorrent/merge.php";
                $results = get_merged_torrent_results($query_encoded);
            }
            break;
        case 4:
            if ($config->disable_hidden_service_search)
                $results = array("error" => "disabled");
            else
            {
                require "engines/ahmia/hidden_service.php";
                $results = get_hidden_service_results($query_encoded);
            }
            break;
        default:
            require "engines/google/text.php";
            $results = get_text_results($query_encoded, $page);
            break;
    }

    header("Content-Type: application/json");
    echo json_encode($results);
?>
