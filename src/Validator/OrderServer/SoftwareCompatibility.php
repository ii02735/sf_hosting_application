<?php

namespace App\Validator\OrderServer;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SoftwareCompatibility extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Le serveur que vous avez choisi est de type {{ serverType }} : vous ne pouvez donc pas prendre de logiciel';

    public function getTargets(): array|string
    {
        return self::CLASS_CONSTRAINT;
    }
}
