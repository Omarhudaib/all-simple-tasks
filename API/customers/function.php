<?php

require '../inc/dbcon.php';

function erorr422($message)
{
    $data = [
        'status' => 422,
        'mesage' => $message

    ];
    header('HTTP/1.0 422 Unprocessable Entity');
    echo json_encode($data);
}

function storeCustomer($customerInput)
{

    global $conn;

    $name = mysqli_real_escape_string($conn, $customerInput['name']);
    $email = mysqli_real_escape_string($conn, $customerInput['email']);
    $phone = mysqli_real_escape_string($conn, $customerInput['phone']);

    if (empty(trim($name))) {
        return erorr422('Enter Your Name');
    } elseif (empty(trim($email))) {
        return erorr422('Enter Your Email');
    } elseif (empty(trim($phone))) {
        return erorr422('Enter Your Phone');
    } else {
        $query = "INSERT INTO customres (name,email,phone) VALUES ('$name','$email','$phone')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $data = [
                'status' => 201,
                'mesage' => 'Customer created Successfuly ',
            ];
            header('HTTP/1.0 201 Created');
            return json_encode($data);
        } else {
            $data = [
                'status' => 405,
                'mesage' => 'Internal Server Error ',
            ];
            header('HTTP/1.0 500 Internal Server Error');
            return json_encode($data);
        }
    }
}

function getCustomerList()
{

    global $conn;


    if (!$conn) {
        $data = [
            'status' => 500,
            'message' => 'Database connection failed',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode($data);
        exit();
    }
    $query = "SELECT * FROM customres";
    $query_run = mysqli_query($conn, $query);


    if ($query_run) {

        if (mysqli_num_rows($query_run) > 0) {
            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

            $data = [
                'satus' => 200,
                'message' => 'Customers list fetched  successfully',
                'data' => $res


            ];
            header("HTTP/1.0 200 Customers list fetched  successfully");
            echo json_encode($data);
        } else {
            $data = [
                'satus' => 404,
                'message' => 'no customer found',

            ];
            header("HTTP/1.0 404 no customer found");
            return json_encode($data);
        }
    } else {
        $data = [
            'satus' => 500,
            'message' => 'Mthod Not Allowed',

        ];
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode($data);
    }
}
