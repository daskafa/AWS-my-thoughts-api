<?php

namespace App\Http\Middleware;

use App\Constants\CommonConstants;
use App\Interfaces\UserRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddleware
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

        if ($authRole !== CommonConstants::DEFAULT_ADMIN_ROLE) {
            return responseJson(
                type: 'message',
                message: 'You are not authorized to access this resource.',
                status: Response::HTTP_FORBIDDEN
            );
        }

        return $next($request);
    }
}
