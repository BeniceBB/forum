<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegistrationForm(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[username]']->setValue('Test');
        $form['registration_form[plainPassword]']->setValue('Testpassword');

        $client->submit($form);

        $this->assertStringContainsString('Test', $client->getResponse()->getContent());
    }
}