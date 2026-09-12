<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserController extends AbstractController
{
    #[Route('/api/users', methods: ['GET'])]
    public function index(EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $users = $em->getRepository(User::class)->findAll();

        $json_content = $serializer->serialize($users, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['id']
        ]);

        return JsonResponse::fromJsonString($json_content);
    }

    #[Route('/api/users/{id}', methods: ['GET'])]
    public function show(User $user): JsonResponse
    {
        return $this->json($user);
    }

    #[Route('/api/users', methods: ['POST'])]
    public function create(Request $request,
                           SerializerInterface $serializer,
                           EntityManagerInterface $em,
                           ValidatorInterface $validator): JsonResponse
    {
        $content = $request->getContent();
        $user = $serializer->deserialize($content, User::class, 'json');

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $error_messages = [];

            foreach ($errors as $error) {
                $error_messages[$error->getPropertyPath()][] = $error->getMessage();
            }

            return $this->json(["errors" => $error_messages], 422);
        }

        $em->persist($user);
        $em->flush();

        return $this->json($user, 201);
    }

    #[Route('/api/users/{id}', methods: ['PUT', "PATCH"])]
    public function update(Request $request,
                           User $user,
                           SerializerInterface $serializer): JsonResponse
    {
        $serializer->deserialize($request->getContent(),
                                 User::class,
                                 'json',
                                 ["object_to_populate" => $user]);
        $em->flush();

        return $this->json($user);
    }

    #[Route('/api/users/{id}', methods: ['DELETE'])]
    public function delete(User $user, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($user);
        $em->flush();

        return $this->json(null, 204);
    }
}