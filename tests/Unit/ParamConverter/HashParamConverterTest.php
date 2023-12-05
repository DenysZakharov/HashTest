<?php

namespace App\Tests\Unit\ParamConverter;

use App\Entity\Hash;
use App\ParamConverter\HashParamConverter;
use App\Response\HashResponseBody;
use PHPUnit\Framework\TestCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HashParamConverterTest extends TestCase
{
    public function testWrongContent(): void
    {
        $attributes = $this->createMock(ParameterBag::class);

        $requestContent = 'test';
        $request = $this->createMock(Request::class);
        $request->method('getContent')->with(false)->willReturn($requestContent);
        $request->attributes = $attributes;
        $configuration = $this->createMock(ParamConverter::class);

        $validator = $this->createMock(ValidatorInterface::class);
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Wrong JSON format');

        $hashParamConverter = new HashParamConverter($validator);
        $this->assertTrue($hashParamConverter->apply($request, $configuration));
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('getSupportsParams')]
    public function testSupports(string $className, bool $expectedValue): void
    {
        $configuration = $this->createMock(ParamConverter::class);
        $configuration->expects($this->any())
            ->method('getClass')
            ->willReturn($className);

        $hashParamConverter = new HashParamConverter(
            $this->createMock(ValidatorInterface::class)
        );

        $this->assertSame($expectedValue, $hashParamConverter->supports($configuration));
    }

    /**
     * @return array<array<mixed>>
     */
    public static function getSupportsParams(): array
    {
        return [
            [Hash::class, true],
            ['', false],
            [HashResponseBody::class, false],
        ];
    }
}
