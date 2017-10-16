<?php

namespace Loevgaard\Pakkelabels;

use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testConstructor()
    {
        $client = new Client('username', 'password');
        $this->assertSame('username', $client->getUsername());
        $this->assertSame('password', $client->getPassword());
    }

    public function testGettersSetters()
    {
        $httpClient = new \GuzzleHttp\Client();
        $defaultOptions = [
            'test' => 'test'
        ];

        $client = new Client('u', 'p');
        $client->setUsername('username')
            ->setPassword('password')
            ->setBaseUrl('baseurl')
            ->setHttpClient($httpClient)
            ->setDefaultOptions($defaultOptions)
        ;

        $this->assertSame('username', $client->getUsername());
        $this->assertSame('password', $client->getPassword());
        $this->assertSame('baseurl', $client->getBaseUrl());
        $this->assertSame($httpClient, $client->getHttpClient());
        $this->assertSame($defaultOptions, $client->getDefaultOptions());
    }

    public function testNoDefaultOptions()
    {
        $client = new Client('u', 'p');
        $this->assertSame([], $client->getDefaultOptions());
    }

    public function testNoHttpClient()
    {
        $client = new Client('u', 'p');
        $this->assertInstanceOf(\GuzzleHttp\ClientInterface::class, $client->getHttpClient());
    }

    public function testDoRequest()
    {
        $client = new Client('u', 'p');
        $res = $client->doRequest('get', '/test');
        $this->assertTrue(is_array($res));
    }
}
