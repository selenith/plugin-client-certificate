SSL Client Certificate Authentication for Kanboard with Nginx
==================================================

[![Build Status](https://travis-ci.org/kanboard/plugin-client-certificate.svg?branch=master)](https://travis-ci.org/kanboard/plugin-client-certificate)

Use SSL client certificate for Kanboard authentication with Nginx server.

This Plug-in is originally created by [Frédéric Guillot](https://github.com/fguillot) for [Kanboard](https://github.com/kanboard).

This fork adds the ability to authenticate a user with a certificate and with the corresponding role.

Author
------

- Frédéric Guillot
- Selenith
- License MIT

Requirements
------------

- Nginx configured with your own SSL certificates.
- Web browser with your own SSL certificate installed.
- User certificate can now use the "role" field. Valid values are "admin", "manager" and "user".
  If the field is not présent or invalid, the "user" role is assigned by default.
- This plugin use these nginx environments variables:
    - $ssl_client_s_dn mapped to `SSL_CLIENT_S_DN`
    - $ssl_client_verify mapped `SSL_CLIENT_VERIFY`

Here is a Nginx configuration example :
```
# redirect HTTP to HTTPS
server {
    listen 80;
    listen [::]:80;

    return 301 https://$host$request_uri;
}

# HTTPS config.
server {

    # SSL configuration
    listen 443 ssl;
    listen [::]:443 ssl;
	
	# Let's encrypt certificate
 	ssl_certificate /etc/letsencrypt/live/kanboard.example.net/fullchain.pem;
	ssl_certificate_key /etc/letsencrypt/live/kanboard.example.net/privkey.pem;

	# Personnal PKI CA public certificate. 
	# Used for check the client certificate.  
    # No relationship with the let's encrypt HTTPS certificate.
    ssl_client_certificate /etc/nginx/certifs/my_certificate_authority.example.net.pem;
    ssl_verify_client optional;

    root /srv/www/kanboard.example.net;

    # Add index.php to the list if you are using PHP
    index index.php;

    server_name kanboard.example.net;

    # Deny access to data directory
    location ~ ^/data {
        deny all;
    }

    location / {
        # First attempt to serve request as file, then
        # as directory, then fall back to displaying a 404.
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_param SSL_CLIENT_VERIFY $ssl_client_verify;
        fastcgi_param SSL_CLIENT_S_DN $ssl_client_s_dn;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }


}
```

This [Docker image is used to test and develop](https://github.com/kanboard/docker-apache-client-certificate) this plugin.

Installation
------------

- Create a folder **plugins/ClientCertificate** or un-compress the latest archive in the folder **plugins**
- Copy all files under this directory


Tested and working whith debian 11, Nginx 1.18.0 and php7.4-fpm.