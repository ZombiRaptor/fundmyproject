<?php
//email pour prévenir le vendeur
$mailTo="Moi <votreemail@domaine.com>";
 
//permet de traiter le retour ipn de paypal
// lire la publication du système PayPal et ajouter 'cmd'
$req = 'cmd=_notify-validate';
 
foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}
 
// renvoyer au système PayPal pour validation
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
 
//www.sandbox.paypal.com pour la phase de test
//www.paypal.com pour la phase réel.
$fp = fsockopen ('www.sandbox.paypal.com', 80, $errno, $errstr, 30);
 
// affecter les variables publiées aux variables locales
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];
 
$headerMail= "Content-Type:text/html;charset=iso-8859-1\n";//permet d'envoyer les message au format html
$headerMail.= "Content-Transfer-Encoding: 8bit\n";//permet d'envoyer les message au format html
$headerMail.="From: me";//pour répondre au message
 
//on prépare le texte de l'email
$textMail="
	<strong>Détail de la commande</strong><br />
	Commande numéro:".$item_number."<br />
	prix: ".$payment_amount." &euro;
";
 
if (!$fp) {
// ERREUR HTTP
} 
else {
	fputs ($fp, $header . $req);
	while (!feof($fp)) {
		$res = fgets ($fp, 1024);
		if (strcmp ($res, "VERIFIED") == 0) {
                        //on envoi un email pour prévenir qu'une commande a ete passee
			mail($mailTo,"Une nouvelle commande à été réglée",$textMail,$headerMail);
 
                        // C'est ici que vous devrez traiter la commande (enregistrement bdd etc..)
			// vérifier que payment_status est Terminé
			// vérifier que txn_id n'a pas été précédemment traité
			// vérifier que receiver_email est votre adresse email PayPal principale
			// vérifier que payment_amount et payment_currency sont corrects
			// traiter le paiement			
		}
		else if (strcmp ($res, "INVALIDE") == 0) {
			// consigner pour étude manuelle
		}
	}
	fclose ($fp);
}
?>