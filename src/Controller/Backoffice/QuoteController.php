<?php

namespace App\Controller\Backoffice;

use App\Entity\Category;
use App\Entity\Quote;
use App\Form\CategoryType;
use App\Form\QuoteType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/quotes", name="quote")
 */
class QuoteController extends AbstractController
{
    /**
     * @Route("/add", name="_add")
     */
    public function addQuote(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $quote = new Quote;
        $quoteForm = $this->createForm(QuoteType::class, $quote);
        $quoteForm->handleRequest($request);

        if ($quoteForm->isSubmitted() && $quoteForm->isValid()) {
            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($quote);
            $entityManager->flush();

            $this->addFlash('success', "L'anecdote a bien été ajoutée");

            // Redirecting the user to be sure that the adding was done once
            return $this->redirectToRoute('quote_add');
        }


        return $this->render('quotes/add_quote.html.twig', [
            'quote_form' => $quoteForm->createView(),
        ]);
    }

    /**
     * @Route("/category/add", name="_category_add")
     */
    public function addCategory(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $category = new Category;
        $categoryForm = $this->createForm(CategoryType::class, $category);
        $categoryForm->handleRequest($request);

        // if the form is valid and submitted, we'll save datas, if it's not, we'll render a new form
        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', "La catégorie a bien été ajoutée");

            // Redirecting the user to be sure that the adding was done once
            return $this->redirectToRoute('quote_category_add');
        }

        return $this->render('quotes/add_categories.html.twig', [
            'category_form' => $categoryForm->createView(),
        ]);
    }
}
