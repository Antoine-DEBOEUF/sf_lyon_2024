<?php

namespace App\Controller\Backend;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin/articles', 'admin.articles')]
class ArticleController extends AbstractController
{
    public function __construct(
        private ArticleRepository $articleRepo,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('', '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render(
            'Backend/Articles/index.html.twig',
            ['articles' => $this->articleRepo->findAll()]
        );
    }

    #[Route('/create', '.create', methods: ['GET', 'POST'])]
    public function create(
        Request $request
    ): Response|RedirectResponse { {
            $article = new Article;
            $form = $this->createForm(ArticleType::class, $article);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user = $this->getUser();
                $article->setUser($user);

                $this->em->persist($article);
                $this->em->flush();

                $this->addFlash('success', 'Votre article a bien été envoyé pour publication');

                return $this->redirectToRoute('admin.articles.index');
            }

            return $this->render('Backend/Articles/create.html.twig', ['form' => $form]);
        }
    }
}
