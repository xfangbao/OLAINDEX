<?php

namespace App\Http\Controllers;

use Dingo\Api\Routing\Helpers;
use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class BaseController extends Controller
{
    use Helpers;

    /**
     * @var $modelClass
     */
    protected $modelClass;

    /**
     * 数组数据返回
     * @param array|null $data
     * @param int $code
     * @param string $message
     * @return mixed
     */
    public function returnData($data = null, $code = 200, $message = 'success')
    {
        $response = [
            'message' => $message,
            'status_code' => $code,
            'data' => $data
        ];
        return $this->response->array($response)->setStatusCode($code);
    }

    /**
     * 抛出字段验证异常
     * @param $validator
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
        throw new ValidationHttpException($result);
    }

    /**
     * 字段验证
     * @param \Illuminate\Http\Request $request
     * @param array $rules
     */
    public function validateField($request, array $rules = [])
    {
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }
    }

}
