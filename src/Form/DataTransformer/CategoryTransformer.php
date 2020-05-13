<?php

namespace App\Form\DataTransformer;

use App\Repository\CategoryRepository;
use Symfony\Component\Form\DataTransformerInterface;

class CategoryTransformer implements DataTransformerInterface
{
    protected CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    /**
     * 
     *
     * @param \App\Entity\Category $value
     * @return int|null
     */
    public function transform($value)
    {
        if (!$value) {
            return null;
        }
        return $value->getId();
    }

    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }

        $category = $this->categoryRepository->find($value);

        if (!$category) {
            return null;
        }

        return $category;
    }
}
