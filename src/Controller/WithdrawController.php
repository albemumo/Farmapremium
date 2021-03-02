<?php

namespace App\Controller;

use App\Exception\CustomerNotFound;
use App\Exception\NotEnoughPoints;
use App\Exception\PharmacyNotFound;
use App\Service\Application\WithdrawService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class WithdrawController extends AbstractController
{
    private WithdrawService $withdrawService;

    public function __construct(WithdrawService $withdrawService)
    {
        $this->withdrawService = $withdrawService;
    }

    /**
     * @Route("/withdraw", name="withdraw")
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $pharmacy_id = $request->get('pharmacy_id');
        $customer_id = $request->get('customer_id');
        $points      = $request->get('points');

        try {
            $availablePoints = $this->withdrawService->withdraw($pharmacy_id, $customer_id, $points);
        } catch (Exception $e) {
            throw new HttpException($this->matchErrorCodeWithException($e), $e->getMessage());
        }

        return new JsonResponse('Remaining Points: ' . $availablePoints, Response::HTTP_OK);
    }

    private function matchErrorCodeWithException(Exception $e): int
    {
        switch ($e) {
            case ($e instanceof PharmacyNotFound):
            case ($e instanceof CustomerNotFound):
            case ($e instanceof NotEnoughPoints):
                $errorCode = Response::HTTP_NOT_FOUND;
                break;
            default:
                $errorCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                break;
        }

        return $errorCode;
    }
}
