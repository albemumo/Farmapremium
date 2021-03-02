<?php

namespace App\Controller;

use App\Exception\CustomerNotFound;
use App\Exception\PharmacyNotFound;
use App\Service\Application\AccumulateService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class AccumulateController extends AbstractController
{
    private AccumulateService $accumulateService;

    public function __construct(AccumulateService $accumulateService)
    {
        $this->accumulateService = $accumulateService;
    }

    /**
     * @Route("/accumulate", name="accumulate")
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $pharmacyId = $request->get('pharmacy_id');
        $customerId = $request->get('customer_id');
        $points     = $request->get('points');

        try {
            $operation = $this->accumulateService->accumulate($pharmacyId, $customerId, $points);
        } catch (Exception $e) {
            throw new HttpException($this->matchErrorCodeWithException($e), $e->getMessage());
        }

        return new JsonResponse($operation->toArray(), Response::HTTP_CREATED);

        //return new Response($this->json($operation->toArray()), $this->httpStatusCode ?? null);
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
