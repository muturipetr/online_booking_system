<?php

namespace App\Controller\Admin;

use App\Entity\Bookings;
use App\Entity\User;
use App\Entity\Services;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class DashboardController extends AbstractDashboardController
{
    private $EntityManager;

    public function __construct(EntityManagerInterface $EntityManager)
    {
        $this->EntityManager = $EntityManager;
    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $ServicesCount = $this->EntityManager->getRepository(Services::class)->count([]);
        $UserCount = $this->EntityManager->getRepository(User::class)->count([]);
        $BookingsCount = $this->EntityManager->getRepository(Bookings::class)->count([]);

        return $this->render('admin/dashboard.html.twig', [
                'ServicesCount' => $ServicesCount,
                'UserCount' => $UserCount,
                'BookingsCount' => $BookingsCount,
            ]);

        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(DashboardController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
        // the name visible to end users
        ->setTitle('StUdio Management')
        ->setFaviconPath('/images/istockphoto-1300694659-612x612.jpg')
    ;
}

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToCrud('Services', 'fa fa-book', Services::class),
            MenuItem::linkToCrud('clients', 'fa fa-user', user::class),
            MenuItem::linkToCrud('Bookings', 'fa fa-book-open', Bookings::class)
    
        ];
    }
}

