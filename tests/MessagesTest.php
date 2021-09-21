<?php

namespace TestMonitor\Teams\Tests;

use Mockery;
use TestMonitor\Teams\Client;
use PHPUnit\Framework\TestCase;
use TestMonitor\Teams\Resources\SimpleCard;
use TestMonitor\Teams\Exceptions\NotFoundException;
use TestMonitor\Teams\Exceptions\ValidationException;
use TestMonitor\Teams\Exceptions\FailedActionException;
use TestMonitor\Teams\Exceptions\UnauthorizedException;

class MessagesTest extends TestCase
{
    /**
     * @var string
     */
    protected $webhookUrl;

    protected function setUp(): void
    {
        parent::setUp();

        $this->webhookUrl = 'https://webhook.url';
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_can_post_a_message()
    {
        // Given
        $teams = new Client($this->webhookUrl);

        $teams->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(1);

        $card = new SimpleCard(['title' => 'Hello', 'text' => 'World']);

        // When
        $result = $teams->postMessage($card);

        // Then
        $this->assertTrue($result);
    }

    /** @test */
    public function it_should_throw_an_unauthorized_exception_when_client_lacks_authorization_to_post_a_message()
    {
        // Given
        $teams = new Client($this->webhookUrl);

        $teams->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(403);
        $response->shouldReceive('getBody')->andReturn('');

        $this->expectException(UnauthorizedException::class);

        $card = new SimpleCard(['title' => 'Hello', 'text' => 'World']);

        // When
        $result = $teams->postMessage($card);
    }

    /** @test */
    public function it_should_throw_a_not_found_exception_when_client_cannot_reach_teams_to_post_a_message()
    {
        // Given
        $teams = new Client($this->webhookUrl);

        $teams->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(404);

        $this->expectException(NotFoundException::class);

        $card = new SimpleCard(['title' => 'Hello', 'text' => 'World']);

        // When
        $teams->postMessage($card);
    }

    /** @test */
    public function it_should_throw_a_validation_exception_when_client_sends_a_incomplete_request()
    {
        // Given
        $teams = new Client($this->webhookUrl);

        $teams->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(422);
        $response->shouldReceive('getBody')->andReturn(json_encode(['foo' => 'bar']));

        $this->expectException(ValidationException::class);

        $card = new SimpleCard(['title' => 'Hello', 'text' => 'World']);

        // When
        $teams->postMessage($card);
    }

    /** @test */
    public function it_should_throw_a_failed_action_exception_when_client_sends_a_bad_request()
    {
        // Given
        $teams = new Client($this->webhookUrl);

        $teams->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(400);
        $response->shouldReceive('getBody')->andReturn('');

        $this->expectException(FailedActionException::class);

        $card = new SimpleCard(['title' => 'Hello', 'text' => 'World']);

        // When
        $teams->postMessage($card);
    }
}
