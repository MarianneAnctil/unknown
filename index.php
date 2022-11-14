<?php
//Définition de la variable de niveau
$niveau='';

//Inclusion du fichier de configuration
include($niveau.'inc/scripts/config.inc.php');

//On établi une nouvelle requête pour afficher les enregistrements figurant dans une page donnée
$strRequete =
    'SELECT id_actualite, titre, date_actualite, article, 
    DAYOFMONTH(date_actualite) AS Jour,
    MONTH(date_actualite) AS Mois,
    YEAR(date_actualite) AS Annee
    FROM t_actualite 
    ORDER BY date_actualite DESC';

//Initialisation de l'objet PDOStatement et exécution de la requête
$pdosResultatActualite = $pdoConnexion->prepare($strRequete);
$pdosResultatActualite->execute();

$arrActualite=array();
$ligne=$pdosResultatActualite->fetch();

//Extraction des enregistrements à afficher de la BD
for($intCptEnr=0;$intCptEnr<$pdosResultatActualite->rowCount();$intCptEnr++){
    $arrActualite[$intCptEnr]['id_actualite'] = $ligne['id_actualite'];
    $arrActualite[$intCptEnr]['titre'] = $ligne['titre'];
    $arrActualite[$intCptEnr]['date_actualite'] = $ligne['date_actualite'];
    $arrActualite[$intCptEnr]['article'] = $ligne['article'];
    $arrActualite[$intCptEnr]['Jour'] = $ligne['Jour'];
    $arrActualite[$intCptEnr]['Mois'] = $ligne['Mois'];
    $arrActualite[$intCptEnr]['Annee'] = $ligne['Annee'];

    //Recupere le chiffre du mois et renvoie le mois en texte
    $arrMoisTexte = array( "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre" );
    $Mois_texte = $arrMoisTexte[$ligne['Mois'] - 1];
    $arrArticle = explode(" ", $ligne['article']);

    //Raccourcie le texte de l'actualite a 45 mots 
    if (count($arrArticle)>45) {
        array_splice($arrArticle, 45, count($arrArticle));
    }
    $arrActualite[$intCptEnr]['article']=implode(" ", $arrArticle);

    $arrActualite[$intCptEnr]['date'] = $ligne['Jour'] . ' ' . $Mois_texte . ' ' . $ligne['Annee'];

    //Repeter un fetch pour recuperer les lignes de chaque article
    $ligne=$pdosResultatActualite->fetch();

    $nombreArticleAfficher = rand(3,5);

}
//On libère la requête principale
$pdosResultatActualite->closeCursor();

//Récupère les artistes à afficher de façon aléatoire
$strRequete =  'SELECT id_artiste, nom_artiste
	FROM t_artiste';

//Initialisation de l'objet PDOStatement et exécution de la requête
$pdosArtisteResultat = $pdoConnexion->prepare($strRequete);
$pdosArtisteResultat->execute();

$arrArtiste=array();
$ligne=$pdosArtisteResultat->fetch();

for($intCptEnr2=0;$intCptEnr2<$pdosArtisteResultat->rowCount();$intCptEnr2++){
    $arrArtiste[$intCptEnr2]['id_artiste'] = $ligne['id_artiste'];
    $arrArtiste[$intCptEnr2]['nom_artiste'] = $ligne['nom_artiste'];
    $ligne=$pdosArtisteResultat->fetch();
}

$nombreArtisteAfficher = rand(3,5);

//On libère la requête des styles
$pdosArtisteResultat->closeCursor();

$arrArtisteAleatoire = shuffle($arrArtiste);

?>

<!doctype html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <title>Festival OFF de Québec - Accueil</title>
    <link rel="stylesheet" href="css/style-daren.css">
    <link rel="stylesheet" href="css/menu.css">
</head>
<body>
    <?php include($niveau . "inc/fragments/header.inc.php") ?>        
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
                <div class="actualites">
                    <?php
                    for($intCpt=0;$intCpt<$nombreArticleAfficher;$intCpt++){?>
                            <div class="article">
                                <h4 class="article__titre"><?php echo $arrActualite[$intCpt]['titre'];?></h4>
                                <picture class="article__image">
                                    <source media="(min-width:1000px)" srcset="images/actualites/actualite<?php echo $arrActualite[$intCpt]['id_actualite'];?>_w886.jpg">
                                    <source media="(min-width:500px)" srcset="images/actualites/actualite<?php echo $arrActualite[$intCpt]['id_actualite'];?>_w552.jpg">
                                    <img src="images/actualites/actualite<?php echo $arrActualite[$intCpt]['id_actualite'];?>_w886.jpg" alt="">
                                </picture>
                                <p class="article__description"><?php echo $arrActualite[$intCpt]['article'];?></p>
                                <time class="article__date" datetime="<?php echo $arrActualite[$intCpt]['date_actualite'];?>"><?php echo $arrActualite[$intCpt]['date'];?></time>
                                <a class="article__lien" href="">Lire la suite</a>
                            </div>
                    <?php } ?>                    
                </div>

            </section>
            <section>
                <h3>Artistes à découvrir</h3>
                <div class="artistes">
                    <?php
                    for($intCpt2=0;$intCpt2<$nombreArtisteAfficher;$intCpt2++){
                    ?>
                        <a class="artiste__lien" href="artistes/fiche/index.php?id_artiste=<?php echo $arrArtiste[$intCpt2]['id_artiste'];?>">
                            <figure>
                                <figcaption class="artiste__nom"><?php echo $arrArtiste[$intCpt2]['nom_artiste'];?></figcaption>
                                <picture class="artiste__image">
                                <source media="(max-width:1000px)" srcset="<?php echo $arrArtiste[$intCpt2]['id_artiste'];?>_<?php echo $arrArtiste[$intCpt2]['nom_artiste'];?>_p__w900.jpg">
                                    <source media="(min-width:500px)" srcset="<?php echo $arrArtiste[$intCpt2]['id_artiste'];?>_<?php echo $arrArtiste[$intCpt2]['nom_artiste'];?>_p__w540.jpg">
                                    <img class="artiste-image__img" src="images/photos_artistes/photosFormes/<?php echo $arrArtiste[$intCpt2]['id_artiste'];?>_<?php echo $arrArtiste[$intCpt2]['nom_artiste'];?>_p__w900.jpg" alt="<?php echo $arrArtiste[$intCpt2]['nom_artiste'];?> en concert">
                                </picture>
                            </figure>
                        </a>
                    <?php } ?>                    
                </div>
            </section>
            <section>
                <h3>Lieux de spectacle</h3>
                <ul class="liste-lieu">
                    <li class="lieu">
                        <a class="lieu__lien" href="">
                            <picture class="lieu__image">
                                <source media="(min-width:1000px)" srcset="images/actualites/actualite<?php echo $arrActualite[$intCpt]['id_lieu'];?>_w324.jpg">
                                <source media="(min-width:250px)" srcset="images/actualites/actualite<?php echo $arrActualite[$intCpt]['id_lieu'];?>_w560.jpg">
                                <img src="images/actualites/actualite<?php echo $arrActualite[$intCpt]['id_actualite'];?>_w886.jpg" alt="">
                            </picture>
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
        <?php include ($niveau . "inc/fragments/footer.inc.php") ?>           
    </div>
    <script src="<?php echo ($niveau . "js/menu.js") ?>"></script>   
</body>
</html>