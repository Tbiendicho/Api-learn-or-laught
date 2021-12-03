<?php

namespace App\Controller\Api\V1;

use App\Repository\QuoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuoteController extends AbstractController
{
    /**
     * @Route("/api/v1/quote", name="api_quote", methods={"GET"})
     */
    public function browseQuotes(QuoteRepository $quoteRepository): Response
    {
        $allQuotes = $quoteRepository->findAll();
    
        return $this->json($allQuotes, Response::HTTP_OK, [], ['groups' => 'api_quote_browse']);
    }

    /**
     * @Route("/api/v1/quote/{id}", name="api_quote", methods={"GET"})
     */
    public function browseQuotesByCategory(QuoteRepository $quoteRepository, $id): Response
    {
        $allQuotesByCategory = $quoteRepository->findAllQuotesByCategory($id);
    
        return $this->json($allQuotesByCategory, Response::HTTP_OK, [], ['groups' => 'api_quote_browse']);
    }

    // /**
    //  * @Route("/api/v1/quote/{id}", name="api_quote", methods={"GET"})
    //  */
    // public function browseQuotesBylike(QuoteRepository $quoteRepository, $id): Response
    // {
    //     $allQuotesByCategory = $quoteRepository->findAllQuotesByCategory($id);
    
    //     return $this->json($allQuotesByCategory, Response::HTTP_OK, [], ['groups' => 'api_quote_browse']);
    // }

    // public function readRandomQuote(): Response
    // {
    //     return $this->render('api/quote/index.html.twig', [
    //         'controller_name' => 'QuoteController',
    //     ]);
    // }

    // public function readRandomQuoteByCategory(): Response
    // {
    //     return $this->render('api/quote/index.html.twig', [
    //         'controller_name' => 'QuoteController',
    //     ]);
    // }


}
