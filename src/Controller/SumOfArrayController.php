<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class SumOfArrayController extends AbstractController
{
    #[Route('/sum/of/array', name: 'app_sum_of_array')]
    public function index(): JsonResponse
    {
        $array = [1, 2, 3, 4, 5];

        $size = count($array);
        $sum = $this->calculateSum($array, 0, $size);
        return new JsonResponse([
            'array' => $array,
            'sum' => $sum,
        ]);
    }

    private function calculateSum(array $array, int $current, int $size): int
    {
        // base case
        if ($current == $size) {
            return 0;
        }

        // recursive case
        return $array[$current]+$this->calculateSum($array, $current + 1, $size);
    }
}
