<?php

declare(strict_types=1);

namespace App\Tests\Validation\Constraint;

use App\Validation\Constraint\PosterExists;
use App\Validation\Constraint\PosterExistsValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @covers \App\Validation\Constraint\PosterExistsValidator
 *
 * @uses \App\Validation\Constraint\PosterExists
 */
final class PosterExistsValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): PosterExistsValidator
    {
        return new PosterExistsValidator();
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new PosterExists());

        $this->assertNoViolation();
    }

    public function testPosterExists()
    {
        $this->validator->validate('eva.png', new PosterExists());

        $this->assertNoViolation();
    }

    public function testPosterDoesNotExists()
    {
        $this->validator->validate('fake.png', new PosterExists());

        $this->buildViolation('{{ filename }} was not found on the system.')
             ->setParameter('{{ filename }}', 'fake.png')
             ->assertRaised();
    }
}
