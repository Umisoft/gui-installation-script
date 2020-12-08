<?php

    namespace Page\Acceptance;

    /**
     * Представляет собой файл install.ini
     */
    class installIni {
        /** @var string $domain домен устанавливаемого сайта */
        public $domain;

        /** @var string $ip ip адрес домена */
        public $ip;

        /** @var string $key ключ от umi.cms */
        public $key;

        /** @var string $host ip адрес БД */
        public $host;

        /** @var string $dbUser имя пользователя БД */
        public $dbUser;

        /** @var string $dbPassword пароль пользователя БД */
        public $dbPassword;

        /** @var string $dbName имя БД */
        public $dbName;

        /** @var string $templateName название устанавливаемого шаблона */
        public $templateName;

        /** @var string $login логин администратора */
        public $login;

        /** @var string $password пароль администратора */
        public $password;

        /** @var string $email эл. почта администратора */
        public $email;

        /** @const string INSTALL_INI PATH Путь к install.ini. Временное решение (хардкод). Позже будет изменен */
        private const INSTALL_INI_PATH = 'install.ini';

        /**
         * При создании класса запускает parseInstallIni
         */
        public function __construct() {
            $this->parseInstallIni();
        }

        /**
         * Парсит install.ini в переменные класса installIni
         */
        private function parseInstallIni() {
            if (!file_exists(self::INSTALL_INI_PATH)) {
                throw new \Exception("Не найден файл install.ini."
                    . "Убедитесь, что он существует в корневой директории.");
            }
            $ini = parse_ini_file(self::INSTALL_INI_PATH, true);

            $license = $ini['LICENSE'];
            $db = $ini['DB'];
            $sv = $ini['SUPERVISOR'];
            try {
                $this->domain = $license['domain'];
                $this->ip = $license['ip'];
                $this->key = $license['key'];
                $this->host = $db['host'];
                $this->dbUser = $db['user'];
                $this->dbPassword = $db['password'];
                $this->dbName = $db['dbname'];
                $this->templateName = $ini['DEMOSITE']['name'];
                $this->login = $sv['login'];
                $this->password = $sv['password'];
                $this->email = $sv['email'];
            } catch (\Exception $e) {
                throw new \Exception('Проблема с одним из полей файла install.ini. Убедитесь, что существуют все поля.');
            }
        }
    }