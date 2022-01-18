<?php

namespace App\Tests\Controller;

use App\DataFixtures\BlogFixtures;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BlogControllerTest extends WebTestCase
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

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('Admin');
        $client->loginUser($testUser);

        $client->request('GET', '/');
        $client->clickLink('Nieuw bericht');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testView(): void
    {
        $client = static::createClient();
        $client->request('GET', '/view/1')->innerText();
        $this::assertStringContainsString('Lorem ipsum', $client->getResponse()->getContent());
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
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

    public function testCreateForm(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('Admin');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/create');

        $form = $crawler->selectButton('Opslaan')->form();
        $form['blog_form[title]']->setValue('Test Title');
        $form['blog_form[body]']->setValue('testbody');
        $form['blog_form[shortDescription]']->setValue('testshortDescription');

        $client->submit($form);
        $client->followRedirect();

        $this->assertStringContainsString('Test Title', $client->getResponse()->getContent());
    }

    public function testDeleteLinkAuthenticatedEN(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('Admin');
        $client->loginUser($testUser);

        $client->request('GET', '/en');
        $client->clickLink('Delete');
        $client->followRedirect();
        $this->assertStringContainsString('Post is deleted', $client->getResponse()->getContent());
    }

        public function testDeleteLinkAuthenticated(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('Admin');
        $client->loginUser($testUser);

        $client->request('GET', '/');
        $client->clickLink('Verwijder');
        $client->followRedirect();
        $this->assertStringContainsString('Bericht is verwijderd', $client->getResponse()->getContent());
    }
}