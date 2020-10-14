<?php
// <!-- pour voir le rendu PHP http://localhost/php/ -->
function debug($var) {
    echo '<pre>';
        var_dump($var);
    echo ' </pre>';
}

// Séctions liées au membre :
function estConnecte() {
    if (isset($_SESSION['membre'])) { // Si membre existe dans la session c'est que l'internaute est passé par la page de connection avec le bon pseudo/mdp et que nous avons rempli la session avec ses informations.
        return true;
    } else {
        return false;
    }
// return isset($_SESSION['membre']); autre plus courte
 
}


function estAdmin() { // cette fonction indique si le memebre est administrateur et connécté
    if(estConnecte() && $_SESSION['membre']['statut'] == 1) { // On vérifie d'abord que la session membre existe,  avant de vérifier qu'elle contient le statut de 1 qui correspond à un admin
        return true;
    } else {
        return false;
    }
}

// function pour faire des requêtes :


function executeRequete($requete, $param = array()) {//Cette fonction attend un strong qui contient la requête SQL à executer. $param est une parametre optionnel dont la valeur par défaut est un array vide. il est déstiné à recevoir un tableau associatif qui lie les marqueurs de la requête à leur valeur. si on ne lui fournit pas ce tableau, $param prend un array vide par défaut On va mettre en lien avec $membre = executeRequete("SELECT * FROM membre WHERE pseudo = :pseudo", array(':pseudo' => $_post['pseudo'])); dans le fichier iscription.

// On fait les htmlspécialchars() : 
    foreach ($param as $indice => $valeur) {
        $param[$indice] = htmlspecialchars($valeur); // On parcourt le tableau $param par ses indice de ses valeurs. A chaque toue de boucle, on prend la valeur, on la passe dans htmlspecialchars() et on la range à sa place au mème indice. Evite les injéctions XSS et CSS.
    }

global $pdo;// Permet d'avoir acces dans la variable dans cette fonction, car $pdo est d'éclarée à l'extérieur de celle-ci

$resultat = $pdo->prepare($requete);// On prépare la requête reçue dans $requete
$succes = $resultat->execute($param);// puis on l'execute en lui donnant $param dont le rôle est d'associer les marqueurs de la requête à la valeur. execut() retourne un booléen pour dire si la requête a marché ou pas.

if ($succes) { // Si true c'est qu'il n'y a pas d'erreurs sur la requête
    return $resultat;// On retourne donc l'objet PDOStatement nécessaire notament aux SELECT
}
else{ // Sinon, si il y a une erreur, on retourne FALSE.
    return false;
}

}




















