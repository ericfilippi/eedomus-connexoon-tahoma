![image capteur](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/img/logo_connexoon.png "Paramétrage capteur")

# eedomus-connexoon-tahoma
Bridge entre box eedomus et le cloud SOMFY pour les boxs Tahoma et Connexoon

### Version 2.0.0
- modification du login pour éviter les déconnexions
- prise en compte des événements somfy pour gérer le retour d'état

### Version 1.2.2 du 27/06/2020
- ajout Brises soleil orientables : io:ExteriorVenetianBlindIOComponent' => 'BSO IO

# Sommaire

0. Introduction
1. Première utilisation
2. Création d'un prériphérique

   2.1 Equipement reconnu
   
   2.2 Equipement non reconnu
   
   2.3 Pilotage de plusieurs équipements Somfy avec un seul périphérique
   
3. Migration depuis les versions 1.x.x

# 0. Introduction

Ce plugin permet de contrôler certains équipements SOMFY. Il est nécessaire pour cela de posséder un bridge Connexoon ou une box Tahoma, et d'avoir associé ses équipements au bridge via l'application mobile Connexoon ou Tahoma de SOMFY.

**Attention** : l'installation du périphérique s'effectue uniquement en étant connecté sur le même réseau que votre box !

La version 2 du plugin introduit un capteur d'état qu'il est nécessaire d'installer pour bénéficier des nouvelles fonctionnalités.

La version 2 est normalement compatible avec la version 1. Toutefois, afin d'éviter tout problème en production et de premettre une migration "en douceur", la V2 est proposée en tant que plugin autonôme dans le store eedomus.

Dans ce document on nomme :

**Périphérique** pour eedomus

**Equipement Somfy** pour Somfy

# 1. Première utilisation

Avant de créer vos prériphériques, installez le capteur d'état (à n'installer qu'une seule fois) :

![image capteur](https://github.com/ericfilippi/eedomus-connexoon-tahoma/blob/v2.0.0/capture/capteur.jpg "Paramétrage capteur")

Adresse de l'équipement Somfy : **Capteur SOMFY** (inutile mais le champ doit être rempli)
Type d'équipement Somfy : **Capteur d'état**

Ce capteur a pour fonction :
- d'indiquer l'état de la connexion avec le cloud SOMFY et les box Connexoon/Tahoma
- d'assurer le retour d'état des commandes envoyées par eedomus vers SOMFY
- de mettre à jour les prériphériques eedomus suite à une action directe IO (RTS ne supporte pas le retour d'état)

### Valeurs

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/valeurs_capteur.jpg)

A la première utilisation, le capteur peut mettre plusieurs minutes à s'initialiser. Puis, il se met à jour toutes les minutes.

# 2. Création d'un périphérique

Lors de l'installation d'un périphérique, vous devrez renseigner l'adresse de l'équipment correspondant. Pour cela, cliquez sur le lien et renseignez vos identifiants SOMFY.

La liste des équipements Somfy connectés à votre box SOMFY est affichée.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/lien.jpg)


## 2.1 Les équipements Somfy reconnus sont listés dans le chapitre *B*.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/liste.jpg)

Il suffit de renseigner l'adresse et le type dans l'écran de paramétrage comme indiqué ci-dessous.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/parametre-affichage.jpg)

**Important** : Une fois le périphérique créé, il est nécessaire d'envoyer une première commande (par exemple "ouvrir") afin d'initialiser le retour d'état.

## 2.2 Les équipements Somfy non reconnus sont listés dans le chapitre *C*.

Cela vous permettra (avec un peu d'entraînement) de paramétrer votre périphérique eedomus pour envoyer la bonne commande à SOMFY et récupérer les bons états.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/liste_non_reconnu.jpg)

**Type** : Sélectionnez "Prériphérique [IO/RTS] non reconnu" en fonction du type affiché dans la liste.

**Liste des états disponibles** : indique les états que peut prendre votre équipement Somfy.

**Liste des commandes disponibles** : comme son nom l'indique, ce sont toutes les commandes acceptée par votre équipement Somfy, avec le nombre de paramètres à fournir. L'API Somfy ne fournit pas le détail des valeurs des paramètres. Il va falloir fair preuve d'immagination, de bon sens, et procéder par essai/erreur pour toruver les bons paramètres.

### Exemple : 

**1. A la création du prériphérique, renseignez les champs en fonction des informations de la liste**

Choisissez l'état que vous souhaitez utiliser pour votre retour d'état eedomus et collez-le dans le champ Etat.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/parametre-non-reconnu.jpg)

Cliquez sur créer

**2. Dans l'onglet "Valeurs" de la configuration de votre périphérique, ajustez les commandes**

Renseignez les commandes en tenant compte du nombre de paramètres comme illustreé ci-dessous.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/commandes-non-reconnu.jpg)

**Attention** : observez bien l'illustration ci-dessus !
- pour les commandes avec 0 paramètre, il ne faut surtout pas ajouter &value en fin de ligne
- pensez à faire correspondre les valeurs brutes avec les états que vous souhaitez avoir en retour

## 2.3 Pilotage de plusieurs équipements Somfy avec un seul périphérique

Les prériphériques "multi" permettent de commander plusieurs équipements Somfy en n'envoyant qu'une seule commande à la box Somfy.

Vous pouvez
- soit dupliquer un périphérique existant et modifier son paramétrage
- soit créer un périphérique multi à partit du store

Le paramétrage est très simple :

**Adresse de l'équipement Somfy** : listez les adresses des équipements en les séparant avec des virgules.

**Requête de mise à jour (Optionnelle)** : http://localhost/script/?exec=connexoon.php&action=auto

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

# 3. Migration depuis les versions 1.x.x

La migration en version 2 ne change rien au fonctionnement de vos équipements Somfy, mais améliore la communication avec le cloud Somfy.

Cependant, afin de bénéficier des nouvelles fonctionnalités il faut :

## 1. Installer le capteur Somfy (voir paragraphe 1)

## 2. Pour chacun de vos périphériques, ajouter 2 états

Ajouter les valeurs suivantes : 

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/nouveaux-etats.jpg)

## 3. Pour chacun de vos périphériques, modifiez la configuration

### Variables utilisatur

**[VAR2]** : core:ClosureState

... ou tout autre valeur pour le retour d'état si vous avez personnalisé votre périphérique

**Important** : Le retour d'état ne fonctionnera pas si cette valeur n'est pas renseignée.

### Paramètres avancés

- **Requête de mise à jour (Optionnelle)** : effacer
- **Chemin XPATH** : effacer
- **Fréquence de la requête** : 0

### Onglet valeurs

modifier les valeurs des commandes comme suit : 

- **URL** : http://localhost/script/?exec=connexoon-status.php
- **Paramètres** : &devices=[VAR1]&etat=[VAR2]&action=[votre action]&value=[votre paramètre]

**Information** : [VAR1] passe du côté paramètres car il existe une limitation eedomus dans la taille du champ URL (URL = 250 caractères max / Paramètres = 1024 caractères max)