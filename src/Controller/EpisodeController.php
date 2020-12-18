<?php

// src/Controller/EpisodeController.php

namespace App\Controller;

use App\Entity\Episode;
use App\Form\EpisodeType;
use App\Repository\EpisodeRepository;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/episode", name="episode_")
 */
class EpisodeController extends AbstractController
{
    /**
     * @Route("/", name="episode_index", methods={"GET"})
     */
    public function index(EpisodeRepository $episodeRepository): Response
    {
        return $this->render('episode/index.html.twig', [
            'episodes' => $episodeRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="episode_new", methods={"GET","POST"})
     */
    public function new(Request $request, Slugify $slugify): Response
    {
        $episode = new Episode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($episode->getTitle());
            $episode->setSlug($slug);
            $entityManager->persist($episode);
            $entityManager->flush();

            return $this->redirectToRoute('episode_index');
        }

        return $this->render('episode/new.html.twig', [
            'episode' => $episode,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{slug}", name="episode_show", methods={"GET"})
     * @return Response
     */
    public function show(Episode $episode): Response
    {
        return $this->render('episode/show.html.twig', [
            'episode' => $episode
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="episode_edit", methods={"GET","POST"})
     * @return Response
     */
    public function edit(Request $request, Episode $episode): Response
    {
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('episode_index');
        }

        return $this->render('episode/edit.html.twig', [
            'episode' => $episode,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="episode_delete", methods={"DELETE"})
     * @return Response
     */
    public function delete(Request $request, Episode $episode): Response
    {
        if ($this->isCsrfTokenValid('delete'.$episode->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($episode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('episode_index');
    }
}