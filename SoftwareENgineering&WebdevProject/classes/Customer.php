<?php


// Customer class inheriting from User
class Customer extends User
{
    protected $address;


    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }
    protected $twoFactorAuth;

    public function getTwoFactorAuth() {
        return $this->twoFactorAuth;
    }

    public function setTwoFactorAuth($twoFactorAuth) {
        $this->twoFactorAuth = $twoFactorAuth;
    }
}