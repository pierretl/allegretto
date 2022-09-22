<?php 


	$title = "Connexion";
	include 'include/header.php';

	///////////////////////////////////////////////

	session_start(); /* Starts the session */
	
	/* Check Login form submitted */	
	if(isset($_POST['Submit'])){
		/* Define username and associated password array */
		$logins = array(
			'Alex' => '123456',
			'username1' => 'password1',
			'username2' => 'password2',
			'admin' => 'admin'
		);
		
		/* Check and assign submitted Username and Password to new variable */
		$Username = isset($_POST['Username']) ? $_POST['Username'] : '';
		$Password = isset($_POST['Password']) ? $_POST['Password'] : '';
		
		/* Check Username and Password existence in defined array */		
		if (isset($logins[$Username]) && $logins[$Username] == $Password){
			/* Success: Set session variables and redirect to Protected page  */
			$_SESSION['UserData']['Username']=$logins[$Username];
			header("location:index.php");
			exit;
		} else {
			/* Erreur */
			$messageErreur="<p class='text-6 mb-1'>Nom d'utilisateur ou mot de passe incorrect. </p>";

			if (isset($Username)) {
				$cssUsernameVide = " v_erreur";
			}

			if (isset($Password)) {
				$cssPassworVide = " v_erreur";
			}

		}
	}
?>

<div class="login bg-3">
	
	<div class="text-center">
		<img src="design/logo.svg" alt="" loading="lazy" width="300px">
		<h1 class="mt-0">Connectez-vous à votre compte</h1>
	</div>

	<form action="" method="post" name="Login_Form">
		<?php echo $messageErreur;?>
		<label for="identifiant">Identifiant</label>
		<input name="Username" type="text" id="identifiant" class="mb-1<?php echo $cssUsernameVide; ?>" value="<?php echo $Username; ?>">

		<label for="motdepasse">Mot de passe</label>
		<input name="Password" type="password" id="motdepasse" class="mb-1<?php echo $cssPassworVide; ?>" value="">

		<button name="Submit" type="submit" name="">Connexion</button>
	</form>
	<p class="text-center">
		<a href="demo-cal.php">Démo calendrier</a>
	</div>
</div>



<?php include 'include/footer.php';?>