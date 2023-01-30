<?php
$config = require "config.php";


if (isset($_REQUEST["save"]) || isset($_REQUEST["reset"])) {
    if (isset($_SERVER["HTTP_COOKIE"])) {
        $cookies = explode(";", $_SERVER["HTTP_COOKIE"]);
        foreach ($cookies as $cookie) {
            $parts = explode("=", $cookie);
            $name = trim($parts[0]);
            setcookie($name, "", time() - 1000);
        }
    }

}

if (isset($_REQUEST["save"])) {
    foreach ($_POST as $key => $value) {
        if (!empty($value)) {
            setcookie($key, $value, time() + (86400 * 90), '/');
            $_COOKIE[$name] = $value;
        }
    }
}

if (isset($_REQUEST["save"]) || isset($_REQUEST["reset"])) {
    header("Location: ./settings.php");
    die();
}

require "misc/header.php";
?>

<title>TrydeX - Settings</title>
</head>

<body>
    <div class="misc-container">
        <h1>Настройки</h1>
        <form method="post" enctype="multipart/form-data" autocomplete="off">
            <div>
                <label for="theme">Тема:</label>
                <select name="theme">
                    <?php
                    $themes = "<option value=\"dark\">Темная</option>
                    <option value=\"yandex\">Yandex</option>
                    <option value=\"darker\">Darker</option>
                    <option value=\"amoled\">AMOLED</option>
                    <option value=\"light\">Light</option>
                    <option value=\"auto\">Auto</option>
					<option value=\"dracula\">Dracula</option>
                    <option value=\"nord\">Nord</option>
                    <option value=\"night_owl\">Night Owl</option>
                    <option value=\"discord\">Discord</option>
                    <option value=\"google\">Google Dark</option>
                    <option value=\"startpage\">Startpage Dark</option>
                    <option value=\"gruvbox\">Gruvbox</option>
                    <option value=\"github_night\">GitHub Night</option>";

                    if (isset($_COOKIE["theme"])) {
                        $cookie_theme = $_COOKIE["theme"];
                        $themes = str_replace($cookie_theme . "\"", $cookie_theme . "\" selected", $themes);
                    }

                    echo $themes;
                    ?>
                </select>
            </div>
            <div>
                <label for="theme">Поисковик:</label>
                <select name="engines">
                    <?php
                    $engines = "<option value=\"yandex\">Яндекс</option>
                    <option value=\"google\">Google</option>";

                    if (isset($_COOKIE["engines"])) {
                        $cookie_engine = $_COOKIE["engines"];
                        $engines = str_replace($cookie_engine . "\"", $cookie_engine . "\" selected", $engines);
                    }

                    echo $engines;
                    ?>
                </select>
            </div>
            <?php
            $edit_settings = $config->edit_settings;
            if ($edit_settings == "1") { ?>
                <div>
                    <label>Отключить специальные запросы (например: конвертация валюты)</label>
                    <input type="checkbox" name="disable_special" <?php echo isset($_COOKIE["disable_special"]) ? "checked" : ""; ?>>
                </div>
                <h2>Альтернативные варианты сервисов</h2>
                <p>Для примера, если вы хотите просматривать YouTube без слежки, нажмите на "Invidious", найдите наиболее
                    подходящий для вас экземпляр и вставьте его в (правильный формат: https://example.com )</p>
                <div class="settings-textbox-container">
                    <?php

                    $frontends = array(
                        "invidious" => array("https://docs.invidious.io/instances/", "YouTube"),
                        "bibliogram" => array("https://git.sr.ht/~cadence/bibliogram-docs/tree/master/docs/Instances.md", "Instagram"),
                        "rimgo" => array("https://codeberg.org/video-prize-ranch/rimgo#instances", "Imgur"),
                        "scribe" => array("https://git.sr.ht/~edwardloveall/scribe/tree/main/docs/instances.md", "Medium"),
                        "gothub" => array("https://codeberg.org/gothub/gothub/wiki/Instances", "GitHub"),
                        "librarian" => array("https://codeberg.org/librarian/librarian#clearnet", "Odysee"),
                        "nitter" => array("https://github.com/zedeus/nitter/wiki/Instances", "Twitter"),
                        "libreddit" => array("https://github.com/spikecodes/libreddit", "Reddit"),
                        "proxitok" => array("https://github.com/pablouser1/ProxiTok/wiki/Public-instances", "TikTok"),
                        "wikiless" => array("https://github.com/Metastem/wikiless#instances", "Wikipedia"),
                        "quetre" => array("https://github.com/zyachel/quetre", "Quora"),
                        "libremdb" => array("https://github.com/zyachel/libremdb", "IMDb"),
                        "breezewiki" => array("https://gitdab.com/cadence/breezewiki", "Fandom"),
                        "anonymousoverflow" => array("https://github.com/httpjamesm/AnonymousOverflow#clearnet-instances", "StackOverflow")
                    );

                    foreach ($frontends as $frontend => $info) {
                        echo "<div>";
                        echo "<a for=\"$frontend\" href=\"" . $info[0] . "\" target=\"_blank\">" . ucfirst($frontend) . "</a>";
                        echo "<input type=\"text\" name=\"$frontend\" placeholder=\"Replace " . $info[1] . "\" value=";
                        echo isset($_COOKIE["$frontend"]) ? htmlspecialchars($_COOKIE["$frontend"]) : json_decode(json_encode($config), true)[$frontend];
                        echo ">";
                        echo "</div>";
                    }
                    ?>
                </div>
                <div>
                    <label>Отключить сервисы</label>
                    <input type="checkbox" name="disable_frontends" <?php echo isset($_COOKIE["disable_frontends"]) ? "checked" : ""; ?>>
                </div>
                <h2>Настройки Google</h2>
                <div class="settings-textbox-container">
                    <div>
                        <span>Язык запросов в Google</span>
                        <?php
                        echo "<input type=\"text\" name=\"google_language\" placeholder=\"E.g.: de\" value=\"";
                        echo isset($_COOKIE["google_language"]) ? htmlspecialchars($_COOKIE["google_language"]) : $config->google_language;
                        ?>">
                    </div>
                    <div>
                        <label>Безопасный поиск</label>
                        <input type="checkbox" name="safe_search" <?php echo isset($_COOKIE["safe_search"]) ? "checked" : ""; ?>>
                    </div>
                </div>
            <?php } else { ?>
            <?php } ?>

            <div>
                <button type="submit" name="save" value="1">Сохранить</button>
                <button type="submit" name="reset" value="1">Сбросить</button>
            </div>
        </form>
    </div>

    <?php require "misc/footer.php"; ?>