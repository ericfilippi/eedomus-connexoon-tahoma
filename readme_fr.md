# eedomus-connexoon-tahoma
Bridge entre box eedomus et le cloud SOMFY pour les boxs Tahoma et Connexoon

### Version 2.0.0
- modification du login pour �viter les d�connexions
- prise en compte des �v�nements somfy pour g�rer le retour d'�tat

### Version 1.2.2 du 27/06/2020
- ajout Brises soleil orientables : io:ExteriorVenetianBlindIOComponent' => 'BSO IO

# Sommaire

0. Introduction
1. Premi�re utilisation
2. Cr�ation d'un pr�riph�rique

   2.1 Equipement reconnu
   
   2.2 Equipement non reconnu
   
   2.3 Pilotage de plusieurs �quipements Somfy avec un seul p�riph�rique
   
3. Migration depuis les versions 1.x.x

# 0. Introduction

Ce plugin permet de contr�ler les volets et stores SOMFY. Il est n�cessaire pour cela de poss�der le bridge Connexoon ou la box Tahoma, et d'avoir associ� ses volets ou stores au bridge via l'application mobile Connexoon ou Tahoma de SOMFY.

**Attention** : l'installation du p�riph�rique s'effectue uniquement en �tant connect� sur le m�me r�seau que votre box !

La version 2 du plugin introduit un nouveau capteur d'�tat qu'il est n�cessaire d'installer pour b�n�ficier des nouvelles fonctionnalit�s.

Dans ce document on nomme :

**P�riph�rique** pour eedomus

**Equipement Somfy** pour Somfy

# 1. Premi�re utilisation

Avant de cr�er vos pr�riph�riques, installez le capteur d'�tat (� n'installer qu'une seule fois) :

![image capteur](https://github.com/ericfilippi/eedomus-connexoon-tahoma/blob/v2.0.0/capture/capteur.jpg "Param�trage capteur")

Adresse de l'�quipement Somfy : **Capteur SOMFY** (inutile mais le champ doit �tre rempli)
Type d'�quipement Somfy : **Capteur d'�tat**

Ce capteur a pour fonction :
- d'indiquer l'�tat de la connexion avec le cloud SOMFY et les box Connexoon/Tahoma
- d'assurer le retour d'�tat des commandes envoy�es par eedomus vers SOMFY
- de mettre � jour les pr�riph�riques eedomus suite � une action directe IO ou RTS

### Valeurs

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/valeurs_capteur.jpg)

Le capteur se met � jour toutes les minutes, mais il peut mettre quelques minutes � s'initialiser.

# 2. Cr�ation d'un p�riph�rique

Lors de l'installation d'un p�riph�rique, vous devrez renseigner l'adresse de l'�quipment correspondant. Pour cela, cliquez sur le lien et renseignez vos identifiants SOMFY.

La liste des �quipements Somfy connect�s � votre box SOMFY est affich�e.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/lien.jpg)


## 2.1 Les �quipements Somfy reconnus sont list�s dans le chapitre *Liste des peripheriques*.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/liste.jpg)

Il suffit de renseigner l'adresse et le type dans l'�cran de param�trage comme indiqu� ci-dessous.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/parametre-affichage.jpg)

**Important** : Une fois le p�riph�rique cr��, il est n�cessaire d'envoyer une premi�re commande (par exemple "ouvrir") afin d'initialiser son fonctionnement.

## 2.2 Les �quipements Somfy non reconnus sont list�s dans le chapitre *Liste des peripheriques non reconnus (mais probablement compatibles)*.

Cela vous permettra (avec un peu d'entra�nement) de param�trer votre device eedomus pour envoyer la bonne commande � SOMFY et r�cup�rer les bons �tats.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/liste_non_reconnu.jpg)

**Type** : S�lectionnez "Pr�riph�rique [IO/RTS] non reconnu" en fonction du type affich� dans la liste.

**Liste des �tats disponibles** : indique les �tats que peut prendre votre �quipement Somfy.

**Liste des commandes disponibles** : comme son nom l'indique, ce sont toutes les commandes accept�e par votre �quipement Somfy, avec le nombre de param�tres � fournir.

### Exemple : 

**1. renseignez les champs en fonction des informations de la liste**

Choisissez l'�tat que vous souhaitez utiliser pour votre retour d'�tat eedomus et collez-le dans le champ Etat.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/parametre-non-reconnu.jpg)

Cliquez sur cr�er

**2. Ajustez les commandes**

Renseignez les commandes en tenant compte du nombre de param�tres comme illustre� ci-dessous.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/commandes-non-reconnu.jpg)

## 2.3 Pilotage de plusieurs �quipements Somfy avec un seul p�riph�rique

Les pr�riph�riques "multi" permettent de commander plusieurs �quipements Somfy en n'envoyant qu'une seule commande � la box Somfy.

Utilisez l'un des p�riph�riques "multi" propos�s.

Dans le champ Adresse de l'�quipemen Somfy, listez les adresses des �quipements en les s�parant avec des virgules.

## cr�er un p�riph g�n�rique pour les p�riphs non connus
## tester les modifs sur la fonction getState
## migration : setSlateOrientation -> setOrientation
