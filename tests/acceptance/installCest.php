<?php

use Page\Acceptance\getSolutionType;
use Page\Acceptance\installPages;
use Page\Acceptance\installIni;

class installCest
{

    public const TIMEOUT = 420;

    /*Основной метод. */
    public function install(AcceptanceTester $I)
    {
        //Первичный парсинг всех данных
        $installIni = new installIni();
        $installPages = new installPages($I,$installIni);
        $checkTemplate = new getSolutionType($installIni);
        $type = $checkTemplate->getSolutionType($installIni->templateName);
        //Основная установка
        $I->amOnUrl($installPages->URL);
        $I->waitForElement(installPages::$keyField);
        $installPages->coreInstaller();
        $I->waitForElementVisible($installPages->typeOfSiteSpanBuilder(), self::TIMEOUT);
        //Установка шаблона
        $installPages->templateInstaller($type);
        $I->waitForElementVisible(installPages::$loginField, self::TIMEOUT);
        //Ввод полей админа
        $installPages->svUserInstaller();
        //Проверка наличия надписи "Установка завершена!"
        $I->waitForElementVisible(installPages::$systemInstalledText);
    }

}
