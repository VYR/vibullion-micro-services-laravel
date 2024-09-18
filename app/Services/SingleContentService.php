<?php

namespace App\Services;

use App\GlobalResponseData;
use App\ServiceInterfaces\SingleContentInterface;
use App\RepositoryInterfaces\SingleContentRepositoryInterface;
use Illuminate\Http\Request;

class SingleContentService implements SingleContentInterface
{
    use GlobalResponseData;
    protected SingleContentRepositoryInterface $singleContentRepositoryInterface;
    public function __construct(SingleContentRepositoryInterface $singleContentRepositoryInterface)
    {
        $this->singleContentRepositoryInterface = $singleContentRepositoryInterface;
    }
    public function addContent(Request $request)
    {
        return $this->singleContentRepositoryInterface->addContent($request->all());
    }
    public function updateContent(Request $request)
    {
        
    }
    public function deleteContent(Request $request)
    {
        
    }
    public function getContent(Request $request)
    {
        
    }
}
