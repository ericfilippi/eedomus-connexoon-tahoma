![image capteur](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/img/logo_connexoon.png "Paramétrage capteur")

# Périphérique Somfy V3
Bridge entre la box eedomus et le cloud SOMFY via les boxes Tahoma et Connexoon

# Sommaire

1. Introduction

2. Première utilisation

3. Création d'un prériphérique

   3.1 Equipement Somfy reconnu
   
   3.2 Equipement Somfy non reconnu
   
   3.3 Pilotage de plusieurs équipements Somfy avec un seul périphérique eedomus
   
4. Migration depuis les versions 1.x.x

5. Historique des versions

# 1. Introduction

Ce plugin permet de contrôler certains équipements SOMFY. Il est nécessaire pour cela de posséder un bridge Connexoon ou une box Tahoma, et d'avoir associé ses équipements au bridge via l'application mobile Connexoon ou Tahoma de SOMFY.

**Attention** : l'installation du périphérique s'effectue uniquement en étant connecté sur le même réseau que votre box eedomus !

La version 3 du plugin utilise un capteur d'état qu'il est nécessaire d'installer pour bénéficier de toutes les fonctionnalités.

La version 3 est normalement compatible avec les versions 1 et 2. Toutefois, afin d'éviter tout problème en production et de premettre une migration "en douceur", le nom du script a été modifié. Suivez attentivement les étapes du chapitre 4.

## qui est TeamListeSomfy

- @Pat : créateur du script initial v1
- @herric : script v2, v3 et tests
- @dommarion : nouveaux équipements, json v3 et tests
- @dom54 et @sev : nouveaux équipements et tests

Dans ce document on nomme :

**Périphérique** pour eedomus

**Equipement Somfy** pour Somfy

**Master Data** pour le capteur d'état du cloud Somfy

# 2. Première utilisation

**Important** : Lors de l'installation d'un périphérique, vous devrez renseigner l'adresse de l'équipment correspondant. Pour cela, cliquez sur le lien et renseignez vos identifiants SOMFY.

La liste des équipements Somfy connectés à votre box SOMFY est affichée.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/lien.jpg)

Avant de créer vos prériphériques, installez le Master Data (à n'installer qu'une seule fois) :

![image capteur](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/capteur.jpg)

**Adresse du Master data** : C'est le PIN de votre box Somfy (situé sur l'étiquette sous la box) que vous retrouvez également dans la liste des gateways :

![image capteur](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/liste_gateways.jpg)


Ce Master Data a pour fonction :

- d'indiquer l'état de la connexion avec le cloud SOMFY et les box Connexoon/Tahoma
- d'assurer le retour d'état des commandes envoyées par eedomus vers SOMFY
- de mettre à jour les prériphériques eedomus suite à une action directe IO (RTS ne supporte pas le retour d'état)

### Valeurs

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/valeurs_capteur.jpg)

A la première utilisation, le Master Data peut mettre plusieurs minutes à s'initialiser. Puis, il se met à jour toutes les minutes.

# 3. Création d'un périphérique

## 3.1 Les équipements Somfy reconnus sont listés dans le chapitre *B*.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/liste.jpg)

Il suffit de recopier l'adresse dans l'écran de paramétrage comme indiqué ci-dessous.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/parametre-affichage.jpg)

**Important** afin d'initialiser le retour d'état (sinon il n'y en aura pas ! ):

- Pour les actionneurs : envoyer une première commande (par exemple "ouvrir").
- Pour les capteurs : l'init se lance toutes les 10 heures, mais vous pouvez le forcer : (paraètres experts, tester le chemin XPATH

## 3.2 Les équipements Somfy non reconnus sont listés dans le chapitre *C*.

Cela vous permettra (avec un peu d'entraînement) de paramétrer votre périphérique eedomus pour envoyer la bonne commande à SOMFY et récupérer les bons états.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/liste_non_reconnu.jpg)

**Type** : Sélectionnez "Prériphérique [IO/RTS] non reconnu" en fonction du type affiché dans la liste.

**Liste des états disponibles** : indique les états que peut prendre votre équipement Somfy.

**Liste des commandes disponibles** : comme son nom l'indique, ce sont toutes les commandes acceptée par votre équipement Somfy, avec le nombre de paramètres à fournir. L'API Somfy ne fournit pas le détail des valeurs des paramètres. Il va falloir fair preuve d'immagination, de bon sens, et procéder par essai/erreur pour toruver les bons paramètres.

### Exemple : 

**1. A la création du prériphérique, renseignez les champs en fonction des informations de la liste**

Choisissez l'état que vous souhaitez utiliser pour votre retour d'état eedomus et collez-le dans le champ Etat.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/parametre-non-reconnu.jpg)

Cliquez sur créer

**2. Dans l'onglet "Valeurs" de la configuration de votre périphérique, ajustez les commandes**

Renseignez les commandes en tenant compte du nombre de paramètres comme illustreé ci-dessous.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/commandes-non-reconnu.jpg)

**Attention** : observez bien l'illustration ci-dessus !

- pour les commandes avec 0 paramètre, il ne faut surtout pas ajouter &value en fin de ligne
- pensez à faire correspondre les valeurs brutes avec les états que vous souhaitez avoir en retour

## 3.3 Pilotage de plusieurs équipements Somfy avec un seul périphérique

Les prériphériques "multi" permettent de commander plusieurs équipements Somfy en n'envoyant qu'une seule commande à la box Somfy.

Vous pouvez

- soit dupliquer un périphérique existant et modifier son paramétrage
- soit créer un périphérique multi à partit du store

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
     - **valeur** brute : unknow (désolé pour la coquille, compatibilité avecla V1 oblige ...)
	 - **icone** : ce que vous voulez
	 - **description** : auto
	 - **URL** : laisser par défaut
	 - **Paramètres** : vide
- si vous avez dupliqué un périphérique existant qui fonctionnait avec la V1, et si vous avez plus de 5 ou 6 équipements Somfy dans [VAR1], [VAR1] doit passer du côté paramètres car il existe une limitation eedomus dans la taille du champ URL (URL = 250 caractères max / Paramètres = 1024 caractères max)

# 4. Migration depuis les versions 1.x.x et 2.x.x

La migration en version 3 ne change rien au fonctionnement de vos équipements Somfy, mais améliore la communication avec le cloud Somfy.

Le nom du script (connexoon.php) a été changé (liste_somfy.php) afin de ne pas perturber les installations existantes.

Cependant, afin de bénéficier des nouvelles fonctionnalités il faut suivre pas à pas les étapes suivantes.

## 4.1. Installer le Master Data (voir paragraphe 2)

**- depuis la v1** : installer le Master Data à partir du store

**- depuis la v2** : Supprimer le Capteur SOMFY et installer le Master Data à partir du store

## 4.2. Pour chacun de vos périphériques IO, ajouter 2 états

Ajouter les valeurs suivantes : 

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/nouveaux-etats.jpg)

## 4.3. Pour chacun de vos périphériques, modifiez la configuration

### Variables utilisatur

#### - depuis la v1 :

A l'aide du chemin XPATH initial et de l'outil de migration : http://votre_@_IP/script/?exec=liste_somfy.php&action=migration , renseignez [VAR2] avec l'état approprié

**exemple** : [VAR2] = core:ClosureState

Vous pouvez aussi recréer un péripérique pour prendre exemple.

#### - depuis la v2 :

normalement, [VAR2] est déjà bien paramétré

**Important** : Le retour d'état ne fonctionnera pas si [VAR2] n'est pas renseignée correctement.

### Paramètres avancés

- **Requête de mise à jour ** : http://localhost/script/?exec=liste_somfy.php&devices=[VAR1]&etat=[VAR2]&action=init
- **Chemin XPATH** : /somfy/state
- **Fréquence de la requête** : 600

### Onglet valeurs

modifier les valeurs des commandes comme suit : 

- **URL** : http://localhost/script/?exec=liste_somfy.php
- **Paramètres** : &devices=[VAR1]&etat=[VAR2]&action=[votre action]&value=[votre paramètre]

**Information** : [VAR1] passe du côté paramètres car il existe une limitation eedomus dans la taille du champ URL (URL = 250 caractères max / Paramètres = 1024 caractères max)

# 5. Historique des versions

### V3.0.0 du 10/07/2021
**Amélioratiuons et nouvelles fonctionnalités :**
- ajout de nombreux équipements SOMFY
- gestion des équipements à piles
- optimisation du retour d'état : limitation des appels à sdk_get_setup() pour privilégier sdk_getDeviceStatus()
- sécurisation des logins pour éviter le blacklistage de Somfy
- gestion des gateways
- amélioration de l'écran de création de périphérique

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