<?php
require_once 'inc/init.php';
$categorie=true;
$ville=true;
$pseudo=true;
$prix=true;
// 1. Affichage des categories :
$resultat = executeRequete("SELECT DISTINCT titre, id_categorie FROM categorie ORDER BY titre ASC");
$tab_categorie=$resultat->fetchAll(PDO::FETCH_ASSOC); // on selectionne les differentes catégories
// on affiche le bouton "Toutes les catégories" :
    $contenu_gauche.= '<select name="categorie_titre" class="custom-select mb-4 border-warning">';
    $contenu_gauche .= '<option value="tous">Toutes les categories</option>';
        foreach ($tab_categorie as $indice => $valeur) {  // On fait autant de tour qu'il y a de catégories
            $contenu_gauche .= '<option  value="'. $valeur['titre'] .'">'.$valeur['titre'].'</option>';
        }
    $contenu_gauche .= '</select>';

// 1. Affichage des villes :
$resultat = executeRequete("SELECT DISTINCT ville FROM annonce ORDER BY ville ASC");
$tab_annonce=$resultat->fetchAll(PDO::FETCH_ASSOC); // on selectionne les differentes villes
// on affiche le bouton "Toutes les villes" :
    $contenu_gauche.= '<select name="ville" class="custom-select mb-4 border-warning">';
    $contenu_gauche .= '<option value="tous">Toutes les villes</option>';
        foreach ($tab_annonce as $indice => $valeur) {  // On fait autant de tour qu'il y a de villes
            $contenu_gauche .= '<option value="'.$valeur['ville'].'">'.$valeur['ville'].'</option>';
        }
    $contenu_gauche .= '</select>';
// 1. Affichage des prix :
$resultat = executeRequete("SELECT DISTINCT pseudo, id_membre FROM membre ORDER BY pseudo ASC");
$tab_membre=$resultat->fetchAll(PDO::FETCH_ASSOC); // on selectionne les differents membres
// on affiche le bouton "Tous les membres" :
    $contenu_gauche.= '<select name="pseudo" class="custom-select mb-4 border-warning">';
    $contenu_gauche .= '<option value="tous">Tous les membres</option>';
        foreach ($tab_membre as $indice => $valeur) {  // On fait autant de tour qu'il y a de membres
            $contenu_gauche .= '<option value="'. $valeur['pseudo'] .'">'.$valeur['pseudo'].'</option>';
        }
    $contenu_gauche .= '</select>';
// 1. Affichage des membres :
$resultat = executeRequete("SELECT DISTINCT prix FROM annonce ORDER BY prix ASC");
$tab_prix=$resultat->fetchAll(PDO::FETCH_ASSOC); // on selectionne les differents prix
// on affiche le bouton "Tous les prix" :
    $contenu_gauche.= '<select name="prix" class="custom-select mb-4 border-warning">';
    $contenu_gauche .= '<option value="tous">Tous les prix</option>';
        foreach ($tab_prix as $indice => $valeur) {  // On fait autant de tour qu'il y a de prix
            $contenu_gauche .= '<option value="'. $valeur['prix'] .'">'.$valeur['prix'].'</option>';
        }
    $contenu_gauche .= '</select>';

// debug($_POST);


    // 2. Affichage des annonces de la categorie selectionnée :
    if ($_POST) {

        if (isset($_POST['categorie_titre']) && $_POST['categorie_titre'] != 'tous') {
            $categorie="categorie.titre='" .$_POST["categorie_titre"]."'";
        }

        if (isset($_POST['ville']) && $_POST['ville'] != 'tous') {
            $ville="annonce.ville='" .$_POST["ville"]."'";
        }

        if (isset($_POST['prix']) && $_POST['prix'] != 'tous') {
            $prix = "annonce.prix='" .$_POST["prix"]."'";
        }

        if (isset($_POST['pseudo']) && $_POST['pseudo'] != 'tous') {
            $pseudo = "membre.pseudo='" .$_POST["pseudo"]."'";
        } 
        
    }

        $donnees = executeRequete("SELECT annonce.*, categorie.*, membre.*, membre.id_membre AS membre_id, categorie.titre AS titre_categorie, annonce.titre AS annonce_titre, annonce.id_annonce AS annonce_id FROM annonce INNER JOIN categorie ON annonce.id_categorie=categorie.id_categorie INNER JOIN membre ON annonce.id_membre=membre.id_membre WHERE $categorie AND $ville AND $pseudo AND $prix");


        $donnees = executeRequete("SELECT annonce.*, categorie.*, membre.*, membre.id_membre AS membre_id, categorie.titre AS titre_categorie, annonce.titre AS annonce_titre, annonce.id_annonce AS annonce_id FROM annonce INNER JOIN categorie ON annonce.id_categorie=categorie.id_categorie INNER JOIN membre ON annonce.id_membre=membre.id_membre WHERE $categorie AND $ville AND $prix AND $pseudo"); // on selectionne donc tous les produits 
        

while ($annonce = $donnees->fetch(PDO::FETCH_ASSOC)) { // boucle while car il y a potentiellement plusieurs annonces
    $contenu_droite .= '<div class="col-sm-4 mb-4">';
        $contenu_droite .= '<div class="card h-100 border-warning">';
            // image cliquable :
            $contenu_droite .= '<a href="fiche_annonce.php?id_annonce='. $annonce['annonce_id'] .'"><img src="'. $annonce['photo'] .'" alt="'. $annonce['annonce_titre'] .'" class="card-img-top"></a>';

            // infos du annonce :
            $contenu_droite .= '<div class="card-body">';
                $contenu_droite .= '<h4>' . $annonce['annonce_titre'] . '</h4>';
                $contenu_droite .= '<h5 class="text-warning">' . $annonce['prix'] . ' €</h5>';
                $contenu_droite .= '<p>' . $annonce['description_courte'] . '</p>';
            $contenu_droite .= '</div>';

        $contenu_droite .= '</div>'; // .card
    $contenu_droite .= '</div>'; // .col-sm-4
}    

$sql = "SELECT COUNT(id_annonce) AS nb_annonce FROM annonce";
debug($sql);


require_once 'inc/header.php';
?>

<h1 class="mt-4 text-warning">MyDeal</h1>
<div class="row">
    <div class="col-md-3">
        <form method="post" action="" enctype="multipart/form-data">
            <?php echo $contenu_gauche; // pour afficher les catégories ?>
            <div class="mt-2">
                <input class="bg-warning form-control" type="submit" value="Valider">
            </div>
        </form>
    </div>
    <div class="col-md-9">
        <div class="row">
            <?php echo $contenu_droite; // pour afficher les annonces ?>
        </div>
    </div>

</div>



<?php
require_once 'inc/footer.php';

?>