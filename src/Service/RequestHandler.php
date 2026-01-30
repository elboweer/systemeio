<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class RequestHandler
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    )
    {
    }

    public function handle(Request $request, string $dtoClass): object
    {
        // Если json невалидный, выкидываю 500 как unexpected через подписчик
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            $dtoClass,
            'json',
        );

        $errors = $this->validator->validate($dto);

        if ($errors->count()) {
            throw new ValidationException($errors);
        }

        return $dto;
    }
}
