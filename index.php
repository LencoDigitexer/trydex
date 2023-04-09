<?php require "misc/header.php"; ?>

    <title>Trydex</title>
    </head>
    <body>
        <form class="search-container" action="search.php" method="get" autocomplete="off">
                <h1>Tryde<span class="X">X</span></h1>
                
                
                <div class="search-button-wrapper">
                    <input type="text" name="q" autofocus/>
                    <input type="hidden" name="p" value="0"/>
                    <input type="hidden" name="t" value="0"/>
                    <input type="submit" class="hide"/>
                    <button name="t" value="0" type="submit">Найти</button>
                </div>
        </form>

<?php require "misc/footer.php"; ?>
