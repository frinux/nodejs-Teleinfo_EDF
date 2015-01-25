# nodejs-Teleinfo_EDF

Récupération et affichage des informations de Téléinfo des compteurs EDF. Plus d'info sur http://www.frinux.fr

Mise en place du port série Raspberry : cf http://www.frinux.fr

Installation PHP/MySQL pour récupération des données Téléinfo : 

	apt-get install php5 mysql-server

Mise en place du CRON PHP toutes les minutes : 

	* * * * * php /home/pi/teleinfo/teleinfo.php

Installation NodeJs pour processeur ARM : 

	wget http://node-arm.herokuapp.com/node_latest_armhf.deb && dpkg -i node_latest_armhf.deb

Récupération des sources de l'application : 

	git clone https://github.com/frinux/nodejs-Teleinfo_EDF.git

Installation des dépendances Node : 

	npm install

Lancement du serveur Node : 

	node app.js

Mise à jour de l'heure : 

	dpkg-reconfigure tzdata
