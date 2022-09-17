<?php namespace App\Response;

class LoginResponse {
    private $status;
    private $error;
    private $token;

    public function __construct($status, $error, $token)
    {
        $this->status = $status;
        $this->error = $error;
        $this->token = $token;
    }

    public function toJson() {
        return array(
          'status' => $this->status,
          'error' => $this->error,
          'token' => $this->token,
        );
    }
}
