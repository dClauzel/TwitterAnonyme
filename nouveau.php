<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Twitter anonymement</title>
	<meta name="author" content="Parti Pirate Lyon">
	<link href='http://fonts.googleapis.com/css?family=Bitter&amp;subset=latin-ext' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro&amp;subset=latin-ext' rel='stylesheet' type='text/css'>
	<link rel=stylesheet type=text/css href=TwitterAnonyme.css>

	<script src="jquery.js"></script>

	<script>
		function reply(name){
			text = document.getElementById('message').value;
			text = text + '@'+name+" ";
			document.getElementById('message').value=text;
			return false;
		}
		
	<!-- repris et adapté depuis http://41mag.fr/tutoriel-un-compteur-de-mots-et-de-caracteres-comme-twitter-sur-un-champ-de-formulaire-textarea-en-jquery.html -->
		$(document).ready(function(e) {
		
			$('#message').keyup(function() {
		  
			var nombreCaractere = $(this).val().length;
			nombreCaractereRestant = 140 - nombreCaractere;
		    
			var nombreMots = jQuery.trim($(this).val()).split(' ').length;
			if($(this).val() === '') {
				nombreMots = 0;
			}
		    
			var msg = ' ' + nombreMots + ' mot(s) | ' + nombreCaractereRestant + ' caractère(s) restant';
			$('#compteur').text(msg);
		
			if (nombreCaractereRestant < 0) {
				$('#compteur').addClass("mauvais");
			} else {
				$('#compteur').removeClass("mauvais");
			}
		
		})  
		  
		});
	</script>

	<script>
	<!-- repris et adapté depuis http://www.netmagazine.com/tutorials/make-disaster-proof-html5-forms -->
		function savedata(){
			var message_en_cours = document.getElementById('message').value;
			localStorage.setItem('message', message_en_cours);
		}
	
		function retrievedata(){
			var message_recupere = localStorage.getItem('message');
			try {
				$('#message').val(message_recupere);
			} catch (err) {
				console.log("ERREUR : impossible de remplir la textarea avec : " + message_recupere);
			}
		}
	
		retrievedata();
		var myInterval = setInterval(savedata, 1000); // saves data locally every 1 second
	</script>

</head>
<body>

<h1>Tweeter anonymement&nbsp;?</h1>
<p>Pourquoi le <a href="http://partiPirate-Lyon.fr">Parti Pirate Lyon</a> vous propose un moyen de tweeter anonymement ?
<ol>
	<li>car nous aimons la liberté d'expression — simple, brute de décoffrage, avec tout ce qu'elle comporte ;
	<li>car cela nous amuse ;
	<li>car nous sommes curieux de voir ce que <em>vous</em> en ferez.
</ol>

<p>Voir aussi le <a href="/post/2012/08/04/Tweeter-anonymement">billet sur le blog</a> pour discuter.

<hr>

<h1>Formulaire pour envoyer un tweet via le compte <a href="https://twitter.com/AnonymePPL" target=_blank>@AnonymePPL</a></h1>

<p>Remarque : les URL seront automatiquement raccourcies par Twitter ; vous avez donc le droit de dépasser la taille maximale. Mais juste parce que c'est vous, hein ! ;)
<form action="Envoi-vers-Twitter.php" method=post>
	<textarea cols=66 rows=2 id=message name=message placeholder="c'est tweetable" required autofocus></textarea>
	<p id="compteur">0 mots | 140 caractères restant</p>
	<p><input type=submit value="Twitter ça">
</form>

<h1>Interactions avec <a href="https://twitter.com/AnonymePPL" target=_blank>@AnonymePPL</a></h1>

<h2>Mentions</h2>

<p>Les 30 dernières mentions de <a href="https://twitter.com/AnonymePPL" target=_blank>@AnonymePPL</a>&nbsp;:
<ul>
<?php

setlocale(LC_ALL, "fr_FR");

include("./config.php");
include("./twitteroauth.php");

// aucun contrôle, aucune sécurité : c'est un squelette d'outil et pas quelque chose de prêt à l'emploi :)

// authentification une fois pour toute sur Twitter
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET);
$content = $connection->get("account/verify_credentials");

$mentions = $connection->get("statuses/mentions", array("count"=>30));

/*
	FIXME Twitter utilise des nombres trop grand pour tenir dans les int de PHP, qui les traite comme des floats.
	Une bonne solution serait de passer par la lib GMP http://php.net/manual/en/book.gmp.php — pas forcément disponible
	Donc on improvise à la main ; problème similaire pour les messages privés plus bas.
	Mais attention, il y a une limite : http://stackoverflow.com/questions/9621792/convert-a-big-integer-to-a-full-string-in-php
*/

foreach($mentions as $status) {
	echo "<li><small><a href=https://Twitter.com/". $status->user->screen_name ."/status/". sprintf('%0.0f',$status->id) .">". utf8_encode(strftime("%A %d %B %Y %T %z ",strtotime($status->created_at)))
		."</a> par </small> <a href=https://Twitter.com/". $status->user->screen_name. ">". $status->user->name ."</a>&nbsp;: "
		. $status->text
		."&nbsp;<a href='#' title='répondre' onclick='reply(".'"'.$status->user->screen_name.'"'.");'>↩</a>\n";
}

?>
</ul>

<h2>Messages privés</h2>
<p><a href="https://twitter.com/AnonymePPL" target=_blank>@AnonymePPL</a> suit automatiquement les gens qui le mentionnent, afin qu'ils puissent lui envoyer des messages privés. Ce qui peut sembler curieux, puisque ces messages sont affichés ici. Voici les 30 derniers messages privés adressés à <a href="https://twitter.com/AnonymePPL" target=_blank>@AnonymePPL</a>&nbsp;:

<ul>
<?php

// récupération des 5 dernières mentions d'AnonymePPL, et abonnement aux auteurs
/*
FIXME si le compte reçoit plus de 5 mentions avant entre 2 chargements de la page, seules les 5 plus récentes seront prises en compte (les autres seront manquées). La raison est que l'envoi des requêtes d'abonnement est long, donc en attendre 5 est acceptable mais plus non.
*/
$mentions = $connection->get("statuses/mentions", array("count"=>5));
foreach($mentions as $status) {
	$connection->post("friendships/create", array("screen_name" => $status->user->screen_name));
}

// récupération des 30 derniers MP, pour affichage
$mentions = $connection->get("direct_messages", array("count"=>30));
foreach($mentions as $status) {
	echo "<li><small><a href=https://Twitter.com/". $status->user->screen_name ."/status/". sprintf('%0.0f',$status->id) .">". utf8_encode(strftime("%A %d %B %Y %T %z ",strtotime($status->created_at)))
		."</a> de </small> <a href=https://Twitter.com/". $status->sender_screen_name. ">". $status->sender->name ."</a>: "
		. $status->text
		."&nbsp;<a href='#' title='répondre' onclick='reply(".'"'.$status->sender_screen_name.'"'.");'>↩</a>\n";
}
?>
</ul>

<hr>

<h1>Sources</h1>
<p>Il s'agit de la version de développement. Elle comprend plus de fonctionnalités, mais peut cesser de fonctionner sans prévenir.
<ul>
	<li><a href="README.md">README.md</a> : la documentation développeur de l'outil
	<li><a href="nouveau.php">nouveau.php</a> : cette page (duh !), qui contient le formulaire de saisie
	<li><a href="TwitterAnonyme.css">TwitterAnonyme.css</a> : mise en forme des pages web
	<li><a href="config.php">config.php</a> : paramètres d'authentification sur Twitter
	<li><a href="Envoi-vers-Twitter.php">Envoi-vers-Titter.php</a> : traitement du formulaire saisi
	<li><a href="OAuth.php">OAuth.php</a> : gestion du protocole d'authentification OAuth
	<li><a href="twitteroauth.php">twitteroauth.php</a> : utilisation de OAuth pour Twitter
	<li><a href="jquery.js">jquery.js</a> : jquery, utilisé pour l'aide à la saisie du message à tweeter
</ul>
<p>Le tout proposé par le <a href="http://PartiPirate-Lyon.fr" target=_blank>Parti Pirate Lyon</a>, sous licence « <a href="http://babgond.com/dotclear/post/2005/02/01/111-nouvelle-licence-public" target=_blank>Fais pas chier</a> », grâce à des bouts de code venant de différents endroits du net — lisez les sources.

</body>
</html>	
