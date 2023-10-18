<?php

namespace App\Http\Controllers;

use App\Constants\CommonConstants;
use App\Http\Requests\Api\AuthenticateRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Services\AuthService;
use Exception;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @throws ValidationException
     */
    public function authenticate(AuthenticateRequest $request): JsonResponse
    {
        $this->prepareAndSetAuthenticateData($request);
        $authToken = $this->authService->authenticate();

        if (is_array($authToken) && $authToken['status'] === Response::HTTP_UNPROCESSABLE_ENTITY) {
            throw ValidationException::withMessages([
                'email' => $authToken['message'],
            ]);
        }

        return responseJson(
            type: 'dataAndMessage',
            data: [
                'authToken' => $authToken,
            ],
            message: 'User authenticated successfully.',
        );
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $this->prepareAndSetRegisterData($request);
            $this->authService->register();

            return responseJson(
                type: 'message',
                message: 'User created successfully.',
                status: 201
            );
        } catch (Exception $exception) {
            return exceptionResponseJson(
                message: CommonConstants::GENERAL_EXCEPTION_ERROR_MESSAGE,
                exceptionMessage: $exception->getMessage()
            );
        }
    }

    private function prepareAndSetAuthenticateData(AuthenticateRequest $request): void
    {
        $this->authService->setAuthenticateData(
            $request->get('email'),
            $request->get('password')
        );
    }

    private function prepareAndSetRegisterData(RegisterRequest $request): void
    {
        $this->authService->setRegisterData(
            $request->get('first_name'),
            $request->get('last_name'),
            $request->get('email'),
            $request->get('password'),
        );
    }
}
