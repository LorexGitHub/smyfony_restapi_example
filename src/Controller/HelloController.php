<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HelloController extends AbstractController
{

    #[Route('/hello', name: 'app_hello')]
    public function index(): Response
    {
        # object 
        $response = new Response();

        # string
        $greeting = "Hello World!";

        $response->setContent('<h1>'.$greeting.'</h1>');

        # int
        $name = "John Doe";
        $age = (int)20; 

        $response->setContent($response->getContent(). '<h2>My name is '.$name.' and I am '.$age.' years old.</h2>');

        return $response;
    }
}
