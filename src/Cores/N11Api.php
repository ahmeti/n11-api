<?php

namespace Ahmeti\N11Api\Cores;

class N11Api
{
    protected $actId, $actPass;

    protected function setAuth($actId = null, $actPass = null)
    {
        if($actId){
            $this->actId = $actId;
        }elseif( getenv('N11_API_KEY') ){
            $this->actId = getenv('N11_API_KEY');
        }

        if($actPass){
            $this->actPass = $actPass;
        }elseif( getenv('N11_API_SECRET') ){
            $this->actPass = getenv('N11_API_SECRET');
        }
    }

    protected function prepareRequest($data = [])
    {
        $params = [
            'auth' => [
                'appKey' => $this->actId,
                'appSecret' => $this->actPass
            ]
        ];

        if( is_array($data) ){
            $params = array_merge($params, $data);
        }

        return $params;
    }
}