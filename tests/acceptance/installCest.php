<?php

    use Page\Acceptance\getSolutionType;
    use Page\Acceptance\installPages;
    use Page\Acceptance\installIni;

    /**
     * Основной класс программы (входная точка)
    */
    class installCest {
        /** Таймаут ожидания для перехода к следующему шагу установки */
        public const TIMEOUT = 420;

        /**
         * Основной метод.
         *
         * @param AcceptanceTester $acceptanceTester - переменная, передается от Codeception.
         * Используется как надстройка над драйвером от разработчика фреймворка.
         * @throws Exception - если установка не удалась.
        */
        public function install(AcceptanceTester $acceptanceTester) {
            $installIni = new installIni();
            $installPages = new installPages($acceptanceTester, $installIni);
            $checkTemplate = new getSolutionType($installIni);
            $type = $checkTemplate->getSolutionType($installIni->templateName);
            $acceptanceTester->amOnUrl($installPages->URL);
            $acceptanceTester->waitForElement($installPages->keyField);
            $installPages->coreInstaller();
            $acceptanceTester->waitForElementVisible($installPages->getTypeOfSiteSpanXpath(), self::TIMEOUT);
            $installPages->templateInstaller($type);
            $acceptanceTester->waitForElementVisible($installPages->loginField, self::TIMEOUT);
            $installPages->svUserInstaller();
            $acceptanceTester->waitForElementVisible($installPages->systemInstalledText);
        }

}
