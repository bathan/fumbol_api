<?php
namespace fumbol\common\resources {

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

                //-- We are being asked to create a new user. First lets create the user authentication information


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
    }

}
