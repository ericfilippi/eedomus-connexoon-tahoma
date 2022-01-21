![image capteur](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/img/logo_connexoon.png "Param�trage capteur")

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

# 3. Cr�ation d'un p�riph�rique

## 3.1 Les �quipements Somfy reconnus sont list�s dans le chapitre *B*.

Un �quipement reconnu est � cod� � dans le plugin, le p�riph�rique a �t� pr�d�fini avec ses commandes, son retour d��tat, la requ�te http, son polling et tous les graphismes correspondants.

Le syst�me n�installe qu�un seul p�riph�rique par type d��quipement. Si vous disposez de plusieurs �quipements de ce type alors 2 options sont possibles :

-	soit relancer autant de fois que n�cessaire le Plugin (assez fastidieux et source d�erreur car il faut cocher le bon type d��quipement)
-	soit dupliquer le p�riph�rique dans la box eedomus et changer l�adresse avec celle de l��quipement � cr�er dans [VAR1]

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/liste.jpg)

Il suffit de recopier l'adresse dans l'�cran de param�trage comme indiqu� ci-dessous.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/parametre-affichage.jpg)

**Important** afin d'initialiser le retour d'�tat (sinon il n'y en aura pas ! ):

- Pour les actionneurs : envoyer une premi�re commande (par exemple "ouvrir").
- Pour les capteurs : l'init se lance toutes les 10 heures (polling � 600 minutes), mais vous pouvez le forcer : (param�tres experts, tester le chemin XPATH).

## 3.2 Les �quipements Somfy non reconnus sont list�s dans le chapitre *C*.

Cela vous permettra (avec un peu d'entra�nement) de cr�er et param�trer votre p�riph�rique eedomus pour envoyer la bonne commande � SOMFY et r�cup�rer les bons �tats.

**Caution** : certaines commandes ont des param�tres associ�s � l�action. Malheureusement le cloud Somfy et donc le listing ne donnent pas les valeurs des param�tres, il faut les � deviner �, souvent en s�aidant des valeurs d��tat.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/liste_non_reconnu.jpg)

**Type** : S�lectionnez "P�riph�rique [IO/RTS] non reconnu" en fonction du type affich� dans la liste.

**Liste des �tats disponibles** : indique les variables d��tat que peut prendre votre �quipement Somfy, chaque variable ayant une liste de valeurs, ou une valeur bool�enne, num�rique.

**Liste des commandes disponibles** : comme son nom l'indique, ce sont toutes les commandes accept�es par votre �quipement Somfy, avec le nombre de param�tres � fournir. L'API Somfy ne fournit pas le d�tail des valeurs des param�tres. Il va falloir faire preuve d'imagination, de bon sens, et proc�der par essai/erreur pour trouver les bonnes valeurs des param�tres.

### Exemple : 

**1. A la cr�ation du p�riph�rique, renseignez les champs en fonction des informations de la liste**

Choisissez l'�tat que vous souhaitez utiliser pour votre retour d'�tat eedomus et collez-le dans le champ Etat.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/parametre-non-reconnu.jpg)

Cliquez sur cr�er

**2. Dans l'onglet "Valeurs" de la configuration de votre p�riph�rique, ajustez les commandes**

Renseignez les commandes en tenant compte du nombre de param�tres comme illustr� ci-dessous.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/commandes-non-reconnu.jpg)

**Caution** : observez bien l'illustration ci-dessus !

- pour les commandes avec 0 param�tre, il ne faut surtout pas ajouter &value en fin de ligne
- pensez � faire correspondre les valeurs brutes avec les �tats que vous souhaitez avoir en retour

## 3.3 Pilotage de plusieurs �quipements Somfy avec un seul p�riph�rique

Les p�riph�riques "multi" permettent de commander plusieurs �quipements Somfy en n'envoyant qu'une seule commande � la box Somfy.

Vous pouvez

- soit dupliquer un p�riph�rique existant et modifier son param�trage
- soit cr�er un p�riph�rique multi � partir du store

Le param�trage est tr�s simple :

**Adresse de l'�quipement Somfy** : listez les adresses des �quipements en les s�parant avec des virgules.

**Requ�te de mise � jour ** : http://localhost/script/?exec=liste_somfy.php&action=auto

**Chemin XPATH** : /connexoon/resultat

**Fr�quence de la requ�te** : 1

**Caution**

- ne pilotez en mode multi que des �quipements Somfy strictement identiques (m�me fonction, m�me commande, m�mes param�tres).
- la liste d'adresses s�par�e par des virgules ne doit contenir aucun espace, y compris en fin de liste.
- il n'y a pas de retour d'�tat en mode multi, le p�riph�rique revient automatiquement en �tat "auto" au bout d'une minute gr�ce � la requ�te de mise � jour.
- si vous avez dupliqu� un p�riph�rique existant, pensez � ajouter une valeur auto :
     - **valeur** brute : unknow (d�sol� pour la coquille, compatibilit� avec la V1 oblige ...)
	 - **icone** : ce que vous voulez
	 - **description** : auto
	 - **URL** : laisser par d�faut
	 - **Param�tres** : vide
- si vous avez dupliqu� un p�riph�rique existant qui fonctionnait avec la V1, et si vous avez plus de 5 ou 6 �quipements Somfy dans [VAR1], [VAR1] doit passer du c�t� param�tres car il existe une limitation eedomus dans la taille du champ URL (URL = 250 caract�res max / Param�tres = 1024 caract�res max)

## 3.4 Les scenarios sont list�s dans le chapitre *D*.

![image scenarios](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/liste-scenarios.jpg)

Il suffit de recopier l'adresse dans l'�cran de param�trage comme indiqu� ci-dessous.

![image scenarios](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/parametre-affichage-scenario.jpg)

# 4. Migration

## 4.1. A partir de la version 3

Simplement supprimer puis recr�ez le Master Data.

## 4.2. Depuis les versions pr�c�dentes 1.x.x et 2.x.x

La migration en version 3 ne change rien au fonctionnement de base de vos �quipements Somfy, mais elle am�liore la communication avec le cloud Somfy g�re les gateway, ouvre � une liste plus importante d��quipements, g�re les piles coupl�es aux capteurs, r�sout des dysfonctionnements, permet un meilleur suivi du fonctionnement de l�application (compteur de devices et autres fonctions internes) et r�duit la consommation du CPU.

Le nom du script (connexoon.php) a �t� chang� (liste_somfy.php) afin de ne pas perturber les installations existantes.

Cependant, afin de b�n�ficier des nouvelles fonctionnalit�s il faut suivre pas � pas les �tapes suivantes.

### 4.2.1. Installer le Master Data (voir paragraphe 2)

**- depuis la v1** : installer le Master Data � partir du store

**- depuis la v2** : Supprimer le Capteur SOMFY et installer le Master Data � partir du store

### 4.2.2. Pour chacun de vos p�riph�riques IO, ajouter 2 �tats

Ajouter les valeurs suivantes : 

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/nouveaux-etats.jpg)

### 4.2.3. Pour chacun de vos p�riph�riques, modifiez la configuration

#### Variables utilisatur

##### - depuis la v1 :

A l'aide du chemin XPATH initial et de l'outil de migration : http://@IP_votre_eedomus/script/?exec=liste_somfy.php&action=migration , renseignez [VAR2] avec l'�tat appropri�

**exemple** : [VAR2] = core:ClosureState

Vous pouvez aussi recr�er un p�riph�rique pour prendre exemple.

##### - depuis la v2 :

Normalement, [VAR2] est d�j� bien param�tr�e

**Important** : Le retour d'�tat ne fonctionnera pas si [VAR2] n'est pas renseign�e correctement.

#### Param�tres avanc�s

- **Requ�te de mise � jour ** : http://localhost/script/?exec=liste_somfy.php&devices=[VAR1]&etat=[VAR2]&action=init
- **Chemin XPATH** : /somfy/state
- **Fr�quence de la requ�te** : 600

#### Onglet valeurs

Modifier les valeurs des commandes comme suit : 

- **URL** : http://localhost/script/?exec=liste_somfy.php
- **Param�tres** : &devices=[VAR1]&etat=[VAR2]&action=[votre action]&value=[votre param�tre]

**Information** : [VAR1] passe du c�t� param�tres car il existe une limitation eedomus dans la taille du champ URL (URL = 250 caract�res max / Param�tres = 1024 caract�res max)

# 5. Outils pratiques (pour les utilisateurs avanc�s)

Ces outils r�serv�s aux utilisateurs avanc�s peuvent vous aider param�trer les p�riph�riques non reconnus. 

## V�rification des commandes sur la box Tahoma

Il est possible de visualiser les commandes pass�es de la box eedomus vers la box Tahoma, il suffit de se connecter � sa Tahoma, puis aller dans "Supervision/Tableau de bord", choisir l'onglet "Tableau de Bord" et choisir "HISTORIQUE". Les commandes pass�es aux �quipements SOMFY apparaissent avec l'heure de commande, en cliquant sur le + on peut voir le d�tail de la commande.

## Mise en pause du plugin

En cas de manipulations sur le plugin et afin d'�viter d'�tre blacklist� par Somfy, il peut �tre n�cessaire de mettre en pause le plugin.
La pause  stoppe toute requ�te vers le cloud Somfy.

1. cliquez sur "Pause" sur votre Master Data
2. Pour revenir � un fonctionnement normal, cliquez sur "Reset verrouillage / pause" sur votre Master Data

## R�initialisation du plugin

Dans certains cas (si vous avez trop test�, copi�, dupliqu� ... bref bidouill�) , il peut-�tre n�cessaire de r�initialiser le PLugin. Cela effacera les informations de vos p�riph�riques.

1. cliquez sur "Reset verrouillage / pause" sur votre Master Data
2. Ensuite deux options sont possibles :

- laisser le syst�me initialiser les p�riph�riques selon le polling (attente jusqu�� 600 minutes soit 10 heures).
- initialiser manuellement tous les p�riph�riques avec retour d'�tat en testant le XPATH. Le retour d��tat doit s�afficher dans r�sultat XPath.

Tous les devices doivent �tre initialis�s, y compris les piles et autres devices li�s.

## Display

Affiche la liste des p�riph�riques avec retour d'�tat initialis�s dans le plugin (dont le Master Data).

Passer la commande : http://@IP_votre_eedomus/script/?exec=liste_somfy.php&action=display

## Track

Permet de compter combien de p�riph�riques sont initialis�s (pratique si vous laissez faire le polling)

Le p�riph�rique "Compteur devices" est cr�� en m�me temps que le Master Data. 

# 6. D�blocage du plugin

Le Master Data � la valeur 4 (SOMFY login v�rouill�) signifique que le plugin s'est mis en s�curit� suite � 3 probl�mes de login successifs afin d'�viter d'�tre blacklist� par Somfy.

Les causes connues sont :

## Changement de mot de passe SOMFY

actions :

1. v�rifiez vos identifiants
2. appliquez la correction c�t� eedomus
3. cliquez sur "Reset verrouillage / pause" sur votre Master Data

## Maintenance ou mise � jour Majeure de la box Tahoma

Ce cas g�n�re un message lors de votre connexion directe � somfy-connect.com, ce qui bloque le login depuis eedomus

actions : 

1. assurez-vous que la maintenance est termin�e avec succ�s
2. cliquez sur "Reset verrouillage / pause" sur votre Master Data

## Cas inconnu

Utilisez l'outil Display (voir chapitre 5) pour afficher la valeur **Affichage erreurs login** qui contient la derni�re r�ponse du cloud SOMFY afin d'essayer de comprendre le probl�me.

Poster si besoin cette valeur dans le forum afin d'en informer la communaut�.

Lorsque le probl�me est identifi� et corrig�, cliquez sur Reset verrouillage sur votre Master Data.

# 7. Historique des versions

### V3.1.0 du 06/01/2022

**Am�liorations et nouvelles fonctionnalit�s :**

S�curisation des logins :
- correction bug
- ajout action � Pause � dans MasterData
- ajout action � Reset verrouillage / pause � dans Master Data
- Ajout action � Reset global � dans Master Data

Nouvelles fonctionnalit�s :
- ajout gestion des scenarios Tahoma

### V3.0.0 du 10/07/2021

**Am�liorations et nouvelles fonctionnalit�s :**

- ajout de nombreux �quipements SOMFY (lien entre script et Json, et cr�ation Json)
- gestion des piles des �quipements
- optimisation du retour d'�tat : limitation des appels � sdk_get_setup() pour privil�gier sdk_getDeviceStatus()
- s�curisation des logins pour �viter le blacklistage de Somfy
- gestion des gateways
- am�lioration de l'�cran de cr�ation de p�riph�rique
- am�lioration du suivi du script avec des fonctions internes (display, compteur)
- optimisation de la charge CPU

**Corrections :**

- suppression de l'�tat "connexion" pour les capteurs (pour �viter de l'avoir dans l'historique)
- prise en compte des caract�res sp�ciaux dans les adresses d'�quipement (urlencode)


### V2.0.1 du 05/04/2021

- ajout io:MicroModuleRollerShutterSomfyIOComponent => volet IO
- correction code usage sur le capteur

### v2.0.0 du 26/03/2021

- modification du login pour �viter les d�connexions
- prise en compte des �v�nements somfy pour g�rer le retour d'�tat
- ajout prise on/off io et fil pilote io

### v1.2.2 du 27/06/2020

- ajout Brises soleil orientables : io:ExteriorVenetianBlindIOComponent' => 'BSO IO