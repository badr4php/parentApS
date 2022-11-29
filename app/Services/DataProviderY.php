<?php

namespace App\Services;

use Illuminate\Http\Request;

class DataProviderY implements DataProviderInterface
{
    const DATA_PATH = 'providers/DataProviderY.json';
    const STATUS_CODE = [
            'authorised' => 100,
            'decline' => 200,
            'refunded' => 300
        ];
    private $collection;

    public function __construct()
    {
        $dataPath = database_path(self::DATA_PATH);
        $this->collection = collect(json_decode(file_get_contents($dataPath), true));
    }

    public function list(Request $request){
        $this->filters($request);
        return $this->collection;
    }

    private function filters(Request $request){
        $this->filterByStatus($request);
        $this->filterByAmount($request);
        $this->filterByCurrency($request);
    }

    private function filterByStatus($request){
        if($request->filled('statusCode') && array_key_exists($request->get('statusCode'), self::STATUS_CODE)){
            $this->collection = $this->collection->where('status', self::STATUS_CODE[$request->get('statusCode')]);
        }
    }

    private function filterByAmount($request){
        if($request->filled('balanceMin')){
            $this->collection = $this->collection->where('balance', '>=', $request->get('balanceMin'));
        }
        if($request->filled('balanceMax')){
            $this->collection = $this->collection->where('balance', '<=', $request->get('balanceMax'));
        }
    }

    private function filterByCurrency($request){
        if($request->filled('currency')){
            $this->collection = $this->collection->where('currency', $request->get('currency'));
        }
    }
}