<?php

namespace App\Controller\Api;

use App\Entity\Pharmacy;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[AsController]
class PharmacySearchController  extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em, private SerializerInterface $serializer)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request): JsonResponse
    {
        

        // $json = $this->serializer->serialize(
        //     $pharmacies,
        //     'json',
        //     [AbstractNormalizer::GROUPS => ['read']]
        // );

        // return new JsonResponse($json, 200, [], true); 

        //return new JsonResponse($pharmacies);
    }
}
