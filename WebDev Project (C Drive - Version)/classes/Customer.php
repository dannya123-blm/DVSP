<?php

namespace classes;

// Customer class inheriting from User
class Customer extends User {
    protected $twoFactorAuth;

    public function getTwoFactorAuth() {
        return $this->twoFactorAuth;
    }

    public function setTwoFactorAuth($twoFactorAuth) {
        $this->twoFactorAuth = $twoFactorAuth;
    }
}