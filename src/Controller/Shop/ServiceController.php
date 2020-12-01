<?php

namespace App\Controller\Shop;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
//use Knp\Component\Pager\Pagination\PaginationInterface;
use App\Entity\CompanyServices;
use App\Repository\ServiceRepository;
use App\Repository\CompanyRepository;
use App\Repository\CompanyServicesRepository;
//use App\Component\Province\Repository\ProvinceRepository;

/**
 * Description of ServiceController
 *
 * @author michel
 */
class ServiceController extends Controller {

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }

    /**
     * 
     * @Route("/explore-services", name="explore_services", methods={"GET"})
     */
    public function index(Request $request,
            CompanyServicesRepository $fetcher,
            ServiceRepository $categoryRepository,
            CompanyRepository $companyRepository
//          , ProvinceRepository $provinceRepository
    ): Response {

        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 9);
        $criteria = $request->query->all();


        if (!array_key_exists('filter', $criteria)) {
            $criteria['filter'] = [];
            $criteria['filter']['eq'] = [];
            $criteria['filter']['ilike'] = [];
        }

        if (!array_key_exists('company_id', $criteria['filter']['eq'])) {
            $criteria['filter']['eq']['company_id'] = null;
        }

        if (!array_key_exists('category_id', $criteria['filter']['eq'])) {
            $criteria['filter']['eq']['category_id'] = null;
        }
        
//        if (!array_key_exists('province_id', $criteria['filter']['eq'])) {
//            $criteria['filter']['eq']['province_id'] = null;
//        }

        if (!array_key_exists('service_title', $criteria['filter']['ilike'])) {
            $criteria['filter']['ilike']['service_title'] = null;
        }


        $category_id = $criteria['filter']['eq']['category_id'];
        $company_id = $criteria['filter']['eq']['company_id'];
//        $province_id = $criteria['filter']['eq']['province_id'];
        $title = $criteria['filter']['ilike']['service_title'];

        $criteria['filter'] = array_map('array_filter', $criteria['filter']);


        $categories = $categoryRepository->findAll();
        $companies = $companyRepository->findAll();
//        $provinces = $provinceRepository->findAll();

        return $this->render('services/explore-services.html.twig', [
                    'services' => $fetcher->fetch($criteria, $page, $limit),
                    'categories' => $categories,
                    'companies' => $companies,
//                    'provinces' => $provinces,
                    'category_id' => $category_id,
                    'company_id' => $company_id,
                    'title' => $title,
//                    'province_id' => $province_id
        ]);
    }

    /**
     * 
     * @Route("/service/{id}/detail", name="detail_services")
     */
    public function detail(Request $request, CompanyServices $service): Response {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        return $this->render('services/detail-services.html.twig', [
                    'service' => $service,
        ]);
    }

}
