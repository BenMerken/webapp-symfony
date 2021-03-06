<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('plaintextPassword', PasswordType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Moderator' => 'ROLE_MOD',
                    'Custodian' => 'ROLE_CUSTODIAN'
                ],
            ])
            ->get('roles')->addModelTransformer(new CallbackTransformer(
                function ($rolesAsArray) {
                    return implode(', ', $rolesAsArray);
                },
                function ($rolesAsString) {
                    return explode(', ', $rolesAsString);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_token_id' => 'authenticate',
        ]);
    }
}
