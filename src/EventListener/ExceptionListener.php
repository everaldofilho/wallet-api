<?php

namespace App\EventListener;

use App\Entity\Transaction;
use App\Entity\TransactionError;
use App\Exception\TransactionException;
use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;


class ExceptionListener
{
    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $content = [
            'message' => $exception->getMessage(),
            'status' => $exception->getCode()
        ];

        $responseStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR; 

        // TransactionException
        if ($exception instanceof TransactionException) {
            $content = [
                'message' => $exception->getMessage(),
                'status' => $exception->getStatus(),
            ];
            $responseStatusCode = Response::HTTP_BAD_REQUEST; 
        }

        // ValidationException
        if ($exception instanceof ValidationException) {
            $content = ['errors' => $exception->getErrors()];
            $responseStatusCode = Response::HTTP_BAD_REQUEST; 
        }

        $response = new JsonResponse($content);
        $response->setStatusCode($responseStatusCode);

        // HttpExceptionInterface is a special type of exception that
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        }

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}
