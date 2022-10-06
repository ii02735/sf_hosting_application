<?php

namespace App\Validator\OrderServer;

use App\Entity\Software;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueWebServerSoftwareValidator extends ConstraintValidator
{
    /**
     * @param Software[] $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if(empty($value))
            return;

        $webServerSoftwaresTaken = array_filter($value,function(Software $software)
        {
            return $software->getSoftwareType() === 'Web Server';
        });

        if(empty($webServerSoftwaresTaken) || count($webServerSoftwaresTaken) === 1)
            return;

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ webServerSoftware }}', array_shift($webServerSoftwaresTaken)->getName())
            ->addViolation();
    }
}
