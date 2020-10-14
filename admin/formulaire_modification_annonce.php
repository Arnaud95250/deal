<?php
require_once '../inc/init.php'; // attention au ../ (pour lier les fichier php ou html...)

// 1 - Vérification administrateur :
if (!estAdmin()){
    header('location:../connexion.php'); // Si le membre n'esyt pas connecté ou n'est pas admi.On le redirige vers la page de connection.
    exit(); // On quitte le script
}
// 4 - Enregistrement du produit : 
debug ($_POST);

if ($_POST){ // équivalent à !empty($_POST), qui signifie que le formulaire a été envoyé

    // On enregistre uniquement le chemin de la photo en BDD, mais pas le fichier en tant que tel. ce dernier est bien dans le répertoire "photo/" du site.

    // code de la photo à venir :

    // Insertion du produit en BDD :

    $requete = executeRequete("REPLACE INTO annonce VALUES (:id_annonce, :titre, :description_courte, :description_longue, :prix, :photo, :pays, :ville, :adresse, :code_postal, :id_membre, :id_categorie, NOW())", array(
        ':id_annonce'                => $_POST['id_annonce'],
        ':titre'                     => $_POST['titre'],
        ':description_courte'        => $_POST['description_courte'],
        ':description_longue'        => $_POST['description_longue'],
        ':prix'                      => $_POST['prix'],
        ':photo'                     => $photo_bdd,
        ':pays'                      => $_POST['pays'],
        ':ville'                     => $_POST['ville'],
        ':adresse'                   => $_POST['adresse'],
        ':code_postal'               => $_POST['code_postal'],
        ':id_membre'                 => $_SESSION['membre']['id_membre'],
        ':id_categorie'              => $_POST['id_categorie'],
));
// REPLACE INTO se comporte comme un INSERT quand l'id_annonce n'éxiste pas (0), ou comme un UPDATE quand l'id_annonce fourni existe.

if($requete){ // Si la fonction executeRequete retourne un objet PDOStatement (donc implication évalué à TRUE ), C'est que la requête marche
   $contenu .= '<div class="alert alert-success">Le annonce à été enrgistré</div>';
}
else { // Sinon on a reçu false en cas d'erreur sur la requête.
   $contenu .='<div class="alert alert-danger" ;">Erreur lors de l\'enregistrement...</div>';
}
}// Fin du if ($_POST)

// 8 - Remplissage du formulaire de modification de annonce :
if (isset($_GET['id_annonce'])) { //  Si on a reçu l'id_annonce dans l'url, c'est qu'on a ddemandé la modification du annonce.
    // On séléctionne les infos du annonce en BDD pour remplir le formulaire :
    $resultat = executeRequete("SELECT * FROM annonce WHERE id_annonce = :id_annonce", array(':id_annonce' => $_GET['id_annonce']));
    $_POST = $resultat->fetch(PDO::FETCH_ASSOC); // Pas de while car nous n'avons qu'un seul annonce par id.
}
// if ($_POST['id_categorie'])) { 
//     $resultat = executeRequete("SELECT * FROM categorie WHERE id_categorie == $resultat;
// }

    require_once '../inc/header.php';
    // 2 - Navigation entre les pages d'administration :
    ?>
    ?>

<h1 class="mt-4" style="padding-left: 20%;">Déposer votre annonce</h1>

<?php
// 3 - Formulaire de création ou d'ajout ou de modification de prosuit : 
?>
<form method="post" action="" enctype="multipart/form-data"><!-- enctype spécifie que le formulaire envoye les données binaires (fichier) en plus du texte (chaque formulaire) : permet d'uploader un fichier photo --> 
    <div>
        <input type="hidden" name="id_annonce" value="<?php echo $annonce_actuel['id_annonce'] ?? 0;?>"> <!-- On met un type hidden pour éviter de la modifier par accident on précise ine value à 0 pour que lors de l'insertion en BDD l'id_annonce s'auto incrémente (création de annonce)-->
<?php
echo $contenu; //Pour affichez les messages ($contenu se trouve dans le fichier "init.php").
// if ($affiche_formulaire) : // Si memebre pas inscrit, on affiche le formulaire. syntaxe en if(): ....  endif;
?>

    <form method="post" action="" >
<div style="padding-left: 20%; padding-right: 20%;">
    <div>
        <div>
            <div><input type="hidden" name="id_annonce" id="id_annonce" style="width: 100%;" value="<?php echo $_POST['id_annonce'] ?? 0; ?>"></div>
        </div>
        <div>
            <div><label for="titre">Titre</label></div>
            <div><input type="text" name="titre" id="titre" style="width: 100%;" value="<?php echo $_POST['titre'] ?? ''; ?>"></div>
        </div>

        <div>
            <div><label for="description_courte">Déscription courte</label></div>
            <div><textarea name="description_courte" id="description_courte" style="width: 100%; " value="<?php echo $_POST['description_courte'] ?? ''; ?>"></textarea></div>
        </div>

        <div>
            <div><label for="description_longue">Déscription longue</label></div>
            <div><textarea name="description_longue" id="description_longue" style="width: 100%;" value="<?php echo $_POST['description_longue'] ?? ''; ?>"></textarea></div>
        </div>

        <div>
            <div><label for="prix">Prix €</label></div>
            <div><input type="text" name="prix" id="prix" style="width: 100%;" value="<?php echo $_POST['prix'] ?? ''; ?>"></div>
        </div>

        <div>
            <div><label for="categorie" >Categorie</label></div>
            <select name="categorie"  style="width: 100%; " >
            <option value="0"<?php echo $_POST['categorie'] ?? ''; ?>>Toutes les catégories</option>
                <option value="Voiture"<?php echo $_POST['categorie'] ?? ''; ?>>Voiture</option>
                <option value="Téléphone"<?php echo $_POST['categorie'] ?? ''; ?>>Téléphone</option>
                <option value="Appartement"<?php echo $_POST['categorie'] ?? ''; ?>>Appartement</option>
                <option value="Maison"<?php echo $_POST['categorie'] ?? ''; ?>>Maison</option>
                <option value="Moto"<?php echo $_POST['categorie'] ?? ''; ?>>Moto</option>
                <option value="Camion"<?php echo $_POST['categorie'] ?? ''; ?>>Camion</option>
                <option value="Télé"<?php echo $_POST['categorie'] ?? ''; ?>>Télé</option>
                <option value="Animaux"<?php echo $_POST['categorie'] ?? ''; ?>>Animaux</option>
                <option value="Sport"<?php echo $_POST['categorie'] ?? ''; ?>>Sport</option>
                
            </select>
        </div>

        <div>
        <div><label for="photo">Photo</label></div>
        <div> <!-- 5 - Upload de la photo-->
            <input type="file" name="photo" id="photo"> <!-- Ne pas oublier l'attribut "entype" sur lka balise form plus haut dans le fichier formulaire_annonce.php(debut du html)-->
            <?php if(isset($annonce_actuel['photo'])) { // En cas de modification du annonce
                      echo '<input type="hidden" name="photo_actuelle" value="'.$annonce_actuel['photo'].'">'; // On veut mettre le chemin de la photo actuelle dans $_POST pour remettre en BDD.
                }?>
            </div>
        </div>

        <div>
            <div><label for="pays">Pays</label></div>
            <select name="pays"  style="width: 100%; " >
                <option value="France"<?php echo $_POST['pays'] ?? ''; ?>>France</option>
                <option value="Angleterre"<?php echo $_POST['pays'] ?? ''; ?>>Angleterre</option>
                <option value="Espagne"<?php echo $_POST['pays'] ?? ''; ?>>Espagne</option>
                <option value="Italie"<?php echo $_POST['pays'] ?? ''; ?>>Italie</option>
                <option value="Belgique"<?php echo $_POST['pays'] ?? ''; ?>>Belgique</option>
            </select>
        </div>

        <div>
            <div><label for="ville" >ville</label></div>
            <select name="ville"  style="width: 100%; " >
                <option value="Paris"<?php echo $_POST['ville'] ?? ''; ?>>Paris</option>
                <option value="Bruge"<?php echo $_POST['ville'] ?? ''; ?>>Bruge</option>
                <option value="Rome"<?php echo $_POST['ville'] ?? ''; ?>>Rome</option>
                <option value="Brive la Gaillarde"<?php echo $_POST['ville'] ?? ''; ?>>Brive la Gaillarde</option>
                <option value="Madrid"<?php echo $_POST['ville'] ?? ''; ?>>Madrid</option>
                <option value="Beauchamp"<?php echo $_POST['ville'] ?? ''; ?>>Beauchamp</option>
                <option value="Taverny"<?php echo $_POST['ville'] ?? ''; ?>>Taverny</option>
                <option value="Bessancourt"<?php echo $_POST['ville'] ?? ''; ?>>Bessancourt</option>
                <option value="Saint Prix"<?php echo $_POST['ville'] ?? ''; ?>>Saint Prix</option>
                <option value="Herblay"<?php echo $_POST['ville'] ?? ''; ?>>Herblay</option>
                <option value="Saint Leu la Forêt"<?php echo $_POST['ville'] ?? ''; ?>>Saint Leu la Forêt</option>
                <option value="Frépillon"<?php echo $_POST['ville'] ?? ''; ?>>Frépillon</option>
                <option value="Mériel"<?php echo $_POST['ville'] ?? ''; ?>>Mériel</option>
                <option value="Londre"<?php echo $_POST['ville'] ?? ''; ?>>Londre</option>
            </select>
        </div>
        
        <div>
            <div><label for="adresse">Adresse</label></div>
            <div><textarea name="adresse" id="adresse" style="width: 100%;" value="<?php echo $_POST['adresse'] ?? ''; ?>"></textarea></div>
        </div>
        
        <div>
            <div><label for="code_postal">Code_postal</label></div>
            <div><input type="text" name="code_postal" id="code_postal" style="width: 100%;" value="<?php echo $_POST['code_postal'] ?? ''; ?>"></div>
        </div>
        
        <div>
            <div><input type="hidden" name="id_membre" id="id_membre" style="width: 100%;" value="<?php echo $_SESSION['id_membre'] ?? 0; ?>"></div>
        </div>
        
        <div>
            <div><input type="hidden" name="id_categorie" id="id_categorie" style="width: 100%;" value="<?php echo $_POST['id_categorie'] ?? 0; ?>"></div>
        </div>
        <br>

        <div style="text-align: center;"><input type="submit" style="background: rgb(8, 8, 85); color:white; border:none;" value="Enregistrer" ></div>
    </form>

</form><br>
    <?php

require_once '../inc/footer.php';
?>