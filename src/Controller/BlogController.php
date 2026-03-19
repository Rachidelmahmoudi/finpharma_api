<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog')]
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    #[Route('/blog/pourquoi-rejoindre-finpharma-en-tant-que-pharmacien', name: 'pharmacist_blog')]
    public function pharmacistBlog(): Response
    {
        return $this->render('blog/blog_pharmacy.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    #[Route('/blog/pourquoi-rejoindre-votre-cabinet-ou-clinique-medicale-sur-finpharma', name: 'doctor_blog')]
    public function doctortBlog(): Response
    {
        return $this->render('blog/blog_doctor.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    #[Route('/blog/pourquoi-rejoindre-votre-laboratoire-danalyses-medicales-sur-finpharma', name: 'laboratory_blog')]
    public function laboratoryBlog(): Response
    {
         return $this->render('blog/blog_laboratory.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }
}
