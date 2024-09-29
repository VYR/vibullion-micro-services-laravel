<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function getEntireTableData($data);
    public function createUserByEmail(array $data);
    public function signup(array $data);
    public function updateBankDetails(array $data);
    public function updateDeliveryAddress(array $data);

}
