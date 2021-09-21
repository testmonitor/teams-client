<?php

namespace TestMonitor\Teams\Actions;

use TestMonitor\Teams\Resources\Card;
use TestMonitor\Teams\Exceptions\UnauthorizedException;

trait PostMessages
{
    /**
     * Post a new message.
     *
     * @param \TestMonitor\Teams\Resources\Card $card
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TestMonitor\Teams\Exceptions\FailedActionException
     * @throws \TestMonitor\Teams\Exceptions\NotFoundException
     * @throws \TestMonitor\Teams\Exceptions\UnauthorizedException
     * @throws \TestMonitor\Teams\Exceptions\ValidationException
     *
     * @return bool
     */
    public function postMessage(Card $card)
    {
        if (empty($this->webhookUrl)) {
            throw new UnauthorizedException();
        }

        $response = $this->post($this->webhookUrl, [
            'json' => $card->getMessage(),
        ]);

        return $response === 1;
    }
}
