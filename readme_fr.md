![image capteur](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/img/logo_connexoon.png "Param�trage capteur")

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

Ce plugin permet de contr�ler certains �quipements SOMFY. Il est n�cessaire pour cela de poss�der un bridge Connexoon ou une box Tahoma, et d'avoir associ� ses �quipements au bridge via l'application mobile Connexoon ou Tahoma de SOMFY.

**Attention** : l'installation du p�riph�rique s'effectue uniquement en �tant connect� sur le m�me r�seau que votre box !

La version 2 du plugin introduit un capteur d'�tat qu'il est n�cessaire d'installer pour b�n�ficier des nouvelles fonctionnalit�s.

La version 2 est normalement compatible avec la version 1. Toutefois, afin d'�viter tout probl�me en production et de premettre une migration "en douceur", la V2 est propos�e en tant que plugin auton�me dans le store eedomus.

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
- de mettre � jour les pr�riph�riques eedomus suite � une action directe IO (RTS ne supporte pas le retour d'�tat)

### Valeurs

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/valeurs_capteur.jpg)

A la premi�re utilisation, le capteur peut mettre plusieurs minutes � s'initialiser. Puis, il se met � jour toutes les minutes.

# 2. Cr�ation d'un p�riph�rique

Lors de l'installation d'un p�riph�rique, vous devrez renseigner l'adresse de l'�quipment correspondant. Pour cela, cliquez sur le lien et renseignez vos identifiants SOMFY.

La liste des �quipements Somfy connect�s � votre box SOMFY est affich�e.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/lien.jpg)


## 2.1 Les �quipements Somfy reconnus sont list�s dans le chapitre *B*.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/liste.jpg)

Il suffit de renseigner l'adresse et le type dans l'�cran de param�trage comme indiqu� ci-dessous.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/parametre-affichage.jpg)

**Important** : Une fois le p�riph�rique cr��, il est n�cessaire d'envoyer une premi�re commande (par exemple "ouvrir") afin d'initialiser le retour d'�tat.

## 2.2 Les �quipements Somfy non reconnus sont list�s dans le chapitre *C*.

Cela vous permettra (avec un peu d'entra�nement) de param�trer votre p�riph�rique eedomus pour envoyer la bonne commande � SOMFY et r�cup�rer les bons �tats.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/liste_non_reconnu.jpg)

**Type** : S�lectionnez "Pr�riph�rique [IO/RTS] non reconnu" en fonction du type affich� dans la liste.

**Liste des �tats disponibles** : indique les �tats que peut prendre votre �quipement Somfy.

**Liste des commandes disponibles** : comme son nom l'indique, ce sont toutes les commandes accept�e par votre �quipement Somfy, avec le nombre de param�tres � fournir. L'API Somfy ne fournit pas le d�tail des valeurs des param�tres. Il va falloir fair preuve d'immagination, de bon sens, et proc�der par essai/erreur pour toruver les bons param�tres.

### Exemple : 

**1. A la cr�ation du pr�riph�rique, renseignez les champs en fonction des informations de la liste**

Choisissez l'�tat que vous souhaitez utiliser pour votre retour d'�tat eedomus et collez-le dans le champ Etat.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/parametre-non-reconnu.jpg)

Cliquez sur cr�er

**2. Dans l'onglet "Valeurs" de la configuration de votre p�riph�rique, ajustez les commandes**

Renseignez les commandes en tenant compte du nombre de param�tres comme illustre� ci-dessous.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/commandes-non-reconnu.jpg)

**Attention** : observez bien l'illustration ci-dessus !
- pour les commandes avec 0 param�tre, il ne faut surtout pas ajouter &value en fin de ligne
- pensez � faire correspondre les valeurs brutes avec les �tats que vous souhaitez avoir en retour

## 2.3 Pilotage de plusieurs �quipements Somfy avec un seul p�riph�rique

Les pr�riph�riques "multi" permettent de commander plusieurs �quipements Somfy en n'envoyant qu'une seule commande � la box Somfy.

Vous pouvez
- soit dupliquer un p�riph�rique existant et modifier son param�trage
- soit cr�er un p�riph�rique multi � partit du store

Le param�trage est tr�s simple :

**Adresse de l'�quipement Somfy** : listez les adresses des �quipements en les s�parant avec des virgules.

**Requ�te de mise � jour (Optionnelle)** : http://localhost/script/?exec=connexoon.php&action=auto

**Chemin XPATH** : /connexoon/resultat

**Fr�quence de la requ�te** : 1

**Attention**

- ne pilotez en mode multi que des �quipements Somfy strictement identiques (m�me fonction, m�me commande, m�mes param�tres).

- la liste d'adresses s�par�e par des virgules ne doit contenir aucun espace, y compris en fin de liste.

- il n'y a pas de retour d'�tat en mode multi, le p�riph�rique revient automatiquement en �tat "auto" au bout d'une minute gr�ce � la requ�te de mise � jour.

- si vous avez dupliqu� un p�riph�rique existant, pensez � ajouter une valeur auto :
     - **valeur** brute : unknow (d�sol� pour la coquille, compatibilit� avecla V1 oblige ...)
	 - **icone** : ce que vous voulez
	 - **description** : auto
	 - **URL** : laisser par d�faut
	 - **Param�tres** : vide

# 3. Migration depuis les versions 1.x.x

La migration en version 2 ne change rien au fonctionnement de vos �quipements Somfy, mais am�liore la communication avec le cloud Somfy.

Cependant, afin de b�n�ficier des nouvelles fonctionnalit�s il faut :

## 1. Installer le capteur Somfy (voir paragraphe 1)

## 2. Pour chacun de vos p�riph�riques, ajouter 2 �tats

Ajouter les valeurs suivantes : 

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/v2.0.0/capture/nouveaux-etats.jpg)

## 3. Pour chacun de vos p�riph�riques, modifiez la configuration

### Variables utilisatur

**[VAR2]** : core:ClosureState

... ou tout autre valeur pour le retour d'�tat si vous avez personnalis� votre p�riph�rique

**Important** : Le retour d'�tat ne fonctionnera pas si cette valeur n'est pas renseign�e.

### Param�tres avanc�s

- **Requ�te de mise � jour (Optionnelle)** : effacer
- **Chemin XPATH** : effacer
- **Fr�quence de la requ�te** : 0

### Onglet valeurs

modifier les valeurs des commandes comme suit : 

- **URL** : http://localhost/script/?exec=connexoon-status.php
- **Param�tres** : &devices=[VAR1]&etat=[VAR2]&action=[votre action]&value=[votre param�tre]

**Information** : [VAR1] passe du c�t� param�tres car il existe une limitation eedomus dans la taille du champ URL (URL = 250 caract�res max / Param�tres = 1024 caract�res max)