<?php

namespace Core;
use Core\Database;

class Auth
{
    protected $server = [];
    protected $database;

    public function __construct()
    {
        header("Content-Type: application/json");

        $this->database = Database::getInstance();
        $this->server = $_SERVER;

        $authorization_array = explode(' ', $this->server['HTTP_AUTHORIZATION']);
        if ($authorization_array[0] == 'Bearer') {
            $token = $authorization_array[1];
        } else {
            $token = '';
        }

        if ((isset($this->server['PHP_AUTH_USER']) && !empty($this->server['PHP_AUTH_USER'])) && (isset($this->server['PHP_AUTH_PW']) && !empty($this->server['PHP_AUTH_PW']))) {
            $data['username'] = sanitize($this->server['PHP_AUTH_USER']);
            $data['password'] = sanitize($this->server['PHP_AUTH_PW']);
            if ($result = $this->verifyUserCredentialsAndCreateToken($data)) {
                echo json_encode(['token' => $result]);
                http_response_code(200);
            } else {
                echo json_encode(['message' => 'Invalid Credentials', 'status' => 403]);
                http_response_code(403);
            }
        } elseif (!empty($token)) {
            if ($this->verifyToken($token)) {
                return true;
            } else {
                return false;
            }
        } elseif (empty($token) || empty($this->server['PHP_AUTH_USER']) || empty($this->server['PHP_AUTH_PW'])) {
            echo json_encode(["message" => "Unauthorized access", "status" => 401]);
            http_response_code(401);
        }
    }

    protected function verifyUserCredentialsAndCreateToken(array $credentials)
    {
        if ($this->database->get('users', ['username', '=', $credentials['username']])->count()) {
            $user = $this->database->get('users', ['username', '=', $credentials['username']])->first();
            if($user->password == $credentials['password']) {
                $token = $this->createToken();
                if ($result = $this->logToken($user->id, $token)) {
                    return $result;
                }
            }
           return false;
        }
        return false;
    }

    private function createToken()
    {
        return md5(uniqid());
    }

    private function logToken($user_id, $token)
    {
        if ($this->database->get('auth', ['user_id', '=', $user_id])->count() == 0) {
            $this->database->insert('auth', ['user_id' => $user_id, 'token' => $token]);
            return $token;
        } else {
            $user = $this->database->get('auth', ['user_id', '=', $user_id])->first();
            return $user->token;
        } 
    }

    protected function verifyToken($token)
    {
        if ($this->database->get('auth', ['token', '=', $token])) {
            return true;
        } else {
            return false;
        }
    }
}