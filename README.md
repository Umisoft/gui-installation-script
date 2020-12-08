UMI.CMS 2 INSTALL BOX
======

### УСТАНОВКА

Смотрите [BEFORESTART.md](lib/BEFORESTART.md).


### Описание
Данный тест предназначен для автоматической установки umi.cms2.

В основе теста лежит фреймворк Codeception. Поэтому, для работоспособности теста необходим данный фреймворк (см. [BEFORESTART.md](lib/BEFORESTART.md)).  
Данный тест переходит по домену, указанному в install.ini и устанавливает umi.cms2 при помощи файла install.php.  
install.php можно скачать по [тут](https://www.umi-cms.ru/downloads/full/)  
На данный момент поддерживается установка через браузер Google Chrome на платформе Windows.

Тест берет все данные из файла install.ini и устанавливает cms через браузер без участия пользователя (аналогично консольному установщику).

Подробнее о параметрах install.ini смотрите [INSTALLINI.md](INSTALLINI.md).


###Примечание
На данный момент, скрипт устанавливает umi.cms и шаблон с таймаутом в 420с (840 суммарно). При необходимости, их можно поменять в файле installCest.php (константа TIMEOUT).  
В дальнейшем планируется произвести отказ от таймаутов, и перейти к постоянной проверке состояния установки.

На данный момент, тест не обрабатывает негативные кейсы (не нужно пытаться его сломать).

Если указан несуществующий шаблон, или недоступный для данного ключа, в консоль будет выведено сообщение и будет установлена cms без шаблона.

В случае, если по каким-то причинам тест будет провален (таймаут и др.) будет сделан скриншот в папку _output корневой директории.

Перед запуском установки убедитесь,

###Запуск установки

1. Убедитесь, что имеются все необходимые файлы, и они лежат в правильных директориях (см. [BEFORESTART.md](lib/BEFORESTART.md)).
2. Очистите корневую директорию сайта и положите туда install.php
3. Проверьте, что файл install.ini существует, и его содержание соответствует минимальным требованиям (см. [INSTALLINI.md](INSTALLINI.md)).
4. Запустить run driver+selenium.sh. Убедитесь, что в консоли нет никаких ошибок.
5. Для запуска самого теста используется файл start.sh. Вы можете запускать данный тест из консоли своими силами. В таком случае, см. [официальную документацию](https://codeception.com/docs/02-GettingStarted#Running-Tests)