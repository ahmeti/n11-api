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
                $_discount = null;

    protected   $_category, $_images, $_stockItems, $_attributes = [];
}