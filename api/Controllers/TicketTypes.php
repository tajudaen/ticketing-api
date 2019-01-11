<?php

namespace App\Controllers;

use Core\Controller;
use Core\Validate;

class TicketTypes extends Controller
{
    public function __construct()
    {
        $this->model = $this->model('\App\Models\TicketType');
    }

    public function index()
    {

    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"));

            $validate = new Validate();
            $validation = $validate->check($data, array(
                'name' => array(
                    'name' => 'Ticket type',
                    'required' => true
                )
            ));

            $ticket_data = array(
                "name" => sanitize($data->name),
            );

            if ($validation->passed()) {
                if (!$this->model->createTicketType($ticket_data)) {
                    echo json_encode(["message" => "Unable to complete request", "status" => 400]);
                    http_response_code(400);
                } else {
                    echo json_encode(["message" => "Ticket type created successfully", "status" => 201]);
                    http_response_code(201);
                }
            } else {
                echo json_encode($validation->errors());
                http_response_code(400);
            }
        } else {
            echo json_encode(['message' => 'Method not allowed', 'status' => 405]);
            http_response_code(405);
        }
    }



    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $data = json_decode(file_get_contents("php://input"));
            if ($this->model->editTicketType($data)) {
                http_response_code(204);
            } else {
                echo json_encode(['message' => 'Request cannot be completed', 'status' => 400]);
                http_response_code(400);
            }

        } else {
            echo json_encode(['message' => 'Method not allowed', 'status' => 405]);
            http_response_code(405);
        }
    }

}