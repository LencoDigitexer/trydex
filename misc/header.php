<!DOCTYPE html >
<html lang="ru">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta charset="UTF-8"/>
        <meta name="description" content="Приватная мета-поисковая система."/>
        <meta name="referrer" content="no-referrer"/>
        <link rel="stylesheet" type="text/css" href="static/css/styles.css"/>
        <link title="TrydeX search" type="application/opensearchdescription+xml" href="/opensearch.xml?method=POST" rel="search"/>
        <link rel="stylesheet" type="text/css" href="<?php
                echo "static/css/";
                if (isset($_COOKIE["theme"]) || isset($_REQUEST["theme"]))
                    echo htmlspecialchars((isset($_COOKIE["theme"]) ? $_COOKIE["theme"] : $_REQUEST["theme"]) . ".css");
                else
                    echo "yandex.css";
        ?>"/>
