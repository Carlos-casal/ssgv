<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class DniNieValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof DniNie) {
            throw new UnexpectedTypeException($constraint, DniNie::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $dni = strtoupper(trim($value));

        if (!preg_match('/^(\d{8}|[XYZ]\d{7})[A-Z]$/', $dni)) {
            $this->context->buildViolation($constraint->message)->addViolation();
            return;
        }

        $numberPart = substr($dni, 0, -1);
        $letter = substr($dni, -1);

        // Replace leading X, Y, Z with numbers for calculation
        $numberPart = str_replace(['X', 'Y', 'Z'], ['0', '1', '2'], $numberPart);

        $validLetters = 'TRWAGMYFPDXBNJZSQVHLCKE';
        $expectedLetter = $validLetters[((int)$numberPart) % 23];

        if ($letter !== $expectedLetter) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}