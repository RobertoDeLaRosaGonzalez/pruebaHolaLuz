<?php

namespace App\Infrastructure\Controller\Battery;

use OpenApi\Annotations as OA;
use App\Application\Battery\ChargeBattery\ChargeBatteryUseCase;
use App\Application\Battery\ChargeBattery\ChargeBatteryRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Infrastructure\Service\BatteryHistoryService;

/**
 * @OA\Tag(name="Battery")
 */
class ChargeBatteryController extends AbstractController
{
    private ChargeBatteryUseCase $chargeBatteryUseCase;

    public function __construct(ChargeBatteryUseCase $chargeBatteryUseCase)
    {
        $this->chargeBatteryUseCase = $chargeBatteryUseCase;
    }
    /**
     * @OA\Post(
     *     path="/battery/charge",
     *     summary="Carga la batería con una cantidad específica",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"amount"},
     *             @OA\Property(property="amount", type="number", example=10)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Batería cargada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Battery charged successfully"),
     *             @OA\Property(property="amount", type="number", example=10)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación"
     *     )
     * )
     */
    #[Route('/battery/charge', name: 'app_battery_charge', methods: ['POST'])]
    public function chargeBattery(Request $request, BatteryHistoryService $batteryHistoryService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $amount = (float)($data['amount']);
        $chargeBatteryRequest = ChargeBatteryRequest::create($amount);
        try {
            $amount = $this->chargeBatteryUseCase->execute($chargeBatteryRequest, $batteryHistoryService);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Battery charged successfully',
            'amount' => $amount
        ]);
    }
}
