#!/bin/bash
###Script para la instalación desatendida de iptables
###Debe ser ejecutado como sudo para que funcione correctamente

#Preguntamos por confirmación, ya que vamos a resetear iptables

read -p "¿Se eliminará toda su configuración actual de iptables, desea continuar? (s/n): "

if [ "$REPLY" = "s" -o "$REPLY" = "S" ]; then
iptables -F
iptables -X
iptables -P INPUT ACCEPT
iptables -P FORWARD ACCEPT
iptables -P OUTPUT ACCEPT
#Permitir al usuario www-data, que es Apache, usar iptables, iptables-restore y ese script sin necesidad de poner contraseña como sudo
echo 'www-data ALL=(ALL) NOPASSWD: /sbin/iptables, /sbin/iptables-restore, /bin/bash /var/www/iptablesgui/lib/iptables-save.sh' >> /etc/sudoers
#se crean las reglas necesarias antes de instalar iptables persistent, para que se guarden
iptables -A INPUT -s localhost -p tcp --dport 80 -j ACCEPT
iptables -A INPUT -s localhost -p tcp --sport 80 -j ACCEPT
iptables -A OUTPUT -d localhost -p tcp --dport 80 -j ACCEPT
iptables -A OUTPUT -d localhost -p tcp --sport 80 -j ACCEPT
#instalación de las aplicaciones necesarias
apt-get update
apt-get install debconf-utils -y
echo iptables-persistent iptables-persistent/autosave_v4 boolean true | debconf-set-selections
echo iptables-persistent iptables-persistent/autosave_v6 boolean true | debconf-set-selections
apt-get install iptables-persistent -y
apt-get install apache2 -y
apt-get install php7.0 -y
apt-get install php7.0-sqlite3 -y
apt-get install libapache2-mod-php7.0 -y
#Copiamos la carpeta iptables que contiene la aplicación web a /var/www/
cp -rf ./iptablesgui /var/www/
chown www-data:www-data -R /var/www/iptablesgui/
#Copiamos el fichero de configuración de apache
cp ./iptablesgui.conf /etc/apache2/sites-available/
#Habilitamos nuestro sitio con el fichero de configuración
a2ensite iptablesgui.conf
a2dismod -f autoindex
a2dissite 000-default.conf
update-rc.d apache2 enable
service apache2 restart
#Inicia firefox como el usuario con el que está logeado, para evitar problemas con los permisos al cerrar
su $SUDO_USER xdg-open http://localhost
else
exit
fi

