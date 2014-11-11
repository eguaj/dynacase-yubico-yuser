<?php

namespace YUBICO;

class User extends \Dcp\Family\Iuser {
    public function getActiveYubicoUserId() {
        if ($this->getRawValue('YUBICO_ENABLED') == 'Y') {
            return $this->getRawValue('YUBICO_USERID', '');
        }
        return false;
    }
    public function extractUserIDFromOTP($otp) {
        return substr($otp, 0, 12);
    }
    public function isValidOTP($otp, $yubicoEnabled = 'N') {
        $ret = array(
            'err' => '',
            'sug' => ''
        );
        if ($yubicoEnabled == 'Y') {
            if (strlen($otp) < 12) {
                return array(
                    'err' => sprintf("Use your Yubico key to enter a OTP here!"),
                    'sug' => ''
                );
            }
            if (self::checkYubicoOTP($otp) === false) {
                return array(
                    'err' => sprintf("Invalid or malformed OTP!"),
                    'sug' => ''
                );
            }
        }
        return $ret;
    }
    public static function checkYubicoOTP($otp) {
        include 'config/dbaccess.php';
        $yubico_api_id = (isset($freedom_authtypeparams['yubico']['yubico_api_id']) ? $freedom_authtypeparams['yubico']['yubico_api_id'] : '');
        $yubico_api_key = (isset($freedom_authtypeparams['yubico']['yubico_api_key']) ? $freedom_authtypeparams['yubico']['yubico_api_key'] : '');
        $yubi = new \Auth_Yubico($yubico_api_id, $yubico_api_key);
        $auth = $yubi->verify($otp);
        if (!\PEAR::isError($auth)) {
            return true;
        }
        error_log(__METHOD__ . " " . sprintf("OTP verification failure: '%s' ('%s')", $auth->getMessage(), $yubi->getLastResponse()));
        return false;
    }
}