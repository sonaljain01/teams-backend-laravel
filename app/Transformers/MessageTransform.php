<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class MessageTransform extends TransformerAbstract
{

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($data)
    {
        $transformData = [];
        foreach ($data as $item) {
            $transformData[] = [
                "id" => $item->id,
                "message" => $item->message,
                "sender_id" => $item->sender_id,
                "sender_name" => $item->sender->name,
                "receiver_id" => $item->receiver_id,
                "receiver_name" => $item->receiver->name,
                "created_at" => $item->created_at,
                "isUpdate"=> $item->isUpdate,
                "updated_at" => $item->updated_at,
                "isDeleted" => $item->isDelete,
                "deleted_at" => $item->deletedAt,
            ];
        }
        return [
            "data" => $transformData
        ];

    }
}
