<?php

$securite = true;
include 'include/header.php';

///////////////////////////////////////////////

include 'include/function-json.php';

$jsonUtilisateur = "data/utilisateur.json";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $form = $_GET['form'];

    switch ($form) {
        case 'addUser':
            include 'formulaire/utilisateur-ajout.php';
            break;
        case 'deleteUser':
            include 'formulaire/utilisateur-delete.php';
            break;
    }
}


?>











<p><a href="deconnexion.php">Déconnexion</a>(<?php echo $_SESSION['Utilisateur']['Identifiant']; ?>)</p>

<hr>

<h2>Ajouter un Utilisateur</h2>

<form method="post" action="index.php?form=addUser">
    <label for="idendifiant">Idendifiant</label>
    <input type="text" name="idendifiant" id="idendifiant">
    <?php 
        if ($erreurIdendifiant) {
            echo "Le champ est vide";
        }
    ?>
    <br>
    <label for="motdepasse">Mot de passe</label>
    <input type="text" name="motDePasse" id="motdepasse">
    <?php 
        if ($erreurMotDePasse) {
            echo "Le champ est vide";
        }
    ?>
    <br>
    <button type="submit">Ajouter</button>
</form>

<hr>

<div class="scroll">
    <table border="1" cellpadding="5" cellspacing="1">
        <caption>Liste des utilisateurs</caption>
        <thead>
            <tr>
                <td>Utilisateur</td>
                <td>Groupe</td>
                <td>Mot de passe</td>
                <td>Supprimer</td>
            </tr>
        </thead>
        <tbody>

            <?php

                $utilisateur = decodeJason($jsonUtilisateur);

                foreach ($utilisateur as $key => $value) {
                    echo "<tr>";
                        echo "<td>" . $utilisateur[$key]["idendifiant"] . "</td>";
                        echo "<td>" . $utilisateur[$key]["groupe"] . "</td>";
                        echo "<td>" . $utilisateur[$key]["motDePasse"] . "</td>";
                        echo "<td>";
                            if ($utilisateur[$key]["groupe"] != "admin") {
                                echo '
                                <form method="post" action="index.php?form=deleteUser&key='.$key.'">
                                    <button>Supprimer</button>
                                </form>';
                            }
                        echo "</td>";
                    echo "</tr>";
                }

            ?>
        </tbody>
    </table>
</div>




    


    

<?php include 'include/footer.php';?>