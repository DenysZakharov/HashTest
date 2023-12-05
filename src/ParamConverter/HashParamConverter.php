<?php

namespace App\ParamConverter;

use App\Entity\Hash;
use App\Validators\Exception\EntityValidationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HashParamConverter implements ParamConverterInterface
{
    public function __construct(
        private ValidatorInterface $validator
    ) {
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $data = json_decode((string) $request->getContent(), true);

        if (empty($data)) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Wrong JSON format');
        }

        $data = $data['data'] ?? '';

        $hash = (new Hash())
            ->setData($data)
            ->setHashcode(sha1($data));

        /** @var ConstraintViolationList $paramValidationErrors */
        $paramValidationErrors = $this->validator->validate($hash);

        if (count($paramValidationErrors) > 0) {
            throw new EntityValidationException(400, (string) json_encode((string) $paramValidationErrors));
        }

        $request->attributes->set($configuration->getName(), $hash);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return Hash::class === $configuration->getClass();
    }
}
