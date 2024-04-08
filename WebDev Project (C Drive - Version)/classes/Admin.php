<?php

namespace classes;

// Admin class inheriting from User
class Admin extends User {
    protected $address;
    protected $role;

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }
}
