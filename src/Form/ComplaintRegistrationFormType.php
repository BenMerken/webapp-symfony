<?php

namespace App\Form;

use App\Entity\Complaint;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class
ComplaintRegistrationFormType extends AbstractType
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reason', TextType::class)
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_value' => function (User $user = null) {
                    return $user ? $user->getId() : '';},
                'choice_label' => 'email',
                'placeholder' => 'Choose a user...',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Complaint::class,
            'csrf_token_id' => 'authenticate',
        ]);
    }
}
