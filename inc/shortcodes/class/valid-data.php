<?php

/**
 * @class validData
 *
 */

if (!class_exists('validData')) {

    class validData
    {

        protected $inputList = [];
        protected $postData = [];
        protected $validations = [];
        protected $validated = [];
        protected $messages = [];
        protected $hasPost = false;

        public function __construct()
        {
            if ($_POST) {
                $this->hasPost = true;
            }
        }

        /**
         * Atribui mensagens de erros para os campos usando
         * chave nome do campo e valor pra a mensagem
         * @param array $messages
         * @return $this
         */
        public function setMessages($messages = [])
        {
            if (is_array($messages)) {
                $this->messages = array_merge($this->messages, $messages);
            }

            return $this;
        }

        /**
         * Adiciona uma lista de campos para serem verificados
         * @param array $list
         * @return $this
         */
        public function setInputs($list = [])
        {
            if (is_array($list)) {
                $this->inputList = array_merge($this->inputList, $list);
            }

            return $this;
        }

        /**
         * Adiciona validações para cada campo onde chave é o nome
         * do campo e valor a validação, aceita uma string 'required'
         * ou uma função anonima com 2 parametros
         * '$data' é o valor do campo
         * e '$instance' a instancia da propria classe,
         * tipo de retorno boolean
         * @param array $list
         * @return $this
         */
        public function setValidations($validations = [])
        {
            if (is_array($validations)) {
                $this->validations = $validations;
            }

            return $this;
        }

        /**
         * Verifica se todos os campos são válidos
         * @return boolean
         */
        public function isValidPost()
        {
            return in_array(0, $this->validated, true) ? false : true;
        }

        /**
         * Recebe o nome de um campo como parametro e verifica se o valor é inválido ou não,
         * caso não encontre o nome do campo retorna falso.
         * @param string $inputName nome do campo
         * @return boolean
         */
        public function hasError($inputName = null)
        {
            if (!is_array($this->inputList) || $this->hasPost == false) {
                return false;
            }

            if (in_array($inputName, $this->inputList)) {
                return !$this->getValidation($inputName);
            }
        }

        /**
         * Recebe o nome de um campo como parametro e verifica se o valor é válido ou não,
         * caso não encontre o nome do campo retorna true.
         * @param string $inputName nome do campo
         * @return boolean
         */
        protected function getValidation($inputName = null)
        {

            if (!empty($inputName)) {
                return $this->validated[$inputName];
            }

            return true;
        }

        /**
         * Recupera todos os dados de post
         * Tenta filtrar somente os campos solicitados para verificação.
         * @param array $post
         * @return $this
         */
        public function getPostData($post)
        {
            if (empty($this->inputList) || empty($post)) {
                return false;
            }

            if (version_compare(phpversion(), '5.6', '>=')) {
                $inputs = array_filter($post, function ($key) {
                    return in_array($key, $this->inputList);
                }, ARRAY_FILTER_USE_KEY);
            } else {
                $inputs = $post;
            }

            $this->postData = $inputs;

            return $this;
        }

        /**
         * Recebe o nome de um campo como parametro e
         * recupera a mensagem de erro.
         * @param string $inputName nome do campo
         * @return string|void
         */
        public function getMessage($inputName = null)
        {
            if (is_string($inputName)) {
                return $this->messages[$inputName]
                    ? $this->messages[$inputName]
                    : 'O campo obrigatório.';
            }

            return null;
        }

        /**
         * Válida os campos
         */
        public function valid()
        {
            $validation_results = [];

            foreach ($this->validations as $key => $validation) {

                if (is_callable($validation) && $validation instanceof Closure) {
                    $validation_results[$key] = (int) $validation($this->postData[$key], $this);
                }

                if ($validation == 'required') {
                    $validation_results[$key] = empty($this->postData[$key]) ? 0 : 1;
                }
            }

            $this->validated = $validation_results;
        }

        /**
         * Retorna um array com informações das validações dos campos
         * @return array
         */
        public function getValidated()
        {
            return $this->validated;
        }

        /**
         * Retorna um array com input post
         *@return array
         */
        public function getExtractData()
        {
            return $this->postData;
        }

        public function getMessages()
        {
            return $this->messages;
        }
    }
}
