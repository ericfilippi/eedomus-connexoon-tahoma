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
3. Migration depuis les versions 1.x.x

# 0. Introduction

Ce plugin permet de contr�ler les volets et stores SOMFY. Il est n�cessaire pour cela de poss�der le bridge Connexoon ou la box Tahoma, et d'avoir associ� ses volets ou stores au bridge via l'application mobile Connexoon ou Tahoma de SOMFY.

**Attention** : l'installation du p�riph�rique s'effectue uniquement en �tant connect� sur le m�me r�seau que votre box !

La version 2 du plugin introduit un nouveau capteur d'�tat qu'il est n�cessaire d'installer pour b�n�ficier des nouvelles fonctionnalit�s.

# 1. Premi�re utilisation

Avant de cr�er vos pr�riph�riques, installez le capteur d'�tat (� n'installer qu'une seule fois) :
- Adresse du pr�riph�rique : **Capteur SOMFY** (inutile mais le champ doit �tre rempli)
- Type de p�riph�rique : **Capteur d'�tat**

Ce capteur a pour fonction :
- d'indiquer l'�tat de la connexion avec le cloud SOMFY et les box Connexoon/Tahoma
- d'assurer le retour d'�tat des commandes envoy�es par eedomus vers SOMFY
- de mettre � jour les pr�riph�riques eedomus suite � une action directe IO ou RTS

### Valeurs

| valeur | libell�               | Signification                             |
|--------|-----------------------|-------------------------------------------|
| 0      | SOMFY OFF             | Perte de connexion avec cloud SOMFY       |
| 1      | SOMFY ON / Tahoma OFF | Connexion au cloud SOMFY OK mais la box SOMFY est down                           |
| 2      | V�rifier devices en erreur | Un ou plusieurs devices SOMFY sont en erreur |
| 3      | SOMFY ON / Tahoma ON | Tout est OK

Le capteur se met � jour toutes les minutes, mais il peut mettre quelques minutes � s'initialiser.

# 2. Cr�ation d'un p�riph�rique

Lors de l'installation d'un p�riph�rique, vous devrez renseigner l'adresse de votre volet. Pour cela, cliquez sur le lien et renseignez vos identifiants Connexoon.

La liste des p�riph�riques connect�s � votre box SOMFY est affich�e.

Les p�riph�riques connus sont list�s dans le chapitre **Liste des peripheriques**
