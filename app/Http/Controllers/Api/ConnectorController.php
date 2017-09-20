<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Nhtsa;


class ConnectorController extends Controller
{

    public function vehicles(Request $request, $year = null, $manufacturer = null, $model = null)
    {
        $userId = md5($request->ip());

        if($request->isMethod('get'))
        {
            $helper = new Nhtsa($year, $manufacturer, $model, $userId);
            $withRating = $request->input('withRating');
            if($withRating === 'true')
            {
                $returnData = $helper->getByUriWithRatings();
                return json_encode($returnData);
            }
            else
            {
                $returnData = $helper->getByUri();
                return json_encode($returnData);
            }
        }
        elseif($request->isMethod('post'))
        {
            $helper = new Nhtsa(
                $request->input('modelYear'),
                $request->input('manufacturer'),
                $request->input('model'),
                $userId
            );

            $returnData = $helper->getByUri();
            return json_encode($returnData);
        }
    }
}