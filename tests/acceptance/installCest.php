<?php

    use Page\Acceptance\apiClient;
    use Page\Acceptance\installPages;
    use Page\Acceptance\installIni;

    /**
     * Класс тестов инсталятора
     *
    */
    class installCest {

        /** @const int TIMEOUT таймаут ожидания для перехода к следующему шагу установки */
        public const TIMEOUT = 420;

        /**
         * Тест установки umi.cms
         * @param AcceptanceTester $acceptanceTester - переменная, передается от Codeception.
         * Используется как надстройка над драйвером от разработчика фреймворка.
         * @throws Exception - если установка не удалась.
        */
        public function install(AcceptanceTester $acceptanceTester) {
            $installIni = new installIni();
            $installPages = new installPages($acceptanceTester, $installIni);
            $checkTemplate = new apiClient($installIni);
            $typeName = $checkTemplate->getSolutionType($installIni->templateName);
            $acceptanceTester->amOnUrl($installPages->url);
            $acceptanceTester->waitForElement($installPages->keyField);
            $installPages->coreInstaller();
            $acceptanceTester->waitForElementVisible($installPages->getTypeOfSiteSpanXpath(), self::TIMEOUT);
            $installPages->templateInstaller($typeName);
            $acceptanceTester->waitForElementVisible($installPages->loginField, self::TIMEOUT);
            $installPages->fillSvUserForm();
            $acceptanceTester->waitForElementVisible($installPages->systemInstalledText);
        }

}
