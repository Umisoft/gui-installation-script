<?php
namespace Page\Acceptance;

class installPages
{
    // include url of current page
    public $URL;
    public static $keyField = ("//input[@name=\"key\"]");
    public static $nextButton = "//input[@value=\"Далее   >\"]";
    public static $templateNextButton = "//input[@value=\"Далее   »\"]";
    public static $dbHostField = "//input[@name=\"host\"]";
    public static $dbNameField = "//input[@name=\"dbname\"]";
    public static $dbUserField = "//input[@name=\"user\"]";
    public static $dbPasswordField = "//input[@name=\"password\"]";
    public static $backupCheckbox = "//label[@for=\"cbbackup\"]";
    public static $chooseTemplateHeader = "//p[@class=\"check_user\"]";
    public static $showLogs = "//a[@class=\"wrapper\"]";
    public static $loginField = "//input[@name=\"sv_login\"]";
    public static $emailField = "//input[@name=\"sv_email\"]";
    public static $passwordField = "//input[@name=\"sv_password\"]";
    public static $verifyPasswordField = "//input[@name=\"sv_password2\"]";
    public static $systemInstalledText = "//p[text()=\"Установка системы завершена\"]";
    public static $typeOfSiteSearchField = "//input[@class=\"search\"]";
    public static $typeOfSiteSearchButton = "//input[@class=\"next_step_submit\"]";
    public static $typeOfSite1Span = "//label[@for=\"type_of_site1\"]/span";
    public static $searchResultOfSite = "//div[@class=\"site\"]";

    protected $I;
    protected $installIni;

    public function __construct(\AcceptanceTester $I, $installIni)
    {
        $this->I = $I;
        $this->installIni = $installIni;
        $this->URL = "http://$installIni->domain/install.php";
    }

    /*Формирует xpath адрес для полученного типа решения
    * @param $number
    * @return string
    */
    public function typeOfSiteSpanBuilder($number = 1) {
        return "//label[@for=\"type_of_site$number\"]/span";
    }

    /*Вводит все поля и устанавливает всё до шаблона*/
    public function coreInstaller() {
        $I=$this->I;
        $installIni = $this->installIni;
        $I->fillField(installPages::$keyField, $installIni->key);
        $I->click(installPages::$nextButton);
        $I->waitForElementVisible(installPages::$dbHostField);
        $I->fillField(installPages::$dbHostField, $installIni->host);
        $I->fillField(installPages::$dbNameField, $installIni->dbname);
        $I->fillField(installPages::$dbUserField, $installIni->dbuser);
        $I->fillField(installPages::$dbPasswordField, $installIni->dbpassword);
        $I->click(installPages::$nextButton);
        $I->waitForElementVisible(installPages::$backupCheckbox);
        $I->click(installPages::$backupCheckbox);
        $I->click(installPages::$nextButton);
        $I->waitForElementVisible(installPages::$showLogs);
        $I->click(installPages::$showLogs);
    }

    /* Установка через поиск.
     * Используется для 1 и 2 типа решения
     * @param $typeNumber
     * */
    private function searchTemplateInstaller($typeNumber){
        $I=$this->I;
        $templateName = $this->installIni->templateName;
        $I->click($this->typeOfSiteSpanBuilder($typeNumber));
        $I->click(installPages::$templateNextButton);
        $I->waitForElementVisible(installPages::$typeOfSiteSearchField);
        $I->fillField(installPages::$typeOfSiteSearchField,$templateName);
        $I->click(installPages::$typeOfSiteSearchButton);
        $I->waitForElementVisible(installPages::$searchResultOfSite);
        $I->moveMouseOver(installPages::$searchResultOfSite."/div");
        $I->waitForElementVisible("//div/a[@rel=\"$templateName\"]");
        $I->click("//div/a[@rel=\"$templateName\"]");
        $I->click(installPages::$templateNextButton);
        $I->click(installPages::$templateNextButton);
    }

    /*Отвечает за установку шаблона после coreInstaller*/
    public function templateInstaller($type){
        $I=$this->I;
        $templateName = $this->installIni->templateName;
        $I->waitForElementVisible($this->typeOfSiteSpanBuilder());
        switch ($type){
            case "demo":
                $I->click($this->typeOfSiteSpanBuilder(3));
                $I->click(installPages::$templateNextButton);
                $I->waitForElementVisible("//label[@for=\"$templateName\"]/span");
                $I->click("//label[@for=\"$templateName\"]/span");
                $I->click(installPages::$templateNextButton);
                break;
            case "free":
                $this->searchTemplateInstaller(2);
                break;
            case "paid":
                $this->searchTemplateInstaller(1);
            break;
            default:
                if ($templateName!="_blank") echo("\nНе найден шаблон с именем $templateName.\nПроверьте название в файле install.ini. Если название корректное, то убедитесь, что этот шаблон привязан к вашему ключу. \nУстанавливаю без шаблона.\n\n");
                $I->click($this->typeOfSiteSpanBuilder(4));
                $I->click(installPages::$templateNextButton);
                break;
        }
    }

    /*Отвечает за ввод данных админа*/
    public function svUserInstaller(){
        $I=$this->I;
        $installIni = $this->installIni;
        $I->fillField(installPages::$emailField, $installIni->email);
        $I->fillField(installPages::$loginField,$installIni->login);
        $I->fillField(installPages::$passwordField, $installIni->password);
        $I->fillField(installPages::$verifyPasswordField, $installIni->password);
        $I->click(installPages::$templateNextButton);
    }

}
