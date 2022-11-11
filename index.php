<!doctype html>
<html lang="fr">
<head class="entete">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <title>Festival OFF de Québec - Accueil</title>
    <link rel="stylesheet" href="css/style-daren.css">
    <link rel="stylesheet" href="css/menu.css">
</head>
<body>
    <?php include($niveauLISTE . "inc/fragments/header.inc.php") ?>        
    <div class="page">
        <main id="contenu">
            <div class="banniere-conteneur">
                <h1>Festival OFF de Québec</h1>
                <h2>Le 22 Novembre 2023</h2>
                <picture class="banniere">
                    <source media="(min-width:800px)" srcset="https://via.placeholder.com/1920x900">
                    <source media="(max-width:799px)" srcset="https://via.placeholder.com/640x1128">
                    <img class="banniere" src="https://via.placeholder.com/1920x900" alt="banniere">
                </picture>
            </div>
            <section>
                <h3>Actualités</h3>
                    <div class="article">
                        <h4 class="article__titre">Titre</h4>
                        <img class="article__image" src="images/actualites/harrycoe01.jpg" alt="">
                        <p class="article__description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum dictum efficitur lacus et molestie. Maecenas consectetur velit quis purus sodales, ut accumsan ante molestie. Cras non maximus felis, nec dapibus nisi. Phasellus pulvinar ut neque vel venenatis. Vestibulum mattis faucibus dolor, a facilisis mauris fringilla et. Interdum et malesuada fames ac ante ipsum primis in faucibus.<a href="#"></a></p>
                        <time class="article__date" datetime=""></time>
                        <a class="article__lien" href="">Lire la suite</a>
                    </div>
            </section>
            <section>
                <h3>Artistes à découvrir</h3>
                <a class="artiste__lien" href="">
                    <figure>
                        <figcaption class="artiste__nom">Test</figcaption>
                        <picture class="artiste__image">
                            <source media="(min-width:800px)" srcset="images/photos_artistes/photosFormes/14_Lesbo Vrouven_p__w540.jpg">
                            <source media="(max-width:799px)" srcset="images/photos_artistes/photosFormes/14_Lesbo Vrouven_p__w360.jpg">
                            <img class="banniere" src="images/photos_artistes/photosFormes/14_Lesbo Vrouven_p__w540.jpg" alt="banniere">
                        </picture>
                    </figure>
                </a>

            </section>
            <section>
                <h3>Lieux de spectacle</h3>
                <ul class="liste-lieu">
                    <li class="lieu">
                        <a class="lieu__lien" href="">
                            <img class="lieu__image" src="https://via.placeholder.com/160x160" alt="">
                        </a>
                    </li>
                </ul>

            </section>
            <section class="section-tarif-et-lieu">
                <h3>Tarif et lieux de vente</h3>
                <div class="section-tarif">
                    <div class="tarif-texte">
                        <h4>Passeport: 10$ pour toute la durée du festival</h4>
                        <ul class="prix__list">
                            <li>5$ à la porte / soir (spectacles à Méduse)</li>
                            <li>Spectacles extérieurs gratuits</li>
                            <li>Spectacles gratuits au Parvis de l’église Saint-Jean-Baptiste, au bar le Sacrilège et au Fou-Bar</li>
                        </ul>
                        <p>Procurez-vous un passeport en ligne à <a class="texte__lien" href="https://lepointdevente.com/">lepointdevente.com</a> et profitez d’offres spéciales!</p>
                    </div>

                </div>
                <div class="section-lieu-de-vente">
                    <div class="conteneur">
                        <h4>Les passeports sont aussi disponibles en prévente chez nos partenaires:</h4>
                        <ul class="lieu__list">
                            <li>La Ninkasi Honoré-Mercier : 840 Avenue Honoré-Mercier, Québec</li>
                            <li>Érico: 634 Rue Saint-Jean, Québec</li>
                            <li>Le Sacrilège: 447 Rue Saint-Jean, Québec</li>
                            <li>Le Bonnet d’âne: 298 Rue Saint-Jean, Québec</li>
                            <li>Disquaire CD Mélomane : 248 rue Saint-Jean, Québec</li>
                            <li>Le Knock-Out: 832 St-Joseph Est, Québec</li>
                        </ul>                        
                    </div>
                </div>
            </section>
        </main>
        <footer>
            <?php include($niveauLISTE . "inc/fragments/footer.inc.php") ?>           
        </footer>    
    </div>
    <script src="<?php echo ($niveauLISTE . "js/menu.js") ?>"></script>   
</body>
</html>