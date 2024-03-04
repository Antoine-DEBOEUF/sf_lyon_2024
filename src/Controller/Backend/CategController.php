<?php

namespace App\Controller\Backend;

use App\Entity\Categorie;
use App\Form\CategType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/admin/categories', 'admin.categories')]
class CategController extends AbstractController
{
    public function __construct(
        private CategorieRepository $categRepo,
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('', '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render(
            'Backend/Categories/index.html.twig',
            ['categories' => $this->categRepo->findAllOrderByTitle()]
        );
    }

    #[Route('/create', '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    { {
            $categorie = new Categorie;
            $form = $this->createForm(CategType::class, $categorie);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($categorie);
                $this->em->flush();

                $this->addFlash('success', 'La catégorie a bien été créée');
                return $this->redirectToRoute('admin.categories.index');
            }

            return $this->render('Backend/Categories/create.html.twig', ['form' => $form]);
        }
    }

    #[Route('{id}/edit', '.edit', methods: ['GET', 'POST'])]
    public function edit(?Categorie $categorie, Request $request): Response|RedirectResponse
    {
        if (!$categorie) {
            $this->addFlash('error', 'Catégorie non trouvée');
            return $this->redirectToRoute('admin.categories.index');
        }
        $form = $this->createForm(CategType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($categorie);
            $this->em->flush();

            $this->addFlash('success', 'Catégorie modifiée avec succès');

            return $this->redirectToRoute('admin.categories.index');
        }

        return $this->render(
            'Backend/Categories/edit.html.twig',
            [
                'form' => $form,
            ]
        );
    }

    #[Route('/{id}/delete', '.delete', methods: ['POST'])]
    public function delete(?Categorie $categorie, Request $request): RedirectResponse
    {
        if (!$categorie) {
            $this->addFlash('error', 'Catégorie non trouvée');
            return $this->redirectToRoute('admin.categories.index');
        }
        if ($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->request->get('token'))) {
            $this->em->remove($categorie);
            $this->em->flush();
            $this->addFlash('success', 'Catégorie supprimée');
        } else {
            $this->addFlash('error', 'token CSRF invalide');
        }

        return $this->redirectToRoute('admin.categories.index');
    }

    #[Route('/{id}/switch', '.switch', methods: ['GET'])]
    public function switch(?Categorie $categorie): JsonResponse
    {
        if (!$categorie) {
            return new JsonResponse([
                'status' => 'Error',
                'message' => 'Catégorie non trouvée'

            ], Response::HTTP_NOT_FOUND);
        }
        $categorie->setEnable(!$categorie->isEnable());

        $this->em->persist($categorie);
        $this->em->flush();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Visibility changed',
            'enable' => $categorie->isEnable(),

        ], Response::HTTP_CREATED);
    }
}
