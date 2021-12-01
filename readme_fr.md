![image capteur](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/img/logo_connexoon.png "Paramétrage capteur")

# Plugin Somfy V3.1.0
Bridge entre la box eedomus et le cloud SOMFY via les boxes Tahoma et Connexoon

# Sommaire

1. Introduction

2. Première utilisation

3. Création d'un périphérique

   3.1 Equipement Somfy reconnu
   
   3.2 Equipement Somfy non reconnu
   
   3.3 Pilotage de plusieurs équipements Somfy avec un seul périphérique eedomus
  
4. Migration

5. Outils pratiques

6. Déblocage du plugin

7. Historique des versions

# 1. Introduction

Ce plugin permet de contrôler certains équipements SOMFY. Il est nécessaire pour cela de posséder un bridge Connexoon ou une box Tahoma, et d'avoir associé ses équipements au bridge via l'application mobile Connexoon ou Tahoma de SOMFY.

**Attention** : l'installation du périphérique s'effectue uniquement en étant connecté sur le même réseau que votre box eedomus !

La version 3 du plugin utilise un capteur d'état qu'il est nécessaire d'installer pour bénéficier de toutes les fonctionnalités.

La version 3 est normalement compatible avec les versions 1 et 2. Toutefois, afin d'éviter tout problème en production et de permettre une migration "en douceur", le nom du script a été modifié. Suivez attentivement les étapes du chapitre 4.

**qui est TeamListeSomfy ?**

- @Pat : créateur du script initial v1
- @herric : script v2, v3, graphismes et tests
- @dommarion : nouveaux équipements, json v3, graphismes et tests
- @dom54 et @sev : nouveaux équipements et tests

Dans ce document on nomme :

**Périphérique** pour eedomus

**Equipement Somfy** pour Somfy

**Master Data** pour le capteur d'état du cloud Somfy

# 2. Première utilisation

**Important** : Lors de l'installation d'un périphérique, vous devrez renseigner l'adresse de l'équipement correspondant. Pour cela, cliquez sur le lien et renseignez vos identifiants SOMFY.

La liste des équipements Somfy connectés à votre box SOMFY est affichée, veillez à bien conserver cette liste (copier/coller dans un fichier et sauvegarde pour utilisation ultérieure).

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/lien.jpg)

Avant de créer vos périphériques, installez le Master Data (à n'installer qu'une seule fois) :

**Adresse du Master data** : C'est le PIN de votre box Somfy (situé sur l'étiquette sous la box) que vous retrouvez également dans la liste des gateways :

![image capteur](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/Master-Data.jpg)


Ce Master Data a pour fonction :

- d'indiquer l'état de la connexion avec le cloud SOMFY et les box Connexoon/Tahoma
- d'assurer le retour d'état des commandes envoyées par eedomus vers SOMFY
- de mettre à jour les périphériques eedomus suite à une action directe IO (RTS ne supporte pas le retour d'état)
- de bloquer/débloquer les requêtes au cloud SOMFY pour éviter les blacklistages

A la première utilisation, le Master Data peut mettre plusieurs minutes à s'initialiser. Puis, il se met à jour toutes les minutes (fréquence dans le polling = 1).

### Valeurs

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/valeurs_capteur.jpg)


# 3. Création d'un périphérique

## 3.1 Les équipements Somfy reconnus sont listés dans le chapitre *B*.

Un équipement reconnu est « codé » dans le plugin, le périphérique a été prédéfini avec ses commandes, son retour d’état, la requête http, son polling et tous les graphismes correspondants.

Le système n’installe qu’un seul périphérique par type d’équipement. Si vous disposez de plusieurs équipements de ce type alors 2 options sont possibles :

-	soit relancer autant de fois que nécessaire le Plugin (assez fastidieux et source d’erreur car il faut cocher le bon type d’équipement)
-	soit dupliquer le périphérique dans la box eedomus et changer l’adresse avec celle de l’équipement à créer dans [VAR1]

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/liste.jpg)

Il suffit de recopier l'adresse dans l'écran de paramétrage comme indiqué ci-dessous.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/parametre-affichage.jpg)

**Important** afin d'initialiser le retour d'état (sinon il n'y en aura pas ! ):

- Pour les actionneurs : envoyer une première commande (par exemple "ouvrir").
- Pour les capteurs : l'init se lance toutes les 10 heures (polling à 600 minutes), mais vous pouvez le forcer : (paramètres experts, tester le chemin XPATH).

## 3.2 Les équipements Somfy non reconnus sont listés dans le chapitre *C*.

Cela vous permettra (avec un peu d'entraînement) de créer et paramétrer votre périphérique eedomus pour envoyer la bonne commande à SOMFY et récupérer les bons états.

**Attention** : certaines commandes ont des paramètres associés à l’action. Malheureusement le cloud Somfy et donc le listing ne donnent pas les valeurs des paramètres, il faut les « deviner », souvent en s’aidant des valeurs d’état.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/liste_non_reconnu.jpg)

**Type** : Sélectionnez "Périphérique [IO/RTS] non reconnu" en fonction du type affiché dans la liste.

**Liste des états disponibles** : indique les variables d’état que peut prendre votre équipement Somfy, chaque variable ayant une liste de valeurs, ou une valeur booléenne, numérique.

**Liste des commandes disponibles** : comme son nom l'indique, ce sont toutes les commandes acceptées par votre équipement Somfy, avec le nombre de paramètres à fournir. L'API Somfy ne fournit pas le détail des valeurs des paramètres. Il va falloir faire preuve d'imagination, de bon sens, et procéder par essai/erreur pour trouver les bonnes valeurs des paramètres.

### Exemple : 

**1. A la création du périphérique, renseignez les champs en fonction des informations de la liste**

Choisissez l'état que vous souhaitez utiliser pour votre retour d'état eedomus et collez-le dans le champ Etat.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/parametre-non-reconnu.jpg)

Cliquez sur créer

**2. Dans l'onglet "Valeurs" de la configuration de votre périphérique, ajustez les commandes**

Renseignez les commandes en tenant compte du nombre de paramètres comme illustré ci-dessous.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/commandes-non-reconnu.jpg)

**Attention** : observez bien l'illustration ci-dessus !

- pour les commandes avec 0 paramètre, il ne faut surtout pas ajouter &value en fin de ligne
- pensez à faire correspondre les valeurs brutes avec les états que vous souhaitez avoir en retour

## 3.3 Pilotage de plusieurs équipements Somfy avec un seul périphérique

Les périphériques "multi" permettent de commander plusieurs équipements Somfy en n'envoyant qu'une seule commande à la box Somfy.

Vous pouvez

- soit dupliquer un périphérique existant et modifier son paramétrage
- soit créer un périphérique multi à partir du store

Le paramétrage est très simple :

**Adresse de l'équipement Somfy** : listez les adresses des équipements en les séparant avec des virgules.

**Requête de mise à jour ** : http://localhost/script/?exec=liste_somfy.php&action=auto

**Chemin XPATH** : /connexoon/resultat

**Fréquence de la requête** : 1

**Attention**

- ne pilotez en mode multi que des équipements Somfy strictement identiques (même fonction, même commande, mêmes paramètres).
- la liste d'adresses séparée par des virgules ne doit contenir aucun espace, y compris en fin de liste.
- il n'y a pas de retour d'état en mode multi, le périphérique revient automatiquement en état "auto" au bout d'une minute grâce à la requête de mise à jour.
- si vous avez dupliqué un périphérique existant, pensez à ajouter une valeur auto :
     - **valeur** brute : unknow (désolé pour la coquille, compatibilité avec la V1 oblige ...)
	 - **icone** : ce que vous voulez
	 - **description** : auto
	 - **URL** : laisser par défaut
	 - **Paramètres** : vide
- si vous avez dupliqué un périphérique existant qui fonctionnait avec la V1, et si vous avez plus de 5 ou 6 équipements Somfy dans [VAR1], [VAR1] doit passer du côté paramètres car il existe une limitation eedomus dans la taille du champ URL (URL = 250 caractères max / Paramètres = 1024 caractères max)

# 4. Migration

## 4.1. A partir de la version 3

Simplement supprimer puis recréez le Master Data.

## 4.2. Depuis les versions précédentes 1.x.x et 2.x.x

La migration en version 3 ne change rien au fonctionnement de base de vos équipements Somfy, mais elle améliore la communication avec le cloud Somfy gère les gateway, ouvre à une liste plus importante d’équipements, gère les piles couplées aux capteurs, résout des dysfonctionnements, permet un meilleur suivi du fonctionnement de l’application (compteur de devices et autres fonctions internes) et réduit la consommation du CPU.

Le nom du script (connexoon.php) a été changé (liste_somfy.php) afin de ne pas perturber les installations existantes.

Cependant, afin de bénéficier des nouvelles fonctionnalités il faut suivre pas à pas les étapes suivantes.

### 4.2.1. Installer le Master Data (voir paragraphe 2)

**- depuis la v1** : installer le Master Data à partir du store

**- depuis la v2** : Supprimer le Capteur SOMFY et installer le Master Data à partir du store

### 4.2.2. Pour chacun de vos périphériques IO, ajouter 2 états

Ajouter les valeurs suivantes : 

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/nouveaux-etats.jpg)

### 4.2.3. Pour chacun de vos périphériques, modifiez la configuration

#### Variables utilisatur

##### - depuis la v1 :

A l'aide du chemin XPATH initial et de l'outil de migration : http://@IP_votre_eedomus/script/?exec=liste_somfy.php&action=migration , renseignez [VAR2] avec l'état approprié

**exemple** : [VAR2] = core:ClosureState

Vous pouvez aussi recréer un périphérique pour prendre exemple.

##### - depuis la v2 :

Normalement, [VAR2] est déjà bien paramétrée

**Important** : Le retour d'état ne fonctionnera pas si [VAR2] n'est pas renseignée correctement.

#### Paramètres avancés

- **Requête de mise à jour ** : http://localhost/script/?exec=liste_somfy.php&devices=[VAR1]&etat=[VAR2]&action=init
- **Chemin XPATH** : /somfy/state
- **Fréquence de la requête** : 600

#### Onglet valeurs

Modifier les valeurs des commandes comme suit : 

- **URL** : http://localhost/script/?exec=liste_somfy.php
- **Paramètres** : &devices=[VAR1]&etat=[VAR2]&action=[votre action]&value=[votre paramètre]

**Information** : [VAR1] passe du côté paramètres car il existe une limitation eedomus dans la taille du champ URL (URL = 250 caractères max / Paramètres = 1024 caractères max)

# 5. Outils pratiques (pour les utilisateurs avancés)

Ces outils réservés aux utilisateurs avancés peuvent vous aider paramétrer les périphériques non reconnus. 

## Vérification des commandes sur la box Tahoma

Il est possible de visualiser les commandes passées de la box eedomus vers la box Tahoma, il suffit de se connecter à sa Tahoma, puis aller dans "Supervision/Tableau de bord", choisir l'onglet "Tableau de Bord" et choisir "HISTORIQUE". Les commandes passées aux équipements SOMFY apparaissent avec l'heure de commande, en cliquant sur le + on peut voir le détail de la commande.

## Mise en pause du plugin

En cas de manipulations sur le plugin et afin d'éviter d'être balcklisté par Somfy, il peut être nécessaire de mettre en pause le plugin.
La pause  stoppe toute requête vers le cloud Somfy.

1. cliquez sur "Pause" sur votre Master Data
2. Pour revenir à un fonctionnement normal, cliquez sur "Reset vérouillage / pause" sur votre Master Data

## Réinitialisation du plugin

Dans certains cas (si vous avez trop testé, copié, dupliqué ... bref bidouillé) , il peut-être nécessaire de réinitialiser le PLugin. Cela effacera les informations de vos périphériques.

1. cliquez sur "Reset vérouillage / pause" sur votre Master Data
2. Ensuite deux options sont possibles :

- laisser le système initialiser les périphériques selon le polling (attente jusqu’à 600 minutes soit 10 heures).
- initialiser manuellement tous les périphériques avec retour d'état en testant le XPATH. Le retour d’état doit s’afficher dans résultat XPath.

Tous les devices doivent être initialisés, y compris les piles et autres devices liés.

## Display

Affiche la liste des périphériques avec retour d'état initialisés dans le plugin (dont le Master Data).

Passer la commande : http://@IP_votre_eedomus/script/?exec=liste_somfy.php&action=display

## Track

Permet de compter combien de périphériques sont initialisés (pratique si vous laissez faire le polling)

Le périphérique "Compteur devices" est créé en même temps que le Master Data. 

# 6. Déblocage du plugin

Le Master Data à la valeur 4 (SOMFY login vérouillé) signifique que le plugin s'est mis en sécurité suite à 3 problèmes de login successifs afin d'éviter d'être blacklisté par Somfy.

Les causes connues sont :

## Changement de mot de passe SOMFY

actions :

1. vérifiez vos identifiants
2. appliquez la correction côté eedomus
3. cliquez sur "Reset vérouillage / pause" sur votre Master Data

## Maintenance ou mise à jour Majeure de la box Tahoma

Ce cas génère un message lors de votre connexion directe à comfy-connect.com, ce qui bloque le login depuis eedomus

actions : 

1. assurez-vous que la maintenance est terminée avec succès
2. cliquez sur "Reset vérouillage / pause" sur votre Master Data

## Cas inconnu

Utilisez l'outil Display (voir chapitre 5) pour afficher la valeur **Affichage erreurs login** qui contient la dernière réponse du cloud SOMFY afin d'essayer de comprendre le problème.

Poster si besoin cette valeur dans le forum afin d'en informer la communauté.

Lorsque le problème est identifié et corrigé, cliquez sur Reset vérouillage sur votre Master Data.

# 7. Historique des versions

### V3.1.0 du 13/09/2021

**Améliorations et nouvelles fonctionnalités :**

- Sécurisation des logins pour éviter le blacklistage de Somfy :
correction bug
ajout action Unlock dans Master Data
- Ajout action Resst ghlobal dans Master Data

### V3.0.0 du 10/07/2021

**Améliorations et nouvelles fonctionnalités :**

- ajout de nombreux équipements SOMFY (lien entre script et Json, et création Json)
- gestion des piles des équipements
- optimisation du retour d'état : limitation des appels à sdk_get_setup() pour privilégier sdk_getDeviceStatus()
- sécurisation des logins pour éviter le blacklistage de Somfy
- gestion des gateways
- amélioration de l'écran de création de périphérique
- amélioration du suivi du script avec des fonctions internes (display, compteur)
- optimisation de la charge CPU

**Corrections :**

- suppression de l'état "connexion" pour les capteurs (pour éviter de l'avoir dans l'historique)
- prise en compte des caractères spéciaux dans les adresses d'équipement (urlencode)


### V2.0.1 du 05/04/2021

- ajout io:MicroModuleRollerShutterSomfyIOComponent => volet IO
- correction code usage sur le capteur

### v2.0.0 du 26/03/2021

- modification du login pour éviter les déconnexions
- prise en compte des événements somfy pour gérer le retour d'état
- ajout prise on/off io et fil pilote io

### v1.2.2 du 27/06/2020

- ajout Brises soleil orientables : io:ExteriorVenetianBlindIOComponent' => 'BSO IO