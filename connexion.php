<?php 


	$title = "Connexion";
	include 'include/header.php';

	///////////////////////////////////////////////

	//commence la session
	session_start();

	//récupérationde la liste des utilisateurs
	$jsonString = file_get_contents("data/utilisateur.json");
	$dataUtilisateur = json_decode($jsonString, true);
	
	
	//Vérification du forumaire saisi
	if(isset($_POST['Submit'])){
		
		// Stock les nom d'utilisateur et mot de passe associé
		$logins= [];
		for ($i=0; $i < count($dataUtilisateur); $i++) {
			$logins += [ $dataUtilisateur[$i]['idendifiant'] => array("idendifiant" => $dataUtilisateur[$i]['idendifiant'],"motDePasse" => $dataUtilisateur[$i]['motDePasse']) ];
		}
		debug($logins);
		
		// Stock les infos saisi dans des varaibles.
		$Identifiant = isset($_POST['Identifiant']) ? $_POST['Identifiant'] : '';
		$MotDePasse = isset($_POST['MotDePasse']) ? $_POST['MotDePasse'] : '';

		if ( 
			$Identifiant !== '' && // champs identifiant saisi
			$MotDePasse !== '' && // champs Mot de passe saisi
			array_search($Identifiant, array_column($logins, 'idendifiant')) !== false && // le login saisi correspond à un utilisateur en data
			$MotDePasse && password_verify($MotDePasse, $logins[$Identifiant]['motDePasse'])  // le mot de passe correspond à l'utilisateur
		) {

			// Succès : ajoute l'identifiant à la session et redirige sur l'index
			$_SESSION['Utilisateur']['Identifiant'] = $logins[$Identifiant]['idendifiant'];
			header("location:index.php");
			exit;
			
		} else {

			// Erreur	
			$erreurMessage="<p class='text-6 mb-1'>Nom d'utilisateur ou mot de passe incorrect. </p>";
			$erreurClassCss = " v_erreur";
			
		}

	}
?>

<div class="cadre1 bg-3">
	
	<div class="text-center">
		<img src="design/logo.svg" alt="" loading="lazy" class="logo">
		<h1 class="mt-0">Connectez-vous à votre compte</h1>
	</div>

	<form action="" method="post" name="Login_Form">
		<?php echo $erreurMessage;?>
		<label for="identifiant">Identifiant</label>
		<input name="Identifiant" type="text" id="identifiant" class="mb-1<?php echo $erreurClassCss; ?>" value="<?php echo $Identifiant; ?>">

		<label for="motdepasse">Mot de passe</label>
		<input name="MotDePasse" type="password" id="motdepasse" class="mb-1<?php echo $erreurClassCss; ?>" value="">

		<button name="Submit" type="submit" name="">Connexion</button>
	</form>
	<p class="text-center">
		<a href="demo-cal.php">Démo calendrier</a>
	</div>
</div>



<?php include 'include/footer.php';?>