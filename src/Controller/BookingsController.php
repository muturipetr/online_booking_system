<?php

namespace App\Controller;

use App\Entity\Bookings;
use App\Form\BookingsType;
use App\Repository\BookingsRepository;
use App\Repository\ServicesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PdfGenerator;

#[Route('/bookings')]
class BookingsController extends AbstractController
{
    private $entityManager;
    private $bookingRepository;
    private $pdfGenerator;

    public function __construct(EntityManagerInterface $entityManager, BookingsRepository $bookingRepository, PdfGenerator $pdfGenerator)
    {
        $this->entityManager = $entityManager;
        $this->bookingRepository = $bookingRepository;
        $this->pdfGenerator = $pdfGenerator;
    }

    #[Route('/', name: 'app_bookings_index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('You need to be logged in to view your bookings.');
        }
        $bookings = $this->bookingRepository->findByUser($user);
        return $this->render('bookings/index.html.twig', [
            'bookings' => $bookings,
        ]);
    }

    #[Route('/new', name: 'app_bookings_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ServicesRepository $servicesRepository): Response
    {
        $services = $servicesRepository->findAll(); // Fetch all services from the repository

        $booking = new Bookings();
        $form = $this->createForm(BookingsType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $duration = $form->get('duration')->getData();
            $service = $form->get('service')->getData();
            $pricePerHour = $service->getPrice(); // Assuming the Service entity has a getPrice method

            $totalPrice = $duration * $pricePerHour;
            $booking->setTotalPrice($totalPrice);

            $entityManager->persist($booking);
            $entityManager->flush();
            
            //set a flash message
            $this->addFlash('success', 'Booking created successfully!');

            return $this->redirectToRoute('app_bookings_index');
        }

        return $this->render('bookings/new.html.twig', [
            'form' => $form->createView(),
            'services' => $services, // Pass services to the template
        ]);
    }
    #[Route('/{id}', name: 'app_bookings_show', methods: ['GET'])]
    public function show(Bookings $booking): Response
    {
        return $this->render('bookings/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bookings_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bookings $booking, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookingsType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            //set a flash message
            $this->addFlash('success', 'Booking updated successfully');

            return $this->redirectToRoute('app_bookings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bookings/edit.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bookings_delete', methods: ['POST'])]
    public function delete(Request $request, Bookings $booking, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$booking->getId(), $request->request->get('_token'))) {
            $entityManager->remove($booking);
            $entityManager->flush();
            //set a flash message
            $this->addFlash('success', 'Booking canceled successfully');
        }

        return $this->redirectToRoute('app_bookings_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/receipt', name: 'app_bookings_receipt', methods: ['GET'])]
    public function bookingReceipt(int $id): Response
    {
        $booking = $this->bookingRepository->find($id);

        if (!$booking) {
            throw $this->createNotFoundException('Booking not found.');
        }

        $pdfContent = $this->pdfGenerator->generateReceipt($booking);

        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="receipt.pdf"',
        ]);
    }
}
