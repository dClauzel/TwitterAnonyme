TwitterAnonyme
==============

Outil web pour tweeter anonymement via un compte banalisé

--------------

# Pourquoi le Parti Pirate Lyon vous propose un moyen de tweeter anonymement ?
1. car nous aimons la liberté d'expression — simple, brute de décoffrage, avec tout ce qu'elle comporte ;
2. car cela nous amuse ;
3. car nous sommes curieux de voir ce que <em>vous</em> en ferez.

Voir aussi le <a href="http://PartiPirate-Lyon.fr/post/2012/08/04/Tweeter-anonymement">billet sur le blog</a> pour discuter.


# Comment déployer chez soi le même outil de tweet anonyme


1. Il s'agit plus d'un squelette d'outil que d'une application web clé en main ; vous avez très certainement envie de verrouiller un peu plus le formulaire PHP de traitement des données envoyées par l'utilisateur…

2. Choisir :
	* un nom de compte Twitter (ici AnonymePPL)
	* un site web où publier l'outil (ici http://PartiPirate-Lyon.fr/AnonymePPL/)

3. créer le compte Twitter (ici AnonymePPL)
	* penser à valider l'adresse courriel de contact (ici Contact+projet-anonyme@PartiPirate-Lyon.fr)
	* définir les divers réglages du compte : ne PAS protéger les tweets, profile, etc.

4. Créer l'API du compte
	* se connecter sur https://dev.twitter.com/ avec les identifiants du compte anonyme
	* lien « Créer une application » : https://dev.twitter.com/apps/new
		* nom : le nom de l'outil que l'on crée (ici Compte public anonyme du PPL)
		* description : une description de l'outil que l'on crée (ici Compte Twitter anonyme proposé par le Parti Pirate Lyon)
		* site web : le site web de l'outil que l'on crée (ici http://PartiPirate-Lyon.fr/AnonymePPL/)

	La validation du formulaire affiche la console d'administration de l'API créée.
	
5. Paramétrer l'API du compte
	* onglet « Réglages » :
		* changer le type d'application en : lecture + écriture + accès aux messages directs
		* les informations sur l'organisation sont optionnelles :
			* nom de l'organisation : la personne/structure qui met à disposition l'outil que l'on crée (ici Parti Pirate Lyon)
			* site web de l'organisation : site web du gestionnaire de l'outil que l'on crée (ici http://PartiPirate-Lyon.fr)
		* valider
	* revenir sur l'onglet « Détails », et « créer mon jeton d'accès » via le bouton idoine

6. Récupérer les paramètres d'accès à l'API
	* les information que nous recherchons sont désormais accessible, sur l'onglet « Détails » où nous nous trouvons déjà
		* Consumer key (ici `xxx_Consummer_key_xxx`)
		* Consumer secret (ici `xxx_Consummer_secret_xxx`)
		* Access token (ici `xxx_Access_token_xxx`)
		* Access token secret (ici `xxx_Access_token_secret_xxx`)

7. Configurer l'outil que l'on crée
	* Dans le fichier `config.php`, définir :
		* CONSUMER_KEY avec `xxx_Consummer_key_xxx`
		* CONSUMER_SECRET avec `xxx_Consummer_secret_xxx`
		* OAUTH_TOKEN avec `xxx_Access_token_xxx`
		* OAUTH_SECRET avec `xxx_Access_token_secret_xxx`
	* Ajuster éventuellement les fichiers :
		* index.html : le formulaire présenté aux utilisateurs
		* twitter.php : le message affiché après l'envoi d'un message à tweeter

8. Téléverser les fichiers sur le serveur web






# Notes de développement

L'outil travaille sans mémoire, c'est à dire que lors de l'appel du formulaire de saisie il n'a aucune idée du passé. Idéalement, il faudrait travailler avec un cache locale, mais cela implique de mettre en place une couche de persistance. Et donc se trimbaler une liaison vers une base de donnée, genre MySQL ou sqlite, ce qui alourdi considérablement le déploiement.

Donc à chaque appel, il faut partir de zéro et regarder la situation avant d'agir; chose que nous ne faisons pas : on balance directement les demandes à Twitter et on le laisse se débrouiller avec les doublons.

Un inconvénient est que l'outil ne peut pas supporter une grande montée en charge : en cas d'utilisation trop forte on va heurter la limite d'appels sur l'API, et l'outil sera alors inopérant durant plusieurs minutes. Il faudra alors *vraiment* mettre en place un système de cache des données avec prétraitement dessus, avant d'aller chatouiller l'API. Tout de suite, ça fait beaucoup de travail pour un simple Projet Rigolo.




# Licence
Le tout proposé par le <a href="http://PartiPirate-Lyon.fr" target=_blank>Parti Pirate Lyon</a>, sous licence « <a href="http://babgond.com/blog/blog/2005/02/01/20050201111-nouvelle-licence-public/" target=_blank>Fais pas chier</a> », grâce à des bouts de code venant de différents endroits du net — lisez les sources.

```text
LICENCE PUBLIQUE FAIS PAS CHIER
Version 2, Fevrier 2005

Copyright (C) 2005 Beryl Gondouin

Cette licence est une traduction et adaptation à la législation
française de la licence « DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE »
de Samuel Hocevar http://sam.zoy.org/wtfpl/.

La redistribution de ce document, avec ou sans modification, est
permise à la seule condition que le nom soit changé et que personne
ne vienne emmerder l’auteur.

LICENCE PUBLIQUE FAIS PAS CHIER

0/ Vous faites absolument ce que vous voulez et vous ne faites pas chier, notamment pas l’auteur.
```
