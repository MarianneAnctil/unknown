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
    <meta charset="utf-8">
    <title>Festival OFF de Québec - Accueil</title>
</head>
<body>
<main>
    <div class="banniere-conteneur">
        <h1>Festival OFF de Québec</h1>
        <h2>Le 22 Novembre 2023</h2>
        <img class="banniere" src="https://via.placeholder.com/1920x800" alt="">
    </div>
    <section>
        <h3>Actualités</h3>
        <section class="article">
            <article>
                <header>
                    <h4 class="article__titre"></h4>
                </header>
                <p class="article__description">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum dictum efficitur lacus et molestie. Maecenas consectetur velit quis purus sodales, ut accumsan ante molestie. Cras non maximus felis, nec dapibus nisi. Phasellus pulvinar ut neque vel venenatis. Vestibulum mattis faucibus dolor, a facilisis mauris fringilla et. Interdum et malesuada fames ac ante ipsum primis in faucibus.<a href="#"></a></p>
                <footer>
                    <time datetime=""></time>
                    <a href="">Lire la suite</a>
                </footer>
            </article>

        </section>
    </section>
    <section>
        <h3>Artistes à découvrir</h3>
        <a href="">
            <figure>
                <figcaption>test</figcaption>
                <img src="https://via.placeholder.com/400x400">
            </figure>
        </a>

    </section>
    <section>
        <h3>Lieux de spectacle</h3>
        <a href="">Ouvrir dans google map
            <img src="https://via.placeholder.com/400x400" alt="">
        </a>

    </section>
    <section>
        <h3>Tarif et lieux de vente</h3>
        <div>
            <div>
                <h4>Passeport: 10$ pour toute la durée du festival</h4>
                <ul>
                    <li>5$ à la porte / soir (spectacles à Méduse)</li>
                    <li>Spectacles extérieurs gratuits</li>
                    <li>Spectacles gratuits au Parvis de l’église Saint-Jean-Baptiste, au bar le Sacrilège et au Fou-Bar</li>
                </ul>
                <p>Procurez-vous un passeport en ligne à <a href="https://lepointdevente.com/">lepointdevente.com</a> et profitez d’offres spéciales!</p>
            </div>

            <div>
                <img src="https://via.placeholder.com/400x400"" alt="">
            </div>
        </div>
        <div>
            <h4>Les passeports sont aussi disponibles en prévente chez nos partenaires:</h4>
            <ul>
                <li>La Ninkasi Honoré-Mercier : 840 Avenue Honoré-Mercier, Québec</li>
                <li>Érico: 634 Rue Saint-Jean, Québec</li>
                <li>Le Sacrilège: 447 Rue Saint-Jean, Québec</li>
                <li>Le Bonnet d’âne: 298 Rue Saint-Jean, Québec</li>
                <li>Disquaire CD Mélomane : 248 rue Saint-Jean, Québec</li>
                <li>Le Knock-Out: 832 St-Joseph Est, Québec</li>
            </ul>
        </div>
    </section>
</main>
</body>
</html>
