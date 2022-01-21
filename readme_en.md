![image capteur](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/img/logo_connexoon.png "Paramétrage capteur")

# Plugin Somfy V3.1.0
Bridge between eedomus box and SOMFY cloud via the Tahoma and Connexoon boxes

# Sommaire

1. Introduction

2. First use

3. Device creation

   3.1 Known Somfy device
   
   3.2 Unknown Somfy device
   
   3.3 Control of several Somfy devices with a single eedomus device
  
4. Migration

5. Tools

6. Unblocking the plugin

7. Version history

# 1. Introduction

This plugin is used to control some SOMFY equipments. To do so, you need to have a Connexoon bridge or a Tahoma box, and have included your devices via the SOMFY Connexoon or Tahoma mobile application.

**Caution** : the plugin can only be installed by being connected to the same network as your eedomus box!

Version 3 of the plugin uses a state sensor (the Master Data) which must be installed to get full functionalities.

Version 3 is normally compatible with versions 1 and 2. However, in order to avoid any problems in production and to allow a "smooth" migration, the name of the script has been changed. Carefully follow the steps in chapter 4.

**who is TeamListeSomfy ?**

- @Pat: creator of the initial script v1
- @herric: script v2, v3, graphics and tests
- @dommarion: new equipment, json v3, graphics and tests
- @ dom54 and @sev: new equipment and tests

In this document we name:

**Device** for eedomus

**Somfy equipment** for Somfy

**Master Data** for the Somfy cloud status sensor

# 2. First use

**Important** : When installing a Device, you will need to enter the address of the corresponding equipment. To do this, click on the link and enter your SOMFY identifiers.

The list of Somfy equipment connected to your SOMFY box is displayed, be sure to keep this list (copy / paste into a file and save for later use).

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/lien.jpg)

Before creating your devices, install the Master Data (to be installed only once):

**Adresse du Master data** : This is the PIN of your Somfy box (located on the label under the box) that you also find in the gateways list:

![image capteur](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/Master-Data.jpg)


The Master Data has the following functions:

- indicates the state of the connection between the SOMFY cloud and the Connexoon / Tahoma boxes
- get the feedback status of orders sent by eedomus to SOMFY
- updates eedomus devices following a direct IO action (RTS does not support feedback status)
- block / unblock requests to the SOMFY cloud to avoid blacklists

On first use, the master data may take several minutes to initialize. Then, it updates every minute.

**Important** : The polling frequency must absolutely remain set to 1 minute due to the somfy API and the plugin internal logic (otherwise, at each polling, the SOMFY cloud will force a re-login and the script will proceed to a full devices setup).

## 2.1 Values

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/valeurs_capteur.jpg)

## 2.2 Commands

![image Master Data](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/Master-Data-2.jpg)

- Pause: set the master data on pause (see chapter 5)
- Reset verrouillage/pause: reset values and return to normal operation (see chapters 5 and 6)
- Reset global: general reset of all devices (see chapter 5)

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

**Caution** : certaines commandes ont des paramètres associés à l’action. Malheureusement le cloud Somfy et donc le listing ne donnent pas les valeurs des paramètres, il faut les « deviner », souvent en s’aidant des valeurs d’état.

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

**Caution** : observez bien l'illustration ci-dessus !

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

**Caution**

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

## 3.4 Les scenarios sont listés dans le chapitre *D*.

![image scenarios](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/liste-scenarios.jpg)

Il suffit de recopier l'adresse dans l'écran de paramétrage comme indiqué ci-dessous.

![image scenarios](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/parametre-affichage-scenario.jpg)

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

En cas de manipulations sur le plugin et afin d'éviter d'être blacklisté par Somfy, il peut être nécessaire de mettre en pause le plugin.
La pause  stoppe toute requête vers le cloud Somfy.

1. cliquez sur "Pause" sur votre Master Data
2. Pour revenir à un fonctionnement normal, cliquez sur "Reset verrouillage / pause" sur votre Master Data

## Réinitialisation du plugin

Dans certains cas (si vous avez trop testé, copié, dupliqué ... bref bidouillé) , il peut-être nécessaire de réinitialiser le PLugin. Cela effacera les informations de vos périphériques.

1. cliquez sur "Reset verrouillage / pause" sur votre Master Data
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
3. cliquez sur "Reset verrouillage / pause" sur votre Master Data

## Maintenance ou mise à jour Majeure de la box Tahoma

Ce cas génère un message lors de votre connexion directe à somfy-connect.com, ce qui bloque le login depuis eedomus

actions : 

1. assurez-vous que la maintenance est terminée avec succès
2. cliquez sur "Reset verrouillage / pause" sur votre Master Data

## Cas inconnu

Utilisez l'outil Display (voir chapitre 5) pour afficher la valeur **Affichage erreurs login** qui contient la dernière réponse du cloud SOMFY afin d'essayer de comprendre le problème.

Poster si besoin cette valeur dans le forum afin d'en informer la communauté.

Lorsque le problème est identifié et corrigé, cliquez sur Reset verrouillage sur votre Master Data.

# 7. Historique des versions

### V3.1.0 du 06/01/2022

**Améliorations et nouvelles fonctionnalités :**

Sécurisation des logins :
- correction bug
- ajout action « Pause » dans MasterData
- ajout action « Reset verrouillage / pause » dans Master Data
- Ajout action « Reset global » dans Master Data

Nouvelles fonctionnalités :
- ajout gestion des scenarios Tahoma

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