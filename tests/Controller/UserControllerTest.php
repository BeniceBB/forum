<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testViewUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/view/user/Admin');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}