<?php

namespace App\Controller;

use App\Service\QueueNotificationService;
use App\Service\TransactionTransferService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Security;

/**
 * @Route("/api/transaction/transfer", name="transaction") 
 * @SWG\Tag(name="Transaction")
 * @SWG\Response(response=200, description="OK")
 * @SWG\Response(response=401, description="Unauthorized")
 * @Security(name="Bearer")
 */
class TransactionTransferController extends AbstractController
{
    /**
     * @var TransactionTransferService
     */
    private $transferService;

    public function __construct(TransactionTransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    /**
     * New transaction by Document
     * @Route("/document", name="transfer_document", methods="POST")
     * @SWG\Parameter(name="document", in="formData", type="string",required=true, description="CPF/CNPJ", default="01234567890")
     * @SWG\Parameter(name="value", in="formData", type="string", required=true, description="Valor da transferencia", default="10.00")
     * @SWG\Parameter(name="sync", in="formData", type="integer", required=false, description="0 = Síncrona, 1 = Assíncrona", default="0")
     */
    public function transferByDocument(Request $request): Response
    {
        $transactionDocument = $this->transferService->transferByDocument(
            $this->getUser(),
            $request->get('document'),
            $request->get('value'),
            $request->get('sync', 0)
        );
        return $this->json($transactionDocument, Response::HTTP_CREATED);
    }

    /**
     * New transaction by Email
     * @Route("/email", name="transfer_email", methods="POST")
     * @SWG\Parameter(name="email", in="formData", type="string",required=true, description="E-mail", default="financeiro@logistax.com.br")
     * @SWG\Parameter(name="value", in="formData", type="string",required=true, description="Valor da transferencia", default="10.00")
     * @SWG\Parameter(name="sync", in="formData", type="integer",required=false, description="0 = Síncrona, 1 = Assíncrona", default="0")
     */
    public function transferByEmail(Request $request): Response
    {
        $transactionEmail = $this->transferService->transferByEmail(
            $this->getUser(),
            $request->get('email'),
            $request->get('value'),
            $request->get('sync', 0),
        );

        return $this->json($transactionEmail , Response::HTTP_CREATED);
    }
}
