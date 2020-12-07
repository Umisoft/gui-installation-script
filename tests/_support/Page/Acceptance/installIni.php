<?php

    namespace Page\Acceptance;

    /**
     * Данный класс отвечает за парсинг файла install.ini
    */
    class installIni {
        public $domain, $ip, $key;
        public $host, $dbuser, $dbpassword, $dbname;
        public $templateName;
        public $login, $password, $email;

        /** Путь к install.ini. Временное решение (хардкод). Позже будет изменен */
        private const INSTALL_INI_PATH = 'install.ini';

        public function __construct() {
            $this->parseInstallIni();
        }

        /**
         * Данный метод парсит install.ini в переменные класса installIni
        */
        private function parseInstallIni() {
            if (!file_exists(self::INSTALL_INI_PATH)) throw new \Exception("Не найден файл install.ini. 
            Убедитесь, что он существует в корневой директории.");
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