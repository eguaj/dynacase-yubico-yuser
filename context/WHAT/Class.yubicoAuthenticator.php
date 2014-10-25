<?php

class yubicoAuthenticator extends htmlAuthenticator
{
    private $otp = null;

    public function checkAuthentication()
    {
        $session = $this->getAuthSession();
        if ($session->read('username') != '') {
            return Authenticator::AUTH_OK;
        };
        $this->otp = (isset($_POST['yubico_otp']) ? $_POST['yubico_otp'] : '');
        return parent::checkAuthentication();
    }

    public function logon()
    {
        $account = new Account();
        if ($account->setLoginName("anonymous") === false) {
            throw new \Dcp\Exception(sprintf("anonymous account not found."));
        }
        $actionRouter = new ActionRouter($account);
        $actionRouter->executeAction();
    }

    public function checkYubicoOTP($otp)
    {
        $yubi = new Auth_Yubico($this->parms['yubico_api_id'], $this->parms['yubico_api_key']);
        $auth = $yubi->verify($otp);
        if (!PEAR::isError($auth)) {
            return true;
        }
        error_log(__METHOD__ . " " . sprintf("OTP verification failure: '%s' ('%s')", $auth->getMessage(), $yubi->getLastResponse()));
        return false;
    }

    public function checkAuthorization($opt)
    {
        if ($this->otp === null) {
            return true;
        }
        if (!isset($opt['dcp_account'])) {
            error_log(__METHOD__ . " " . sprintf("'dcp_account' not set in 'opt'."));
            return false;
        }
        $yubicoUserId = $this->getYubicoUserId($opt['dcp_account']);
        if ($yubicoUserId === false) {
            /* User has not enabled Yubico or set it's Yubico user id */
            return true;
        }
        if ($this->otpMatchYubicoUserId($this->otp, $yubicoUserId) && $this->checkYubicoOTP($this->otp)) {
            return true;
        }
        return false;
    }

    private function getYubicoUserId(Account & $account)
    {
        require_once 'FDL/freedom_util.php';
        $fid = $account->fid;
        $user = new_Doc('', $fid, true);
        if (method_exists($user, 'getActiveYubicoUserId')) {
            return $user->getActiveYubicoUserId();
        }
        return false;
    }

    private function otpMatchYubicoUserId($otp, $yubicoUserId)
    {
        return (substr($otp, 0, 12) == $yubicoUserId);
    }
}