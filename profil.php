<?php
require_once 'inc/init.php';
$message = ''; 

//Exercice : vous faites 
// 1 . si le visiteur n'est pac connecté, on le redirige ves la page connexion.
if (!estConnecte()){
    header('location:connexion.php'); 
    exit();
}
//************************************************************************************************************** */
//************************************************************************************************************** */
//************************************************************************************************************** */

// 7 - Suppression du annonce :
if (isset($_GET['id_annonce'])){ // Si existe id_annonce dans l'url, donc dans $_GET, c'est qu'on a demandé la suppression du annonce.
    $resultat = executeRequete("DELETE FROM annonce WHERE id_annonce = :id_annonce", array(':id_annonce' => $_GET['id_annonce']));
    
        if ($resultat->rowCount() == 1){
            $contenu .= '<div class="alert alert-success">L\'annonce a bien été supprimé.</div>';
        }
        else {
            $contenu .= '<div class="alert alert-danger">Erreur lors de la suppression</div>';
        }
    }
    


    $resultat = executeRequete("SELECT id_annonce, annonce.titre, description_courte, description_longue, prix, photo, pays, ville,                                   adresse, code_postal, membre.prenom, categorie.titre AS titrecat, annonce.date_enregistrement AS date
                                FROM annonce 
                                INNER JOIN membre ON annonce.id_membre2 = membre.id_membre
                                INNER JOIN categorie ON annonce.id_categorie = categorie.id_categorie
                                WHERE annonce.id_membre2 = :id_membre", 
                                array(':id_membre' => $_SESSION['membre']['id_membre'],
    ));
    // // 6 - Affichage des annonces dans le back-office.
    // $resultat = executeRequete("SELECT * FROM annonce"); // On selectionne tous les annonces.
    
    
    
    
    
    
    $contenu .= '<div>Nombre d\'annonce : ' . $resultat->rowCount() . '</div>';
    
    $contenu .= '<div class="table-responsive">';
        $contenu .= '<table class="table">';
        //Lignes des entêtes du tableau :
            $contenu .= '<tr>';
                $contenu .= '<th>id_annonce</th>';
                $contenu .= '<th>titre</th>';
                $contenu .= '<th>description courte</th>';
                $contenu .= '<th>description longue</th>';
                $contenu .= '<th>prix €</th>';
                $contenu .= '<th>photo</th>';
                $contenu .= '<th>pays</th>';
                $contenu .= '<th>ville</th>';
                $contenu .= '<th>adresse</th>';
                $contenu .= '<th>cp</th>';
                $contenu .= '<th>membre</th>';
                $contenu .= '<th>catégorie</th>';
                $contenu .= '<th>enregistrement</th>';
                $contenu .= '<th>action</th>';
            $contenu .= '</tr>';
    
            //Affichage des lignes du tableau :
            while ($annonce = $resultat->fetch(PDO::FETCH_ASSOC)){ //$annonce est un array avec toutes les informations d'un seul annonce à chaque tour de boucle. On le parcourt donc avec une boucle foreach.
            $contenu .= '<tr>';
                foreach ($annonce as $indice => $valeur){
                    if ($indice == 'photo') { 
                    $contenu .= '<td><img src="'. $valeur .'"style="width:90px"></a></td>';
                }
                elseif ($indice == 'description_longue'){
                    $contenu .= '<td>' . substr($valeur, 0, 10) . '...</td>';
                
                }
                else{
                    $contenu .= '<td>' . $valeur . '</td>';
                }
            }
            $contenu .= '<td>
                            <a href="deposer_annonce.php?id_annonce='.$annonce['id_annonce'].'">modifer</a> |
                            <a href="?id_annonce='.$annonce['id_annonce'].'" onclick = "return confirm(\'Etes-vous certain de vouloir supprimer ce annonce?\')">supprimer</a>
                         </td>';
    
    
            $contenu .= '</tr>';
            }
    
        $contenu .='</div>';
    $contenu .= '</table>';
    

// 2 . Vous affichez son profil tel que dessiné au tableau.
// Solution numéro 2 
debug ($_SESSION);
extract($_SESSION['membre']); // Extrait tous les indices de l'array associatif sous forme de variable, à l'aquelle est affée la valeur corresondante. Exemple : $_SESSION['membre']['email'] devient la variable $email
//************************************************************************************************************** */
//************************************************************************************************************** */
//************************************************************************************************************** */


require_once 'inc/header.php';
?>

<!-- Solution numéro 2 -->
<?php
// if ($statut == 1) {
//     echo '<p>Vous êtes un administrateur.</p>'

?>
    <h1>Profil : </h1>
    <h2>Bonjour : <?php echo $prenom . ' ' . $nom ;?></h2>
    <p>Vous êtes un administrateur : <?php echo $statut;?></p>
    <p>Email : <?php echo $email;?></p>
    <p>Civilité : <?php echo $civilite;?></p>
    <p>Téléphone : <?php echo $telephone;?></p>
    <p>Date d'enregistrement : <?php echo $date_enregistrement;?></p>


<!-- //************************************************************************************************************** */
//************************************************************************************************************** */
//************************************************************************************************************** */ -->



<h1 class="mt-4">Gestion des annonces</h1>

<?php
echo $contenu;
?>
<?php
require_once 'inc/footer.php';
?>