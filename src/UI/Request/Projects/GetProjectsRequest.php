<?php

declare(strict_types=1);

namespace App\UI\Request\Projects;

use App\UI\Request\AbstractRequest;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\MissingOptionsException;

final class GetProjectsRequest extends AbstractRequest
{
    /**
     * @throws InvalidOptionsException
     * @throws MissingOptionsException
     * @throws ConstraintDefinitionException
     */
    protected function validationRules(): Assert\Collection
    {
        return new Assert\Collection([]);
    }
}
