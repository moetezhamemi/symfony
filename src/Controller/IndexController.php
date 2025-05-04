<?php

namespace App\Controller;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Entity\Article;
use App\Entity\PropertySearch;
use App\Form\CategoryType;
use App\Form\PriceSearchType;
use App\Form\PropertySearchType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\CategotySearch;
use App\Entity\PriceSearch;
use App\Form\CategorySearchType;
class IndexController extends AbstractController
{
    #[Route('/', name: 'article_list')]
    public function home(Request $request, EntityManagerInterface $entityManager): Response
    {

        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $propertySearch);
        $form->handleRequest($request);

        $articles = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $propertySearch->getNom();

            if ($nom !== '') {
                // Si un nom est fourni, on recherche par nom
                $articles = $entityManager->getRepository(Article::class)->findBy(['nom' => $nom]);
            } else {
                // Sinon, on affiche tous les articles
                $articles = $entityManager->getRepository(Article::class)->findAll();
            }
        }

        return $this->render('articles/index.html.twig', [
            'form' => $form->createView(),
            'articles' => $articles,
        ]);
    }

    #[Route('/article/save', name: 'article_save')]
    public function save(EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $article->setNom('Article 3');
        $article->setPrix(3000);

        $entityManager->persist($article);
        $entityManager->flush();

        return new Response('Article enregistré avec id ' . $article->getId());
    }

    #[Route('/article/new', name: 'new_article', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();
            
            return $this->redirectToRoute('article_list');
        }

        return $this->render('articles/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/article/{id}', name: 'article_show')]
    public function show(Article $article): Response
    {
        return $this->render('articles/show.html.twig', [
            'article' => $article,
        ]);
    }
    #[Route('/article/edit/{id}', name: 'edit_article', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);
        
        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }
        
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('article_list');
        }
    
        return $this->render('articles/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
#[Route('/article/delete/{id}', name: 'delete_article', methods: ['DELETE','POST'])]
public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->get('_token'))) {
        $entityManager->remove($article);
        $entityManager->flush();
    }

    return $this->redirectToRoute('article_list');
}
#[Route('/category/newCat', name: 'new_category', methods: ['GET', 'POST'])]
public function newCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('new_category'); // À ajuster selon votre logique
        }

        return $this->render('articles/newCategory.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/art_cat/', name: 'article_par_cat', methods: ['GET', 'POST'])]
    public function articlesParCategorie(Request $request, ArticleRepository $articleRepository): Response
    {
        $categorySearch = new CategotySearch();
        $form = $this->createForm(CategorySearchType::class, $categorySearch);
        $form->handleRequest($request);

        $articles = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $categorySearch->getCategory();
            if ($category !== null) {
                $articles = $category->getArticles();
            } else {
                $articles = $articleRepository->findAll();
            }
        }

        return $this->render('articles/articlesParCategorie.html.twig', [
            'form' => $form->createView(),
            'articles' => $articles,
        ]);
    }

    #[Route('/art_prix/', name: 'article_par_prix', methods: ['GET', 'POST'])]
    public function articlesParPrix(Request $request, ArticleRepository $articleRepository): Response
    {
        $priceSearch = new PriceSearch();
        $form = $this->createForm(PriceSearchType::class, $priceSearch);
        $form->handleRequest($request);

        $articles = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $minPrice = $priceSearch->getMinPrice();
            $maxPrice = $priceSearch->getMaxPrice();

            // Assure-toi que cette méthode existe dans ton ArticleRepository
            $articles = $articleRepository->findByPriceRange($minPrice, $maxPrice);
        }

        return $this->render('articles/articlesParPrix.html.twig', [
            'form' => $form->createView(),
            'articles' => $articles,
        ]);
    }


}