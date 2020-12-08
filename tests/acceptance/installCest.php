<?php

    use Page\Acceptance\apiClient;
    use Page\Acceptance\installPages;
    use Page\Acceptance\installIni;

    /**
     * Основной класс программы (входная точка)
     * Создает экземпляр класса installIni и installPages.
     *
    */
    class installCest {
        /** Таймаут ожидания для перехода к следующему шагу установки */
        public const TIMEOUT = 420;

        /**
         * Основной метод.
         * 1) Создает экземпляр класса installIni и installPages.
         * 2) Переходит по URL, созданному в installPages
         * 3) Запускает основные методы установки в классе installPages
         * 4) Управляет ожиданиями между методами установки
         * @param AcceptanceTester $acceptanceTester - переменная, передается от Codeception.
         * Используется как надстройка над драйвером от разработчика фреймворка.
         * @throws Exception - если установка не удалась.
        */
        public function install(AcceptanceTester $acceptanceTester) {
            $installIni = new installIni();
            $installPages = new installPages($acceptanceTester, $installIni);
            $checkTemplate = new apiClient($installIni);
            $type = $checkTemplate->getSolutionType($installIni->templateName);
            $acceptanceTester->amOnUrl($installPages->url);
            $acceptanceTester->waitForElement($installPages->keyField);
            $installPages->coreInstaller();
            $acceptanceTester->waitForElementVisible($installPages->getTypeOfSiteSpanXpath(), self::TIMEOUT);
            $installPages->templateInstaller($type);
            $acceptanceTester->waitForElementVisible($installPages->loginField, self::TIMEOUT);
            $installPages->fillSvUserForm();
            $acceptanceTester->waitForElementVisible($installPages->systemInstalledText);
        }

}
