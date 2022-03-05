<?php
//getting the database connection
include_once '../../Includes/config/Database.php';

$database = new Database();
$conn = $database->getConnection();

//an array to display response
$response = array();

//if it is an api call 
//that means a get parameter named api call is set in the URL 
//and with this parameter we are concluding that it is an api call 
if (isset($_GET['apicall'])) {

    switch ($_GET['apicall']) {

        case 'signup':

            //checking the parameters required are available or not
            if (isTheseParametersAvailable(array('full_name', 'email', 'user_phone', 'password'))) {

                //getting the values
                $full_name = $_POST['full_name'];
                $email = $_POST['email'];
                $user_phone = $_POST['user_phone'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $verification_code = "mobile_" . rand(100000, 999999);

                //checking if the user is already exist with this username or email
                //as the email and username should be unique for every user
                $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ? OR email = ?");
                $stmt->bind_param("ss", $user_phone, $email);
                $stmt->execute();
                $stmt->store_result();


                //if the user already exist in the database
                if ($stmt->num_rows > 0) {
                    $response['error'] = true;
                    $response['message'] = 'User already exist, try another email';
                    $stmt->close();
                } else {

                    //if user is new creating an insert query
                    $stmt = $conn->prepare("INSERT INTO users (name, email,password, phone,verification_code) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $full_name, $email, $password, $user_phone, $verification_code);

                    //if the user is successfully added to the database
                    if ($stmt->execute()) {

                        //fetching the user back
                        $stmt = $conn->prepare("SELECT id, name, email, phone FROM users WHERE phone = ? OR email = ?");
                        $stmt->bind_param("ss", $user_phone, $email);
                        $stmt->execute();
                        $stmt->bind_result($customer_id, $customer_full_name, $customer_email, $customer_phone_number);

                        $stmt->fetch();


                        $user = array(
                            'id' => $customer_id,
                            'fullname' => $customer_full_name,
                            'email' => $customer_email,
                            'phone' => $customer_phone_number,
                        );


                        $stmt->close();

                        //adding the user data in response
                        $response['error'] = false;
                        $response['message'] = 'User registered successfully';
                        $response['user'] = $user;
                    }
                }
            } else {
                $response['error'] = true;
                $response['message'] = 'required parameters are not available';
            }

            break;

        case 'login':

            //for login we need the username and password
            if (isTheseParametersAvailable(array('username', 'password'))) {
                //getting values
                $username = $_POST['username'];
                $password = $_POST['password'];


                $sql = "select * from users where email = '".$username."' OR phone = '".$username."' ";
                $rs = mysqli_query($conn,$sql);
                $numRows = mysqli_num_rows($rs);

                if($numRows  == 1){
                    $row = mysqli_fetch_assoc($rs);
                    if(password_verify($password,$row['password'])){
                        $check_email = Is_email($username);

                        if ($check_email) {
                            // email & password combination
                            $stmt = $conn->prepare("SELECT id, name, email, phone FROM users WHERE email = ? ");
                        } else {
                            // username & password combination
                            $stmt = $conn->prepare("SELECT id, name, email, phone FROM users WHERE phone = ? ");
                        }

                        //creating the query
                        $stmt->bind_param("s", $username);

                        $stmt->execute();

                        $stmt->store_result();

                        //if the user exist with given credentials
                        if ($stmt->num_rows > 0) {

                            $stmt->bind_result($customer_id, $customer_full_name, $customer_email, $customer_phone_number);
                            $stmt->fetch();

                            $user = array(
                                'id' => $customer_id,
                                'fullname' => $customer_full_name,
                                'email' => $customer_email,
                                'phone' => $customer_phone_number,
                            );

                            $response['error'] = false;
                            $response['message'] = 'Login successfull';
                            $response['user'] = $user;
                        } else {
                            //if the user not found
                            $response['error'] = true;
                            $response['message'] = 'Your Credentials are invalid!';
                        }
                    }
                    else{
                        $response['error'] = true;
                        $response['message'] = 'Wrong Password';
                    }
                }
                else{
                    $response['error'] = true;
                    $response['message'] = 'No User Found';
                }

            }
            break;

        default:
            $response['error'] = true;
            $response['message'] = 'Invalid Operation Called';
    }
} else {
    //if it is not api call
    //pushing appropriate values to response array
    $response['error'] = true;
    $response['message'] = 'Invalid API Call';
}

//displaying the response in json structure 
echo json_encode($response);

//function validating all the paramters are available
//we will pass the required parameters to this function 
function isTheseParametersAvailable($params)
{

    //traversing through all the parameters
    foreach ($params as $param) {
        //if the paramter is not available
        if (!isset($_POST[$param])) {
            //return false
            return false;
        }
    }
    //return true if every param is available
    return true;
}

function Is_email($user)
{
    //If the username input string is an e-mail, return true
    if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}
