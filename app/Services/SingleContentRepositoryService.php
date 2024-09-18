<?php

namespace App\Services;
use App\Exceptions\GlobalExceptionHandler;
use App\RepositoryInterfaces\SingleContentRepositoryInterface;
use App\Models\User;
use App\Models\SingleContent;
use Illuminate\Support\Arr;

class SingleContentRepositoryService implements SingleContentRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function addContent(array $data){
        // $dbStatus = SingleContent::insert($data);
        // return $dbStatus;
        $singleContent = SingleContent::find(1);
        $singleContent->products = $data['products'];
        $dbStatus = $singleContent->save();
        // return is_array($data['main_page']);
        return $dbStatus;
    }

    public function updateContent(array $data){

    }
    public function deleteContent(array $data){

    }
    public function getContent(array $data){

    }
}
