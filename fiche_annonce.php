<?php
require_once 'inc/init.php';
$suggestion='';
// 1 - On controle l'existance du annonce demandé : 
debug($_GET);





if (isset($_GET['id_annonce'])) { // Si un annonce a été demandé.
// On vérifie que l'identifiant est en BDD (une annonce mis en favori a pu être supprimé) :
    $resultat = executeRequete("SELECT a.*, c.titre, m.nom, m.prenom, m.telephone 
                                FROM annonce a 
                                INNER JOIN categorie c
                                ON a.id_categorie = c.id_categorie 
                                INNER JOIN membre m 
                                ON a.id_membre2 = m.id_membre
                                WHERE id_annonce = :id_annonce",array(':id_annonce' => $_GET['id_annonce']));


    // Début Modale Contact
    // $telephone = executeRequete("SELECT nom, prenom, telephone FROM membre");
    // $contact = $telephone->fetch(PDO::FETCH_ASSOC);
    // Fin Modale Contact


    if ($resultat->rowCount() == 0 ) {
        header('location:index.php');
        exit();
    }

// 2 - Préparation de l'affichage  annonce demandé :
    $annonce = $resultat->fetch(PDO::FETCH_ASSOC);
    extract($annonce); // On crée autant de variable qu'il y a d'indices dans le tableau pour afficher les valeurs plus bas.
debug($annonce);
}
else {
    //Si id_annonce n'existe pas dans $_GET c'est que l'url et altérée : on redirige vers la boutique :
    header('location:index.php');
    exit();
}

// *****************************************
// debutnote moyenne
// *****************************************

// $resultat = executeRequete("SELECT note, id_membre, pseudo 
//             FROM note 
//             INNER JOIN membre 
//             ON id_membre1 = id_membre
//             WHERE id_note = 'note' 

// ");


// $noteMoyenne .= '<div class="table-responsive">';
//     $noteMoyenne .= '<div class="table"><h4>note Moyenne</h4>';
        


// while ($requete = $resultat->fetch(PDO::FETCH_ASSOC)){
//     $noteMoyenne .= '<tr>';
//         $noteMoyenne .= '<td>'. $requete['AVG(note)'] . '</td>';
//     $noteMoyenne .= '</tr>';
// }


// // // récupération des notes et de l'auteur dans la BDD
// $reqNote = $pdo->prepare('SELECT n.*, m.pseudo
//                           FROM note n
//                           LEFT JOIN membre m ON n.id_membre1 = m.id_membre
//                           WHERE n.id_membre2 = note
//                           ORDER BY id_note
//       ');

// $reqNote->execute(array((int)$_GET['id_membre2']));
// $notes = $reqNote->fetchAll();
// $nbNotes = count($notes);

// // note moyenne de l'auteur
// $noteMoyenne = $pdo->prepare('SELECT AVG(note) FROM note WHERE id_membre2 = note');
// $noteMoyenne->execute(array($id_membre));
// $moyenne_note = $noteMoyenne->fetchColumn();







// *****************************************
// fin note moyenne
// *****************************************


$requete = executeRequete("SELECT * FROM annonce  WHERE id_categorie = :id_categorie AND id_annonce != :id_annonce ORDER BY RAND() LIMIT 3", array(':id_categorie' => $id_categorie, ':id_annonce' => $id_annonce));

    while ($annonce = $requete->fetch(PDO::FETCH_ASSOC)){

        $suggestion .= '<div class="col-4">';
            $suggestion .= '<h4>'. $annonce['titre'] .'</h4>';
            $suggestion .= '<a href="?id_annonce='. $annonce['id_annonce'] .'"><img src="'. $annonce['photo'] .'" alt="'. $annonce['titre'] .'" class="img-fluid"></a>';
        $suggestion .= '</div>'; // class="col-4"
} 


if (!empty($_POST['commentaire']) && !empty($_POST['id_membre1'])) {
// $commentaire = executeRequete("SELECT id_annonce FROM annonce");
    $commentaire = executeRequete("INSERT INTO commentaire VALUES (:id_commentaire, :commentaire, :id_membre, :id_annonce, NOW())",
            array( 
                ':id_commentaire'           => $_POST['id_commentaire'],
                ':commentaire'              => $_POST['commentaire'],
                ':id_membre'                => $_POST['id_membre1'],
                ':id_annonce'               => $_POST['id_annonce'], 
));
       $contenu .= '<div class="alert alert-success">La modification a été enrgistré</div>';
    }

if (!empty($_POST['nom']) && ($_POST['note'] >= 0 ) && !empty($_POST['id_membre1'])) {
    $note = executeRequete("INSERT INTO note VALUES (:id_note, :note, :nom, :id_membre1, :id_membre2, NOW())",
           array(
                ':id_note'             => $_POST['id_note'],
                ':note'                => $_POST['note'],
                ':nom'                 => $_POST['nom'],
                ':id_membre1'          => $_POST['id_membre1'],
                ':id_membre2'          => $_POST['id_membre2'], 
    ));
    $contenu .= '<div class="alert alert-success">La modification a été enrgistré</div>';
    }        
debug($id_membre2);


require_once 'inc/header.php';
?>
    <div class="row">

        <div class="col-12">
            <h1><?php  ?></h1>
        </div>

        <div class="col-md-12" style="background: rgb(61, 61, 61) ;">
            <img src="<?php echo $photo; ?>" alt="<?php echo $titre; ?>" style="padding: 0 20% 0 20%" class="img-fluid">
        </div>

        <div class="col-md-12">
           <h4>Date création de l'annonce : <?php echo  $date_enregistrement; ?></h4>
           <p><?php echo $annonce['titre'] . $description_courte; ?></p><br>

           <h3 style="color:blue;";>Prix : <?php echo $prix . ' €'; ?></h3><br>

           <h5>Détails</h5>
           <ul style="list-style: none;">
               <li>Catégorie : <?php echo $titre; ?></li><br>
               <li><h5>Déscription de l'annonce : </h5><?php echo $description_longue; ?></li><br>
               <li>Localisation : <?php echo $adresse. ', ' .$ville . ' - ' . $code_postal; ?></li><br>
           </ul>
    </div> <!--class="row"-->

    
    







    
    <form method="post" action="?id_annonce=<?php echo $_GET['id_annonce']?>">
        <!-- Button trigger modal -->
        <div>
            <div><input type="hidden" name="id_note" id="id_note" style="width: 100%;" value="<?php echo $_POST['id_note'] ?? 0; ?>"></div>
        </div>

        <div>
            <div><input type="hidden" name="id_commentaire" id="id_commentaire" style="width: 100%;" value="<?php echo $_POST['id_commentaire'] ?? 0; ?>"></div>
        </div>

        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
        Déposer un Commentaire
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Rédiger votre commentaire</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div><label for="commentaire">Donner votre nom</label></div>
                <div><textarea name="commentaire" class="commentaire" cols="62" rows="5"></textarea></div>
            </div>
            <div class="modal-body">Donner votre NOTE
                <select name="note" id="note">
                    <option value="-">-</option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select> /5
            </div>
            <div  class="modal-body">
                <div><label for="nom">Donnez votre AVIS</label></div>
                <div><textarea id="nom" name="nom" cols="62" rows="5"><?php echo $_POST['nom'] ?? ''; ?></textarea></div>
            </div><br>
            <div>
                <div><input type="hidden" name="id_membre1" id="id_membre1" style="width: 100%;" value="<?php echo $_SESSION['membre']['id_membre'] ?? 0; ?>"></div>
            </div>
            <div>
                <div><input type="hidden" name="id_membre2" id="id_membre2" style="width: 100%;" value="<?php echo $_POST['prenom'] ?? 0; ?>"></div>
            </div>
            <div>
                <div><input type="hidden" name="id_annonce" id="id_annonce" style="width: 100%;" value="<?php echo $id_annonce ?? 0; ?>"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
            </div>
        </div>
        </div>
    </form>

    <p style="color: white;">---------</p>

    <form method="post" action="?id_annonce=">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalcontact">
        Contacter<?php echo $_POST['id_membre'] ?? ' ' . $prenom;?>
        </button>

        <div class="modal fade" id="modalcontact" tabindex="-1" role="dialog" aria-labelledby="modalcontactTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"><h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <h3><?php echo $_POST['id_membre'] ?? $prenom . ' ' . $nom . ' : ' .  $telephone; ?></h3>
            <div  class="modal-body">
                <div><label for="nom">ecriver à  <?php echo $_POST['id_membre'] ?? ' : ' . $prenom; ?></label></div>
                <div><textarea id="nom" name="nom" cols="62" rows="5"></textarea></div>
            </div><br>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
            </div>
        </div>
        </div>
    </form>

    <div>
        <div><input type="hidden" name="id_note" id="id_note" style="width: 100%;" value="<?php echo $_POST['note']['id_membre1'] ?? 0; ?>"></div>
    </div>
    <?php echo $_POST['note']['id_membre1'] ?? 0; ;?>

    <?php
echo $noteMoyenne;
?>


    <!-- Exercice -->
    <hr>
    <div class="row">
        <div class="col-12"><br>
            <h5>Suggestions d'annonces</h5>
        </div>
        <?php echo $suggestion;?>
    </div>
 
<?php
require_once 'inc/footer.php';
?>