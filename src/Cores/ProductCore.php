<?php

namespace Ahmeti\N11Api\Cores;


class ProductCore extends N11Api
{
    protected   $_productSellerCode, $_title, $_subtitle,
                $_description, $_specialProductInfoList, $_displayPrice,
                $_price, $_currencyType, $_preparingDay,
                $_saleStartDate, $_saleEndDate, $_productCondition,
                $_shipmentTemplate, $_approvalStatus, $_saleStatus,
                $_currencyAmount, $_productionDate, $_expirationDate,
                $_discount;

    protected   $_category = [];
    protected   $_images = [];
    protected   $_stockItems = [];
    protected   $_attributes = [];

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
