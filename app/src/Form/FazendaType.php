<?php

namespace App\Form;

use App\Entity\Fazenda;
use App\Entity\Veterinario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FazendaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nome')
            ->add('tamanho')
            ->add('responsavel')
            ->add('veterinarios', EntityType::class, [

    'class' => Veterinario::class,

    'choice_label' => function (Veterinario $veterinario) {

        return $veterinario->getNome()
            . ' - CRMV: '
            . $veterinario->getCrmv();

    },

    'multiple' => true,

    'expanded' => false,

    'attr' => [

        'class' => 'form-select'

    ]

    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fazenda::class,
        ]);
    }
}
