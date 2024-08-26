<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Validation;

use App\UI\Validation\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints as Assert;

final class ValidatorTest extends TestCase
{
    private ?Validator $sut;

    protected function setUp(): void
    {
        $this->sut = new Validator();
    }

    protected function tearDown(): void
    {
        $this->sut = null;
    }

    #[DataProvider('dataValidate')]
    /**
     * @param array<string, mixed> $data
     * @param Assert\Collection $rules
     * @param array<string, string> $errors
     */
    public function testItValidatesData(array $data, Assert\Collection $rules, array $errors): void
    {
        $result = $this->sut->validate($data, $rules);
        $this->assertEquals($errors, $result);
    }

    /**
     * @return array<string, array<array<string, mixed>|Assert\Collection>>
     */
    public static function dataValidate(): array
    {
        return [
            'empty data for empty rules' => [
                [],
                new Assert\Collection([]),
                []
            ],
            'some data for empty rules' => [
                [
                    'id' => 'abcde'
                ],
                new Assert\Collection([]),
                [
                    'id' => 'This field was not expected.'
                ]
            ],
            'valid data for optional rules' => [
                [
                    'topics' => ['foo', 'bar'],
                ],
                new Assert\Collection([
                    'topics' => new Assert\Optional([
                        new Assert\NotBlank(),
                        new Assert\Type('array'),
                        new Assert\All([
                            new Assert\Type('string')
                        ]),
                    ]),
                ]),
                []
            ],
            'invalid data for mandatory rules' => [
                [
                    'repository' => 'http://www.example.com',
                ],
                new Assert\Collection([
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
                ]),
                [
                    'repository' => 'This value is not valid.'
                ]
            ],
        ];
    }
}
