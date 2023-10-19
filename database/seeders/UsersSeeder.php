<?php

namespace Database\Seeders;

use Exception;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fileData = file_get_contents('database/seeders/json/usersSeeder.json');
        $jsonData = json_decode($fileData, true);
        if ($jsonData) {
            $response = $this->createUser($jsonData);
            if ($response) {
                $this->command->info('User Created Successfully');
            } else {
                $this->command->info('Failed to Create User!');
            }
        }
    }

    public function createUser($data)
    {
        $users = $data['users'];
        if (empty($data['users'])) {
            return false;
        }

        try {
            foreach ($users as $key => $user) {
                $userInfo = $this->userRepository->findwhere('email', $user['email']);
                if (empty($userInfo)) {
                    $insertData = [
                        'name'       => $user['name'],
                        'email'      => $user['email'],
                        'phone'      => $user['phone'],
                        'password'   => Hash::make($user['password']),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'created_by' => 1,
                        'status'     => 1,
                    ];
                    $this->userRepository->create($insertData);
                }
            }
            return true;
        } catch (Exception $exception) {
            $this->command->info($exception->getMessage());
            return false;
        }
    }
}
