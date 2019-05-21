<?php


namespace App\Utils;

use League\Fractal\Serializer\ArraySerializer;

class ResponseSerializer extends ArraySerializer
{
    public function collection($resourceKey, array $data)
    {
        return ['data' => $data, 'status_code' => 200, 'message' => 'success'];
    }

    public function item($resourceKey, array $data)
    {
        return ['data' => $data, 'status_code' => 200, 'message' => 'success'];
    }

}
