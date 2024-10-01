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
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        $successMessage = $session->get('success');
        $session->remove('success');
        $seriesList = $this->seriesRepository->findAll();
        return $this->render('series/index.html.twig', [
            'controller_name' => 'SeriesController',
            'series' => $seriesList,
            'success' => $successMessage
        ]);
    }

    #[Route('/series/create', methods: ['GET'], name: 'app_addSeriesForm')]
    public function addSeriesForm(): Response
    {
        return $this->render('series/form.html.twig');
    }

    #[Route('/series/edit/{id}', methods: ['GET'], name: 'app_updateSeriesForm')]
    public function updateSeriesForm(int $id): Response
    {
        $series = $this->seriesRepository->find($id);
        return $this->render('series/form.html.twig', ['series' => $series]);
    }

    #[Route('/series/update/{id}', methods: ['PUT'], name: 'app_editSeries')]
    public function editSeries(int $id, Request $request): Response
    {
        $session = $request->getSession();

        $seriesName = $request->request->get(key: 'name');
        $series = $this->seriesRepository->find($id);

        $series->setName($seriesName);
        $this->seriesRepository->add($series, flush: true);

        $session->set('success', 'Series updated successfully');
        return new RedirectResponse('/series');
    }

    #[Route('/series/create', methods: ['POST'], name: 'app_addSeries')]
    public function addSeries(Request $request): Response
    {
        $session = $request->getSession();
        $seriesName = $request->request->get(key: 'name');
        $series = new Series($seriesName);


        $this->seriesRepository->add($series, flush: true);
        $session->set('success', 'Series added successfully');
        return new RedirectResponse('/series');
    }
    #[Route(
        '/series/delete/{id}',
        name: 'app_delete_series',
        methods: ['DELETE'],
        requirements: ['id' => '[0-9]+']
    )]
    public function deleteSeries(int $id, Request $request): Response
    {
        $this->seriesRepository->removeById($id, flush: true);
        $session = $request->getSession();
        $session->set('success', 'Series deleted successfully');
        return new RedirectResponse(url: '/series');
    }
}
