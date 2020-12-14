<?php

    /**
     * Класс для переопределения стандартных методов Codeception и добавления пользовательских.
     * @link https://codeception.com/docs/06-ReusingTestCode
     */
    class AcceptanceTester extends \Codeception\Actor {
        use _generated\AcceptanceTesterActions;
    }
