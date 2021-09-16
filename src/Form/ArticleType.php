<?php

namespace App\Form;

use App\Entity\Article;
use App\Service\FileUploader;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ArticleType extends AbstractType
{
    private $fileUploader;
    public function __construct(FileUploader $fileUploader){
        $this->fileUploader=$fileUploader;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content',TextareaType::class)
            ->add('url', FileType::class, [
                'label' => 'télécharge image',
                'mapped' => false, // Tell that there is no Entity to link
                'required' => true,
                'constraints' => [
                    new File([
                        'mimeTypes' => [ // We want to let upload only txt, csv or Excel files
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => "This document isn't valid.",
                    ])
                ],
            ])
            ->add('submit',SubmitType::class)
            ->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $formEvent){
                $fileName=$this->fileUploader->upload($formEvent->getForm()->get('url')->getData());
                $formEvent->getData()->setUrl($fileName);

            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data-class'=>Article::class
        ]);
    }
}
