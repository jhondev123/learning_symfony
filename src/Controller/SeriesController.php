<?php

namespace App\Controller;

use App\Entity\Series;
use App\Repository\SeriesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SeriesController extends AbstractController
{
    public function __construct(private SeriesRepository $seriesRepository) {}
    #[Route('/series', name: 'app_series')]
    public function index(): Response
    {
        $seriesList = $this->seriesRepository->findAll();
        return $this->render('series/index.html.twig', [
            'controller_name' => 'SeriesController',
            'series' => $seriesList,
        ]);
    }

    #[Route('/series/create', methods: ['GET'], name: 'addSeriesForm')]
    public function addSeriesForm(): Response
    {
        return $this->render('series/form.html.twig');
    }

    #[Route('/series/create', methods: ['POST'])]
    public function addSeries(Request $request): Response
    {
        $seriesName = $request->request->get(key: 'name');
        $series = new Series($seriesName);

        $this->seriesRepository->add($series, flush: true);
        return new RedirectResponse('/series');

    }
}
