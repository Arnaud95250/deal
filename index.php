<?php
require_once 'inc/init.php';

$categorie=true;
$ville=true;
$pseudo=true;
$prix=true;



// ***********************************************
$annonceParPage = '';
$annonceTotalesReq = '';
$annonceTotales = '';

// https://www.youtube.com/watch?v=yqoYvUuePvM


// $resultat=$bdd->fetch(PDO::FETCH_ASSOC);


// $annonceParPage = 5;
// $annonceTotalesReq = $bdd->query("SELECT id_annonce FROM annonce'.$depart.','.$annonceParPage")
// $annonceTotales = $annonceTotalesReq->rowCount();
// $pagesTotales = $annonceTotales/$annonceParPage;

// if (isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] > 0){
//     $_GET['page'] = intval(var);
//     $pageCourante = $_GET['page'];
// } else{
//     $pageCourante = 1;
// }

// $depart = ($pageCourante-1)*$annoncePagination

// debug($pageCourante);



// $annonceTotalesReq = $bdd->query("SELECT id_annonce FROM annonce ORDER BY id_annonce DESC LIMIT '.$depart.','.$annonceParPage");

// **************************************************************



//*************************************************************************************************************************************
// selecteur affichage des annonces
//*************************************************************************************************************************************

// 1. Affichage des categories :
$resultat = executeRequete("SELECT DISTINCT titre, id_categorie FROM categorie ");
$tab_categorie=$resultat->fetchAll(PDO::FETCH_ASSOC); // on selectionne les differentes catégories
// on affiche le bouton "Toutes les catégories" :
    $contenu_gauche.= '<select name="categorie_titre" class="custom-select mb-4 form-control">';
    $contenu_gauche .= '<option value="tous">Toutes les categories</option>';
        foreach ($tab_categorie as $indice => $valeur) {  // On fait autant de tour qu'il y a de catégories
            $contenu_gauche .= '<option  value="'. $valeur['titre'] .'">'.$valeur['titre'].'</option>';
        }
    $contenu_gauche .= '</select>';


//*************************************************************************************************************************************
// 2 - On affiche les autres ville :
$resultat = executeRequete("SELECT DISTINCT ville FROM annonce ORDER BY ville ASC");
$tab_annonce=$resultat->fetchAll(PDO::FETCH_ASSOC); // on selectionne les differentes villes
// on affiche le bouton "Toutes les villes" :
    $contenu_gauche.= '<select name="ville" class="custom-select mb-4 form-control">';
    $contenu_gauche .= '<option value="tous">Toutes les villes</option>';
        foreach ($tab_annonce as $indice => $valeur) {  // On fait autant de tour qu'il y a de villes
            $contenu_gauche .= '<option value="'.$valeur['ville'].'">'.$valeur['ville'].'</option>';
        }
$contenu_gauche .= '</select>';


//*************************************************************************************************************************************
// 3 - On affiche les autres membre :
$resultat = executeRequete("SELECT DISTINCT pseudo, id_membre FROM membre ORDER BY pseudo ASC");
$tab_membre=$resultat->fetchAll(PDO::FETCH_ASSOC); // on selectionne les differents membres
// on affiche le bouton "Tous les membres" :
    $contenu_gauche.= '<select name="pseudo" class="custom-select mb-4 form-control">';
    $contenu_gauche .= '<option value="tous">Tous les membres</option>';
        foreach ($tab_membre as $indice => $valeur) {  // On fait autant de tour qu'il y a de membres
            $contenu_gauche .= '<option value="'. $valeur['pseudo'] .'">'.$valeur['pseudo'].'</option>';
        }
    $contenu_gauche .= '</select>';

//*************************************************************************************************************************************
// 4 - On affiche les autres prix :
$resultat = executeRequete("SELECT DISTINCT prix FROM annonce ORDER BY prix ASC");
$tab_prix=$resultat->fetchAll(PDO::FETCH_ASSOC); // on selectionne les differents prix
// on affiche le bouton "Tous les prix" :
    $contenu_gauche.= '<select name="prix" class="custom-select mb-4 form-control">';
    $contenu_gauche .= '<option value="tous">Tous les prix</option>';
        foreach ($tab_prix as $indice => $valeur) {  // On fait autant de tour qu'il y a de prix
            $contenu_gauche .= '<option value="'. $valeur['prix'] .'">'.$valeur['prix'].'</option>';
        }
    $contenu_gauche .= '</select>';

//***********************************************************************************************************************************
// 2. Affichage des annonces de la categorie selectionnée :
//*************************************************************************************************************************************
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
//***********************************************************************************************************************************
// 2. les requetes pour les selecteurs :
//*************************************************************************************************************************************
$donnees = executeRequete("SELECT annonce.*, categorie.*, membre.*, membre.id_membre AS membre_id, categorie.titre AS titre_categorie, annonce.titre AS annonce_titre, annonce.id_annonce AS annonce_id FROM annonce INNER JOIN categorie ON annonce.id_categorie=categorie.id_categorie INNER JOIN membre ON annonce.id_membre2=membre.id_membre WHERE $categorie AND $ville AND $prix AND $pseudo");


//*************************************************************************************************************************************

// // 2 - Affichage des produits de la catégorie choisie ;
// if (isset($_GET['id_annonce']) && $_GET['id_annonce'] != 'tous') {// Si existe l'indice id_annonce de $_GET et que ca valeur est différente de 'tous', c'est qu'on a cliqué sur une catégorie de la BDD . On requête alors les produits correspondants.
//     $donnees = executeRequete("SELECT * FROM annonce WHERE id_annonce = :id_annonce", array(':id_annonce'=> $_GET['id_annonce'])); 
//     }
//     else {
//         $donnees = executeRequete("SELECT * FROM annonce"); // On séléctionne donc tous le produits.
//     }

//***********************************************************************************************************************************
// 2. Contenue des annonces affiché sur la page index.html
//*************************************************************************************************************************************
while ($annonce = $donnees->fetch(PDO::FETCH_ASSOC)){ // Boucle while car il y potentielement plusieurs annonce.
    $contenu_droite .= '<div class="col-sm-8 mb-4">';
        $contenu_droite .= '<div class="card bg-dark text-white">';
            //Image cliquable :
            $contenu_droite .= '<a href="fiche_annonce.php?id_annonce='. $annonce['id_annonce'] .'"><img src="'. $annonce['photo'] .'" alt="'. $annonce['titre'] .'" class="card-img-top"></a>';

            // Infos du annonce :
            $contenu_droite .= '<div class="card-img">';
                $contenu_droite .= '<h5 class="card-title">'. $annonce['titre'] .'</h5>';
                $contenu_droite .= ' <h5 class="card-title">Prix : '. $annonce['prix'] .' €</h5>';
                $contenu_droite .= '<p>'. $annonce['ville'] .'</p>';
                $contenu_droite .= '<pclass="card-text">'. $annonce['description_courte'] .'</p>';
                
            $contenu_droite .= '</div>'; // class="card-body"
        $contenu_droite .= '</div>'; // class="card"
    $contenu_droite .= '</div>'; //  class="col-sm4 mb-4"
}
?>
<!-- ******************************************************************************************************* -->
<!-- HTML -->
<!-- ******************************************************************************************************* -->
<?php
require_once 'inc/header.php';
?>


    <h1 class="mt-4">Annonce</h1>
        <div class="row">
        <div class="col-md-3"> <!-- bootstrap est en 12 colones dans la largeur avec le md-3 on en prend 3 sur les 12-->

        <form method="post" action="" enctype="multipart/form-data">
                <?php echo $contenu_gauche; // pour afficher les catégories?>
                <div class="mt-2">
                    <input class=" form-control btn btn-primary" type="submit" value="Valider">
                </div>
            </form>

            </div>
            <div class="col-md-9"> <!-- bootstrap est en 12 colones dans la largeur avec le md-9 on en prend 9 sur les 12-->
                <div class="row"> <!-- bootstrap 12 colones ajouté dans l'une des boite qui se trouve dans l'une des 9 boite-->
                    <?php echo $contenu_droite; // Pour afficher les produits ?>
                </div>
            </div>
        </div>
<!-- ******************************************************************************************************* -->
<!-- La pagination -->
<!-- ******************************************************************************************************* -->
