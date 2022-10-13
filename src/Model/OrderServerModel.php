<?php

namespace App\Model;

use App\Entity\Option;
use App\Entity\Product;
use App\Entity\Server;

use App\Validator\OrderServer as OrderServerAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @Assert\GroupSequence({"First","OrderServerModel","After"})
 * @OrderServerAssert\SoftwareCompatibility
 */
class OrderServerModel
{
    /**
     * @var Server
     * @Assert\NotNull(message = "Vous devez choisir un serveur", groups={"First"})
     */
    private Server $server;

    /**
     * @Assert\Sequentially({
     *      @Assert\Count(max = 5, maxMessage = "Vous pouvez choisir jusqu'à 5 logiciels seulement"),
     *      @OrderServerAssert\UniqueWebServerSoftware(groups={"After"})
     * })
     * @var Product[] $softwares
     */
    private array $softwares = [];

    /**
     * @var Product[] $options
     */
    private array $options = [];

    /**
     * @return Server
     */
    public function getServer(): Server
    {
        return $this->server;
    }

    /**
     * @param Server $server
     * @return OrderServerModel
     */
    public function setServer(Server $server): self
    {
        $this->server = $server;
        return $this;
    }

    /**
     * @return array
     */
    public function getSoftwares(): array
    {
        return $this->softwares;
    }

    /**
     * @param array $softwares
     * @return OrderServerModel
     */
    public function setSoftwares(array $softwares): self
    {
        $this->softwares = $softwares;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return OrderServerModel
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    public function getServerType(): string
    {
        return $this->server->getServerType();
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if($this->getServerType() !== 'SHARED' && count($this->softwares) > 0)
            $context->buildViolation('Le serveur que vous avez choisi est de type {{ serverType }} : vous ne pouvez donc pas prendre de logiciel')
            ->setParameter('{{ serverType }}', $this->getServerType())
            ->addViolation();
    }


}