<?php

namespace App\Controller;

use App\Entity\Hash;
use App\Response\HashResponseBody;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class HashController extends AbstractController
{
    /**
     * @param Serializer $serializer
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
    ) {
    }

    #[Route('/hash/{hashcode}', methods: ['GET'])]
    public function get(HashResponseBody $hashResponseBody): JsonResponse
    {
        $group = ($hashResponseBody->isCollision()) ? 'show_all' : 'show_item';
        $hashResponseBody = $this->serializer->normalize(
            $hashResponseBody,
            null,
            [AbstractNormalizer::GROUPS => $group]
        );

        return new JsonResponse($hashResponseBody);
    }

    #[Route('/hash', methods: ['POST'])]
    public function post(Hash $hash): JsonResponse
    {
        $this->entityManager->persist($hash);
        $this->entityManager->flush();

        return new JsonResponse(['hash' => $hash->getHashcode()]);
    }
}
