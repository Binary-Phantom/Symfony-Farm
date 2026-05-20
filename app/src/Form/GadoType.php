<?php

namespace App\Form;

use App\Entity\Fazenda;
use App\Entity\Gado;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('codigo')
            ->add('leiteSemana')
            ->add('racaoSemana')
            ->add('peso')
            ->add('nascimento')
            ->add('abatido')
            ->add('fazenda', EntityType::class, [

    'class' => Fazenda::class,

    'choice_label' => function (Fazenda $fazenda) {

        return $fazenda->getNome()
            . ' - Resp: '
            . $fazenda->getResponsavel();

    },

    'placeholder' => 'Selecione uma fazenda',

    'attr' => [

        'class' => 'form-select'

    ]

])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gado::class,
        ]);
    }
}
