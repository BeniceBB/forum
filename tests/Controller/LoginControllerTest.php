<?php

namespace App\Tests\Controller;

use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    #[NoReturn] public function testLoginPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'For http status code 500, enable csrf-protection in framewrok.yaml (conflicts with testSearchFromNoResults in BlogControllerTest)');
        self::assertSelectorTextContains('h1', 'Login');
    }
}
