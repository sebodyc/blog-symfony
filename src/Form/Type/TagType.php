<?php

namespace App\Form\Type;

use App\Form\DataTransformer\TagsTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TagType extends AbstractType
{
    protected TagsTransformer $tagsTransformer;

    public function __construct(TagsTransformer $tagsTransformer)
    {
        $this->tagsTransformer = $tagsTransformer;
    }

    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->tagsTransformer);
    }

    public function getParent()
    {
        return TextType::class;
    }
}
