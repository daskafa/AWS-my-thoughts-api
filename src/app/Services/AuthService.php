<?php
namespace App\Services;
use App\Http\Requests\Api\AuthenticateRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AuthService {
    private UserRepositoryInterface $userRepository;
    private array $authenticateData;
    private array $registerData;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(): void
    {
        $this->userRepository->createUser($this->registerData);
    }

    public function authenticate(): array|string
    {
        $checkUser = $this->checkUser(
            $this->authenticateData['email'],
            $this->authenticateData['password']
        );

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

    public function setAuthenticateData(string $email, string $password): void
    {
        $this->authenticateData = [
            'email' => $email,
            'password' => $password,
        ];
    }

    public function setRegisterData(string $firstName, string $lastName, string $email, string $password): void
    {
        $this->registerData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => $password,
        ];
    }
}
