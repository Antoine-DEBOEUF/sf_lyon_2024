<?php

namespace App\Controller\Frontend;

use App\Entity\Article;
use App\Form\CommentType;
use App\Entity\ArticleCommentary;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/articles', 'app.articles')]
class ArticleController extends AbstractController
{
    public function __construct(
        private ArticleRepository $articleRepo,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('', '.index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepo): Response
    {
        return $this->render(
            'Frontend/Articles/index.html.twig',
            ['articles' => $articleRepo->findAllOrderByDate()]
        );
    }

    #[Route('/{slug}', '.show', methods: ['GET', 'POST'])]
    public function show(?Article $article, ?Request $request): Response|RedirectResponse
    {
        if (!$article) {
            $this->addFlash('error', 'Article non trouvé');
            return $this->redirectToRoute('app.articles.index');
        }

        $comment = new ArticleCommentary;
        $form = $this->createForm(CommentType::class, $comment, ['isAdmin' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $comment
                ->setUser($user)
                ->setArticle($article)
                ->setEnable(true);

            $this->em->persist($comment);
            $this->em->flush();

            $this->addFlash('success', 'Votre commentaire a bien été posté');

            return $this->redirectToRoute('app.articles.show', [
                'slug' => $article->getSlug(),
            ]);
        }
        return $this->render('Frontend/Articles/show.html.twig', ['article' => $article, 'form' => $form,]);
    }
}
