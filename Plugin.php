<?php

namespace Kanboard\Plugin\ClientCertificate;

use Kanboard\Core\Plugin\Base;
use Kanboard\Plugin\ClientCertificate\Auth\ClientCertificateAuth;

/**
 * SSL Client certificate
 *
 * @author   Frederic Guillot
 */
class Plugin extends Base
{
    public function initialize()
    {
        $this->authenticationManager->register(new ClientCertificateAuth($this->container));
    }

    public function getPluginDescription()
    {
        return 'SSL client certificate authentication';
    }

    public function getPluginAuthor()
    {
        return 'Frédéric Guillot';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/kanboard/plugin-client-certificate';
    }
}
