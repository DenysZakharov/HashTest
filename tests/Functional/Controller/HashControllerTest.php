<?php

namespace App\Tests\Functional\Controller;

use App\DataFixtures\HashFixtures;
use App\Entity\Hash;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HashControllerTest extends WebTestCase
{
    private const API_HASH_ENDPOINT = '/hash';

    protected KernelBrowser $client;
    protected EntityManagerInterface $em;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get(EntityManagerInterface::class);
        $this->em = $em;
    }

    public function testGetUniqueSuccess(): void
    {
        /** @var Hash $hash */
        $hash = $this->em->getRepository(Hash::class)->findOneBy(['data' => HashFixtures::UNIQUE_HASH]);

        $this->client->request('GET', sprintf('%s/%s', self::API_HASH_ENDPOINT, $hash->getHashcode()));
        $this->assertResponseIsSuccessful();

        $content = json_decode((string) $this->client->getResponse()->getContent(), true);

        $this->assertIsArray($content);
        $this->assertSame($hash->getData(), $content['item']);
        $this->assertArrayNotHasKey('collisions', $content);
    }

    public function testGetCollisionsSuccess(): void
    {
        /** @var Hash $hash */
        $hash = $this->em->getRepository(Hash::class)->findOneBy(['data' => HashFixtures::COLLISION_HASH]);

        $this->client->request('GET', sprintf('%s/%s', self::API_HASH_ENDPOINT, $hash->getHashcode()));
        $this->assertResponseIsSuccessful();

        $content = json_decode((string) $this->client->getResponse()->getContent(), true);

        $this->assertIsArray($content);
        $this->assertSame($hash->getData(), $content['item']);
        $this->assertSame([$hash->getData(), $hash->getData()], $content['collisions']);
    }

    public function testPostSuccess(): void
    {
        $postArray = [
            'data' => $dataValue = 'test3',
        ];
        $this->client->request('POST', self::API_HASH_ENDPOINT, [], [], [], (string) json_encode($postArray));
        $content = json_decode((string) $this->client->getResponse()->getContent(), true);

        $this->assertSame(['hash' => sha1($dataValue)], $content);
    }
}
