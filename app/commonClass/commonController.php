<?php

namespace App\commonClass;

use Auth;
trait commonController
{
    /**
     * checkIdExistOrNotController
     * This method takes a arry...
     *
     * @param  array $data
     * 
     * @return false or object
     */
    public function checkIdExistOrNotCommon(array $data)
    {
        if (!isset($data['id'])) {
            return response()->json([
                "message" => "Invalid request"
            ], 422);
        }
        return null;
    }
    /**
     * checkProductIdExistOrNotController
     * This method takes a arry...
     *
     * @param  array $data
     * 
     * @return false or object
     */
    public function checkProductIdExistOrNotCommon(array $data)
    {
        if (!isset($data['product_id'])) {
            return response()->json([
                "message" => "Invalid request"
            ], 422);
        }
        return null;
    }


}