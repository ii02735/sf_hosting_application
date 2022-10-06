<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Server;
use App\Repository\OptionRepository;
use App\Repository\ServerRepository;
use App\Repository\SoftwareRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderServerType extends AbstractType
{
    private OptionRepository $optionRepository;
    private ServerRepository $serverRepository;
    private SoftwareRepository $softwareRepository;

    public function __construct(OptionRepository $optionRepository, ServerRepository $serverRepository, SoftwareRepository $softwareRepository)
    {
        $this->optionRepository = $optionRepository;
        $this->serverRepository = $serverRepository;
        $this->softwareRepository = $softwareRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choiceLabelCallback = function(Product $product): string
        {
            return $product->getName();
        };

        $builder
            ->add('server', ChoiceType::class, [
                'choices' => $this->serverRepository->findAll(),
                'choice_value' => 'reference',
                'choice_label' => $choiceLabelCallback,
                'group_by' => function(Server $product) {
                    return $product->getServerType() === 'SHARED' ? 'Mutualisé' : $product->getServerType();
                },
                'label' => 'Serveur',
            ])
            ->add('softwares',ChoiceType::class, [
                'choices' => $this->softwareRepository->findAll(),
                'choice_value' => 'reference',
                'choice_label' => $choiceLabelCallback,
                'multiple' => true,
                'expanded' => true,
                'empty_data' => [],
                'label' => 'Logiciels',
            ])
            ->add('options',ChoiceType::class, [
                'choices' => $this->optionRepository->findAll(),
                'choice_value' => 'reference',
                'choice_label' => $choiceLabelCallback,
                'multiple' => true,
                'expanded' => true,
                'empty_data' => []
            ])
            ->add('continue',SubmitType::class, [
                'label' => 'Poursuivre'
            ])
        ;
    }
}
