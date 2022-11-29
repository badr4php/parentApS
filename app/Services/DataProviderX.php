<?php

namespace App\Services;

use Illuminate\Http\Request;

class DataProviderX implements DataProviderInterface
{
    const DATA_PATH = 'providers/DataProviderX.json';
    const STATUS_CODE = [
            'authorised' => 1,
            'decline' => 2,
            'refunded' => 3
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
            $this->collection = $this->collection->where('statusCode', self::STATUS_CODE[$request->get('statusCode')]);
        }
    }

    private function filterByAmount($request){
        if($request->filled('balanceMin')){
            $this->collection = $this->collection->where('parentAmount', '>=', $request->get('balanceMin'));
        }
        if($request->filled('balanceMax')){
            $this->collection = $this->collection->where('parentAmount', '<=', $request->get('balanceMax'));
        }
    }

    private function filterByCurrency($request){
        if($request->filled('currency')){
            $this->collection = $this->collection->where('Currency', $request->get('currency'));
        }
    }
}