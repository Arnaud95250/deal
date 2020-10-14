<?php
require_once '../inc/init.php'; // attention au ../ (pour lier les fichier php ou html...)
// 1 - Vérification administrateur :
if (!estAdmin()){
    header('location:../connexion.php'); // Si le membre n'esyt pas connecté ou n'est pas admi.On le redirige vers la page de connection.
    exit(); // On quitte le script
}




// 4 - Enregistrement du produit : 
debug ($_POST);
if (isset($_POST["cat"])){ 
    $succes = executeRequete("REPLACE INTO categorie (id_categorie, titre, mots_cles) VALUES (:id_categorie, :titre, :mots_cles)",
    array(':id_categorie'       => $_POST['id_categorie'],
          ':titre'              => $_POST['titre'],
          ':mots_cles'          => $_POST['mots_cles'],   
    ));

    if($succes){ // Si la fonction executeRequete retourne un objet PDOStatement (donc implication évalué à TRUE ), C'est que la requête marche
       $contenu .= '<div class="alert alert-success">La modification a été enrgistré</div>';
    }
    else { // Sinon on a reçu false en cas d'erreur sur la requête.
       $contenu .='<div class="alert alert-danger">Erreur lors de l\'enregistrement...</div>';
    }
}




if (isset($_POST["com"])) {

    $commentaire = executeRequete("REPLACE INTO commentaire (id_commentaire, commentaire, id_membre, id_annonce, date_enregistrement) VALUES (:id_commentaire, :commentaire, :id_membre, :id_annonce, NOW())",
                array( 
                    ':id_commentaire'           => $_POST['id_commentaire'],
                    ':commentaire'              => $_POST['commentaire'],
                    ':id_membre'                => $_POST['id_membre'],
                    ':id_annonce'               => $_POST['id_annonce'], 
    ));

    if (!isset($succes["avisnote"])) { // Si la fonction executeRequete retourne un objet PDOStatement (donc implication évalué à TRUE ), C'est que la requête marche
       $contenu .= '<div class="alert alert-success">La modification a été enrgistré</div>';
    }
    else { // Sinon on a reçu false en cas d'erreur sur la requête.
       $contenu .='<div class="alert alert-danger">Erreur lors de l\'enregistrement...</div>';
    }
} 
    


    
if (isset($_POST["avisnote"])) {   
    $note = executeRequete("REPLACE INTO note (id_note, note, avis, id_membre1, id_membre2, date_enregistrement) VALUES (:id_note, :note, :avis, :id_membre1, :id_membre2, NOW())",
               array(
                    ':id_note'             => $_POST['id_note'],
                    ':note'                => $_POST['note'],
                    ':avis'                => $_POST['avis'],
                    ':id_membre1'          => $_POST['id_membre1'],
                    ':id_membre2'          => $_POST['id_membre2'], 
                ));
    if (!isset($succes["avisnote"])) { // Si la fonction executeRequete retourne un objet PDOStatement (donc implication évalué à TRUE ), C'est que la requête marche
       $contenu .= '<div class="alert alert-success">La modification a été enrgistré</div>';
    }
    else { // Sinon on a reçu false en cas d'erreur sur la requête.
       $contenu .='<div class="alert alert-danger">Erreur lors de l\'enregistrement...</div>';
    }
}



// 8 - Remplissage du formulaire de modification de produit :
    if (isset($_GET['id_categorie'])) { //  Si on a reçu l'id_produit dans l'url, c'est qu'on a demandé la modification du produit.
        // On séléctionne les infos du produit en BDD pour remplir le formulaire :
        $resultat = executeRequete("SELECT * FROM categorie WHERE id_categorie = :id_categorie", array(':id_categorie' => $_GET['id_categorie']));
        $_POST = $resultat->fetch(PDO::FETCH_ASSOC); // Pas de while car nous n'avons qu'un seul produit par id.
    }
    if (isset($_GET['id_commentaire'])) { //  Si on a reçu l'id_produit dans l'url, c'est qu'on a demandé la modification du produit.
        // On séléctionne les infos du produit en BDD pour remplir le formulaire :
        $resultat = executeRequete("SELECT * FROM commentaire WHERE id_commentaire = :id_commentaire", array(':id_commentaire' => $_GET['id_commentaire']));
        $_POST = $resultat->fetch(PDO::FETCH_ASSOC); // Pas de while car nous n'avons qu'un seul produit par id.

        debug($_POST);
    }
    if (isset($_GET['id_note'])) { //  Si on a reçu l'id_produit dans l'url, c'est qu'on a demandé la modification du produit.
        // On séléctionne les infos du produit en BDD pour remplir le formulaire :
        $resultat = executeRequete("SELECT * FROM note WHERE id_note = :id_note", array(':id_note' => $_GET['id_note']));
        $_POST = $resultat->fetch(PDO::FETCH_ASSOC); // Pas de while car nous n'avons qu'un seul produit par id.
    }
    
    require_once '../inc/header.php';
    // 2 - Navigation entre les pages d'administration :
    ?>
    <ul class="nav nav-tabs">
        <li><a class="nav-link active" href="formulaire_modification.php">Formulaire modifiction</a></li>
        <li><a class="nav-link" href="gestion_boutique.php">Affichage des annonces</a></li>
        <li><a class="nav-link" href="gestion_categorie.php">Gestion catégories</a></li>
        <li><a class="nav-link" href="gestion_membre.php">Gestion membre</a></li>
        <li><a class="nav-link" href="gestion_commentaire.php">Gestion commentaire</a></li>
        <li><a class="nav-link" href="gestion_note.php">Gestion note</a></li>
        <li><a class="nav-link" href="gestion_statistique.php">Gestion statistique</a></li>
    </ul>
    
    <h1 class="mt-4" style="padding-left: 20%;">Page des modifications</h1><br>
    <?php
    echo $contenu;
    ?>


    <form  method="post" action="" enctype="multipart/form-data" style="padding-left: 20%; padding-right: 20%;">
        <div>
            <div><input type="hidden" name="id_categorie" id="id_categorie" style="width: 100%;" value="<?php echo $_POST['id_categorie'] ?? ''; ?>"></div>
        </div>

        <div>
            <div><label for="titre"><h4>Modifier la catégorie</h4></label></div>
            <div><input type="text" name="titre" id="titre" style="width: 100%;" value="<?php echo $_POST['titre'] ?? ''; ?>"></div>
        </div>

        <div>
            <div><label for="mots_cles">Déscription courte</label></div>
            <div><textarea name="mots_cles" id="mots_cles" style="width: 100%; "><?php echo $_POST['mots_cles'] ?? ''; ?></textarea></div>
        </div><br>
    
        <div><input type="submit" name="cat" style="background: rgb(8, 8, 85); color:white; border:none;" value="Enregistrer" ></div>
        </form><br>
<hr style="border:solid 2px rgb(8, 8, 85);">





    <form  method="post" action="" enctype="multipart/form-data" style="padding-left: 20%; padding-right: 20%;">

        <div>
            <div><label for="commentaire"><h4>Modifier le commentaire</h4></label></div>
            <div><textarea name="commentaire" class="commentaire" cols="62" rows="5"><?php echo $_POST['commentaire'] ?? ''; ?></textarea></div>
        </div>
        <div>
            <div><input type="hidden" name="id_commentaire" id="id_commentaire" style="width: 100%;" value="<?php echo $_POST['id_commentaire'] ?? 0; ?>"></div>
        </div>
        <div>
            <div><input type="hidden" name="id_membre" id="id_membre" style="width: 100%;" value="<?php echo $_POST['id_membre'] ?? 0; ?>"></div>
        </div>
        <div>
            <div><input type="hidden" name="id_annonce" id="id_annonce" style="width: 100%;" value="<?php echo $_POST['id_annonce'] ?? 0; ?>"></div>
        </div><br>

        <div><input type="submit" name="com" style="background: rgb(8, 8, 85); color:white; border:none;" value="Enregistrer" ></div>
    </form><br>
    <hr style="border:solid 2px rgb(8, 8, 85);">






    <form  method="post" action="" enctype="multipart/form-data" style="padding-left: 20%; padding-right: 20%;">
        <div>
            <div><label for="avis"><h4>Modifier les avis</h4></label></div>
            <div><textarea id="avis" name="avis" cols="62" rows="5"><?php echo $_POST['avis'] ?? ''; ?></textarea></div>
        </div><br>
        <div>
            <div><input type="hidden" name="id_membre1" id="id_membre1" style="width: 100%;" value="<?php echo $_POST['id_membre1'] ?? 0; ?>"></div>
        </div>
        <div>
            <div><input type="hidden" name="id_membre2" id="id_membre2" style="width: 100%;" value="<?php echo $_POST['id_membre2'] ?? 0; ?>"></div>
        </div><br>

        <div><input type="submit" name="avisnote" style="background: rgb(8, 8, 85); color:white; border:none;" value="Enregistrer" ></div>
    </form><br>








    <?php

require_once '../inc/footer.php';
?>