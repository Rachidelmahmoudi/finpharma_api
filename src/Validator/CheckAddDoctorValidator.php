<?php

namespace App\Validator;

use App\Entity\Doctor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

final class CheckAddDoctorValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var CheckAddDoctor $constraint */
        if (!$value) {
            return;
        }

        $validator = Validation::createValidator();
        $violations = $validator->validate($value, [
            new Assert\Valid(),
        ]);
        // TODO: implement the validation here
        if (0 !== count($violations)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('Les informations invalid', '')
            ->addViolation();
        }
    }
}
