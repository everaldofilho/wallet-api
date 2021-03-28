<?php

namespace App\Service;

use App\Entity\Transaction;
use App\Entity\TransactionCategory;
use App\Entity\TransactionStatus;
use App\Entity\TransactionTransfer;
use App\Entity\TransactionType;
use App\Entity\User;
use App\Entity\UserType;
use App\Exception\TransactionException;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class TransactionTransferService
{

    /** @var EntityManagerInterface */
    private $em;

    /** @var TransactionService */
    private $transactionService;

    /** @var TransactionStatusService */
    private $statusService;

    /** @var AuthorizerService */
    private $authorizerService;

    /** @var TransactionTypeService */
    private $typeService;

    /** @var TransactionCategoryService */
    private $categoryService;

    /** @var QueueTransactionService */
    private $queueTransactionService;

    /** @var QueueNotificationService */
    private $queueNotificationService;

    /** @var WalletService */
    private $walletService;

    /** @var UserService */
    private $userService;

    /** @var ValidatorInterface */
    private $validator;

    public function __construct(
        EntityManagerInterface $em,
        TransactionService $transactionService,
        TransactionStatusService $statusService,
        TransactionTypeService $typeService,
        TransactionCategoryService $categoryService,
        QueueTransactionService $queueTransactionService,
        QueueNotificationService $queueNotificationService,
        UserService $userService,
        WalletService $walletService,
        AuthorizerService $authorizerService,
        ValidatorInterface $validator
    ) {
        $this->em = $em;
        $this->userService = $userService;
        $this->transactionService = $transactionService;
        $this->statusService = $statusService;
        $this->typeService = $typeService;
        $this->categoryService = $categoryService;
        $this->queueTransactionService = $queueTransactionService;
        $this->queueNotificationService = $queueNotificationService;
        $this->walletService = $walletService;
        $this->authorizerService = $authorizerService;
        $this->validator = $validator;
    }

    public function transferByDocument(User $from_user, $document, float $value, $async = false): ?TransactionTransfer
    {
        $to_user = $this->userService->getUserByDocument($document);
        $transfer = $this->transferSync($from_user, $to_user, $value, $async);
        return $transfer;
    }

    public function transferByEmail(User $from_user, $email, float $value, $async = false): ?TransactionTransfer
    {
        $to_user = $this->userService->getUserByEmail($email);
        $transfer = $this->transferSync($from_user, $to_user, $value, $async);
        return $transfer;
    }

    public function transferSync(User $from_user, ?User $to_user, float $value, $async = false): ?TransactionTransfer
    {
        $status = $async ? TransactionStatus::STATUS_QUEUE : TransactionStatus::STATUS_PROCESSING;
        $transfer = $this->createTransfer($from_user, $to_user, $value, $status);
        if ($async) {
            $this->queueTransactionService->publishTransaction($transfer);
            return $transfer;
        }
        return $this->transfer($transfer);
    }

    public function getTransfer(string $id): ?TransactionTransfer
    {
        return $this->em->getRepository(TransactionTransfer::class)->find($id);
    }

    public function transfer(TransactionTransfer $transfer): ?TransactionTransfer
    {
        $this->em->beginTransaction();
        try {

            if ($transfer->getStatus()->getId() == TransactionStatus::STATUS_PROCESSED) {
                throw new TransactionException("Transaction already processed", TransactionStatus::STATUS_DENIED);
            }

            if (!$this->authorizerService->isAuthorized()) {
                throw new TransactionException("No authorized", TransactionStatus::STATUS_DENIED);
            }

            $this->updateStatus($transfer, TransactionStatus::STATUS_PROCESSED);
            $this->createTransactions($transfer);

            $this->em->commit();

            $this->queueNotificationService->publishTransfer($transfer);

            return $transfer;
        } catch (TransactionException $th) {
            $this->em->rollback();
            $this->updateStatus($transfer, $th->getStatus() ?: TransactionStatus::STATUS_ERROR);
            throw $th;
        } catch (Throwable $th) {
            $this->em->rollback();
            $this->updateStatus($transfer, TransactionStatus::STATUS_ERROR);
            throw $th;
        }
    }

    private function updateStatus(TransactionTransfer $transfer, int $status)
    {
        $transfer->setStatus($this->statusService->getStatus($status));
        $transfer->setUpdatedAt(new DateTime());
        $this->em->persist($transfer);
        $this->em->flush();
    }

    private function createTransfer(?User $from_user, ?User $to_user, float $value, int $status)
    {
        $status = $this->statusService->getStatus($status);

        $transfer  = new TransactionTransfer;
        $transfer->setFromUser($from_user);
        $transfer->setToUser($to_user);
        $transfer->setStatus($status);
        $transfer->setValue($value);
        $transfer->setCreatedAt(new DateTime);
        $transfer->setUpdatedAt(new DateTime);

        $errors = $this->validator->validate($transfer);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $this->businessValidation($transfer);

        $this->em->persist($transfer);
        $this->em->flush();

        return $transfer;
    }

    private function createTransactions(TransactionTransfer $transfer)
    {
        $category = $this->categoryService->getCategory(TransactionCategory::CATEGORY_TRANSFER);
        $typeCredit = $this->typeService->getType(TransactionType::TYPE_CREDIT);
        $typeDebit = $this->typeService->getType(TransactionType::TYPE_DEBIT);

        $this->transactionService->createTransaction([
            'transfer' => $transfer,
            'user' => $transfer->getFromUser(),
            'status' => $transfer->getStatus(),
            'value' => $transfer->getValue(),
            'type' => $typeDebit,
            'category' => $category,
            'notification' => 1,
        ]);

        $this->transactionService->createTransaction([
            'transfer' => $transfer,
            'user' => $transfer->getToUser(),
            'status' => $transfer->getStatus(),
            'value' => $transfer->getValue(),
            'type' => $typeCredit,
            'category' => $category,
            'notification' => 0,
        ]);
    }

    private function businessValidation(TransactionTransfer $transfer)
    {
        if ($transfer->getToUser()->getId() == $transfer->getFromUser()->getId()) {
            throw new TransactionException("Not authorized to make transfers for yourself!");
        }

        if ($transfer->getFromUser()->getType()->getId() == UserType::TYPE_COMPANY) {
            throw new TransactionException("Unauthorized company transfer!");
        }

        $wallet = $this->walletService->getWallet($transfer->getFromUser());
        if ($transfer->getValue() >= $wallet->getBalance()) {
            throw new TransactionException("Insufficient balance! 1");
        }
    }
}
