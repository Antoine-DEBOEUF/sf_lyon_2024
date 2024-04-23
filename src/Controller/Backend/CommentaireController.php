<?php

namespace App\Controller\Backend;

use App\Entity\Article;
use App\Entity\ArticleCommentary;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ArticleCommentaryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin/commentaires', 'admin.commentaires')]
class CommentaireController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
        private ArticleCommentaryRepository $commentRepo,
    ) {
    }

    #[Route('/{slug}', '.index', methods: ['GET'])]
    public function index(?Article $article): Response|RedirectResponse
    {
        if (!$article) {
            $this->addFlash('error', 'Article non trouvé');
            return $this->redirectToRoute('admin.articles.index');
        }

        return $this->render('Backend/Commentaires/index.html.twig', [
            'commentaires' => $article->getArticleCommentaries()
        ]);
    }

    #[Route('/{id}/delete', '.delete', methods: ['POST'])]
    public function delete(?ArticleCommentary $comment, ?Request $request): RedirectResponse
    {
        if (!$comment) {
            $this->addFlash('error', 'Commentaire non trouvé');
            return $this->redirectToRoute('admin.articles.index');

            if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('token'))) {
                $this->em->remove($comment);
                $this->em->flush();

                $this->addFlash('success', 'Commentaire supprimé avec succès');
            } else {
                $this->addflash('error', 'token CSRF invalide');
            }
            [
                'slug' => $comment->getArticle()->getSlug(),
            ];
        }
    }
}
