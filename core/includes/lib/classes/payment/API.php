<?php

namespace Payment;

class API
{

    /**
     *
     * @param \Purchase $purchase
     * @return string
     */
    public static function getCRCSend(\Purchase $purchase)
    {
        $mrhLogin = ROBOKASSA_LOGIN;
        $mrhPass1 = ROBOKASSA_PASS1;
        $invId = $purchase->id;
        $outSumm = money_format('%i', $purchase->total);
        return md5("$mrhLogin:$outSumm:$invId:$mrhPass1");
    }

    /**
     *
     * @param string $outSumm
     * @param string $invId
     * @param string $signature
     * @return boolean
     */
    public static function checkPayment($outSumm, $invId, $signature)
    {
        $mrhPass1 = ROBOKASSA_PASS1;
        $pm = new \PurchaseManager();
        $purchase = $pm->getById($invId);
        return strtoupper($signature) == strtoupper(md5("$outSumm:$invId:$mrhPass1"))
            && !empty($purchase)
            && floatval($purchase->total) == floatval($outSumm);
    }

    /**
     *
     * @param string $outSumm
     * @param string $invId
     * @param string $signature
     * @return boolean
     */
    public static function processPayment($outSumm, $invId, $signature)
    {
        $mrhPass2 = ROBOKASSA_PASS2;
        $pm = new \PurchaseManager();
        $purchase = $pm->getById($invId);
        return strtoupper($signature) == strtoupper(md5("$outSumm:$invId:$mrhPass2"))
            && !empty($purchase)
            && floatval($purchase->total) == floatval($outSumm);
    }

}
