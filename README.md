# VideoVerhuurProject-4AM2A
Bron van project voor opleiding Applicatie- en mediaontwikkelaar

Het project gaat onder de naam "TempoVideo".

**DEADLINE: 31 maart 2017**



#Installatie
Kleine instructie over hoe je het project op een (nieuwe) GNU/Linux machine moet installeren. 

__Installeer Apache__
```
sudo apt-get update
sudo apt-get install apache2
```

__Installeer MySQL en dergelijke pakketten__
```
sudo apt-get install mysql-server libapache2-mod-auth-mysql php5-mysql
```

__Activeer MySQL__
```
sudo mysql_install_db
```

__Run de MySQL setup (script)__
```
sudo /usr/bin/mysql_secure_installation
```

__Installeer PHP__
```
sudo apt-get install php5 libapache2-mod-php5 php5-mcrypt
```

Het is handig om index.php aan je Directory Index lijst toe te voegen
```
sudo nano /etc/apache2/mods-enabled/dir.conf
```
Het bestand moet er nu zo uit zien:
```
<IfModule mod_dir.c>

          DirectoryIndex index.php index.html index.cgi index.pl index.php index.xhtml index.htm

</IfModule>
```
__Activeer URL Rewriting__
```
sudo a2enmod rewrite
```

__Start Apache opnieuw op__
```
sudo service apache2 restart
```
