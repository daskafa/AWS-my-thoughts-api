<?php

namespace App\Http\Middleware;

use App\Constants\CommonConstants;
use App\Interfaces\UserRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsUserMiddleware
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authRole = $this->userRepository->getAuthUserRole();

        if ($authRole !== CommonConstants::DEFAULT_USER_ROLE) {
            return responseJson(
                type: 'message',
                message: CommonConstants::NOT_AUTHORIZED_ERROR_MESSAGE,
                status: Response::HTTP_FORBIDDEN
            );
        }

        return $next($request);
    }
}
