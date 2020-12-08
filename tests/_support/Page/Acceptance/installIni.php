<?php

    namespace Page\Acceptance;

    /**
     * Данный класс представляет собой install.ini
    */
    class installIni
    {
        /** @var $domain - содержит domain из секции [LICENSE] файла install.ini */
        public $domain;

        /** @var $ip - содержит ip из секции [LICENSE] файла install.ini */
        public $ip;

        /** @var $key -  содержит key из секции [LICENSE] файла install.ini */
        public $key;

        /** @var $host -  содержит host из секции [DB] файла install.ini */
        public $host;

        /** @var $dbuser -  содержит user из секции [DB] файла install.ini */
        public $dbuser;

        /** @var $dbpassword -  содержит password из секции [DB] файла install.ini */
        public $dbpassword;

        /** @var $dbname -  содержит dbname из секции [DB] файла install.ini */
        public $dbname;

        /** @var $templateName -  содержит name из секции [DEMOSITE] файла install.ini */
        public $templateName;

        /** @var $login -  содержит login из секции [SUPERVISOR] файла install.ini */
        public $login;

        /** @var $password -  содержит password из секции [SUPERVISOR] файла install.ini */
        public $password;

        /** @var $email -  содержит email из секции [SUPERVISOR] файла install.ini */
        public $email;

        /** Путь к install.ini. Временное решение (хардкод). Позже будет изменен */
        private const INSTALL_INI_PATH = 'install.ini';

        /**
         * При создании класса запускает parseInstallIni
         */
        public function __construct()
        {
            $this->parseInstallIni();
        }

        /**
         * Данный метод парсит install.ini в переменные класса installIni
         */
        private function parseInstallIni()
        {
            if (!file_exists(self::INSTALL_INI_PATH)) {
                throw new \Exception("Не найден файл install.ini."
                    . "Убедитесь, что он существует в корневой директории.");
            }
            $iniArray = parse_ini_file(self::INSTALL_INI_PATH, true);

            $licenseTestArray = ['domain', 'ip', 'key'];
            if (!array_key_exists('LICENSE', $iniArray)) {
                foreach ($licenseTestArray as $key => $value) {
                    $iniArray['LICENSE'][$key] = $value;
                }
            }

            $licenseArray = $iniArray['LICENSE'];
            $dbArray = $iniArray['DB'];
            $svArray = $iniArray['SUPERVISOR'];
            try {
                $this->domain = $licenseArray['domain'];
                $this->ip = $licenseArray['ip'];
                $this->key = $licenseArray['key'];
                $this->host = $dbArray['host'];
                $this->dbuser = $dbArray['user'];
                $this->dbpassword = $dbArray['password'];
                $this->dbname = $dbArray['dbname'];
                $this->templateName = $iniArray['DEMOSITE']['name'];
                $this->login = $svArray['login'];
                $this->password = $svArray['password'];
                $this->email = $svArray['email'];
            } catch (\Exception $e) {
                throw new \Exception('Проблема с одним из полей файла install.ini. Убедитесь, что существуют все поля.');
            }
        }
    }