sudo git clone https://github.com/nnavrio140/TFG-Peluqueria.git

sudo cp -r TFG-Peluqueria/Servidor/ .

cd Servidor/TFG-Peluqueria

sudo apt install php-cli unzip curl -y

sudo curl -sS https://getcomposer.org/installer -o composer-setup.php

sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

sudo apt install php8.4-xml -y

sudo chmod -R 775 /var/www/Servidor/TFG-Peluqueria/

sudo chown -R $USER:$USER /var/www/Servidor/TFG-Peluqueria/

composer install

cp .env.example .env

sudo nano .env (cambiar todos las db conect) pAss5678?

sudo apt install php8.4-mysql

sudo apt install php8.4-mbstring

php artisan migrate:fresh --seed

php artisan storage:link