<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function getEntireTableData($data);
    public function findById($id);
    public function findByEmail($email);
    public function findByUsername($username);
    public function findByEmailAndPassword($email, $password);
    public function createUserByEmail(array $data);
    public function signup(array $data);
    public function updateBankDetails(array $data);
    public function updateDeliveryAddress(array $data);
    public function updatePaymentDetails(array $data);
    public function addPaymentDetails(array $data);

}
