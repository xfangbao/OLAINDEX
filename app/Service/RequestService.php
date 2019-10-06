<?php
/**
 * This file is part of the wangningkai/OLAINDEX.
 * (c) wangningkai <i@ningkai.wang>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Service;

use Curl\Curl;
use App\Service\Core\Constants;
use ErrorException;
use Log;

class RequestService
{
    /**
     * @var string $accessToken
     */
    protected $accessToken = '';

    /**
     * @var string $baseUrl
     */
    protected $baseUrl = '';

    /**
     * @var string $apiVersion
     */
    protected $apiVersion = '';

    /**
     * The endpoint to call
     *
     * @var string string
     */
    protected $endpoint = '';

    /**
     * An array of headers to send with the request
     *
     * @var array $headers
     */
    protected $headers = [];

    /**
     * The body of the request (optional)
     *
     * @var string $requestBody
     */
    protected $requestBody;

    /**
     * The type of request to make ("GET", "POST", etc.)
     *
     * @var string $requestType
     */
    protected $requestType = '';

    /**
     * The timeout, in seconds
     *
     * @var string
     */
    protected $timeout;

    /**
     * @var $response
     */
    protected $response;

    /**
     * @var $responseHeaders
     */
    protected $responseHeaders;

    /**
     * @var $responseError
     */
    protected $responseError;

    /**
     * @var bool $error
     */
    public $error = false;

    /**
     * 构造 microsoft graph 请求
     * @param string $method
     * @param mixed $param
     * @param bool $needToken
     * @param string|mixed $attachBody
     * @return RequestService
     * @throws ErrorException
     */
    public function request(string $method, $param, $needToken = true, $attachBody = ''): RequestService
    {
        if (is_array($param)) {
            [$endpoint, $requestHeaders, $timeout] = $param;
            $this->endpoint = $endpoint;
            $this->headers = $requestHeaders ?? $this->headers;
            $this->requestBody = $attachBody ?? '';
            $this->timeout = $timeout ?? Constants::DEFAULT_TIMEOUT;
        } else {
            $this->endpoint = $param;
            $this->timeout = Constants::DEFAULT_TIMEOUT;
            $this->requestBody = $attachBody ?? '';
        }

        $headers = $this->headers;
        $this->headers = array_merge([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken,
        ], $headers);

        if (!$needToken) {
            unset($this->headers['Authorization']);
        }

        if (stripos($this->endpoint, 'http') !== 0) {
            $this->endpoint = $this->apiVersion . $this->endpoint;
        }
        $this->requestType = strtoupper($method);
        $options = [
            CURLOPT_CUSTOMREQUEST => $this->requestType,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_ENCODING => 'gzip,deflate',
        ];
        if ($this->requestBody) {
            $options = array_add($options, CURLOPT_POST, true);
            $options = array_add(
                $options,
                CURLOPT_POSTFIELDS,
                $this->requestBody
            );
        }
        if ($this->baseUrl) {
            $curl = new Curl($this->baseUrl);
        } else {
            $curl = new Curl();
        }
        $curl->setUserAgent('ISV|OLAINDEX|OLAINDEX/9.9.9');
        $curl->setHeaders($this->headers);
        $curl->setRetry(Constants::DEFAULT_RETRY);
        $curl->setConnectTimeout(Constants::DEFAULT_CONNECT_TIMEOUT);
        $curl->setTimeout((int)$this->timeout);
        $curl->setUrl($this->endpoint);
        $curl->setOpts($options);
        $curl->exec();
        $curl->close();
        if ($curl->error) {
            Log::error(
                'Get OneDrive source content error.',
                [
                    'errno' => $curl->errorCode,
                    'message' => $curl->errorMessage,
                    'statusCode' => $curl->httpStatusCode,
                    'responseHeaders' => $curl->responseHeaders
                ]
            );
            $this->responseError = collect([
                'errno' => $curl->errorCode,
                'message' => $curl->errorMessage,
                'statusCode' => $curl->httpStatusCode,
            ])->toJson();
            $this->error = true;
        }
        $this->responseHeaders = collect($curl->responseHeaders)->toJson();
        $this->response = collect($curl->response)->toJson();
        return $this;
    }

    /**
     * @param $accessToken
     *
     * @return $this
     */
    public function setAccessToken($accessToken): self
    {
        $this->accessToken = $accessToken;
        $this->headers['Authorization'] = 'Bearer ' . $this->accessToken;
        return $this;
    }

    /**
     * @param $baseUrl
     *
     * @return $this
     */
    public function setBaseUrl($baseUrl): self
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * @param $version
     *
     * @return $this
     */
    public function setApiVersion($version): self
    {
        $this->apiVersion = $version;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @return mixed
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return mixed
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    /**
     * @return mixed
     */
    public function getResponseError()
    {
        return $this->responseError;
    }
}
