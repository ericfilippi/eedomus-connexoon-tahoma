![image capteur](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/img/logo_connexoon.png "Param�trage capteur")

# P�riph�rique Somfy V3
Bridge entre la box eedomus et le cloud SOMFY via les boxes Tahoma et Connexoon

# Sommaire

1. Introduction

2. Premi�re utilisation

3. Cr�ation d'un pr�riph�rique

   3.1 Equipement Somfy reconnu
   
   3.2 Equipement Somfy non reconnu
   
   3.3 Pilotage de plusieurs �quipements Somfy avec un seul p�riph�rique eedomus
   
4. Migration depuis les versions 1.x.x

5. Historique des versions

# 1. Introduction

Ce plugin permet de contr�ler certains �quipements SOMFY. Il est n�cessaire pour cela de poss�der un bridge Connexoon ou une box Tahoma, et d'avoir associ� ses �quipements au bridge via l'application mobile Connexoon ou Tahoma de SOMFY.

**Attention** : l'installation du p�riph�rique s'effectue uniquement en �tant connect� sur le m�me r�seau que votre box eedomus !

La version 3 du plugin utilise un capteur d'�tat qu'il est n�cessaire d'installer pour b�n�ficier de toutes les fonctionnalit�s.

La version 3 est normalement compatible avec les versions 1 et 2. Toutefois, afin d'�viter tout probl�me en production et de premettre une migration "en douceur", le nom du script a �t� modifi�. Suivez attentivement les �tapes du chapitre 4.

## qui est TeamListeSomfy

- @Pat : cr�ateur du script initial v1
- @herric : script v2, v3 et tests
- @dommarion : nouveaux �quipements, json v3 et tests
- @dom54 et @sev : nouveaux �quipements et tests

Dans ce document on nomme :

**P�riph�rique** pour eedomus

**Equipement Somfy** pour Somfy

**Master Data** pour le capteur d'�tat du cloud Somfy

# 2. Premi�re utilisation

**Important** : Lors de l'installation d'un p�riph�rique, vous devrez renseigner l'adresse de l'�quipment correspondant. Pour cela, cliquez sur le lien et renseignez vos identifiants SOMFY.

La liste des �quipements Somfy connect�s � votre box SOMFY est affich�e.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/lien.jpg)

Avant de cr�er vos pr�riph�riques, installez le Master Data (� n'installer qu'une seule fois) :

![image capteur](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/capteur.jpg)

**Adresse du Master data** : C'est le PIN de votre box Somfy (situ� sur l'�tiquette sous la box) que vous retrouvez �galement dans la liste des gateways :

![image capteur](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/liste_gateways.jpg)


Ce Master Data a pour fonction :

- d'indiquer l'�tat de la connexion avec le cloud SOMFY et les box Connexoon/Tahoma
- d'assurer le retour d'�tat des commandes envoy�es par eedomus vers SOMFY
- de mettre � jour les pr�riph�riques eedomus suite � une action directe IO (RTS ne supporte pas le retour d'�tat)

### Valeurs

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/valeurs_capteur.jpg)

A la premi�re utilisation, le Master Data peut mettre plusieurs minutes � s'initialiser. Puis, il se met � jour toutes les minutes.

# 3. Cr�ation d'un p�riph�rique

## 3.1 Les �quipements Somfy reconnus sont list�s dans le chapitre *B*.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/liste.jpg)

Il suffit de recopier l'adresse dans l'�cran de param�trage comme indiqu� ci-dessous.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/parametre-affichage.jpg)

**Important** afin d'initialiser le retour d'�tat (sinon il n'y en aura pas ! ):

- Pour les actionneurs : envoyer une premi�re commande (par exemple "ouvrir").
- Pour les capteurs : l'init se lance toutes les 10 heures, mais vous pouvez le forcer : (para�tres experts, tester le chemin XPATH

## 3.2 Les �quipements Somfy non reconnus sont list�s dans le chapitre *C*.

Cela vous permettra (avec un peu d'entra�nement) de param�trer votre p�riph�rique eedomus pour envoyer la bonne commande � SOMFY et r�cup�rer les bons �tats.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/liste_non_reconnu.jpg)

**Type** : S�lectionnez "Pr�riph�rique [IO/RTS] non reconnu" en fonction du type affich� dans la liste.

**Liste des �tats disponibles** : indique les �tats que peut prendre votre �quipement Somfy.

**Liste des commandes disponibles** : comme son nom l'indique, ce sont toutes les commandes accept�e par votre �quipement Somfy, avec le nombre de param�tres � fournir. L'API Somfy ne fournit pas le d�tail des valeurs des param�tres. Il va falloir fair preuve d'immagination, de bon sens, et proc�der par essai/erreur pour toruver les bons param�tres.

### Exemple : 

**1. A la cr�ation du pr�riph�rique, renseignez les champs en fonction des informations de la liste**

Choisissez l'�tat que vous souhaitez utiliser pour votre retour d'�tat eedomus et collez-le dans le champ Etat.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/parametre-non-reconnu.jpg)

Cliquez sur cr�er

**2. Dans l'onglet "Valeurs" de la configuration de votre p�riph�rique, ajustez les commandes**

Renseignez les commandes en tenant compte du nombre de param�tres comme illustre� ci-dessous.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/commandes-non-reconnu.jpg)

**Attention** : observez bien l'illustration ci-dessus !

- pour les commandes avec 0 param�tre, il ne faut surtout pas ajouter &value en fin de ligne
- pensez � faire correspondre les valeurs brutes avec les �tats que vous souhaitez avoir en retour

## 3.3 Pilotage de plusieurs �quipements Somfy avec un seul p�riph�rique

Les pr�riph�riques "multi" permettent de commander plusieurs �quipements Somfy en n'envoyant qu'une seule commande � la box Somfy.

Vous pouvez

- soit dupliquer un p�riph�rique existant et modifier son param�trage
- soit cr�er un p�riph�rique multi � partit du store

Le param�trage est tr�s simple :

**Adresse de l'�quipement Somfy** : listez les adresses des �quipements en les s�parant avec des virgules.

**Requ�te de mise � jour ** : http://localhost/script/?exec=liste_somfy.php&action=auto

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
- si vous avez dupliqu� un p�riph�rique existant qui fonctionnait avec la V1, et si vous avez plus de 5 ou 6 �quipements Somfy dans [VAR1], [VAR1] doit passer du c�t� param�tres car il existe une limitation eedomus dans la taille du champ URL (URL = 250 caract�res max / Param�tres = 1024 caract�res max)

# 4. Migration depuis les versions 1.x.x et 2.x.x

La migration en version 3 ne change rien au fonctionnement de vos �quipements Somfy, mais am�liore la communication avec le cloud Somfy.

Le nom du script (connexoon.php) a �t� chang� (liste_somfy.php) afin de ne pas perturber les installations existantes.

Cependant, afin de b�n�ficier des nouvelles fonctionnalit�s il faut suivre pas � pas les �tapes suivantes.

## 4.1. Installer le Master Data (voir paragraphe 2)

**- depuis la v1** : installer le Master Data � partir du store

**- depuis la v2** : Supprimer le Capteur SOMFY et installer le Master Data � partir du store

## 4.2. Pour chacun de vos p�riph�riques IO, ajouter 2 �tats

Ajouter les valeurs suivantes : 

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/nouveaux-etats.jpg)

## 4.3. Pour chacun de vos p�riph�riques, modifiez la configuration

### Variables utilisatur

#### - depuis la v1 :

A l'aide du chemin XPATH initial et de l'outil de migration : http://votre_@_IP/script/?exec=liste_somfy.php&action=migration , renseignez [VAR2] avec l'�tat appropri�

**exemple** : [VAR2] = core:ClosureState

Vous pouvez aussi recr�er un p�rip�rique pour prendre exemple.

#### - depuis la v2 :

normalement, [VAR2] est d�j� bien param�tr�

**Important** : Le retour d'�tat ne fonctionnera pas si [VAR2] n'est pas renseign�e correctement.

### Param�tres avanc�s

- **Requ�te de mise � jour ** : http://localhost/script/?exec=liste_somfy.php&devices=[VAR1]&etat=[VAR2]&action=init
- **Chemin XPATH** : /somfy/state
- **Fr�quence de la requ�te** : 600

### Onglet valeurs

modifier les valeurs des commandes comme suit : 

- **URL** : http://localhost/script/?exec=liste_somfy.php
- **Param�tres** : &devices=[VAR1]&etat=[VAR2]&action=[votre action]&value=[votre param�tre]

**Information** : [VAR1] passe du c�t� param�tres car il existe une limitation eedomus dans la taille du champ URL (URL = 250 caract�res max / Param�tres = 1024 caract�res max)

# 5. Historique des versions

### V3.0.0 du 10/07/2021
**Am�lioratiuons et nouvelles fonctionnalit�s :**
- ajout de nombreux �quipements SOMFY
- gestion des �quipements � piles
- optimisation du retour d'�tat : limitation des appels � sdk_get_setup() pour privil�gier sdk_getDeviceStatus()
- s�curisation des logins pour �viter le blacklistage de Somfy
- gestion des gateways
- am�lioration de l'�cran de cr�ation de p�riph�rique

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