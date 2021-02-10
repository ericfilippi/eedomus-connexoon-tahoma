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
3. Migration depuis les versions 1.x.x

# 0. Introduction

Ce plugin permet de contrôler les volets et stores SOMFY. Il est nécessaire pour cela de posséder le bridge Connexoon ou la box Tahoma, et d'avoir associé ses volets ou stores au bridge via l'application mobile Connexoon ou Tahoma de SOMFY.

**Attention** : l'installation du périphérique s'effectue uniquement en étant connecté sur le même réseau que votre box !

La version 2 du plugin introduit un nouveau capteur d'état qu'il est nécessaire d'installer pour bénéficier des nouvelles fonctionnalités.

# 1. Première utilisation

Avant de créer vos prériphériques, installez le capteur d'état (à n'installer qu'une seule fois) :

![image capteur](https://github.com/ericfilippi/eedomus-connexoon-tahoma/blob/v2.0.0/capture/capteur.jpg "Paramétrage capteur")

Adresse du prériphérique : **Capteur SOMFY** (inutile mais le champ doit être rempli)
Type de périphérique : **Capteur d'état**

Ce capteur a pour fonction :
- d'indiquer l'état de la connexion avec le cloud SOMFY et les box Connexoon/Tahoma
- d'assurer le retour d'état des commandes envoyées par eedomus vers SOMFY
- de mettre à jour les prériphériques eedomus suite à une action directe IO ou RTS

### Valeurs

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/valeurs_capteur.jpg)

Le capteur se met à jour toutes les minutes, mais il peut mettre quelques minutes à s'initialiser.

# 2. Création d'un périphérique

Lors de l'installation d'un périphérique, vous devrez renseigner son adresse. Pour cela, cliquez sur le lien et renseignez vos identifiants SOMFY.

La liste des périphériques connectés à votre box SOMFY est affichée.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/lien.jpg)


## 2.1 Les périphériques reconnus sont listés dans le chapitre *Liste des peripheriques*.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/liste.jpg)

Et c'est tout.

Il suffit de renseigner l'adresse et le type dans l'écran de paramétrage comme indiqué ci-dessous.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/parametre-affichage.jpg)

**Important** : Une fois le périphérique créé, il est nécessaire d'envoyer une première commande (par exemple "ouvrir") afin d'initialiser son fonctionnement.

## 2.2 Les périphériques non reconnus sont listés dans le chapitre *Liste des peripheriques non reconnus (mais probablement compatibles)*.

Cela vous permettra (avec un peu d'entraînement) de paramétrer votre device eedomus pour envoyer la bonne commande à SOMFY et récupérer les bons états.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/liste_non_reconnu.jpg)

### Exemple : 

**1. renseignez les champs en fonction des informations de la liste**

**Type** : Sélectionnez "Prériphérique [IO/RTS] non reconnu" en fonction du type affiché dans la liste.

**Liste des états disponibles** : indique les états que peut prendre votre périphérique SOMFY. Choisissez l'état que vous souhaitez utiliser pour votre retour d'état eedomus et collez-le dans le champ Etat.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/parametre-non-reconnu.jpg)

Cliquez sur créer

**2. Ajustez les commandes**

**Liste des commandes disponibles** : comme son nom l'indique, ce sont toutes les commandes acceptée par votre périphérique SOMFY, avec le nombre de paramètres à fournir.

Renseignez les commandes en tenant compte du nombre de paramètres comme illustreé ci-dessous.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/commandes-non-reconnu.jpg)


## créer un périph générique pour les périphs non connus
## tester les modifs sur la fonction getState
## migration : setSlateOrientation -> setOrientation
