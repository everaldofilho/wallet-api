<?php

namespace App\Controller;

use App\Entity\TransactionStatus;
use App\Exception\TransactionException;
use App\Exception\ValidationException;
use App\Service\AccountService;
use App\Service\TransactionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @Route("/api/transaction", name="transaction")
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
     * @Route("/transfer", name="transfer", methods="POST")
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
        return $this->json($data, Response::HTTP_OK);
    }
}
