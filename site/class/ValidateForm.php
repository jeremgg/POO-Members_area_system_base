<?php

    class ValidateForm{

        //Initialize the data property that will have the form data
        private $data;

        //Initialize the error property that will have the error messages based on the data
        private $errors = [];



        /**
         * Redefine property $data
         * @param $data The form data
         */
        public function __construct($data){
            $this->data = $data;
        }



        /**
         * Retrieve the passed field as a parameter
         * And check that it contains data
         * @param  $field The field to be validated
         * @return string The value of the fields
         */
        private function getField($field){
            if(empty($this->data[$field])){
                return null;
            }
            return $this->data[$field];
        }



        /**
         * Check that the value of the field is valid
         * @param  string $field    The field to be validated
         * @param  string $errorMsg The error message
         * @return bool
         */
        public function isAlphanumeric($field, $errorMsg){
            if(!preg_match('/^[a-zA-Z0-9_]+$/', $this->getField($field))){
                $this->errors[$field] = $errorMsg;
            }
        }



        /**
         * Check that the value of a field does not exist in the database
         * @param  $field    The field to be validated
         * @param  $table    The table in the database
         * @param  $errorMsg The error message
         * @return bool
         */
        public function isUniq($field, $db, $table, $errorMsg){
            $record = $db->query("SELECT id FROM $table WHERE username = ?", [$this->getField($field)])->fetch();
            if($record){
                $this->errors[$field] = $errorMsg;
            }
        }



        /**
         * Check that the value of the email field is valid
         * @param  $field    The field to be validated
         * @param  $errorMsg The error message
         * @return bool
         */
        public function isEmail($field, $errorMsg){
            if(!filter_var($this->getField($field), FILTER_VALIDATE_EMAIL)){
                $this->errors[$field] = $errorMsg;
            }
        }



        /**
         * Check that the password fields have the same value
         * @param  $field    The field to be validated
         * @param  $errorMsg The error message
         * @return bool
         */
        public function isConfirmed($field, $errorMsg = ''){
            $value = $this->getField($field);
            if(empty($value) || $value != $this->getField($field . "_confirm")){
                $this->errors[$field] = $errorMsg;
            }
        }



        /**
         * Check that the error property is empty
         * If the array is empty, there is no error and the form data is valid
         * @return bool
         */
        public function isValid(){
            return empty($this->errors);
        }



        /**
         * display error messages
         * @return array
         */
        public function getErrors(){
            return $this->errors;
        }
    }

?>
