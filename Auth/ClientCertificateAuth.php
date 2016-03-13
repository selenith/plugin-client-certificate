<?php

namespace Kanboard\Plugin\ClientCertificate\Auth;

use Kanboard\Core\Base;
use Kanboard\Core\Security\PreAuthenticationProviderInterface;
use Kanboard\Core\Security\SessionCheckProviderInterface;
use Kanboard\Plugin\ClientCertificate\User\ClientCertificateUserProvider;

/**
 * SSL Client Certificate Authentication Provider
 *
 * @package  auth
 * @author   Frederic Guillot
 */
class ClientCertificateAuth extends Base implements PreAuthenticationProviderInterface, SessionCheckProviderInterface
{
    const ENV_USERNAME = 'SSL_CLIENT_S_DN_CN';
    const ENV_EMAIL = 'SSL_CLIENT_S_DN_Email';

    /**
     * User properties
     *
     * @access protected
     * @var \Kanboard\Plugin\ClientCertificate\User\ClientCertificateUserProvider
     */
    protected $userInfo = null;

    /**
     * Get authentication provider name
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'SSL Client Certificate';
    }

    /**
     * Authenticate the user
     *
     * @access public
     * @return boolean
     */
    public function authenticate()
    {
        $username = $this->request->getServerVariable(self::ENV_USERNAME);
        $email = $this->request->getServerVariable(self::ENV_EMAIL);

        if (! empty($username)) {
            $this->userInfo = new ClientCertificateUserProvider($username, $email);
            return true;
        }

        return false;
    }

    /**
     * Check if the user session is valid
     *
     * @access public
     * @return boolean
     */
    public function isValidSession()
    {
        return $this->request->getServerVariable(self::ENV_USERNAME) === $this->userSession->getUsername();
    }

    /**
     * Get user object
     *
     * @access public
     * @return \Kanboard\Plugin\ClientCertificate\User\ClientCertificateUserProvider
     */
    public function getUser()
    {
        return $this->userInfo;
    }
}
