<?php

namespace Ahmeti\N11Api;

class N11Core
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



    protected function parse($arr, $tag = null)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        self::recursiveParser($dom,$arr,$dom);
        $xml = trim(str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $dom->saveXML()));

        $request =
            '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" '.
            'xmlns:sch="http://www.n11.com/ws/schemas">'.
            '<soapenv:Header/>'.
            '<soapenv:Body>'.
            '@@content@@'.
            '</soapenv:Body>'.
            '</soapenv:Envelope>';

        $data = '';

        if ($tag){
            $data .= '<'.$tag.'>';
        }

        $data .= $xml;

        if ($tag){
            $data .= '</'.$tag.'>';
        }

        $data = str_replace('@@content@@', $data, $request);

        return $data;
    }

    protected function recursiveParser(&$root, $arr, &$dom)
    {
        foreach($arr as $key => $item){
            if(is_array($item) && !is_numeric($key)){
                $node = $dom->createElement($key);
                self::recursiveParser($node,$item,$dom);
                $root->appendChild($node);
            }elseif(is_array($item) && is_numeric($key)){
                self::recursiveParser($root,$item,$dom);
            }else{
                $node = $dom->createElement($key, $item);
                $root->appendChild($node);
            }
        }
    }

    protected function getResponse($responseText)
    {
        $responseText = str_ireplace(['env:', 'ns3:', ':env', ':ns3', 'xmlns=""'], '', $responseText);
        $data = simplexml_load_string($responseText);
        $data = json_decode(json_encode($data->Body));
        return $data;
    }
}