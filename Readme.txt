Security : 

Toute les pages sont sécurisé par différentes fonctions venant tester le rôle ainsi l'etat 
de connexion de l'utilisateur, si ça ne correspond pas à ce qu'il faut il est redirigé.


Header : 

Le header change suivant le rôle ainsi que si la personne est connectée ou pas.
Si la personne est admin 2 boutons apparaissent, Add Product et Panel
Si la personne est connectée le bouton login est remplacé par le bouton logout.

Connexion/Inscription :

Dans le header si vous n'êtes pas connecté il y a un bouton login sinon c'est un bouton logout.
Le formulaire de login se compose de 2 parties, SignIn et SignOut, il suffit de cliquer sur SignIn ou SignOut
pour passer de l'un à l'autre.
Tous les champs des formulaires sont en required.
Dans le SignOut on a bien les vérifications nécessaires sur les différents champs que ce soit l'username
ou l'e-mail ou encore le mot de passe avec les recommandations de la CNIL

Accueil : 

L'accueil est la plage principal du site, quand on arrive sur la page il y a différentes catégories,
l'affichage des catégories est fait en conséquences des catégories en BDD, si il y a une nouvelle catégorie
qui est mise en base elle sera automatiquement affichée. 
L'affichage général de cette page est géré avec du JS pour pouvoir faire en sorte que si il y a un clique
sur l'une des catégories, tout l'affichage des catégories disparait pour laisser place aux produits correspondant
sans avoir à refresh la page.
Les produits sont aussi affiché en conséquences de la base et de la catégorie sélectionnée.

Ajouter des produits au panier :

L'ajout au panier est fait en php et redirige automatiquement vers le panier

Le panier : 

Le panier est juste une page où les différents produits ajouté au panier par un utilisateur (1 panier -> 1 utilisateur -> n produit)
Il y a aussi un calcul du prix total des articles
Il y a aussi un bouton permettant la suppression du panier renvoyant vers la même page mais avec l'id du panier correspondant en GET.

Add Product :

La page Add product accessible seulement par un utilisateur ayant le rôle admin.
Il y a juste un formulaire avec 2 champs, un champ produit et un champ catégorie.
L'admin rentre un style de produit qu'il veut ajouter à son site du style " Montres Rolex " et l'api va chercher et récupérer 10 produits
et ils sont ensuite ajoutés en BDD
L'Admin choisit aussi une catégorie dans l'exemple là ce serait " Montre " mais il ne choisi pas vraiment, ce n'est pas un select un input 
Au moment de la récupération je vérifie si la catégorie existe déjà, si c'est le cas j'ajoute direct les produits à cette catégorie existante 
sinon je crée la catégorie et y ajoute les produits et donc la catégorie s'affichera instantanément dans l'accueil.

Panel : 

La page panel accessible seulement par un utilisateur ayant le rôle admin contient un tableau avec tous les produits du site.
Il peut les modifier ou les supprimer.

Améliorations possibles : 

Une meilleure gestion des catégories.
Un meilleur panel admin (plus complet).
Le front.
Un autoloader ainsi qu'un router
Refaire la structure pour faire un vrai MVC.


++++++
Je n'ai pas fait de .env car utilisant SQLite la connexion à ma base c'est seulement l'appel à un fichier donc je n'ai pas vraiment de variables
d'environnements ou autre.
