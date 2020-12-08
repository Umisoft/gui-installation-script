<?php

    namespace Page\Acceptance;

    use DOMDocument;
    use DOMXPath;
    use Exception;

    /**
     * Данный класс является api клиентом для сервера обновлений
     * @return $name - имя типа устанавливаемого шаблона.
     * Может вернуть null если не найдет шаблон.
    */
    class apiClient {

        /** @var $installIni - переменная класса installIni, нужна для создания параметров GET запроса */
        private $installIni;

        /**
         * Сохраняет instalIni в переменную внутри класса.
         * @param installIni $installIni - переменная класса installIni, нужна для создания параметров GET запроса
         */
        public function __construct(installIni $installIni) {

            $this->installIni = $installIni;
        }

        /**
         * Формирует адрес для запроса и возвращает его
         * @param $type - тип запроса
         * @param array $params - параметры для запроса
         * @return string - возвращает URL
        */
        private function buildUrl($type, $params = []) : string {
            $installIni = $this->installIni;
            $params['type'] = $type;
            $params['revision'] = 'last';
            $params['host'] = $installIni->domain;
            $params['key'] = $installIni->key;
            $params['ip'] = $installIni->ip;
            return base64_decode('aHR0cDovL3VwZGF0ZXMudW1pLWNtcy5ydS91cGRhdGVzZXJ2ZXIv') . '?' .
                http_build_query($params, '', '&');
        }

        /**
         * Загружает файл на удаленном сервере
         * @param $url - URL адрес
         * @return string - возвращает загруженный файл в виде строки
         * @throws Exception - если загрузка не удалась
         */
        private function getRemoteFile($url) : string {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $remoteFileContent = curl_exec($curl);
            $headers = curl_getinfo($curl);

            curl_close($curl);

            if (isset($headers['content_type']) && stripos($headers['content_type'], 'text/xml') !== false) {
                $this->checkXmlErrors($remoteFileContent);
            }

            return $remoteFileContent;
        }

        /**
         * Проверяет xml файл на наличие ошибок
         * @param String $xml - передается XML в виде строки
         * @throws Exception - если отсутствует класс DomDocument
         */
        private function checkXmlErrors(String $xml) {
            if (!class_exists('DomDocument')) {
                throw new Exception(
                    'Отсутствует класс DomDocument. Подробное описание ошибки и способы её устранения ' .
                    'доступны по ссылке http://errors.umi-cms.ru/13051/'
                );
            }

            $doc = new DOMDocument('1.0', 'utf-8');

            if (is_string($xml) && strpos($xml, '<') === 0 && $doc->loadXML($xml)) {
                $this->checkResponseErrors($doc);
            }
        }

        /**
         * Получает список сайтов с сервера
         * @throws Exception - если не удается загрузить список
         * @return DOMDocument - список сайтов
         */
        private function getDemositesList() {
            $url = $this->buildUrl('get-solution-list');
            $result = $this->getRemoteFile($url);
            $doc = new DOMDocument('1.0', 'utf-8');

            if ($doc->loadXML($result)) {
                $this->checkResponseErrors($doc);
                return $doc;
            }

            throw new Exception('Не удается загрузить список сайтов.');
        }

        /**
         * Проверяет ответ сервера на наличие ошибок
         * @throws Exception - выбрасывает ошибку сервера
         */
        private function checkResponseErrors(DOMDocument $doc) {
            if ($doc->documentElement->getAttribute('type') !== 'exception') {
                return;
            }

            $xpath = new DOMXPath($doc);
            $errors = $xpath->query('//error');

                foreach ($errors as $error) {
                    throw new Exception($error->nodeValue, $error->getAttribute('code'));
                }
        }

        /**
         * Получает тип шаблона по его имени
         * @return string - название типа шаблона
         * @throws Exception - в случае ошибок
         */
        public function getSolutionType($name) : string {
            $doc = $this->getDemositesList();
            $xpath = new DOMXPath($doc);
            $types = $xpath->query("//solution[@name=\"$name\"]/parent::*");
            foreach ($types as $type) {
                return $type->nodeName;
            }
        }

}