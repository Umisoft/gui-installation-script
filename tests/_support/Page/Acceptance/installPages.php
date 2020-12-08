<?php

    namespace Page\Acceptance;

    /**
     * Представляет страницу установки /install.php в виде POM
    */
    class installPages {
        /** @var string $url адрес страницы с /install.php */
        public $url;

        /** @var string $labelTypeOfSite xpath к radiobutton при выборе типа решения */
        private $labelTypeOfSite;

        /** @var string $keyField xpath к полю ввода "Введите ключ"*/
        public $keyField = ("//input[@name=\"key\"]");

        /** @var string $nextButton xpath к кнопке "Далее   >", которая используется до установки шаблона */
        private $nextButton = "//input[@value=\"Далее   >\"]";

        /** @var string $templateNextButton xpath к кнопке "Далее   »", которая используется при установке шаблона */
        private $templateNextButton = "//input[@value=\"Далее   »\"]";

        /** @var string $dbHostField xpath к полю ввода "Имя хоста" */
        private $dbHostField = "//input[@name=\"host\"]";

        /** @var string $dbNameField xpath к полю ввода "Имя базы данных" */
        private $dbNameField = "//input[@name=\"dbname\"]";

        /** @var string $dbUserField xpath к полю ввода "Логин" при настройке БД */
        private $dbUserField = "//input[@name=\"user\"]";

        /** @var string $dbPasswordField xpath к полю ввода "Пароль" при настройке БД */
        private $dbPasswordField = "//input[@name=\"password\"]";

        /** @var string $backupCheckbox xpath к чекбоксу "Подтверждаю, что сделал бэкап всех файлов,
        а также дамп базы данных средствами хостинг-провайдера." */
        private $backupCheckbox = "//label[@for=\"cbbackup\"]";

        /** @var string $showLogs xpath к гиперссылке "Показать ход установки" */
        private $showLogs = "//a[@class=\"wrapper\"]";

        /** @var string $loginField xpath к полю ввода "Логин" при настройке суперпользователя */
        public $loginField = "//input[@name=\"sv_login\"]";

        /** @var string $emailField xpath к полю ввода "E-mail" при настройке суперпользователя */
        private $emailField = "//input[@name=\"sv_email\"]";

        /** @var string $passwordField xpath к полю ввода "Пароль" при настройке суперпользователя */
        private $passwordField = "//input[@name=\"sv_password\"]";

        /** @var string $verifyPasswordField xpath к полю ввода "Пароль ещё раз" при настройке суперпользователя */
        private $verifyPasswordField = "//input[@name=\"sv_password2\"]";

        /** @var string $systemInstalledText xpath к тексту "Установка системы завершена" */
        public $systemInstalledText = "//p[text()=\"Установка системы завершена\"]";

        /** @var string $typeOfSiteSearchField xpath к полю ввода поиска "Введите номер сайта" */
        private $typeOfSiteSearchField = "//input[@class=\"search\"]";

        /** @var string $typeOfSiteSearchButton xpath к кнопке "Найти" на странице поиска */
        private $typeOfSiteSearchButton = "//input[@class=\"next_step_submit\"]";

        /** @var string $searchResultOfSite  xpath к результату поиска */
        private $searchResultOfSite = "//div[@class=\"site\"]";

        /** @var \AcceptanceTester $acceptanceTester экземпляр класса для управления фреймворком */
        private $acceptanceTester;

        /** @var installIni $installIni экземпляр installIni */
        private $installIni;

        /** @const int PAID_SOLUTION_TYPE тип "платные готовые решения" */
        private const PAID_SOLUTION_TYPE = 1;

        /** @const int FREE_SOLUTION_TYPE тип "бесплатные готовые решения" */
        private const FREE_SOLUTION_TYPE = 2;

        /** @const int DEMO_SOLUTION_TYPE тип "демошаблоны" */
        private const DEMO_SOLUTION_TYPE = 3;

        /** @const int BLANK_SOLUTION_TYPE тип "без шаблона" */
        private const BLANK_SOLUTION_TYPE = 4;

        /**
         * При создании класса производит первоначальную установку.
         * @param \AcceptanceTester $acceptanceTester - используется для управления драйвером.
         * @param $installIni - экземпляр класса installIni для получения параметров
         */
        public function __construct(\AcceptanceTester $acceptanceTester, $installIni) {
            $this->acceptanceTester = $acceptanceTester;
            $this->installIni = $installIni;
            $this->url = "http://$installIni->domain/install.php";
        }

        /**
         * Формирует xpath адрес для полученного типа решения
         * @param int $type тип решения
         * @see self::PAID_SOLUTION_TYPE
         * @see self::FREE_SOLUTION_TYPE
         * @see self::DEMO_SOLUTION_TYPE
         * @see self::BLANK_SOLUTION_TYPE
         * @return string
         */
        public function getTypeOfSiteSpanXpath($type = self::PAID_SOLUTION_TYPE) {
            return "//label[@for=\"type_of_site$type\"]/span";
        }

        /** Выбирает в браузере тип шаблона
         * @param int $type тип готового решения
         * @see self::PAID_SOLUTION_TYPE
         * @see self::FREE_SOLUTION_TYPE
         * @see self::DEMO_SOLUTION_TYPE
         * @see self::BLANK_SOLUTION_TYPE
         */
        private function selectTypeOfSite($type) {
            $this->acceptanceTester->click($this->getTypeOfSiteSpanXpath($type));
            $this->acceptanceTester->click($this->templateNextButton);
        }

        /**
         * Данный метод:
         * 1)Заполняет страницу "Проверка подлинности"
         * 2)Заполняет страницу "Настройка базы данных"
         * 3)Устанавливает чекбокс "Подтверждаю, что сделал бэкап всех файлов,
         * а также дамп базы данных средствами хостинг-провайдера."
         * 4)Нажимает на ссылку "Показать ход установки"
         */
        public function coreInstaller() {
            $acceptanceTester = $this->acceptanceTester;
            $installIni = $this->installIni;
            $acceptanceTester->fillField($this->keyField, $installIni->key);
            $acceptanceTester->click($this->nextButton);
            $acceptanceTester->waitForElementVisible($this->dbHostField);
            $acceptanceTester->fillField($this->dbHostField, $installIni->host);
            $acceptanceTester->fillField($this->dbNameField, $installIni->dbName);
            $acceptanceTester->fillField($this->dbUserField, $installIni->dbUser);
            $acceptanceTester->fillField($this->dbPasswordField, $installIni->dbPassword);
            $acceptanceTester->click($this->nextButton);
            $acceptanceTester->waitForElementVisible($this->backupCheckbox);
            $acceptanceTester->click($this->backupCheckbox);
            $acceptanceTester->click($this->nextButton);
            $acceptanceTester->waitForElementVisible($this->showLogs);
            $acceptanceTester->click($this->showLogs);
        }

        /** Находит через поиск введенное решение и устанавливает его.
         * На данный момент используется только для 1 и 2 типов решения
         * @see self::PAID_SOLUTION_TYPE
         * @see self::FREE_SOLUTION_TYPE
         * @throws \Exception - если установка не удалась.
         */
        private function searchTemplateInstaller() {
            $acceptanceTester = $this->acceptanceTester;
            $templateName = $this->installIni->templateName;
            $chooseButton = "//div/a[@rel=\"$templateName\"]";
            $acceptanceTester->click($this->templateNextButton);
            $acceptanceTester->waitForElementVisible($this->typeOfSiteSearchField);
            $acceptanceTester->fillField($this->typeOfSiteSearchField, $templateName);
            $acceptanceTester->click($this->typeOfSiteSearchButton);
            $acceptanceTester->waitForElementVisible($this->searchResultOfSite);
            $acceptanceTester->moveMouseOver($this->searchResultOfSite . "/div");
            $acceptanceTester->waitForElementVisible($chooseButton);
            $acceptanceTester->click($chooseButton);
            $acceptanceTester->click($this->templateNextButton);
            $acceptanceTester->click($this->templateNextButton);
        }

        /**
         * Выбирает устанавливаемый шаблон.
         * @param string $typeName имя типа готового решения
         * @throws \Exception если установка не удалась
         */
        public function templateInstaller($typeName) {
            $acceptanceTester = $this->acceptanceTester;
            $templateName = $this->installIni->templateName;
            $this->labelTypeOfSite = "//label[@for=\"$templateName\"]/span";
            switch ($typeName) {
                case "demo": {
                    $this->selectTypeOfSite(self::DEMO_SOLUTION_TYPE);
                    $acceptanceTester->waitForElementVisible($this->labelTypeOfSite);
                    $acceptanceTester->click($this->labelTypeOfSite);
                    $acceptanceTester->click($this->templateNextButton);
                    break;
                }
                case "free": {
                    $this->selectTypeOfSite(self::FREE_SOLUTION_TYPE);
                    $this->searchTemplateInstaller();
                    break;
                }
                case "paid": {
                    $this->selectTypeOfSite(self::PAID_SOLUTION_TYPE);
                    $this->searchTemplateInstaller();
                    break;
                }
                default: {
                    $this->selectTypeOfSite(self::BLANK_SOLUTION_TYPE);
                }
            }
        }

        /**
         * Заполняет форму администратора
         */
        public function fillSvUserForm() {
            $acceptanceTester = $this->acceptanceTester;
            $installIni = $this->installIni;
            $acceptanceTester->fillField($this->emailField, $installIni->email);
            $acceptanceTester->fillField($this->loginField, $installIni->login);
            $acceptanceTester->fillField($this->passwordField, $installIni->password);
            $acceptanceTester->fillField($this->verifyPasswordField, $installIni->password);
            $acceptanceTester->click($this->templateNextButton);
        }

}
