<?php

namespace Kanboard\Plugin\ClientCertificate\Auth;

use Kanboard\Core\Base;
use Kanboard\Core\Security\PreAuthenticationProviderInterface;
use Kanboard\Core\Security\SessionCheckProviderInterface;
use Kanboard\Plugin\ClientCertificate\User\ClientCertificateUserProvider;
use Kanboard\Core\Security\Role;


/**
 * SSL Client Certificate Authentication Provider
 *
 * @package auth
 * @author  Frederic Guillot
 * @author  Selenith
 */
class ClientCertificateAuth extends Base implements PreAuthenticationProviderInterface, SessionCheckProviderInterface
{
    const ENV_DN = 'SSL_CLIENT_S_DN';
    private static $ROLE_MAP = ['admin'=>  Role::APP_ADMIN, 'manager'=>Role::APP_MANAGER, 'user'=>Role::APP_USER];

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

        if(!isset($_SERVER['SSL_CLIENT_VERIFY']) and $_SERVER['SSL_CLIENT_VERIFY'] !== "SUCCESS"){
            return false;
        }

        $DN = $this->request->getServerVariable(self::ENV_DN);
        $dn_fields_list = $this->extract_user_DN_fields($DN);

        $username = $dn_fields_list['CN'];
        $email = $dn_fields_list['emailAddress'];

        if(isset($dn_fields_list['role']) and array_key_exists($dn_fields_list['role'], self::$ROLE_MAP)){
            
            $role = self::$ROLE_MAP[$dn_fields_list['role']];
        }else{
            $role =  Role::APP_USER;
        }
        if (! empty($username)) {
            $this->userInfo = new ClientCertificateUserProvider($username, $email, $role);
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
        $DN = $this->request->getServerVariable(self::ENV_DN);
        $dn_fields_list = $this->extract_user_DN_fields($DN);
        
        return $dn_fields_list['CN'] === $this->userSession->getUsername();
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

    /**
     * Extract fiels in DN.
     * 
     * @param string $DN  The “subject DN” string of the client certificate for an established SSL connection according to RFC 2253
     * @access private
     * @return array
     */
    private function extract_user_DN_fields($DN){
		
		$dn_pairs = explode(',', $DN);
		$dn_list = [];
		foreach ($dn_pairs as $pair){
			$key_value = explode('=', $pair);
			$dn_list[$key_value[0]] = $key_value[1];
		}

		return $dn_list;
	}

   
}
