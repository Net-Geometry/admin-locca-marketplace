<?php

namespace App\Services;

use App\CentralLogics\Helpers;

class NotificationService
{

    public function getAddData(Object $request): array
    {
        if ($request->has('image')) {
            $image_name = Helpers::upload('notification/', 'png', $request->file('image'));
        } else {
            $image_name = null;
        }
        return [
            'title' => $request->notification_title,
            'description' => $request->description,
            'image' => $image_name,
            'tergat' => $request->tergat,
            'status' => 1,
            'zone_id' => $request->zone=='all'?null:$request->zone,
        ];
    }

    public function getTopic(Object $request): string
    {
        $topic_all_zone =[
            'customer'=>'all_zone_customer',
            'deliveryman'=>'all_zone_delivery_man',
            'store'=>'all_zone_store',
        ];

        $topic_zone_wise=[
            'customer'=>'zone_'.$request->zone.'_customer',
            'deliveryman'=>'zone_'.$request->zone.'_delivery_man',
            'store'=>'zone_'.$request->zone.'_store',
        ];

        return $request->zone == 'all'?$topic_all_zone[$request->tergat]:$topic_zone_wise[$request->tergat];
    }


}
