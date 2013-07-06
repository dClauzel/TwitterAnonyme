<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Twitter anonymement</title>
	<meta name="author" content="Parti Pirate Lyon">
	<link href='http://fonts.googleapis.com/css?family=Bitter&amp;subset=latin-ext' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro&amp;subset=latin-ext' rel='stylesheet' type='text/css'>
	<link rel=stylesheet type=text/css href=TwitterAnonyme.css>
	<script>
		localStorage.removeItem('message');
	</script>
</head>
<body>

<?php
// repris et adapté depuis http://www.wescutshall.com/2011/09/how-to-post-to-twitter-from-php/

include("./config.php");
include("./twitteroauth.php");

// aucun contrôle, aucune sécurité : c'est un squelette d'outil et pas quelque chose de prêt à l'emploi :)

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET);

$content = $connection->get("account/verify_credentials");

$connection->post("statuses/update", array("status" => stripslashes($_REQUEST['message'])));

echo "<p>Je viens de tweeter ça : ". stripslashes($_REQUEST['message']);

?>

<input type="button" value="Retour au formulaire" onclick="window.history.back();">

</body>
</html>	
