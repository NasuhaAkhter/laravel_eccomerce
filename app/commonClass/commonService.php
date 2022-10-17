<?php

namespace App\commonClass;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\User;

trait commonService
{
    /**
     * deleteMessage
     * This method takes a boolen and string  ...
     *
     * @param  bool $d
     * @param  string $str
     *
     * @return message with status 200(success)|422 (fail)
     */
    public function deleteMessage(bool $d, string $str)
    {
        if (!$d) {
            return response()->json([
                "message" => "{$str} delete failed"
            ], 422);
        }
        return response()->json([
            "message" => "{$str} has been deleted"
        ], 200);
    }
    /**
     * updateFailMessage
     * This method takes a string  ...
     *
     * @param  string $str
     *
     * @return string with status 200(success)|422 (fail)
     */
    public function updateFailMessage(string $str)
    {
        return response()->json([
            "message" => "{$str} update failed"
        ], 422);
    }
    /**
     * coustomErrorMessage
     * This method takes a string  ...
     *
     * @param  string $str
     *
     * @return string with status 422
     */
    public function coustomErrorMessage(string $str)
    {
        return response()->json([
            "message" => $str
        ], 422);
    }
    /**
     * checkKeyExistOrNot
     * This method takes a array of keys and $data  ...
     *
     * @param  arrray $keys
     * @param  arrray $data
     *
     * @return bool
     */
    public function checkKeyExistOrNot(array $keys,array $data)
    {
        foreach($keys as $key){
            if(!isset($data[$key])){
                return null;
            }
        }
        return true;
    }
    /**
     * formatedgetApiData
     * This method takes a array of keys and $data  ...
     *
     * @param  arrray $data
     *
     * @return array $data
     */
    public function formatedgetApiData(array $data)
    {
        $data['category_id'] = isset($data['category_id']) ? $data['category_id'] : 0;
        $data['franchise_id'] = isset($data['franchise_id']) ? $data['franchise_id'] : 0;
        $data['str'] = isset($data['str']) ? $data['str'] : '';
        $data['pageSize'] = isset($data['pageSize']) ? $data['pageSize'] : 10;
        $data['colName'] = isset($data['colName']) ? $data['colName'] : 'id';
        $data['order'] = isset($data['order']) ? $data['order'] : 'desc';
        $data['page'] = isset($data['page']) ? $data['page'] : 1;
        return $data;
    }
    public function sendPushNotification($orderId,$data,$userId){
        $order = Order::where('id',$orderId)->select('user_id','driver_id')->first();
        $uids=[];
        // $deviceTokens = [];
        array_push($uids,$order->user_id);
        if($order->driver_id) array_push($uids,$order->driver_id);
        $shopIds = OrderDetails::where('order_id',$orderId)->pluck('partner_shop_id');
        $shopDeviceTokens = User::whereIn('shop_id',$shopIds)->where([['appToken','!=',null],['id','!=',$userId]])->pluck('appToken');

        $deviceTokens = User::whereIn('id',$uids)->where([['appToken','!=',null],['id','!=',$userId]])->pluck('appToken');


        $allTokens = [];
        if(sizeof($shopDeviceTokens) > 0){

            foreach($shopDeviceTokens as $value){

                array_push($allTokens,$value);
            }
        }
        if(sizeof($deviceTokens) > 0){

            foreach($deviceTokens as $value){

                array_push($allTokens,$value);
            }
        }


        // return $deviceTokens;




        if(sizeof($allTokens) == 0) return;
        \Log::info('allTokens');
        \Log::info($allTokens);

        $notification = $data;
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array (
                'registration_ids' => $allTokens,
                'data' => array (
                        'title' => $data['title'],
                        "message" =>  $data,
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                ),
                'notification' => array (
                        'title' => $data['title'],
                        "body" => $data['body'],
                        'sound' => 'default',
                        "badge" => 1,
                ),
                'time_to_live' => 6000,
        );
        $fields = json_encode ( $fields );
        $headers = array (
                'Authorization: key=' . "AAAAIG73OVw:APA91bG2nCBKLsV5lDuJMDhAo1m4bzJ_QLmkVQthqwwvYLgyAGM0cr466TYaG5X0Wan-ZmMPlbznwr0PvT7j3dW0cV7ifADC4F2qOGJ82yofDYrmPVTH-tW3Eg7r0ltvN3FFbBE3U5gM ",
                'Content-Type: application/json'
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
        $result = curl_exec ( $ch );
        curl_close ( $ch );


        if($result){
            return $result;
        }
    }
    public function sendPushNotificationToUser($orderId,$data,$userId){
        $order = Order::where('id',$orderId)->select('user_id','driver_id')->first();
        $deviceTokens = User::where([['appToken','!=',null],['id',$order->user_id]])->pluck('appToken');
        $allTokens = [];
        return $deviceTokens->toArray();
        if(sizeof($deviceTokens) > 0){
            foreach($deviceTokens as $value){
                foreach($value as $vv){
                    array_push($allTokens,$vv);
                }
            }
        }

        if(sizeof($allTokens) == 0) return;
        $data["type"] = "order";
        $notification = $data;
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array (
                'registration_ids' => $allTokens,
                'data' => array (
                        'title' => $data['title'],
                        "message" =>  $data,
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',

                ),
                'notification' => array (
                        'title' => $data['title'],
                        "body" => $data['body'],
                        'sound' => 'default',
                        "badge" => 1,
                ),
                "android" => [
                    "priority" =>"high"
                ],
                "apns"=>[
                    "headers"=>[
                        "apns-priority"=>"5"
                    ]
                ],
                "webpush"=> [
                    "headers"=> [
                      "Urgency"=> "high"
                    ]
                ],
                'time_to_live' => 6000,
        );
        $fields = json_encode ( $fields );
        $headers = array (
                'Authorization: key=' . "AAAAIG73OVw:APA91bG2nCBKLsV5lDuJMDhAo1m4bzJ_QLmkVQthqwwvYLgyAGM0cr466TYaG5X0Wan-ZmMPlbznwr0PvT7j3dW0cV7ifADC4F2qOGJ82yofDYrmPVTH-tW3Eg7r0ltvN3FFbBE3U5gM ",
                'Content-Type: application/json'
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
        $result = curl_exec ( $ch );
        curl_close ( $ch );


        if($result){
            return $result;
        }
    }
    public function sendPushNotificationToDrivers($orderId,$data){
        $shopDeviceTokens = User::where([['appToken','!=',null],['userType','Driver']])->pluck('appToken');
        $allTokens = [];
        \Log::info('shopDeviceTokens drivers');
        \Log::info($shopDeviceTokens);
        if(sizeof($shopDeviceTokens) > 0){

            foreach($shopDeviceTokens as $value){

                array_push($allTokens,$value);
            }
        }
        return $allTokens;
        if(sizeof($allTokens) == 0) return;
        \Log::info('allTokens drivers');
        \Log::info($allTokens);

        $notification = $data;
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array (
                'registration_ids' => $allTokens,
                'data' => array (
                        'title' => $data['title'],
                        "message" =>  $data,
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                ),
                'notification' => array (
                        'title' => $data['title'],
                        "body" => $data['body'],
                        'sound' => 'default',
                        "badge" => 1,
                ),
                'time_to_live' => 6000,
        );
        $fields = json_encode ( $fields );
        $headers = array (
                'Authorization: key=' . "AAAAIG73OVw:APA91bG2nCBKLsV5lDuJMDhAo1m4bzJ_QLmkVQthqwwvYLgyAGM0cr466TYaG5X0Wan-ZmMPlbznwr0PvT7j3dW0cV7ifADC4F2qOGJ82yofDYrmPVTH-tW3Eg7r0ltvN3FFbBE3U5gM ",
                'Content-Type: application/json'
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
        $result = curl_exec ( $ch );
        curl_close ( $ch );

        \Log::info('resutl');
        \Log::info($result);
        if($result){
            return $result;
        }
    }
    public function sendPushNotificationToCustomersCommon($data,$deviceTokens){
        $allTokens = [];
        $deviceTokens = $deviceTokens->toArray()  ;
        // \Log::info($deviceTokens);
        if(sizeof($deviceTokens) > 0){
            foreach($deviceTokens as $value){
                $value = json_decode($value);
                // $value = $value->toArray();
                // \Log::info($value);
                foreach($value as $vv){
                    array_push($allTokens,$vv);
                }
            }
        }
        if(sizeof($allTokens) == 0) return;
        $notification = $data;
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array (
                'registration_ids' => $allTokens,
                'data' => array (
                        'title' => $data['title'],
                        "message" =>  $data,
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                ),
                'notification' => array (
                        'title' => $data['title'],
                        'body' => $data['body'],
                        'image' => $data['image'],
                        'sound' => 'default',
                        "badge" => 1,
                ),
                'time_to_live' => 6000,
        );
        $fields = json_encode ( $fields );
        $headers = array (
                'Authorization: key=' . "AAAAIG73OVw:APA91bG2nCBKLsV5lDuJMDhAo1m4bzJ_QLmkVQthqwwvYLgyAGM0cr466TYaG5X0Wan-ZmMPlbznwr0PvT7j3dW0cV7ifADC4F2qOGJ82yofDYrmPVTH-tW3Eg7r0ltvN3FFbBE3U5gM ",
                'Content-Type: application/json'
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
        $result = curl_exec ( $ch );
        curl_close ( $ch );

        // \Log::info('resutl');
        // \Log::info($result);
        if($result){
            return $result;
        }
    }


}
