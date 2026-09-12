<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BinarySearchController extends AbstractController
{
    #[Route('/binary/search', name: 'app_binary_search')]
    public function index(): Response
    {
        # prepare the algorithm
        $response = new Response();
        $array = [14, 27, 33, 46, 58, 60, 71, 82, 94, 105];
        $response->setContent("<h1>Array: ".implode(', ', $array)."</h1>");
        $target = (int)82;

        # initialize starting boundaries
        $low = 0; 
        $high = count($array) - 1;

        # algorithm logic
        while ($low <= $high) {
            $mid = floor(($low + $high) / 2);
            if ($array[$mid] < $target) {
                $low = $mid + 1;
            } elseif ($array[$mid] > $target) {
                $high = $mid - 1;
            } else { // target found
                $response->setContent($response->getContent()."<h2>Found at index: ".$mid."</h2>");
                break;
            }
        }

        return $response;
    }
}
