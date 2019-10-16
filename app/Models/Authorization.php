<?php


namespace App\Models;

use Carbon\Carbon;

class Authorization
{
    /**
     * @var $token
     */
    protected $token;
    /**
     * @var $payload
     */
    protected $payload;

    /**
     * Authorization constructor.
     *
     * @param null $token
     */
    public function __construct($token = null)
    {
        $this->token = $token;
    }

    /**
     * @param $token
     *
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return null
     * @throws \Exception
     */
    public function getToken()
    {
        if (!$this->token) {
            throw new \Exception('请设置token');
        }
        return $this->token;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getPayload()
    {
        if (!$this->payload) {
            $this->payload = \Auth::setToken($this->getToken())->getPayload();
        }
        return $this->payload;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getExpiredAt()
    {
        return Carbon::createFromTimestamp($this->getPayload()->get('exp'))
            ->toDateTimeString();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getRefreshExpiredAt()
    {
        return Carbon::createFromTimestamp($this->getPayload()->get('iat'))
            ->addMinutes(config('jwt.refresh_ttl'))
            ->toDateTimeString();
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function user()
    {
        return \Auth::authenticate($this->getToken());
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function toArray()
    {
        return [
            'id' => hash('md5', $this->getToken()),
            'token' => 'Bearer ' . $this->getToken(),
            'expired_at' => $this->getExpiredAt(),
            'refresh_expired_at' => $this->getRefreshExpiredAt(),
        ];
    }
}
