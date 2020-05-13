<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\Category;
use App\Form\Type\TagType;
use App\Form\Type\AddressType;
use Doctrine\ORM\EntityRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use App\Form\DataTransformer\TagsTransformer;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\DataTransformer\CategoryTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostType extends AbstractType
{
    protected TagsTransformer $tagTransformer;

    public function __construct(TagsTransformer $tagTransformer)
    {
        $this->tagTransformer = $tagTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => "Titre de l'article",
                'attr' => [
                    'class' => 'nomDeLaClasse',
                    'placeholder' => "Entrer le titre de l'article"
                ],
                'help' => 'Soyez percutant et concis'
            ])

            ->add('tags', EntityType::class, [
                'label' => 'Etiquettes',
                'class' => Tag::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,
                //pour forcer doctrine a faire les remove
                'by_reference' => false
            ])

            // ->add('address', AddressType::class, [
            //     'mapped' => false,
            //     'with_country' => false
            // ])

            // ->add('tags', TagType::class, [
            //     'label' => 'Etiquettes',

            // ])

            ->add('content', TextareaType::class, [
                'label' => "Contenu de l'article",
                'attr' => ['placeholder' => "Faites envie avec un belle histoire"],
                'help' => "soignez la mise en forme"
            ])

            ->add('image', UrlType::class, [
                'attr' => ['placeholder' => "url de l'image"],
                'help' => "une belle image"
            ])
            ->add('category', EntityType::class, [
                'label' => "Categorie de l'article",
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.title', 'ASC');
                },
                'choice_label' => function (category $category) {
                    return sprintf('%s - %s', $category->getId(), $category->getTitle());
                }


            ]);

        // $builder->get('tags')->addModelTransformer($this->tagTransformer);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
