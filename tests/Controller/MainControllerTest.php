<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{

    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testButtonIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $client->clickLink('Create post');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testDeleteLink(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $client->clickLink('Delete');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testDeleteLinkAuthenticated(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('Admin');
        $client->loginUser($testUser);

        $client->request('GET', '/');
        $client->clickLink('Delete');
        $this->assertResponseIsSuccessful('', $client->getResponse()->getStatusCode());
    }

    public function testView(): void
    {
        $client = static::createClient();
        $client->request('GET', '/view/1');
        self::assertPageTitleSame('Post', $client->getCrawler()->innerText());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testViewUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/viewUser/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreate(): void
    {
        $client = static::createClient();
        $client->request('GET', '/create');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testCreateAuthenticated(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('Admin');
        $client->loginUser($testUser);

        $client->request('GET', '/create');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}