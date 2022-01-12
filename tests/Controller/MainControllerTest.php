<?php

namespace App\Tests\Controller;

use App\DataFixtures\AppFixtures;
use App\DataFixtures\AppUserFixtures;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{

    public function testView()
    {
        $client = static::createClient();
        $client->request('GET', '/view/1');
        $this->assertPageTitleSame('Post', $client->getCrawler()->innerText());
    }

    public function testButtonBlogList()
    {
        $client = static::createClient();
        $client->request('Get', '/');
        $client->clickLink('Create post');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testCreateNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET', '/create');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testCreateLoggedIn()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('Admin');
        $client->loginUser($testUser);

        $client->request('GET', '/create');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}