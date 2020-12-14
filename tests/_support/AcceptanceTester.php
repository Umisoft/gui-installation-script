<?php

    /**
     * Все стандартные действия и проверки Codeception содержатся в классе Actor.
     * Данный класс позволяет их переопределить / расширить пользовательскими методами.
     * Наличие данного класса обязательно, в противном случае Codeception запускаться не будет.
     * @link https://codeception.com/docs/06-ReusingTestCode
     */
    class AcceptanceTester extends \Codeception\Actor {
        use _generated\AcceptanceTesterActions;
    }
