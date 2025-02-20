<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/{name}', name: 'home')]
    public function home(string $name): Response
    {
        return $this->render('index.html.twig', ['name' => $name]);
    }
}
