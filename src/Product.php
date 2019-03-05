<?php

namespace Ahmeti\N11Api;

use Ahmeti\N11Api\Cores\ProductCore;
use GuzzleHttp\Client;

class Product extends ProductCore
{
    public function __construct($apiKey = null, $apiSecret = null)
    {
        parent::setAuth($apiKey, $apiSecret);
    }

    public function productSellerCode($text)
    {
        $this->_productSellerCode = $text;
        return $this;
    }

    public function title($text)
    {
        $this->_title = $text;
        return $this;
    }

    public function subtitle($text)
    {
        $this->_subtitle = $text;
        return $this;
    }

    public function description($text)
    {
        $this->_description = $text;
        return $this;
    }

    public function category($id, $name = null)
    {
        array_push($this->_category, ['id' => $id, 'name' => $name]);
        return $this;
    }

    public function specialProductInfoList($text)
    {
        $this->_specialProductInfoList = $text;
        return $this;
    }

    public function displayPrice($decimal)
    {
        $this->_displayPrice = $decimal;
        return $this;
    }

    public function price($decimal)
    {
        $this->_price = $decimal;
        return $this;
    }

    public function currencyType($int)
    {
        $this->_currencyType = $int;
        return $this;
    }

    public function preparingDay($int)
    {
        $this->_preparingDay = $int;
        return $this;
    }

    public function saleStartDate($date)
    {
        $this->_saleStartDate = $date;
        return $this;
    }

    public function saleEndDate($date)
    {
        $this->_saleEndDate = $date;
        return $this;
    }

    public function productCondition($int)
    {
        $this->_productCondition = $int;
        return $this;
    }

    public function images($url, $order)
    {
        array_push($this->_images, [
            'image' => [
                'url' => $url,
                'order' => $order
            ]
        ]);
        return $this;
    }

    public function stockItems($sellerStockCode, $optionPrice, $quantity, $attributes)
    {
        array_push($this->_stockItems, [
            'stockItem' => [
                'sellerStockCode' => $sellerStockCode,
                'optionPrice' => $optionPrice,
                'quantity' => $quantity,
                'attributes' => $attributes,
            ]
        ]);
        return $this;
    }

    public function shipmentTemplate($text)
    {
        $this->_shipmentTemplate = $text;
        return $this;
    }

    public function attributes($id, $name, $value)
    {
        array_push($this->_attributes, [
            'attribute' => [
                [
                    'id' => $id,
                    'name' => $name,
                    'value' => $value
                ]
            ]
        ]);
        return $this;
    }

    public function approvalStatus($int)
    {
        $this->_approvalStatus = $int;
        return $this;
    }

    public function saleStatus($int)
    {
        $this->_saleStatus = $int;
        return $this;
    }

    public function currencyAmount($decimal)
    {
        $this->_currencyAmount = $decimal;
        return $this;
    }

    public function productionDate($date)
    {
        $this->_productionDate = $date;
        return $this;
    }

    public function expirationDate($date)
    {
        $this->_expirationDate = $date;
        return $this;
    }

    public function discount($decimal)
    {
        $this->_discount = $decimal;
        return $this;
    }

    public function save($debug = false)
    {
        $product = [
            'productSellerCode' => $this->_productSellerCode,
            'title' => $this->_title,
            'subtitle' => $this->_subtitle,
            'description' => '<![CDATA['.$this->_description.']]>',
            'category' => $this->_category,
            'specialProductInfoList' => $this->_specialProductInfoList,
            'displayPrice' => $this->_displayPrice,
            'price' => $this->_price,
            'currencyType' => $this->_currencyType,
            'preparingDay' => $this->_preparingDay,
            'saleStartDate' => $this->_saleStartDate,
            'saleEndDate' => $this->_saleEndDate,
            'productCondition' => $this->_productCondition,
            'images' => $this->_images,
            'stockItems' => $this->_stockItems,
            'shipmentTemplate' => $this->_shipmentTemplate,
            'attributes' => $this->_attributes,
            'approvalStatus' => $this->_approvalStatus,
            'saleStatus' => $this->_saleStatus,
            'currencyAmount' => $this->_currencyAmount,
            'productionDate' => $this->_productionDate,
            'expirationDate' => $this->_expirationDate,
            'discount' => $this->_discount,
        ];

        $data = parent::prepareRequest([
            'product' => $product
        ]);

        if($debug){ print_r($data); exit(); }

        try {

            $xml = self::parse($data, 'sch:SaveProductRequest');

            $options = [
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'Connection' => 'keep-alive',
                    'Content-Length' => strlen($xml),
                    'Content-Type' => 'text/xml; charset=UTF8',
                    'Pragma' => 'no-cache',
                    'SOAPAction' => '',
                ],
                'body' => $xml,
            ];

            $client = new Client();
            $response = $client->post('https://api.n11.com/ws/productService/', $options);
            $xmlObject = $this->getResponse((string)$response->getBody());
            return $xmlObject->SaveProductResponse;

        }catch ( \Exception $e){
            return $e;
        }
    }
}
