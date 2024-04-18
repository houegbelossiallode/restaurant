<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Plat;
use App\Repository\CategorieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PlatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('title')
            ->add('description')
            ->add('prix')
            ->add('image', FileType::class, [
                'label' => "L'image de l'article (Images uniquement )",

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using attributes
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/png',
                            'image/jpeg',
                            'image/gif',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => "L'image ne correspond pas aux contraintes",
                    ])
                ],
            ])
            ->add('categorie',EntityType::class,[
                'class'=> Categorie::class,
                'choice_label'=>'description',
                'label'=> 'Categorie',
                'group_by'=> 'parent.description',
                'query_builder'=> function(CategorieRepository $cr)
                {
                    return $cr->createQueryBuilder('C')
                    ->where('C.parent IS NULL')
                    ->orderBy('C.description', 'ASC');
                }
                
            ])
            
            ->add('Valider',SubmitType::class)
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
        ]);
    }
}