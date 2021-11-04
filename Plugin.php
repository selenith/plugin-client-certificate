<?php

namespace Kanboard\Plugin\ClientCertificate;

use Kanboard\Core\Plugin\Base;
use Kanboard\Plugin\ClientCertificate\Auth\ClientCertificateAuth;

/**
 * SSL Client certificate
 *
 * @author  Frederic Guillot
 * @author  Selenith   
 */
class Plugin extends Base
{
    public function initialize()
    {
        $this->authenticationManager->register(new ClientCertificateAuth($this->container));
    }

    public function getPluginDescription()
    {
        return 'SSL client certificate authentication for nginx';
    }

    public function getPluginAuthor()
    {
        return 'Frédéric Guillot (original Author) and Selenith';
    }

    public function getPluginVersion()
    {
        return '1.1.0';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/selenith/plugin-client-certificate';
    }
}
