<?php

namespace App\Infrastructure\Controller\Battery;

use OpenApi\Annotations as OA;
use App\Application\Battery\GetBatteryHistory\GetBatteryHistoryUseCase;
use App\Application\Battery\GetBatteryHistory\GetBatteryHistoryRequest;
use App\Infrastructure\Service\BatteryHistoryService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Domain\Entity\Battery;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @OA\Tag(name="Battery")
 */
class GetBatteryHistoryController extends AbstractController
{

    private GetBatteryHistoryUseCase $getBatteryHistoryUseCase;

    public function __construct(GetBatteryHistoryUseCase $getBatteryHistoryUseCase)
    {
        $this->getBatteryHistoryUseCase = $getBatteryHistoryUseCase;
    }

    /**
     * @OA\Get(
     *     path="/battery/history",
     *     summary="Obtiene los últimos eventos de carga y descarga",
     *     @OA\Response(
     *         response=200,
     *         description="Listado de eventos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="type", type="string", example="charge"),
     *                 @OA\Property(property="amount", type="number", example=3.5),
     *                 @OA\Property(property="timestamp", type="string", format="date-time", example="2025-07-30T17:00:00Z")
     *             )
     *         )
     *     )
     * )
     */
    #[Route('/battery/history', name: 'battery_history', methods: ['GET'])]
    public function __invoke(BatteryHistoryService $batteryService): JsonResponse
    {
        $getBatteryHistoryRequest = new GetBatteryHistoryRequest();
        $response = $this->getBatteryHistoryUseCase->execute($getBatteryHistoryRequest, $batteryService);
        return $this->json($response);
    }
}
