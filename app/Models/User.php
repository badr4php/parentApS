<?php

namespace App\Models;
use Illuminate\Http\Request;
use App\Services\DataProviderX;
use App\Services\DataProviderY;

class User
{
    private $providers;
    private $collection;
    private $request;

    public function __construct(Request $request)
    {
        $this->collection = collect();
        $this->request = $request;
        $this->setProviders();
    }
    private function setProviders(){
        if($this->request->filled('provider')){
            switch ($this->request->get('provider')) {
                case 'DataProviderX':
                    $this->providers = [new DataProviderX()];
                  break;
                case 'DataProviderY':
                    $this->providers = [new DataProviderY()];
                  break;
                default:
                $this->providers = [];
              } 
        }else{
            $this->providers = [
                new DataProviderX(),
                new DataProviderY()
            ];
        }
    }

    public function list(){
        foreach($this->providers as $provider){
            $this->collection = $this->collection->concat($provider->list($this->request));
        }
        return $this->collection;
    }
}