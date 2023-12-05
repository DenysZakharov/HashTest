<?php

namespace App\DataFixtures;

use App\Entity\Hash;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HashFixtures extends Fixture
{
    public const UNIQUE_HASH = 'test';
    public const COLLISION_HASH = 'test1';

    public function load(ObjectManager $manager): void
    {
        $hash = $this->createHash($manager, 'test');
        $this->addReference(self::UNIQUE_HASH, $hash);

        $hash1 = $this->createHash($manager, 'test1');
        $this->addReference(self::COLLISION_HASH, $hash1);
        $this->createHash($manager, 'test1');
        $this->createHash($manager, 'test1');

        $manager->flush();
    }

    private function createHash(ObjectManager $manager, string $hashData): Hash
    {
        $hash = new Hash();
        $hash->setData($hashData);
        $hash->setHashcode(sha1($hashData));

        $manager->persist($hash);

        return $hash;
    }
}
