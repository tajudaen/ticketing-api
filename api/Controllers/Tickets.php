<?php

namespace App\Controllers;

use Core\Controller;
use Core\Validate;
use Core\Auth;

class Tickets extends Controller
{
    public function __construct()
    {
        $this->model = $this->model('\App\Models\Ticket');
        // $auth = new Auth();

        
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
                'event' => array(
                    'name' => 'Event Name',
                    'required' => true
                )
            ));

            $ticket_data = array(
                "event" => sanitize($data->event),
                "type" => $data->type,
                "created_at" => date('Y-m-d H:i:s')
            );

            if ($validation->passed()) {
                if(!$this->model->createTicket($ticket_data)) {
                    echo json_encode(["message" => "Unable to complete request", "status" => 400]);
                    http_response_code(400);
                } else {
                    echo json_encode(["message" => "Ticket created successfully", "status" => 201]);
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

    public function all()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (count($this->model->getTickets())) {
                echo json_encode($this->model->getTickets());                      
            } else {
                http_response_code(404);
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
            if ($this->model->editTicket($data)) {
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

    public function update()
    {

    }

}