<?php

namespace App\Controller;

use App\Exception\CustomerNotFound;
use App\Exception\PharmacyNotFound;
use App\Service\Application\SearchService;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    private SearchService $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * @Route("/pharmacy/available/points", name="pharmacy_available_points")
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $pharmacyId = $request->get('pharmacy_id');
        $startDate  = $request->get('startDate');
        $endDate    = $request->get('endDate');

        $startDateDateTime  = DateTime::createFromFormat('Y-m-d H:i:s', date($startDate));
        $endDateDateTime    = DateTime::createFromFormat('Y-m-d H:i:s', date($endDate));

        try {
            $pharmacyCurrentBalance = $this->searchService->getPharmacyCurrentBalanceBetweenTwoDates($pharmacyId, $startDateDateTime, $endDateDateTime);
        } catch (Exception $e) {
            throw new HttpException($this->matchErrorCodeWithException($e), $e->getMessage());
        }

        return new JsonResponse('Remaining Points: ' . $pharmacyCurrentBalance, Response::HTTP_OK);
    }

    /**
     * @Route("/pharmacy/customer/given/points", name="pharmacy_customer_given_points")
     * @param Request $request
     *
     * @return Response
     */
    public function pharmacyCustomerGivenPoints(Request $request): Response
    {
        $pharmacyId = $request->get('pharmacy_id');
        $customerId = $request->get('customer_id');

        try {
            $pharmacyCustomerTotalGivenPoints = $this->searchService->getPharmacyCustomerTotalGivenPoints($pharmacyId, $customerId);
        } catch (Exception $e) {
            throw new HttpException($this->matchErrorCodeWithException($e), $e->getMessage());
        }

        return new JsonResponse('Total given points: ' . $pharmacyCustomerTotalGivenPoints, Response::HTTP_OK);
    }

    /**
     * @Route("/customer/available/points", name="customer_available_points")
     * @param Request $request
     *
     * @return Response
     */
    public function customerAvailablePoints(Request $request): Response
    {
        $customerId = $request->get('customer_id');

        try {
            $customerTotalGivenPoints = $this->searchService->getCustomerAvailablePoints($customerId);
        } catch (Exception $e) {
            throw new HttpException($this->matchErrorCodeWithException($e), $e->getMessage());
        }

        return new JsonResponse('Total customer points: ' . $customerTotalGivenPoints, Response::HTTP_OK);
    }



    private function matchErrorCodeWithException(Exception $e): int
    {
        switch ($e) {
            case ($e instanceof PharmacyNotFound):
            case ($e instanceof CustomerNotFound):
                $errorCode = Response::HTTP_NOT_FOUND;
                break;
            default:
                $errorCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                break;
        }

        return $errorCode;
    }
}
