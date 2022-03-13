<?php

namespace App\Controllers;

class BaseController
{

    /**
     * Render a php view in views folder
     * @param string $name
     */
    public function renderView(string $name)
    {
        include_once(sprintf('%s/../../views/%s.php', __DIR__, $name));
    }

    /**
     * Return success or error json response
     * @param $data
     * @param int $responseCode
     */
    public function respond($data, int $responseCode = 200): void
    {
        //check data is string
        if (is_string($data)) {
            $data = ['message' => $data];
        }

        //set status code
        http_response_code($responseCode);
        //set headers
        header('Content-type:application/json;charset=utf-8');
        // return the encoded json
        echo json_encode($data);
        exit;
    }
}