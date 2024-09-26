Security :

Toutes les pages sont sécurisées par différentes fonctions testant le rôle ainsi que l'état de connexion de l'utilisateur. 
Si cela ne correspond pas aux attentes, l'utilisateur est redirigé.

Header :

Le header change en fonction du rôle de l'utilisateur et de son état de connexion. 
Si la personne est admin, deux boutons apparaissent : "Add Product" et "Panel". 
Si la personne est connectée, le bouton "login" est remplacé par le bouton "logout".

Connexion/Inscription :

Dans le header, si vous n'êtes pas connecté, un bouton "login" est affiché, sinon c'est un bouton "logout". 
Le formulaire de connexion est composé de deux parties : "SignIn" et "SignUp". 
Il suffit de cliquer sur l'un ou l'autre pour basculer entre eux. Tous les champs des formulaires sont requis. 
Dans la partie "SignUp", des vérifications sont effectuées sur les différents champs (nom d'utilisateur, e-mail, mot de passe) 
en respectant les recommandations de la CNIL.

Accueil :

La page d'accueil est la page principale du site. Lorsqu'on y accède, différentes catégories s'affichent, en fonction de celles 
présentes dans la base de données. 
Si une nouvelle catégorie est ajoutée à la base, elle sera automatiquement affichée. 
L'affichage général de cette page est géré par du JavaScript, permettant de masquer l'affichage des catégories lorsqu'une 
d'elles est sélectionnée, pour afficher les produits correspondants sans recharger la page. 
Les produits sont également affichés selon la base de données et la catégorie sélectionnée.

Ajouter des produits au panier :

L'ajout de produits au panier est géré en PHP, et l'utilisateur est automatiquement redirigé vers la page du panier.

Le panier :

Le panier est une page regroupant les différents produits ajoutés par un utilisateur (1 panier -> 1 utilisateur -> n produits). 
Un calcul du prix total des articles est effectué.
 Un bouton permet également de supprimer le panier, en renvoyant vers la même page, mais avec l'ID du panier correspondant en paramètre GET.

Ajouter un produit :

La page "Add Product" est accessible uniquement par un utilisateur ayant le rôle d'admin. 
Elle contient un simple formulaire avec deux champs : "produit" et "catégorie". 
L'admin saisit un type de produit qu'il souhaite ajouter au site, par exemple "Montres Rolex". 
L'API récupère alors 10 produits qui sont ensuite ajoutés à la base de données. 
L'admin choisit également une catégorie (par exemple "Montre"), mais il ne s'agit pas vraiment d'un champ de sélection, c'est un champ de saisie. 
Lors de la récupération, je vérifie si la catégorie existe déjà. Si c'est le cas, j'ajoute directement les produits à cette catégorie existante ; 
sinon, je crée la catégorie et y ajoute les produits, qui seront instantanément affichés sur la page d'accueil.

Panel :

La page "Panel", accessible uniquement aux administrateurs, contient un tableau regroupant tous les produits du site. 
L'admin peut les modifier ou les supprimer.

Améliorations possibles :

Une meilleure gestion des catégories.
Un panneau d'administration plus complet.
Amélioration du front-end.
Implémentation d'un autoloader et d'un router.
Refonte de la structure pour mettre en place une vraie architecture MVC.

++++++

Je n'ai pas créé de fichier .env car, en utilisant SQLite, la connexion à ma base de données se fait simplement via l'appel à un fichier. 
Je n'ai donc pas vraiment de variables d'environnement ou autres.