<?php

declare(strict_types=1);

namespace App\UI\Request\Projects;

use App\Shared\Domain\Service\LocalDateTimeZoneConverter;
use App\UI\Request\AbstractRequest;
use App\UI\Validation\Validator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class CreateProjectRequest extends AbstractRequest
{
    public function __construct(
        Validator $validator,
        RequestStack $request,
        private readonly LocalDateTimeZoneConverter $dateTimeConverter
    ) {
        parent::__construct($validator, $request);
    }

    protected function validationRules(): Assert\Collection
    {
        return new Assert\Collection([
            'id' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Type('integer'),
                new Assert\Positive(),
            ]),
            'name' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Type('string'),
                new Assert\Length(max: 255),
            ]),
            'description' => new Assert\Optional([
                new Assert\NotBlank(),
                new Assert\Type('string'),
            ]),
            'topics' => new Assert\Optional([
                new Assert\NotBlank(),
                new Assert\Type('array'),
                new Assert\All([
                    new Assert\Type('string')
                ]),
            ]),
            'repository' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Url([
                    'protocols' => ['https'],
                    'requireTld' => true
                ]),
                new Assert\Regex([
                    'pattern' => '/github\.com/'
                ])
            ]),
            'homepage' => new Assert\Optional([
                new Assert\NotBlank(),
                new Assert\Url([
                    'protocols' => ['https'],
                    'requireTld' => true
                ])
            ]),
            'archived' => new Assert\Required([
                new Assert\NotNull(),
                new Assert\Type('boolean'),
            ]),
            'lastPushedAt' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\DateTime([
                    'format' => \DateTimeInterface::ATOM
                ]),
                new Assert\Callback($this->validateDateTimeIsInThePast(...))
            ])
        ]);
    }

    public function validateDateTimeIsInThePast(
        string $lastPushedAt,
        ExecutionContextInterface $context
    ): void {
        try {
            $now = new \DateTimeImmutable();
            $lastPushedAt = new \DateTimeImmutable($lastPushedAt);

            if ($this->dateTimeConverter->convert($lastPushedAt) >= $now) {
                $context->buildViolation("This value should be in the past.")
                    ->addViolation();
            }
        } catch (\DateMalformedStringException $e) {
            $context->buildViolation($e->getMessage())
                ->addViolation();
        }
    }
}
