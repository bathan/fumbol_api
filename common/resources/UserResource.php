<?php
namespace fumbol\common\resources {

    use fumbol\common\data\entities\User;
    use fumbol\common\data\entities\UserAuth;
    use fumbol\common\Language;
    use fumbol\common\Utilities;
    use fumbol\common\UserUtilities;

    class UserResource extends AbstractResource {

        /*
         * Adds a new User
         */

        public function addUser() {

            try {

                $min_data = ['nickname','email','password'];

                $form = $this->getApp()->request()->post();

                foreach($min_data as $required_field) {
                    if(!isset($form[$required_field])) {
                        throw new \Exception("Missing required field ".$required_field.". Required fields are ".implode(",",$min_data));
                    }
                }

                $email = $form["email"];
                $password = $form["password"];
                $nickname = $form["nickname"];

                if(!Utilities::isValidEmail($email)) {
                    throw new \Exception(Language::t('USERS_NO_USER_WITH_EMAIL',"Email ".$email." is not a valid email address"));
                }

                //-- Before creating a new user, we need to check we dont have a user with this same user name
                $existing_user = UserAuth::getByUserName($email);

                if(!is_null($existing_user)) {
                    throw new \Exception("Email ".$email." is already in use");
                }

                //-- Ok, lets create the new User
                $user_auth = new UserAuth();
                $user_auth->setUserName($email);

                //-- Lets encode the password using a salt string
                $salt_string = strval(time());
                $password = $password.$salt_string;

                $user_auth->setPassword(sha1($password));
                $user_auth->setSalt(base64_encode($salt_string));

                $user_auth->persist();

                //-- Now we have the user_id, lets create the user now

                $user = new User();
                $user->setUserId($user_auth->getUserId());
                $user->setEmail($email);
                $user->setNickname($nickname);
                $user->persist();

                //-- Remove Password from Response
                unset($form['password']);

                $this->getApp()->render(
                    200,
                    ['data' => $form]
                );

            }catch(\Exception $e) {

                $this->getApp()->render(
                    500,
                    ['error' => $e->getMessage()]
                );
            }


        }

        public function loginUser() {

            try {

                $min_data = ['email','password'];

                $form = $this->getApp()->request()->post();

                foreach($min_data as $required_field) {
                    if(!isset($form[$required_field])) {
                        throw new \Exception("Missing required field ".$required_field.". Required fields are ".implode(",",$min_data));
                    }
                }

                $email = $form["email"];

                //-- In order to check the user password we need to retrieve the row by email and compare encoded passwords
                $user_auth = UserAuth::getByUserName($email);

                if(is_null($user_auth)) {
                    throw new \Exception("No user with that email address");
                }

                //-- Ok, we have the user_auth info, lets check the password
                $salt = $user_auth->getSalt();
                $salt = base64_decode($salt);
                $password = $form["password"].$salt;

                if(sha1($password)!= $user_auth->getPassword()) {
                    throw new \Exception("Wrong password");
                }

                $user_auth->setLastSuccessfulLogin(Utilities::now());
                $user_auth->persist();

                $token_info = ["user_id"=>$user_auth->getUserId(),"user_name"=>$user_auth->getUserName(),"created"=>Utilities::now(),"env_secret"=>_TOKEN_SECRET];

                $token = Utilities::generate_signed_request($token_info,_ENCODING_SECRET);

                $response_data = $user_auth->toArray();
                $response_data["token"] = $token;

                $this->getApp()->render(
                    200,
                    ['data' => $response_data]
                );
            }catch(\Exception $e) {

                $this->getApp()->render(
                    200,
                    ['error' => $e->getMessage()]
                );
            }


        }

        public function tokenTest() {
            try {

                $min_data = ['token'];

                $form = $this->getApp()->request()->post();

                foreach($min_data as $required_field) {
                    if(!isset($form[$required_field])) {
                        throw new \Exception("Missing required field ".$required_field.". Required fields are ".implode(",",$min_data));
                    }
                }

                $token = $form["token"];

                $uu = new UserUtilities();
                $user_info = $uu->getUserInfoFromToken($token);

                $this->getApp()->render(
                    200,
                    ['data' => $user_info->toArray()]
                );

            }catch(\Exception $e) {

                $this->getApp()->render(
                    500,
                    ['error' => $e->getMessage()]
                );
            }

        }
    }

}
