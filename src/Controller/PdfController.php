<?php

namespace App\Controller;

use App\Entity\AnalyseCategory;
use App\Entity\Analyses;
use App\Repository\AnalyseCategoryRepository;
use App\Repository\AnalysesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Smalot\PdfParser\Parser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PdfController extends AbstractController
{
    #[Route('/pdf', name: 'app_pdf')]
    public function index(Parser $parser): Response
    {
        try {
            $pdf = $parser->parseFile($this->getParameter('kernel.project_dir') . '/public/cout_analyses.pdf');
            $text = $pdf->getText(); // Extracts all text content from the PDF

            //     return $this->render('pdf/index.html.twig', [
            //     'controller_name' => 'PdfController',
            // ]);
            return new Response('<pre>' . htmlspecialchars($text) . '</pre>');
        } catch (\Exception $e) {
            return new Response('Error parsing PDF: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/read', name: 'read')]
    public function read(AnalyseCategoryRepository $analyseCategoryRepository, AnalysesRepository $analysesRepository, EntityManagerInterface $entityManager): Response
    {
        try {
            $file = $this->getParameter('kernel.project_dir') . '/public/file.txt';
            $file_handle = fopen($file, 'r');
            $rootCategory = null;
            $subCategory = null;
            if ($file_handle) {
                while (($line = fgets($file_handle)) !== false) {
                    if (trim($line) !== 'Analyse B Prix en DH') {
                        // Process each line as needed
                        $line_parts = explode(' ', $line);
                        $line_parts = array_filter($line_parts, fn($item) => !empty(trim($item)));
                        $line_parts = array_values($line_parts);
                        $count = count($line_parts);
                        if (in_array($line_parts[0], ['1-', '2-', '3-', '4-', '5-']) && !empty($line_parts[1])) {
                            $rootCategory = new AnalyseCategory();
                            $name = str_replace(['1-', '2-', '3-', '4-', '5-'], ['', '', '', '', ''], $line);
                            $rootCategory->setName($name);
                            $entityManager->persist($rootCategory);
                        } else if (count($line_parts) < 3) {
                            $subCategory = new AnalyseCategory();
                            $subCategory->setName($line);
                            $subCategory->setParentCategory($rootCategory);
                            $entityManager->persist($subCategory);
                        } else {
                            $price = $line_parts[$count - 1];
                            $bindex = $line_parts[$count - 2];
                            $name = str_replace([$price, $bindex], ['', ''], $line);
                            $price = str_replace(',', '.', $price);
                            $analyse = new Analyses();
                            $analyse->setName(trim($name));
                            $analyse->setPrice(floatval($price));
                            //$analyse->setBIndex($bindex);
                            $analyse->setCategory($subCategory);
                            $entityManager->persist($analyse);
                        }
                    }
                }
                $entityManager->flush();
                fclose($file_handle); // Close the file handle
            } else {
                echo "Error: Unable to open the file.";
            }


            return new Response('All passed');
        } catch (\Exception $e) {
            return new Response('Error parsing PDF: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

     #[Route('/showread', name: 'showread')]
    public function show(AnalyseCategoryRepository $analyseCategoryRepository, AnalysesRepository $analysesRepository, EntityManagerInterface $entityManager): Response
    {
        $content = '';
        foreach($analyseCategoryRepository->findAll() as $categories) {
            $content .= 'Root : '. $categories->getName() .'<br/>';
            foreach($categories->getSubCategories() as $category) {
                $content .= 'Sub : '. $category->getName() .'<br/>';
                foreach($category->getAnalyses() as $analyse) {
                    $content .= $analyse->getName() .'<br/>';
                }
            }
        }
         return new Response($content);
    }
}
