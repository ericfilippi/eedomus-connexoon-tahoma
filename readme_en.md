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

# 3. Device creation

## 3.1 Known Somfy equipments are listed on chapter *B*.

A known equipment is "coded" in the plugin, the equipment has been predefined with its commands, its state feedback, the http request, its polling and all the corresponding icons.

The system installs only one device per equipment type. If you have several equipments of the same type then 2 options are possible :

- either restart the Plugin as many times as necessary (quite tedious and source of error because you have to check the right type of equipment)
- either duplicate the device in the eedomus box and change the address with the new equipment address on [VAR1]

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/liste.jpg)

Just copy the address in the settings screen as shown below.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/parametre-affichage.jpg)

**Important** in order to initialize the state feedback (otherwise there will be none!):

- For actuators: send a first command (eg "open").
- For sensors: the init is launched every 10 hours (polling at 600 minutes), but you can force it: (expert settings, test the XPATH path).

## 3.2 Unknown Somfy equipments are listed on chapter *C*.

This will allow you (with a bit of practice) to create and configure your eedomus device to send the correct command to SOMFY and retrieve the correct statuses.

**Caution** : some commands have action-related parameters. Unfortunately, the Somfy cloud and therefore the listing does not give the values of the parameters, you have to "guess" them, often using the status values.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/liste_non_reconnu.jpg)

**Type** : Select "Périphérique [IO/RTS] non reconnu" according to the type displayed in the list.

**Liste des états disponibles** : indicates the status variables that your Somfy equipment can take, each variable having a list of values, or a boolean, or a numeric value.

**Liste des commandes disponibles** : these are all the commands accepted by your Somfy equipment, with the number of parameters to provide. The Somfy API does not provide detailed parameter values. One will have to be imaginative, have common sense, and proceed by trial and error to find the right values of the parameters.

### Example : 

**1. When creating the device, fill in the fields according to the information in the list**

Choose the state you want to use for your eedomus state feedback and paste it into the State field.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/parametre-non-reconnu.jpg)

Click on create

**2. In the "Values" tab of your device's configuration, adjust the commands**

Fill in the commands considering the number of parameters as shown below.

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/commandes-non-reconnu.jpg)

**Caution** : take a good look at the picture above !

- for commands with 0 parameters, it is important not to add &value at the end of the line
- think about matching the raw values with the states you want to have in return

## 3.3 Control of several Somfy equipments with a single device

"Multi" devices allow several Somfy equipments to be controlled by sending a single command to the Somfy box.

You can

- either duplicate an existing device and modify its settings
- either create a multi device from the store

The configuration is very simple :

**Adresse de l'équipement Somfy** : list the equipments addresses separated by commas.

**Requête de mise à jour ** : http://localhost/script/?exec=liste_somfy.php&action=auto

**Chemin XPATH** : /connexoon/resultat

**Fréquence de la requête** : 1

**Caution**

- only control strictly identical Somfy equipment in multi mode (same function, same command, same parameters).
- the comma-separated list of addresses must not contain any space, neither at the end of the list.
- there is no state feedback in multi mode, the device automatically returns to "auto" status after one minute thanks to the update request.
- if you duplicated an existing device, consider adding an auto value:
     - **valeur** brute : unknow (sorry for the typo, needed for compatibility with V1 ...)
	 - **icone** : choose what you like
	 - **description** : auto
	 - **URL** : let default value
	 - **Paramètres** : vide
- if you have duplicated an existing device that worked with V1, and if you have more than 5 or 6 Somfy equipments in [VAR1], [VAR1] must go to the parameter side because there is an eedomus limitation in the size of the URL field ( URL = 250 characters max / Parameters = 1024 characters max)

## 3.4 Scenarios are listed in chapter*D*.

![image scenarios](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/liste-scenarios.jpg)

Just copy the address in the settings screen as shown below.

![image scenarios](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/parametre-affichage-scenario.jpg)

# 4. Migration

## 4.1. From version 3

Just delete and reinstall the Master Data.

## 4.2. From previous versions 1.x.x et 2.x.x

Migration to version 3 does not change the basic operation of your Somfy equipment, but it improves communication with the Somfy cloud, manages gateways, provides a larger list of known equipments, manages batteries associated with sensors, correct bugs, allows better monitoring of script operation (device counter and other internal functions) and reduces CPU load.

The name of the script (connexoon.php) has been changed (liste_somfy.php) in order not to disrupt existing installations.

However, in order to take advantage of the new features, you must follow the following steps step by step.

### 4.2.1. Install the Master Data (see chapter 2)

**- depuis la v1** : install the Master Data from the store

**- depuis la v2** : delete the "Capteur SOMFY" and install the Master Data from the store

### 4.2.2. For every IO equipment, add 2 states

Add the following values : 

![image lien](https://raw.githubusercontent.com/ericfilippi/eedomus-connexoon-tahoma/main/capture/nouveaux-etats.jpg)

### 4.2.3. For every  equipment, update configuration

#### User variables

##### - from v1 :

Using the initial XPATH path and the migration tool: http://@IP_votre_eedomus/script/?exec=liste_somfy.php&action=migration , fill in [VAR2] with the appropriate state

**exemple** : [VAR2] = core:ClosureState

You can also recreate a device as an example.

##### - depuis la v2 :

Normally, [VAR2] is already set correctly

**Important** : State feedback will not work if [VAR2] is not set correctly.

#### advanced parameters

- **Requête de mise à jour ** : http://localhost/script/?exec=liste_somfy.php&devices=[VAR1]&etat=[VAR2]&action=init
- **Chemin XPATH** : /somfy/state
- **Fréquence de la requête** : 600

#### Values tab

Change the values of the commands as follows : 

- **URL** : http://localhost/script/?exec=liste_somfy.php
- **Paramètres** : &devices=[VAR1]&etat=[VAR2]&action=[votre action]&value=[votre paramètre]

**Information** : [VAR1] goes to the parameters side because there is an eedomus limitation in the size of the URL field (URL = 250 characters max / Parameters = 1024 characters max)

# 5. Tools (for advanced users)

These tools reserved for advanced users can help you set up unknown equipments.

## Checking the commands on the Tahoma box

It is possible to view the orders placed from the eedomus box to the Tahoma box, just connect to your Tahoma, then go to "Monitoring/Dashboard", choose the "Dashboard" tab and choose "HISTORY ". Orders placed with SOMFY equipment appear with the time of order, by clicking on the + you can see the details of the order.

## Pause

In case of manipulations on the plugin and to avoid being blacklisted by Somfy, it may be necessary to pause the plugin.
Pausing stops any request to the Somfy cloud.

1. click "Pause" on your Master Data
2. To return to normal operation, click "Reset verrouillage / pause" on your Master Data

## Plugin reset

In some cases (if you have tested, copied, duplicated too much ... or tweaked), it may be necessary to reset the Plugin. This will erase your device information.

1. click on "Reset verrouillage / pause" on your Master Data
2. There are then two options :

- let the system initialize the devices according to the polling (waiting up to 600 minutes, i.e. 10 hours).
- manually initialize all devices with state feedback by testing the XPATH. The state feedback should appear in XPath result.

All devices need to be initialized, including batteries and other related devices.

## Display

Displays devices list with state feedback initialized in the plugin (including the Master Data).

Run the following command : http://@IP_votre_eedomus/script/?exec=liste_somfy.php&action=display

## Track

Usefull to count initialized devices (useful if you let the system initialize the devices according to the polling)

The "Compteur devices" device is created at the same time as the Master Data. 

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