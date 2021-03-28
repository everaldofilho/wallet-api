<?php

namespace App\Controller;

use App\Service\AccountService;
use App\Service\QueueNotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

use Nelmio\ApiDocBundle\Annotation\Security;

/**
 * @Route("/api/account", name="account")
 * @SWG\Tag(name="Account")
 * @SWG\Response(response=200, description="OK")
 * @SWG\Response(response=401, description="Unauthorized")
 */
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
     * Register Account
     * @Route("/", name="create", methods="POST")
     * @SWG\Parameter(name="type", in="formData", type="integer",required=true, description="1 = Pessoa Fisica, 2 = Pessoa Juridica", default="1")
     * @SWG\Parameter(name="name", in="formData", type="string",required=true, description="Nome completo", default="")
     * @SWG\Parameter(name="email", in="formData", type="string",required=true, description="E-mail", default="")
     * @SWG\Parameter(name="password", in="formData", type="string",required=true, description="Minimo de 6 caracteres", default="")
     * @SWG\Parameter(name="document", in="formData", type="string",required=true, description="CPF/CNPJ", default="")
     */
    public function create(Request $request): Response
    {
        $this->accountService->createAccount($request->request->all());
        return $this->json([
            'message' => "Account created",
        ], Response::HTTP_CREATED);
    }


    /**
     * My Account
     * @Route("/", name="my_account", methods="GET")
     * @Security(name="Bearer")
     */
    public function account(): Response
    {
        $waller = $this->accountService->getWallet($this->getUser());
        return $this->json([
            'data' => $waller
        ]);
    }
}
