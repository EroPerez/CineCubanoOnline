<?php

namespace App\Controller\Shop;

use App\Component\Order\OrderFactory;
use App\Component\Product\Repository\ProductRepository;
use App\Repository\CompanyServicesRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController {

    /**
     * 
     * @Route("/", name="home", defaults={"_locale"="es"})
     */
    public function index(
            ProductRepository $productRepository,
            OrderFactory $order,
            CompanyServicesRepository $ccoServicesRepository,
            ServiceRepository $categoryRepository
    ): Response {
        $products = $productRepository->findBy([], ['createdAt'=> 'desc'], 3);

        $services = $ccoServicesRepository->findBy([], ['createdAt'=> 'desc'], 3);

        $categories = $categoryRepository->findAll();

        return $this->render('home/index.html.twig', [
                    'itemsInCart' => $order->getCurrent()->getItemsTotal(),
                    'categories' => $categories,
                    'products' => $products,
                    'services' => $services
        ]);
    }

}
