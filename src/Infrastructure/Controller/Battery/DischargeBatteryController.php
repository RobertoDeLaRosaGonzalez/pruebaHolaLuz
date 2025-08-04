<?php

namespace App\Infrastructure\Controller\Battery;

use OpenApi\Annotations as OA;
use App\Application\Battery\DischargeBattery\DischargeBatteryUseCase;
use App\Application\Battery\DischargeBattery\DischargeBatteryRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Domain\Entity\Battery;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Infrastructure\Service\BatteryHistoryService;

/**
 * @OA\Tag(name="Battery")
 */
class DischargeBatteryController extends AbstractController
{
    private DischargeBatteryUseCase $dischargeBatteryUseCase;

    public function __construct(DischargeBatteryUseCase $dischargeBatteryUseCase)
    {
        $this->dischargeBatteryUseCase = $dischargeBatteryUseCase;
    }

    /**
     * @OA\Post(
     *     path="/battery/discharge",
     *     summary="Descarga energía de la batería",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"amount"},
     *             @OA\Property(property="amount", type="number", example=1.2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Descarga exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Battery discharged"),
     *             @OA\Property(property="currentLevel", type="number", example=4.3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la descarga (p. ej., batería vacía o cantidad inválida)"
     *     )
     * )
     */
    #[Route('/battery/discharge', name: 'app_battery_discharge', methods: ['POST'])]
    public function dischargeBattery(Request $request, BatteryHistoryService $batteryHistoryService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $amount = (float)($data['amount']);
        $dischargeBatteryRequest = DischargeBatteryRequest::create($amount);
        try {
            $amount = $this->dischargeBatteryUseCase->execute($dischargeBatteryRequest, $batteryHistoryService);
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
