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
use Swagger\Annotations as SWG;

class SearchController extends AbstractController
{
    private SearchService $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * @Route("/api/v1/pharmacies/{pharmacyId}", methods={"GET"})
     * @param Request $request
     * @SWG\Response(
     *     response=200,
     *     description="Show total current points of pharmacy between two dates.",
     *     @SWG\Schema(
     *         type="string",
     *     )
     * )
     * @SWG\Parameter(
     *     name="pharmacyId",
     *     in="path",
     *     type="integer",
     *     description="The field used to input pharmacy id"
     * )
     * @SWG\Parameter(
     *     name="startDate",
     *     in="query",
     *     type="integer",
     *     description="The field used to input customer id"
     * )
     * @SWG\Parameter(
     *     name="endDate",
     *     in="query",
     *     type="integer",
     *     description="The field used to input points to withdraw"
     * )
     * @SWG\Tag(name="search")
     *
     * @return JsonResponse
     */
    public function getPharmaciesAvailablePointsAction(Request $request): Response
    {
        $pharmacyId = $request->get('pharmacyId');
        $startDate  = $request->get('startDate');
        $endDate    = $request->get('endDate');

        $startDateDateTime = DateTime::createFromFormat('Y-m-d H:i:s', date($startDate));
        $endDateDateTime   = DateTime::createFromFormat('Y-m-d H:i:s', date($endDate));

        try {
            $pharmacyCurrentBalance = $this->searchService->getPharmacyCurrentBalanceBetweenTwoDates($pharmacyId,
                $startDateDateTime, $endDateDateTime);
        } catch (Exception $e) {
            throw new HttpException($this->matchErrorCodeWithException($e), $e->getMessage());
        }

        $data = [
            'status' => 'success',
            'data'   => sprintf('Remaining points: %d', $pharmacyCurrentBalance)
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/v1/pharmacies/{pharmacyId}/customers/{customerId}", methods={"GET"})
     * @param Request $request
     * @SWG\Response(
     *     response=200,
     *     description="Show total given points of pharmacy and customer.",
     *     @SWG\Schema(
     *         type="string",
     *     )
     * )
     * @SWG\Parameter(
     *     name="pharmacyId",
     *     in="path",
     *     type="integer",
     *     description="The field used to input pharmacy id"
     * )
     * @SWG\Parameter(
     *     name="customerId",
     *     in="path",
     *     type="integer",
     *     description="The field used to input customer id"
     * )
     * @SWG\Tag(name="search")
     *
     * @return JsonResponse
     */
    public function getPharmaciesCustomersGivenPointsAction(Request $request): Response
    {
        $pharmacyId = $request->get('pharmacyId');
        $customerId = $request->get('customerId');

        try {
            $pharmacyCustomerTotalGivenPoints = $this->searchService->getPharmacyCustomerTotalGivenPoints($pharmacyId,
                $customerId);
        } catch (Exception $e) {
            throw new HttpException($this->matchErrorCodeWithException($e), $e->getMessage());
        }

        $data = [
            'status' => 'success',
            'data'   => sprintf('Remaining points: %d', $pharmacyCustomerTotalGivenPoints)
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/v1/customers/{customerId}", methods={"GET"})
     * @param Request $request
     * @SWG\Response(
     *     response=200,
     *     description="Show customer total available points.",
     *     @SWG\Schema(
     *         type="string",
     *     )
     * )
     * @SWG\Parameter(
     *     name="customerId",
     *     in="path",
     *     type="integer",
     *     description="The field used to input customer id"
     * )
     * @SWG\Tag(name="search")
     *
     * @return JsonResponse
     */
    public function getCustomerAvailablePointsAction(Request $request): Response
    {
        $customerId = $request->get('customerId');

        try {
            $customerTotalGivenPoints = $this->searchService->getCustomerAvailablePoints($customerId);
        } catch (Exception $e) {
            throw new HttpException($this->matchErrorCodeWithException($e), $e->getMessage());
        }

        $data = [
            'status' => 'success',
            'data'   => sprintf('Total customer points: %d', $customerTotalGivenPoints)
        ];

        return new JsonResponse($data, Response::HTTP_OK);
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
