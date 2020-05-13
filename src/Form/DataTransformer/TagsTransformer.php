<?php

namespace App\Form\DataTransformer;

use App\Repository\TagRepository;
use Symfony\Component\Form\DataTransformerInterface;

class TagsTransformer implements DataTransformerInterface
{
    protected TagRepository $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function transform($tags)
    {
        if (!$tags) {
            return;
        }

        $arr = [];

        foreach ($tags as $tag) {
            $arr[] = $tag->getTitle();
        }

        $str = implode(",", $arr);


        return $str;
    }

    public function reverseTransform($str)
    {
        if (!$str) {
            return;
        }

        $arr = explode(',', $str);
        $tags = [];

        foreach ($arr as $title) {

            $tag = $this->tagRepository->findOneBy(['title' => $title]);

            if ($tag) {
                $tags[] = $tag;
            }
        }

        return $tags;
    }
}
