<?php
require_once 'inc/init.php'; // attention au  (pour lier les fichier php ou html...)

// 1 - Vérification administrateur :
if (!estConnecte()){
    header('location:connexion.php'); // Si le membre n'est pas connecté ou n'est pas admi.On le redirige vers la page de connection.
    exit(); // On quitte le script
}

// 4 - Enregistrement du annonce : 
if ($_POST){ // équivalent à !empty($_POST), qui signifie que le formulaire a été envoyé

    // Ici il faudrait mettre tout les controles sur le formulaire, ce qu'on ne fait pas pour alléger un peu le cour...

    $photo_bdd = ''; // par défaut le champ est un string vide en BDD.

$photo_bdd = ''; // par défaut le champ est un string vide en BDD.

    // 9 - Modification de la photo :
        if(isset($_POST['photo_actuelle'])) { // Si on est en train de modifier le produit on remet le chemin de la photo en BDD actuellement dans le formulaire.
    $photo_bdd = $_POST['photo_actuelle'];
    } 

    // 5 - Suite :
    debug($_FILES);// On voit que la superglobal $_FILES posséde un indice "photo" correspondant au "name" de l'imput type "file" du formulaire, On y trouve aussi un indice prédéfini "name" qui contient le nom du fichier en cours d'upload, et un indice "tmp_name" contenant le nom du fichier temporaire uploadé.

    if (!empty($_FILES['photo']['name'])){ // Si nous avons un nom de fichier, c'est que  nous sommes en train de le télécharger.
        $fichier_photo = $_POST['titre'] . '_' . $_FILES['photo']['name'];  // Nous construisons un nom de fichier photo unique sur la base de la référence fourni dans le formulaire.

        $photo_bdd = 'photo/' . $fichier_photo; // Ceci est le chemin relatif de la photo,chemin enregistré par la BDD. Il sera utilisé par les attributs src des balises <img> pour afficher la photo

        copy($_FILES['photo']['tmp_name'], '' . $photo_bdd); // On copie le fichier temporaire qui se trouve à l'adresse contenue dans copy($_FILES['photo']['tmp_name'] vers l'endroit définitif contenu dans $photo_bdd

    }


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
            ':id_membre'                 => $_POST['id_membre'],
            ':id_categorie'              => $_POST['id_categorie'],
));
// REPLACE INTO se comporte comme un INSERT quand l'id_annonce n'éxiste pas (0), ou comme un UPDATE quand l'id_annonce fourni existe.

    if($requete){ // Si la fonction executeRequete retourne un objet PDOStatement (donc implication évalué à TRUE ), C'est que la requête marche
       $contenu .= '<div class="alert alert-success">La modification à été enrgistré</div>';
    }
    else { // Sinon on a reçu false en cas d'erreur sur la requête.
       $contenu .='<div class="alert alert-danger" ;">Erreur lors de l\'enregistrement...</div>';
    }
}// Fin du if ($_POST)

// 8 - Remplissage du formulaire de modification de annonce :
    if (isset($_GET['id_annonce'])) { //  Si on a reçu l'id_annonce dans l'url, c'est qu'on a ddemandé la modification du annonce.
        // On séléctionne les infos du annonce en BDD pour remplir le formulaire :
        $resultat = executeRequete("SELECT a.*, c.*, a.titre AS titrecat  
        FROM annonce a 
        INNER JOIN categorie c
        ON a.id_categorie = c.id_categorie    
        WHERE id_annonce = :id_annonce", array(':id_annonce' => $_GET['id_annonce']));
        $_POST = $resultat->fetch(PDO::FETCH_ASSOC); // Pas de while car nous n'avons qu'un seul annonce par id.
    }
    // if ($_POST['id_categorie'])) { 
    //     $resultat = executeRequete("SELECT * FROM categorie WHERE id_categorie == $resultat;
    // }
    debug($_POST);
require_once 'inc/header.php';
// 2 - Navigation entre les pages d'administration :
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
            <div><input type="text" name="titre" id="titre" style="width: 100%;" value="<?php echo $_POST['titrecat'] ?? ''; ?>"></div>
        </div>

        <div>
            <div><label for="description_courte">Déscription courte</label></div>
            <div><textarea name="description_courte" id="description_courte" style="width: 100%; "><?php echo $_POST['description_courte'] ?? ''; ?></textarea></div>
        </div>

        <div>
            <div><label for="description_longue">Déscription longue</label></div>
            <div><textarea name="description_longue" id="description_longue" style="width: 100%;"><?php echo $_POST['description_longue'] ?? ''; ?></textarea></div>
        </div>

        <div>
            <div><label for="prix">Prix €</label></div>
            <div><input type="text" name="prix" id="prix" style="width: 100%;" value="<?php echo $_POST['prix'] ?? ''; ?>"></div>
        </div><br>

<?php
// Selecteur pour les différentes catégories
$resultat = executeRequete("SELECT DISTINCT id_categorie, titre FROM categorie");
$valider .= '<select name="id_categorie">';
    while ($categorie = $resultat->fetch(PDO::FETCH_ASSOC)){
        if (isset($_POST['id_categorie']) && $_POST['id_categorie'] == $categorie['id_categorie']) {
           $selected = 'selected';  
        }
        else {
            $selected = '';
        }
        
            $valider .= '<option value="'. $categorie['id_categorie'] .'" '. $selected .'>  '. $categorie['titre'] .'</option>';
    }
$valider .= '</select>';

echo $valider;
?>
        <!-- <div>
             <div><input type="text" name="id_categorie" id="id_categorie" style="width: 100%;" value="<?php echo $_POST['id_categorie']['titre'];  ?>">
        </div>  -->
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
            <div><textarea name="adresse" id="adresse" style="width: 100%;"><?php echo $_POST['adresse'] ?? ''; ?></textarea></div>
        </div>
        
        <div>
            <div><label for="code_postal">Code_postal</label></div>
            <div><input type="text" name="code_postal" id="code_postal" style="width: 100%;" value="<?php echo $_POST['code_postal'] ?? ''; ?>"></div>
        </div>
        
        <div>
            <div><input type="hidden" name="id_membre" id="id_membre" style="width: 100%;" value="<?php echo $_POST['id_membre'] ?? $_SESSION['membre']['id_membre']; ?>"></div>
        </div>
        
         
        <br>

        <div style="text-align: center;"><input type="submit" style="background: rgb(8, 8, 85); color:white; border:none;" value="Enregistrer" ></div>
    </form>

</form>


<?php
// endif;
require_once 'inc/footer.php';
?>