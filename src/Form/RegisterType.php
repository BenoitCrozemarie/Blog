<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterType extends AbstractType
{

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',TextType::class,[
            'label' => 'Username',
                'constraints'=>[
                    new NotBlank()
                ]
           ] )

            ->add('password',PasswordType::class,[
                'label' => 'Mot de passe',
                'constraints'=>[
                    new NotBlank()
                    ]
                ])
            ->add('firstname',TextType::class,[
                'label' => 'Firstname',
                'constraints'=>[
                    new NotBlank()
                ]
            ] )
            ->add('lastname',TextType::class,[
                'label' => 'Lastname',
                'constraints'=>[
                    new NotBlank()
                ]
            ] )
            ->add('submit', SubmitType::class, [
                'label' => 'Register'
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                /** @var User $user */
                $user = $event->getData();
                $pass = $this->userPasswordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($pass);
            });

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
