<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\CalculatePriceRequest;
use App\Request\PurchaseRequest;
use App\Service\OrderService;
use App\Service\RequestHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ApiController extends AbstractController
{
    public function __construct(
        private readonly RequestHandler $requestHandler,
        private readonly OrderService   $orderService,
    )
    {
    }

    #[Route('/calculate-price', name: 'api_calculate_price', methods: ['POST'])]
    public function calculatePrice(Request $request): JsonResponse
    {
        $priceRequest = $this->requestHandler->handle($request, CalculatePriceRequest::class);

        $price = $this->orderService->calculatePrice(
            $priceRequest->product,
            $priceRequest->taxNumber,
            $priceRequest->couponCode,
        );

        return new JsonResponse(['price' => $price]);
    }

    #[Route('/purchase', name: 'api_purchase', methods: ['POST'])]
    public function purchase(Request $request): JsonResponse
    {
        $purchaseRequest = $this->requestHandler->handle($request, PurchaseRequest::class);

        $this->orderService->purchase(
            $purchaseRequest->product,
            $purchaseRequest->taxNumber,
            $purchaseRequest->couponCode,
            $purchaseRequest->paymentProcessor,
        );

        // при проблемах обрабатываются исключения через прослушку
        return new JsonResponse(['success' => true]);
    }
}
