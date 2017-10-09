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
}
