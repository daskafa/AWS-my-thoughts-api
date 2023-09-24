<?php
namespace App\Services;
use App\Http\Requests\Api\AuthenticateRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AuthService {
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterRequest $request): void
    {
        $validatedRequest = $request->validated();
        $this->userRepository->createUser($validatedRequest);
    }

    public function authenticate(AuthenticateRequest $request): array|string
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $checkUser = $this->checkUser($email, $password);

        if (!$checkUser) {
            return [
                'message' => 'Invalid email or password. Please try again.',
                'status' => 422
            ];
        }

        return $this->createAuthToken();
    }

    private function checkUser(string $email, string $password): bool
    {
        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            return false;
        }

        return true;
    }

    private function createAuthToken(): string
    {
        return Auth::user()->createToken('auth_token')->plainTextToken;
    }
}
