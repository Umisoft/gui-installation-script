<?php

    /**
     * Необходим для использования стандартных методов Codeception.
     * Так же, здесь можно создать свои.
     * Подробнее https://codeception.com/docs/06-ReusingTestCode
     * @method void wantToTest($text)
     * @method void wantTo($text)
     * @method void execute($callable)
     * @method void expectTo($prediction)
     * @method void expect($prediction)
     * @method void amGoingTo($argumentation)
     * @method void am($role)
     * @method void lookForwardTo($achieveValue)
     * @method void comment($description)
     * @method void pause()
     *
     * @SuppressWarnings(PHPMD)
    */
    class AcceptanceTester extends \Codeception\Actor {
        use _generated\AcceptanceTesterActions;

    }
