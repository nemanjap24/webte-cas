# Server Deployment Guide (Manual Installation)

This guide provides step-by-step instructions for deploying the application on a Linux server (Ubuntu/Debian) without using Docker.

## 1. System Requirements
*   **PHP**: 8.3 or higher (with extensions: `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`)
*   **Web Server**: Nginx or Apache
*   **Database**: MariaDB 11+ or MySQL 8.0+
*   **CAS**: GNU Octave (with the `control` package installed)
*   **Composer**: Latest version
*   **Node.js & NPM**: For frontend asset compilation (Vite)

---

## 2. Install GNU Octave and Dependencies
The application relies on GNU Octave for mathematical computations. You must install the environment and the control system toolbox.

```bash
sudo apt update
sudo apt install octave liboctave-dev -y

# Install the 'control' package inside Octave
# This may take a few minutes as it compiles from source
octave --eval "pkg install -forge control"
```

---

## 3. Clone and Configure the Project
Assuming the project will reside in `/var/www/webte-cas`.

```bash
cd /var/www/webte-cas/src

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Setup environment
cp .env.example .env
php artisan key:generate
```

Edit the `.env` file and configure your database and Octave settings:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Path to Octave on the server (run 'which octave' to verify)
CAS_EXECUTABLE_PATH=/usr/bin/octave
```

---

## 4. Frontend Asset Compilation
To enable styles and scripts, you need to build the production assets using Vite:

```bash
npm install
npm run build
```

---

## 5. Database Setup
Run the migrations to create the required database schema:

```bash
php artisan migrate --force
```

---

## 6. Permissions
The web server user (typically `www-data`) requires write access to the `storage` and `bootstrap/cache` directories.

```bash
sudo chown -R www-data:www-data /var/www/webte-cas/src/storage
sudo chown -R www-data:www-data /var/www/webte-cas/src/bootstrap/cache
sudo chmod -R 775 /var/www/webte-cas/src/storage
sudo chmod -R 775 /var/www/webte-cas/src/bootstrap/cache
```

---

## 7. Nginx Configuration
Create a configuration file at `/etc/nginx/sites-available/webte-cas`:

```nginx
server {
       listen 80;
       listen [::]:80;

       server_name nodeXX.webte.fei.stuba.sk;

       rewrite ^ https://$server_name$request_uri? permanent;
}

server {
        listen 443 ssl;
        listen [::]:443 ssl;

        server_name nodeXX.webte.fei.stuba.sk;

        access_log /var/log/nginx/access.log;
        error_log  /var/log/nginx/error.log info;

        root /var/www/nodeXX.webte.fei.stuba.sk/webte-cas/src/public;
        index index.php index.html;

	error_page 404 /404.html;

	location = /404.html {
    		root /var/www/nodeXX.webte.fei.stuba.sk/;
    		internal;
	}

        location / {
                try_files $uri $uri/ /index.php?$query_string;
        }
   
        location ~ \.php$ {
               include snippets/fastcgi-php.conf;
               # Using 8.4 as per your main config, change to 8.5 if preferred
               fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
               fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        }

        ssl_certificate /etc/ssl/certs/webte_fei_stuba_sk.pem;
        ssl_certificate_key /etc/ssl/private/webte.fei.stuba.sk-ec.key;

	
        location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        }
	include snippets/phpmyadmin.conf;
}
```

---

## 8. Production Optimization
To ensure maximum performance on the server:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 9. Verification
Open your domain in a browser. Navigate to the **Console** and run a simple command:
`a = 5 + 5` -> It should return `10` if everything is configured correctly.
