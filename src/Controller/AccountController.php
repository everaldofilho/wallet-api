<?php

namespace App\Controller;

use App\Exception\ValidationException;
use App\Service\AccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @var AccountService
     */
    private $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * @Route("/api/account", name="account", methods="POST")
     */
    public function index(Request $request): Response
    {
        $this->accountService->createAccount($request->request->all());
        return $this->json([
            'message' => "Account created",
        ], Response::HTTP_CREATED);
    }
}
