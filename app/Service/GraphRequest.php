<?php

namespace App\Service;

use App\Utils\CoreConstants;
use Curl\Curl;
use Illuminate\Support\Arr;

class GraphRequest
{

    /**
     * @var $baseUrl
     */
    protected $baseUrl;

    /**
     * @var $apiVersion
     */
    protected $apiVersion;

    /**
     * The endpoint to call
     *
     * @var string
     */
    protected $endpoint;

    /**
     * An array of headers to send with the request
     *
     * @var array(string => string)
     */
    protected $headers;

    /**
     * @var $accessToken
     */
    protected $accessToken;

    /**
     * The body of the request (optional)
     *
     * @var string
     */
    protected $requestBody;

    /**
     * The type of request to make ("GET", "POST", etc.)
     *
     * @var object
     */
    protected $requestType;

    /**
     * The timeout, in seconds
     *
     * @var string
     */
    protected $timeout;

    /**
     * @var $response
     */
    protected $response = null;

    /**
     * @var $responseHeaders
     */
    protected $responseHeaders = null;

    /**
     * @var $responseError
     */
    protected $responseError = null;

    /**
     * 构造 microsoft graph 请求
     * @param      $method
     * @param      $params
     * @param null $token
     *
     * @return $this
     * @throws \ErrorException
     */
    public function request($method, $params, $token = null)
    {
        if (is_array($params)) {
            @list($endpoint, $requestBody, $requestHeaders, $timeout) = $params;
            $this->requestBody = $requestBody ?? '';
            $this->headers = $requestHeaders ?? [];
            $this->timeout = $timeout ?? CoreConstants::DEFAULT_TIMEOUT;
            $this->endpoint = $endpoint;
        } else {
            $this->endpoint = $params;
            $this->headers = [];
            $this->timeout = CoreConstants::DEFAULT_TIMEOUT;
        }
        if (!$token) {
            $this->headers = array_merge([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->accessToken,
            ], $this->headers);
            if (stripos($this->endpoint, "http") !== 0) {
                $this->endpoint = $this->apiVersion . $this->endpoint;
            }
        }
        $this->requestType = strtoupper($method);
        $options = [
            CURLOPT_CUSTOMREQUEST => $this->requestType,
            //            CURLOPT_HEADER => true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_ENCODING => 'gzip,deflate',
        ];
        if ($this->requestBody) {
            $options = Arr::add($options, CURLOPT_POST, true);
            $options = Arr::add(
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
        $curl->setRetry(CoreConstants::DEFAULT_RETRY);
        $curl->setConnectTimeout(CoreConstants::DEFAULT_CONNECT_TIMEOUT);
        $curl->setTimeout((int)$this->timeout);
        $curl->setUrl($this->endpoint);
        $curl->setOpts($options);
        $curl->exec();
        $curl->close();
        if ($curl->error) {
            \Log::error(
                'OneDrive Source Request Error.',
                [
                    'errno' => $curl->errorCode,
                    'msg' => $curl->errorMessage,
                ]
            );
            $this->responseError = collect([
                'errno' => $curl->errorCode,
                'msg' => $curl->errorMessage,
            ])->toJson();

            return $this;
        } else {
            $this->responseHeaders = collect($curl->responseHeaders)->toJson();
            $this->response = collect($curl->response)->toJson();

            return $this;
        }
    }

    /**
     * @param $accessToken
     *
     * @return $this
     */
    public function setAccessToken($accessToken)
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
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * @param $version
     *
     * @return $this
     */
    public function setApiVersion($version)
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
