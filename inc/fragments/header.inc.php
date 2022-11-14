<?php
?>
<header class="entete">
    <a href="#contenu" class="visuallyhidden focusable">Allez au contenu</a>
    <div class="nav-conteneur">
        <nav class="nav" aria-label="Menu principal">
            <div class="haut-nav">
                <a class="logo__lien" href="<?php echo $niveau ?>index.php"><img class="logo__img" src="<?php echo($niveau . "images/logos/logo_Off/SVG/off.svg") ?> " alt=""></a>
                <div class="menu-btn">
                    <div class="menu-btn__burger"></div>
                </div>
            </div>
            <ul class="nav__list" id="navList">
                <li class="nav__list-item"><a class="nav__list-lien" href="<?php echo $niveau ?>programmation/index.php">Programmation</a></li>
                <li class="nav__list-item"><a class="nav__list-lien" href="<?php echo $niveau ?>artistes/index.php">Artistes</a></li>
                <li class="nav__list-item"><a class="nav__list-lien" href="#">Partenaires</a></li>
            </ul>
        </nav>
    </div>
    <a class="lien-passeport" href="#">Acheter mon passeport</a>
</header>