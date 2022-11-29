<?php

namespace App\Services;

use Illuminate\Http\Request;

interface DataProviderInterface
{
    public function list(Request $request);
}