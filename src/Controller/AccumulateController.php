<?php

namespace App\Controller;

use App\Service\OperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccumulateController extends AbstractController
{
    private $operationService;

    public function __construct(OperationService $operationService)
    {
        $this->operationService = $operationService;
    }

    /**
     * @Route("/accumulate", name="accumulate")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $pharmacy_id = $request->get('pharmacy_id');
        $customer_id = $request->get('customer_id');
        $points      = $request->get('points');

        $operation = $this->operationService->accumulate($pharmacy_id, $customer_id, $points);

        return $this->json((array)$operation);
    }
}
