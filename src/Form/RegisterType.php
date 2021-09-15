<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterType extends AbstractType
{

    private $userPasswordHasher;

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
