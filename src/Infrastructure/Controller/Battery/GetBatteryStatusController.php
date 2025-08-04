<?php

namespace App\Infrastructure\Controller\Battery;

use OpenApi\Annotations as OA;
use App\Application\Battery\GetBatteryStatus\GetBatteryStatusRequest;
use App\Application\Battery\GetBatteryStatus\GetBatteryStatusUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Infrastructure\Service\BatteryHistoryService;

/**
 * @OA\Tag(name="Battery")
 */
class GetBatteryStatusController extends AbstractController
{

    private GetBatteryStatusUseCase $getBatteryStatusUseCase;

    public function __construct(GetBatteryStatusUseCase $getBatteryStatusUseCase)
    {
        $this->getBatteryStatusUseCase = $getBatteryStatusUseCase;
    }

    /**
     * @OA\Get(
     *     path="/battery/status",
     *     summary="Obtiene el estado actual de la batería",
     *     @OA\Response(
     *         response=200,
     *         description="Estado actual de la batería",
     *         @OA\JsonContent(
     *             @OA\Property(property="levelPercentage", type="number", example=40),
     *             @OA\Property(property="maxCapacity", type="number", example=10),
     *             @OA\Property(property="lastUpdated", type="string", format="date-time", example="2025-07-30T17:00:00Z")
     *         )
     *     )
     * )
     */
    #[Route('/battery/status', name: 'battery_status', methods: ['GET'])]
    public function __invoke(BatteryHistoryService $batteryService): JsonResponse
    {
        $getBatteryStatusRequest = new GetBatteryStatusRequest();
        $response = $this->getBatteryStatusUseCase->execute($getBatteryStatusRequest, $batteryService);
        return $this->json($response);
    }
}
