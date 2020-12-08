<?php

    namespace Page\Acceptance;

    /**
     * Класс представляет страницу установки /install.php в виде POM
    */
    class installPages {
        /** @var $url - содержит url адрес страницы с /install.php */
        public $url;

        /** @var $labelTypeOfSite - содержит xpath к radiobutton при выборе типа решения */
        public $labelTypeOfSite;

        /** @var $keyField - содержит xpath к полю ввода "Введите ключ"*/
        public $keyField = ("//input[@name=\"key\"]");

        /** @var $nextButton - содержит xpath к кнопке "Далее   >", которая используется до установки шаблона */
        public $nextButton = "//input[@value=\"Далее   >\"]";

        /** @var $templateNextButton - содержит xpath к кнопке "Далее   »", которая используется при установке шаблона */
        public $templateNextButton = "//input[@value=\"Далее   »\"]";

        /** @var $dbHostField - содержит xpath к полю ввода "Имя хоста" */
        public $dbHostField = "//input[@name=\"host\"]";

        /** @var $dbNameField - содержит xpath к полю ввода "Имя базы данных" */
        public $dbNameField = "//input[@name=\"dbname\"]";

        /** @var $dbUserField - содержит xpath к полю ввода "Логин" при настройке БД */
        public $dbUserField = "//input[@name=\"user\"]";

        /** @var $dbPasswordField - содержит xpath к полю ввода "Пароль" при настройке БД */
        public $dbPasswordField = "//input[@name=\"password\"]";

        /** @var $backupCheckbox - содержит xpath к чекбоксу "Подтверждаю, что сделал бэкап всех файлов,
        а также дамп базы данных средствами хостинг-провайдера." */
        public $backupCheckbox = "//label[@for=\"cbbackup\"]";

        /** @var $showLogs - содержит xpath к гиперссылке "Показать ход установки" */
        public $showLogs = "//a[@class=\"wrapper\"]";

        /** @var $loginField - содержит xpath к полю ввода "Логин" при настройке суперпользователя */
        public $loginField = "//input[@name=\"sv_login\"]";

        /** @var $emailField - содержит xpath к полю ввода "E-mail" при настройке суперпользователя */
        public $emailField = "//input[@name=\"sv_email\"]";

        /** @var $passwordField - содержит xpath к полю ввода "Пароль" при настройке суперпользователя */
        public $passwordField = "//input[@name=\"sv_password\"]";

        /** @var $verifyPasswordField - содержит xpath к полю ввода "Пароль ещё раз" при настройке суперпользователя */
        public $verifyPasswordField = "//input[@name=\"sv_password2\"]";

        /** @var $systemInstalledText - содержит xpath к тексту "Установка системы завершена" */
        public $systemInstalledText = "//p[text()=\"Установка системы завершена\"]";

        /** @var $typeOfSiteSearchField - содержит xpath к полю ввода поиска "Введите номер сайта" */
        public $typeOfSiteSearchField = "//input[@class=\"search\"]";

        /** @var $typeOfSiteSearchButton - содержит xpath к кнопке "Найти" на странице поиска */
        public $typeOfSiteSearchButton = "//input[@class=\"next_step_submit\"]";

        /** @var $searchResultOfSite - содержит xpath к результату поиска */
        public $searchResultOfSite = "//div[@class=\"site\"]";

        /** @var $acceptanceTester - содержит экземпляр класса для управления фреймворком */
        private $acceptanceTester;

        /** @var $installIni - содержит экземпляр installIni */
        private $installIni;

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
         * @param int $type - тип решения, по умолчанию тип 1
         * @return string
         */
        public function getTypeOfSiteSpanXpath($type = 1) {
            return "//label[@for=\"type_of_site$type\"]/span";
        }

        /** Данный метод отвечает за выбор в браузере типа шаблона.
         * @param $type - передается тип готового решения
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
            $acceptanceTester->fillField($this->dbNameField, $installIni->dbname);
            $acceptanceTester->fillField($this->dbUserField, $installIni->dbuser);
            $acceptanceTester->fillField($this->dbPasswordField, $installIni->dbpassword);
            $acceptanceTester->click($this->nextButton);
            $acceptanceTester->waitForElementVisible($this->backupCheckbox);
            $acceptanceTester->click($this->backupCheckbox);
            $acceptanceTester->click($this->nextButton);
            $acceptanceTester->waitForElementVisible($this->showLogs);
            $acceptanceTester->click($this->showLogs);
        }

        /** Находит через поиск введенное решение и устанавливает его.
         * На данный момент используется только для 1 и 2 типов решения
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
         * @param $type - передается тип готового решения
         * @throws \Exception - если установка не удалась
         */
        public function templateInstaller($type) {
            $acceptanceTester = $this->acceptanceTester;
            $templateName = $this->installIni->templateName;
            $this->labelTypeOfSite = "//label[@for=\"$templateName\"]/span";
            switch ($type) {
                case "demo": {
                    $this->selectTypeOfSite(3);
                    $acceptanceTester->waitForElementVisible($this->labelTypeOfSite);
                    $acceptanceTester->click($this->labelTypeOfSite);
                    $acceptanceTester->click($this->templateNextButton);
                    break;
                }
                case "free": {
                    $this->selectTypeOfSite(2);
                    $this->searchTemplateInstaller();
                    break;
                }
                case "paid": {
                    $this->selectTypeOfSite(3);
                    $this->searchTemplateInstaller();
                    break;
                }
                default:
                {
                    if ($templateName != "_blank") {
                        echo("\nНе найден шаблон с именем $templateName.\n"
                            . "Проверьте название в файле install.ini.\n"
                            . "Если название корректное, то убедитесь, что этот шаблон привязан к вашему ключу.\n"
                            . "Устанавливаю без шаблона.\n\n");
                    }
                    $this->selectTypeOfSite(4);
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
