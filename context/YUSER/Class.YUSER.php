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
    public function isValidOTP($otp) {
        $ret = array(
            'err' => '',
            'sug' => ''
        );
        if (strlen($otp) < 12) {
            return array(
                'err' => sprintf("Use your Yubico key to enter a OTP here!"),
                'sug' => ''
            );
        }
        return $ret;
    }
}