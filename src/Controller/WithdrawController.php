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
use Swagger\Annotations as SWG;

class WithdrawController extends AbstractController
{
    private WithdrawService $withdrawService;

    public function __construct(WithdrawService $withdrawService)
    {
        $this->withdrawService = $withdrawService;
    }

    /**
     * @Route("/api/v1/operations/withdraw", methods={"POST"})
     * @param Request $request
     * @SWG\Response(
     *     response=200,
     *     description="Withdraw the input points by pharmacy and customer and return the remaining points",
     *     @SWG\Schema(
     *         type="string",
     *     )
     * )
     * @SWG\Parameter(
     *     name="pharmacyId",
     *     in="query",
     *     type="integer",
     *     description="The field used to input pharmacy id"
     * )
     * @SWG\Parameter(
     *     name="customerId",
     *     in="query",
     *     type="integer",
     *     description="The field used to input customer id"
     * )
     * @SWG\Parameter(
     *     name="points",
     *     in="query",
     *     type="integer",
     *     description="The field used to input points to withdraw"
     * )
     * @SWG\Tag(name="withdraw")
     *
     * @return JsonResponse
     */
    public function postWithdrawAction(Request $request): JsonResponse
    {
        $pharmacyId = $request->get('pharmacyId');
        $customerId = $request->get('customerId');
        $points     = $request->get('points');

        try {
            $remainingPoints = $this->withdrawService->withdraw($pharmacyId, $customerId, $points);
        } catch (Exception $e) {
            throw new HttpException($this->matchErrorCodeWithException($e), $e->getMessage());
        }

        $data = [
            'status' => 'success',
            'data'   => sprintf('Remaining points: %d', $remainingPoints)
        ];

        return new JsonResponse($data, Response::HTTP_OK);
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
