<?php

namespace App\Http\Controllers;

class BaseController extends Controller
{
    /**
     * @param mixed $data
     * @param int $code
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnData($data, $code = 200, $message = 'ok')
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * 抛出字段验证异常
     * @param $validator
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorBadRequest($validator)
    {
        $result = [];
        /* @var $validator \Illuminate\Validation\Validator */
        $messages = $validator->errors()->toArray();
        if ($messages) {
            foreach ($messages as $field => $errors) {
                foreach ($errors as $error) {
                    $result[] = [
                        'field' => $field,
                        'code' => $error,
                    ];
                }
            }
        }
        return $this->returnData($result,400,'出现错误了');
    }

}
