<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Model\Sign;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Class RoundType
 * single round sign selection form
 *
 * @package AppBundle\Form
 */
class RoundType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('game_identifier', HiddenType::class, array(
                    'data' => $options['game_identifier']
                )
            )
            ->add('sign', ChoiceType::class, array(
                'expanded' => true,
                'multiple' => false,
                'choices'  => array(
                    Sign::ROCK => Sign::ROCK,
                    Sign::PAPER => Sign::PAPER,
                    Sign::SCISSORS => Sign::SCISSORS,
                    Sign::SPOCK => Sign::SPOCK,
                    Sign::LIZARD => Sign::LIZARD,
                ),
            ))
            ->add('save', SubmitType::class, array(
                    'label' => 'Play'
                )
            );

        $builder->get('sign')
            ->addModelTransformer(new CallbackTransformer(
                    function ($originalSign) {
                        return $originalSign;
                    },
                    function ($submittedSign) {
                        $sign = new Sign($submittedSign);

                        return $sign;
                    }
                )
            );

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event)  use ($options) {
                if (true === $options['auto_mode']) {
                    $form = $event->getForm();
                    $config = $form->get('sign')->getConfig();
                    $options = $config->getOptions();
                    $form->add('sign', HiddenType::class);
                }

            });

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
                'game_identifier',
            ));
        $resolver->setDefault('auto_mode', 0);

    }
}
