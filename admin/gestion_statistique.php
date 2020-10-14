<?php
require_once '../inc/init.php'; // attention au ../ (pour lier les fichier php ou html...)

// 1 - Vérification administrateur :
if (!estAdmin()){
    header('location:../connexion.php'); // Si le membre n'esyt pas connecté ou n'est pas admi.On le redirige vers la page de connection.
    exit(); // On quitte le script
}


//***********************************************************************************************************************************
// Top 5 des membres les mieux notés
//***********************************************************************************************************************************
$resultat = executeRequete("SELECT AVG(note), id_membre, pseudo 
            FROM note 
            INNER JOIN membre 
            ON id_membre1 = id_membre
            GROUP BY prenom
            ORDER BY AVG(note) 
            DESC LIMIT 5

");

$contenu .= '<div class="table-responsive">';
    $contenu .= '<table class="table"><h1>Top 5 des membres les mieux notés';
    //Lignes des entêtes du tableau :
        $contenu .= '<tr>';
            $contenu .= '<th>Classement</th>';
            $contenu .= '<th>Pseudo</th>';
            $contenu .= '<th>Note moyenne</th>';
        $contenu .= '</tr>';

$i = 1;

while ($requete = $resultat->fetch(PDO::FETCH_ASSOC)){
    $contenu .= '<tr>';

        $contenu .= '<td>'. $i . '</td>';
        $contenu .= '<td>'. $requete['pseudo'] . '</td>';
        $contenu .= '<td>'. $requete['AVG(note)'] . '</td>';

    $contenu .= '</tr>';
    $i++;
}

//***********************************************************************************************************************************
// Top 5 des membres les plus anciens
//***********************************************************************************************************************************
$resultat = executeRequete("SELECT id_annonce, titre, date_enregistrement FROM annonce GROUP BY id_annonce ORDER BY date_enregistrement ASC LIMIT 5

");

$contenu .= '<div class="table-responsive">';
    $contenu .= '<table class="table"><h1>Top 5 des annonces les plus ancciennes</h1>';
    //Lignes des entêtes du tableau :
    $contenu .= '<tr>';
            $contenu .= '<th>Classement</th>';
            $contenu .= '<th>id_annonce</th>';
            $contenu .= '<th>Titre</th>';
            $contenu .= '<th>Date enregistrement</th>';
        $contenu .= '</tr>';

$i = 1;

while ($requete = $resultat->fetch(PDO::FETCH_ASSOC)){
    $contenu .= '<tr>';

        $contenu .= '<td>'. $i . '</td>';
        $contenu .= '<td>'. $requete['id_annonce'] . '</td>';
        $contenu .= '<td>'. $requete['titre'] . '</td>';
        $contenu .= '<td>'. $requete['date_enregistrement'] . '</td>';

    $contenu .= '</tr>';
    $i++;
}
  

//***********************************************************************************************************************************
// Top 5 des membres les plus actifs
//***********************************************************************************************************************************
$resultat = executeRequete("SELECT m.prenom, COUNT(id_annonce) 
                            FROM annonce a
                            INNER JOIN membre m
                            ON a.id_membre2 = m.id_membre
                            GROUP BY id_membre
                            ORDER BY COUNT(id_annonce)
                            DESC LIMIT 5

");

$contenu .= '<div class="table-responsive">';
    $contenu .= '<table class="table"><h1>Top 5 des membres les plus actifs (Nb d\'annonces)</h1>';
    //Lignes des entêtes du tableau :
    $contenu .= '<tr>';
            $contenu .= '<th>Classement</th>';
            $contenu .= '<th>Prénom</th>';
            $contenu .= '<th>Nombre d\'annonce</th>';
        $contenu .= '</tr>';

$i = 1;

while ($requete = $resultat->fetch(PDO::FETCH_ASSOC)){
    $contenu .= '<tr>';

        $contenu .= '<td>'. $i . '</td>';
        $contenu .= '<td>'. $requete['prenom'] . '</td>';
        $contenu .= '<td>'. $requete['COUNT(id_annonce)'] . '</td>';
    $contenu .= '</tr>';
    $i++;
}

//***********************************************************************************************************************************
// Top 5 des categories contenant le plus d'annonce
//***********************************************************************************************************************************
$resultat = executeRequete("SELECT c.id_categorie, c.titre AS titrecat, COUNT(id_annonce) 
                            FROM categorie c 
                            INNER JOIN annonce a
                            ON c.id_categorie = a.id_categorie
                            GROUP BY id_categorie
                            ASC LIMIT 5;
");

$contenu .= '<div class="table-responsive">';
    $contenu .= '<table class="table"><h1>Top 5 des catégories contenant le plus d\'annonces</h1>';
    //Lignes des entêtes du tableau :
    $contenu .= '<tr>';
            $contenu .= '<th>Classement</th>';
            $contenu .= '<th>id_categorie</th>';
            $contenu .= '<th>categorie</th>';
            $contenu .= '<th>Nombre d\'annonce</th>';
        $contenu .= '</tr>';

$i = 1;

while ($requete = $resultat->fetch(PDO::FETCH_ASSOC)){
    $contenu .= '<tr>';

        $contenu .= '<td>'. $i . '</td>';
        $contenu .= '<td>'. $requete['id_categorie'] . '</td>';
        $contenu .= '<td>'. $requete['titrecat'] . '</td>';
        $contenu .= '<td>'. $requete['COUNT(id_annonce)'] . '</td>';
        
    $contenu .= '</tr>';
    $i++;
}













require_once '../inc/header.php';

?>

<ul class="nav nav-tabs">
    <li><a class="nav-link" href="gestion_boutique.php">Affichage des annonces</a></li>
    <li><a class="nav-link" href="gestion_categorie.php">Gestion catégories</a></li>
    <li><a class="nav-link" href="gestion_membre.php">Gestion membre</a></li>
    <li><a class="nav-link" href="gestion_commentaire.php">Gestion commentaire</a></li>
    <li><a class="nav-link" href="gestion_note.php">Gestion note</a></li>
    <li><a class="nav-link active" href="gestion_statistique.php">Gestion statistique</a></li>
</ul>

<h1>Statistiques</h1>



<?php
echo $contenu;
?>
<?php
// endif;

// require_once '../inc/footer.php';






