<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BookingsRepository;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(BookingsRepository $bookingsRepository): Response
    {
        $user = $this->getUser();

        $bookings = $bookingsRepository->createQueryBuilder('b')
        ->where('b.username = :user')
        ->andWhere('b.date >= :today')
        ->setParameter('user', $user)
        ->setParameter('today', new \DateTime('now'))
        ->orderBy('b.date', 'ASC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();

        return $this->render('dashboard/index.html.twig', [
            'bookings' => $bookings, // Use consistent variable naming
        ]);
    }
}
