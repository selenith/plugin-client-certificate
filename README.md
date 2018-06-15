SSL Client Certificate Authentication for Kanboard
==================================================

**This plugin is not maintained anymore!**

Use SSL client certificate for Kanboard authentication.

Author
------

- Frédéric Guillot
- License MIT

Requirements
------------

- Apache configured with your own SSL certificates
- Web browser with your own SSL certificate installed
- This plugin use these environments variables:
    - Kanboard username: `SSL_CLIENT_S_DN_CN`
    - Kanboard email: `SSL_CLIENT_S_DN_Email`

This [Docker image is used to test and develop](https://github.com/kanboard/docker-apache-client-certificate) this plugin.

Installation
------------

- Create a folder **plugins/ClientCertificate** or un-compress the latest archive in the folder **plugins**
- Copy all files under this directory
