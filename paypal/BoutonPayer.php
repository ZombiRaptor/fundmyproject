<?php
#Scrypt traduit et modifie par maniT4c
#Retrouvez le scrypt d'origine sur http://www.stellarwebsolutions.com/en/articles/paypal_button_encryption_php.php
 
#Defini le chemin de la racine pour OpenSSL
putenv("HOME=~");
 
# chemin vers la clef privee
$MY_KEY_FILE = "cle_prive_paypal.pem";
 
# chemin vers le certificat public
$MY_CERT_FILE = "paypal_certificat_public.pem";
 
# chemin vers le certificat public de paypal
$PAYPAL_CERT_FILE = "/mon_chemin/paypal_cert_pem.txt";
 
# chemin vers openssl sur votre serveur
# pensez a verifier que openssl est active sur votre serveur avec la commande phpinfo()
$OPENSSL = "C:\wamp\bin\apache\Apache2.4.4\conf\openssl.cnf";
 
$form = array('cmd' => '_xclick',//indique a paypal qu'il s'agit d'un bouton payer maintenant
		'business' => $_POST['email_vendeur'],//adresse du vendeur (qui doit recevoir le paiement)
		'item_name' => $_POST['titre_projet'],  //nom de la commande
		'item_number' => '1', //numero de la commande
		'currency_code' => 'EUR', //Devise
		'amount' => $_POST['montant'], //montant a payer
		'lc' => 'FR', //langue de l'interface paypal
        'cert_id' => '72B3ENUM3MZGC', //identifiant de certificat donné par paypal
        'custom' => 'mes valeurs utiles pour le traitement',//variable permettant de recevoir diverses informations sur la page de retour
        'invoice' => '1',//valeur unique empechant les paiements accidentels (doit être differente pour chaque paiement)
        'charset' => 'utf-8',//Definit le charset utilisez
        'no_shipping' => '1', //Le client n'est pas invite a rentrer son adresse
        'cpp_header_image' => 'http://www.domaine.com/logo.jpg',//Adresse de l'image se trouvant en haut de la page de paiement (750x90px maxi) dans l'ideal cette image soit se trouver sur un serveur securisé pour eviter d'avoir un message indiquant que certaine parti de la page ne sont pas écurisée.
		'return' => 'http://www.domaine.com/boutique.php',//Adresse de retour lorsque l'utilisateur clique sur retouner a la boutique
		'cancel_return' => 'html://www.domaine.com/anul.php',//Adresse de retour pour les annulations
		'no_note' => '1',//Empeche l'utilisateur de rajouter des commentaires a son paiement.
		'notify_url' => 'http://www.manit4c.com/global/panier/ipn.php'//Url appelee par paypal lors du paiement, cette page permettra le traitement des commandes payees.
	);
	//on enregistre le formulaire crypte dans une variable
	$encrypted = paypal_encrypt($form);
 
//Cette fonction encrypte le formulaire il n'est pas necessaire de comprendre son fonctionnement
function paypal_encrypt($hash){
 
	global $MY_KEY_FILE;
	global $MY_CERT_FILE;
	global $PAYPAL_CERT_FILE;
	global $OPENSSL;
 
	if (!file_exists($MY_KEY_FILE)) {
		echo "ERROR: MY_KEY_FILE $MY_KEY_FILE not found\n";
	}
	if (!file_exists($MY_CERT_FILE)) {
		echo "ERROR: MY_CERT_FILE $MY_CERT_FILE not found\n";
	}
	if (!file_exists($PAYPAL_CERT_FILE)) {
		echo "ERROR: PAYPAL_CERT_FILE $PAYPAL_CERT_FILE not found\n";
	}
	if (!file_exists($OPENSSL)) {
		echo "ERROR: OPENSSL $OPENSSL not found\n";
	}
 
	//Assign Build Notation for PayPal Support
	$hash['bn']= 'StellarWebSolutions.PHP_EWP';
 
	$openssl_cmd = "$OPENSSL smime -sign -signer $MY_CERT_FILE -inkey $MY_KEY_FILE " .
                "-outform der -nodetach -binary | $OPENSSL smime -encrypt " .
                "-des3 -binary -outform pem $PAYPAL_CERT_FILE";
 
	$descriptors = array(
        	0 => array("pipe", "r"),
		1 => array("pipe", "w"),
	);
 
	$process = proc_open($openssl_cmd, $descriptors, $pipes);
 
	if (is_resource($process)) {
		foreach ($hash as $key => $value) {
			if ($value != "") {
				//echo "Adding to blob: $key=$value\n<br />";
				fwrite($pipes[0], "$key=$value\n");
			}
		}
		fflush($pipes[0]);
        	fclose($pipes[0]);
 
		$output = "";
		while (!feof($pipes[1])) {
			$output .= fgets($pipes[1]);
		}
		//echo "outpout=".$output;
		fclose($pipes[1]); 
		$return_value = proc_close($process);
		return $output;
	}
	return "ERROR";
};
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
  <title>Paiement Paypal</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="Content-Language" content="fr" />
</head>
<body>
<!-- l'attribut action du formulaire doit rediriger vers www.sandbox.paypal.com pour la phase de test et vers www.paypal.com pour le réel -->
<h1>Récapilulatif de la commande</h1>
<p>
    <h2>Projet soutenu : <?php echo $_POST['titre_projet']?></h2>
    <h2>Montant à payer: <?php echo $_POST['montant']?>€</h2>
</p>
<form target="paypal" action="https://www.sandbox.paypal.com/fr/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<!-- on affiche le formulaire crypté -->
<input type="hidden" name="encrypted" value="
<?php echo $encrypted; ?>">
<!-- Indique la source de l'image du bouton payer maintenant -->
<input type="image" src="http://images.paypal.com/images/x-click-but01.gif"
name="submit" alt="Effectuez vos paiements via PayPal : une solution rapide, gratuite
et sécurisée">
</form>
</body>
</html>