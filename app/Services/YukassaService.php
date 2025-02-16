<?php

namespace App\Services;

use YooKassa\Client;
use YooKassa\Common\Exceptions\ApiConnectionException;
use YooKassa\Common\Exceptions\ApiException;
use YooKassa\Common\Exceptions\AuthorizeException;
use YooKassa\Common\Exceptions\BadApiRequestException;
use YooKassa\Common\Exceptions\ExtensionNotFoundException;
use YooKassa\Common\Exceptions\ForbiddenException;
use YooKassa\Common\Exceptions\InternalServerError;
use YooKassa\Common\Exceptions\NotFoundException;
use YooKassa\Common\Exceptions\ResponseProcessingException;
use YooKassa\Common\Exceptions\TooManyRequestsException;
use YooKassa\Common\Exceptions\UnauthorizedException;

class YukassaService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuth(
            config('yukassa.shop_id'),
            config('yukassa.secret_key')
        );
    }

    /**
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws AuthorizeException
     * @throws InternalServerError
     * @throws ForbiddenException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     * @throws ApiConnectionException
     */
    public function createPayment($amount, $description, $transactionId, $adId, $promotionId)
    {
        $payment = $this->client->createPayment([
            'amount' => [
                'value' => $amount,
                'currency' => 'RUB',
            ],
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => route('my_ads'),
            ],
            'capture' => true,
            'description' => $description,
            'metadata' => [
                'transaction_id' => $transactionId,
                'ad_id' => $adId,
                'promotion_id' => $promotionId
            ]
        ], uniqid('',true));

        return $payment;
    }
}
