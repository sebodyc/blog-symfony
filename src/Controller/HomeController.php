<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\EventDispatcher\EventDispatcherInterface;



class HomeController
{

    /**
     * @Route("/", name="home")
     */
    public function index(EventDispatcherInterface $dispatcher): Response
    {
        return new Response("hello World");
    }
}
