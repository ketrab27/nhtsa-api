<?php

namespace App\Helpers;

use GuzzleHttp\Client;


class Nhtsa
{
    const NHTSA_API = 'https://one.nhtsa.gov/webapi/api/SafetyRatings/';

    protected $year;
    protected $manufacturer;
    protected $model;
    protected $userId;

    public function __construct($year, $manufacturer, $model, $userId)
    {
        $this->year = $year;
        $this->manufacturer = $manufacturer;
        $this->model = $model;
        $this->userId = $userId;
    }

    public function getByUri()
    {
        if($this->validateParams())
        {
            $dataStr = 'modelyear/'.$this->year.'/make/'.$this->manufacturer.'/model/'. $this->model;
            $postfix = '?format=json';
            $client = new Client();
            $res = $client->request('GET', self::NHTSA_API . $dataStr . $postfix);
            $results = json_decode($res->getBody()->getContents());
            $returnDataArr = array(
                "Count" => $results->Count,
                "Results" => array()
            );
            foreach ($results->Results as $vehicle)
            {
                $returnDataArr["Results"][] = array(
                    'Description' => $vehicle->VehicleDescription,
                    'VehicleId' => $vehicle->VehicleId
                );
            }
        }else{
            $returnDataArr = array(
                "Count" => 0,
                "Results" => array()
            );
        }

        return $returnDataArr;
    }

    public function getByUriWithRatings()
    {
        if($this->validateParams())
        {
            $dataStr = 'modelyear/'.$this->year.'/make/'.$this->manufacturer.'/model/'. $this->model;
            $postfix = '?format=json';
            $client = new Client();
            $res = $client->request('GET', self::NHTSA_API . $dataStr . $postfix);
            $results = json_decode($res->getBody()->getContents());
            $returnDataArr = array(
                "Count" => $results->Count,
                "Results" => array()
            );
            foreach ($results->Results as $vehicle)
            {
                $dataStr = 'VehicleId/'.$vehicle->VehicleId;
                $clientById = new Client();
                $res = $clientById->request('GET', self::NHTSA_API . $dataStr . $postfix);

                $response = json_decode($res->getBody()->getContents());
                if(count($response->Results))
                    $rating = $response->Results[0]->OverallRating;
                else
                    $rating = 'Not Rated';

                $returnDataArr["Results"][] = array(
                    'CrashRating' => $rating,
                    'Description' => $vehicle->VehicleDescription,
                    'VehicleId' => $vehicle->VehicleId
                );
            }
        }else{
            $returnDataArr = array(
                "Count" => 0,
                "Results" => array()
            );
        }

        return $returnDataArr;
    }

    private function validateParams()
    {
        if(!is_null($this->year) && !is_null($this->manufacturer) && !is_null($this->manufacturer))
        {
            $yearPattern = "/^[0-9]+$/";
            $manufacturerPattern = "/[a-zA-Z0-9]+[&|\-|\'\s]*[a-zA-Z0-9]+/";
            $modelPattern = "/[a-zA-Z0-9]+[&|\-|\'\s]*[a-zA-Z0-9]+/";
            $yearStatus = preg_match($yearPattern, $this->year) ? true : false;
            $manufacturerStatus = preg_match($manufacturerPattern, $this->manufacturer) ? true : false;
            $modelStatus = preg_match($modelPattern, $this->model) ? true : false;

            return $yearStatus && $manufacturerStatus && $modelStatus;
        }else{
            return false;
        }
    }
}