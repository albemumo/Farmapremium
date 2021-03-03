<?php

namespace App\Controller;

use App\Entity\Operation;
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
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;

class AccumulateController extends AbstractController
{
    private AccumulateService $accumulateService;

    public function __construct(AccumulateService $accumulateService)
    {
        $this->accumulateService = $accumulateService;
    }

    /**
     * @Route("/api/v1/operations/accumulate", methods={"POST"})
     * @param Request $request
     * @SWG\Response(
     *     response=201,
     *     description="Accumulate the input points by pharmacy and customer and return the operation.",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Operation::class, groups={"full"}))
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
     *     description="The field used to input points to accumulate"
     * )
     * @SWG\Tag(name="accumulate")
     *
     * @return JsonResponse
     */
    public function postAccumulateAction(Request $request): JsonResponse
    {
        $pharmacyId = $request->get('pharmacyId');
        $customerId = $request->get('customerId');
        $points     = $request->get('points');

        try {
            $operation = $this->accumulateService->accumulate($pharmacyId, $customerId, $points);
        } catch (Exception $e) {
            throw new HttpException($this->matchErrorCodeWithException($e), $e->getMessage());
        }

        $data = [
            'status' => 'success',
            'data'   => $operation->toArray()
        ];

        return new JsonResponse($data, Response::HTTP_CREATED);
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
