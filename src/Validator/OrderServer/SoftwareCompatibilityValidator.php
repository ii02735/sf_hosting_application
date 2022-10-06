<?php

namespace App\Validator\OrderServer;

use App\Model\OrderServerModel;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SoftwareCompatibilityValidator extends ConstraintValidator
{
    /**
     * @param OrderServerModel $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if($value->getServer() === null)
        {
            $this->context->buildViolation('Vous devez choisir un serveur pour prendre un logiciel')
                ->addViolation();
        }
        elseif($value->getServerType() !== 'SHARED' && count($value->getSoftwares()) > 0)
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ serverType }}', $value->getServerType())
                ->addViolation();
    }


}
