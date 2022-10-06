<?php

namespace App\Entity;

use App\Repository\OptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OptionRepository::class)
 * @ORM\Table(name="`option`")
 */
class Option extends Product
{

    public function __construct(string $name)
    {
        $this->setName($name);
        parent::__construct("MONTHLY");
    }
}
