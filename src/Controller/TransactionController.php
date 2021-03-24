<?php

namespace App\Controller;

use App\Entity\TransactionStatus;
use App\Service\TransactionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Security;

/**
 * @Route("/api/transaction", name="transaction") 
 * @SWG\Tag(name="Transaction")
 * @SWG\Response(response=200, description="OK")
 * @SWG\Response(response=401, description="Unauthorized")
 * @Security(name="Bearer")
 */
class TransactionController extends AbstractController
{
    /**
     * @var TransactionService
     */
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Last five transactions
     * @Route("/", name="list", methods="GET")
     */
    public function index()
    {
        $transactions = $this->transactionService->lastFiveTransaction($this->getUser());
        return $this->json([
            'data' => $transactions
        ]);
    }

    /**
     * New transaction
     * @Route("/", name="transfer", methods="POST")
     * @SWG\Parameter(name="user", in="formData", type="string",required=true, description="Id do Usuário a qual deseja transferir", default="2")
     * @SWG\Parameter(name="value", in="formData", type="string",required=true, description="Nome completo", default="")
     */
    public function transfer(Request $request): Response
    {
        $this->transactionService->transferById(
            $this->getUser()->getId(),
            $request->get('user'),
            $request->get('value')
        );
        $data = [
            'message' => "Transfer success!",
            'status' => TransactionStatus::STATUS_PROCESSED
        ];
        return $this->json($data, Response::HTTP_CREATED);
    }
}
