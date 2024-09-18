<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function getEntireTableData();
    public function findById($id);
    public function findByEmail($email);
    public function findByUsername($username);
    public function findByEmailAndPassword($email, $password);
    public function createUserByEmail(array $data);
    public function signup(array $data);
    public function login(array $data);
}
