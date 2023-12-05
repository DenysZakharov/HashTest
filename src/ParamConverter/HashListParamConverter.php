<?php

namespace App\ParamConverter;

use App\Entity\Hash;
use App\Repository\HashRepository;
use App\Response\HashResponseBody;
use App\Validators\Exception\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class HashListParamConverter implements ParamConverterInterface
{
    public function __construct(
        private HashRepository $hashRepository
    ) {
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $hashcode = $request->attributes->get('hashcode');

        $hashData = $this->hashRepository->findByHashCode($hashcode);

        if (!$hashData->valid()) {
            throw new EntityNotFoundException(404, sprintf('Hash with hashcode %s not found!', $hashcode));
        }
        $isFirst = true;
        $hashResponseData = new HashResponseBody();

        /** @var Hash $hashObject */
        foreach ($hashData as $hashObject) {
            if ($isFirst) {
                $hashResponseData->setItem($hashObject->getData());
            } else {
                $hashResponseData->setCollision($hashObject->getData());
            }
            $isFirst = false;
        }
        $request->attributes->set($configuration->getName(), $hashResponseData);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return HashResponseBody::class === $configuration->getClass();
    }
}
