<?php require "misc/header.php"; ?>

    <title>Trydex</title>
    </head>
    <body>
        <form class="search-container" action="search.php" method="get" autocomplete="off">
                <h1>Tryde<span class="X">X</span></h1>
                <input type="text" name="q" autofocus/>
                <input type="hidden" name="p" value="0"/>
                <input type="hidden" name="t" value="0"/>
                <input type="submit" class="hide"/>
                <div class="search-button-wrapper">
                    <button name="t" value="0" type="submit">Поиск в TrydeX</button>
                </div>
        </form>
    </body>
</html>