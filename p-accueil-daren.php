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
<header>
    <nav class="nav">
        <ul class="nav__list">
            <li class="list__item"><a class="list__lien" href="#">Accueil</a></li>
            <li class="list__item"><a class="list__lien" href="programmation/p-programmation-daren.php">Programmation</a></li>
            <li class="list__item"><a class="list__lien" href="artistes/p-liste-daren.php">Artistes</a></li>
            <li class="list__item"><a class="list__lien" href="#">Partenaires</a></li>
        </ul>
        <a class="lien-passeport" href="#">Acheter mon passeport</a>
    </nav>
    <div class="banniere-conteneur">
        <h1>Festival OFF de Québec</h1>
        <h2>Le 22 Novembre 2023</h2>
        <img class="banniere" src="https://via.placeholder.com/1920x800" alt="">
    </div>
</header>
<main>
    <section>
        <h3>Actualités</h3>
        <?php
            for($intCpt=0;$intCpt<$nombreArticleAfficher;$intCpt++){?>
        <section class="article">
            <article>
                <header>
                    <h4 class="article__titre"><?php echo $arrActualite[$intCpt]['titre'];?></h4>                
                </header>
                <p class="article__description"><?php echo $arrActualite[$intCpt]['article'];?><a href="#">...</a></p>  
                <footer>
                    <time datetime="<?php echo $arrActualite[$intCpt]['date_actualite'];?>"><?php echo $arrActualite[$intCpt]['date'];?></time>
                </footer>              
            </article>

        </section>
        <?php } ?>
    </section>
    <section>
        <h3>Artistes à découvrir</h3>
        <?php
        for($intCpt2=0;$intCpt2<$nombreArtisteAfficher;$intCpt2++){
        ?>
        <figure>
            <img src="https://via.placeholder.com/250" alt="<?php echo $arrArtiste[$intCpt2]['nom_artiste'];?> en concert">
            <figcaption><?php echo $arrArtiste[$intCpt2]['nom_artiste'];?></figcaption>
            <a href="artistes/fiche/p-fiche-daren.php?id_artiste=<?php echo $arrArtiste[$intCpt2]['id_artiste'];?>">Lien vers l'artiste</a>
        </figure>
        <?php } ?>
    </section>

</main>
<footer>

</footer>
</body>
</html>
