<?php
require_once '../inc/init.php'; // attention au ../ (pour lier les fichier php ou html...)

// 1 - Vérification administrateur :
if (!estAdmin()){
    header('location:../connexion.php'); // Si le membre n'esyt pas connecté ou n'est pas admi.On le redirige vers la page de connection.
    exit(); // On quitte le script
}
// 4 - Enregistrement du produit : 
debug ($_POST);

if ($_POST){ 
    $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);



    $succes = executeRequete("REPLACE INTO membre (id_membre, pseudo, mdp, nom, prenom, telephone, email, civilite, statut, date_enregistrement) VALUES (:id_membre, :pseudo, :mdp, :nom, :prenom, :telephone, :email, :civilite, '0', NOW())",
    array(':id_membre'    => $_POST['id_membre'],
          ':pseudo'       => $_POST['pseudo'],
          ':mdp'          => $mdp,
          ':nom'          => $_POST['nom'],
          ':prenom'       => $_POST['prenom'],
          ':telephone'    => $_POST['telephone'],
          ':email'        => $_POST['email'],
          ':civilite'     => $_POST['civilite'],
     
    ));

    if($succes){ // Si la fonction executeRequete retourne un objet PDOStatement (donc implication évalué à TRUE ), C'est que la requête marche
       $contenu .= '<div class="alert alert-success">La modification a été enrgistré</div>';
    }
    else { // Sinon on a reçu false en cas d'erreur sur la requête.
       $contenu .='<div class="alert alert-danger">Erreur lors de l\'enregistrement...</div>';
    }
}// Fin du if ($_POST)

// 8 - Remplissage du formulaire de modification de produit :
    if (isset($_GET['id_membre'])) { //  Si on a reçu l'id_produit dans l'url, c'est qu'on a demandé la modification du produit.
        // On séléctionne les infos du produit en BDD pour remplir le formulaire :
        $resultat = executeRequete("SELECT * FROM membre WHERE id_membre = :id_membre", array(':id_membre' => $_GET['id_membre']));
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
    
    <h1 class="mt-4">Ajouter un membre</h1>
    
    <?php
    echo $contenu;
    ?>
        <form  method="post" action="" enctype="multipart/form-data" style="padding-left: 20%; padding-right: 20%;">
            <div>
                <div><input type="hidden" name="id_membre" id="id_membre" style="width: 100%;" value="<?php echo $_POST['id_membre'] ?? ''; ?>">
            
            </div>
            <div>
                <div><label for="pseudo">Votre pseudo *</label></div>
                <div><input type="text" name="pseudo" id="pseudo" style="width: 100%;" value="<?php echo $_POST['pseudo'] ?? ''; ?>"></div>
            </div>
    
            <div>
                <div><label for="mdp">Votre mot de passe *</label></div>
                <div><input type="password" name="mdp" id="mdp" style="width: 100%;" value="<?php echo $_POST['mdp'] ?? ''; ?>"></div>
            </div>
    
            <div>
                <div><label for="nom">Votre Nom *</label></div>
                <div><input type="text" name="nom" id="nom" style="width: 100%;" value="<?php echo $_POST['nom'] ?? ''; ?>"></div>
            </div>
    
            <div>
                <div><label for="prenom">Votre prénom *</label></div>
                <div><input type="text" name="prenom" id="prenom"style="width: 100%;" value="<?php echo $_POST['prenom'] ?? ''; ?>"></div>
            </div>
    
            <div>
                <div><label for="telephone" >Votre téléphone *</label></div>
                <div><input type="text" name="telephone"  id="telephone" style="width: 100%;" value="<?php echo $_POST['telephone'] ?? ''; ?>"></div>
            </div>
    
            <div>
                <div><label for="email">Votre email *</label></div>
                <div><input type="text" name="email" id="email" style="width: 100%;" value="<?php echo $_POST['email'] ?? ''; ?>"></div>
            </div>
    
            <div>
            <div><label for="civilite">Civilité</label></div>
            <div class="input-group mb-3">
                <select name="civilite" class="custom" id="inputGroupSelect02">
                    <option value="m"<?php if (isset($_POST['civilite']) && $_POST['civilite'] == 'm') echo 'selected';?>>M.</option>
                    <option value="f"<?php if (isset($_POST['civilite']) && $_POST['civilite'] == 'f') echo 'selected';?>>Mme.</option>
                </select>
            </div>
        </div>
    
            <div>
            <div><label for="statut">Statut</label></div>
            <div class="input-group mb-3" >
                <select name="statut" class="custom" id="inputGroupSelect02">
                    <option value="0"<?php if (isset($_POST['statut']) && $_POST['statut'] == '0') echo 'selected';?>>User</option>
                    <option value="1"<?php if (isset($_POST['statut']) && $_POST['statut'] == '1') echo 'selected';?>>Admin</option>
                </select>
            </div>
        </div>
    
            <div><input type="submit" value="Enregistrer" ></div>
        </form><br>
    <?php

require_once '../inc/footer.php';
?>