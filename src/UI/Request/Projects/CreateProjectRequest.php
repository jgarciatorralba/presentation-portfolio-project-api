<?php

declare(strict_types=1);

namespace App\UI\Request\Projects;

use App\UI\Request\AbstractRequest;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateProjectRequest extends AbstractRequest
{
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
                    'protocols' => ['https']
                ]),
                new Assert\Regex([
                    'pattern' => '/github\.com/'
                ])
            ]),
            'homepage' => new Assert\Optional([
                new Assert\NotBlank(),
                new Assert\Url([
                    'protocols' => ['https']
                ])
            ]),
            'archived' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Type('boolean'),
            ]),
            'lastPushedAt' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\DateTime([
                    'format' => DateTimeInterface::ATOM
                ]),
            ])
        ]);
    }
}
