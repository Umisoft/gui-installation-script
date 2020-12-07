<?php

    namespace Page\Acceptance;

    use DOMDocument;
    use DOMXPath;
    use Exception;

    /**
     * Данный класс содержит методы для получения типа загружаемого решения.
     * @return $name - тип устанавливаемого шаблона.
     * 1 - Мои покупки
     * 2 - Бесплатные готовые решения
     * 3 - Демошаблоны
     * Может вернуть null если не найдет шаблон.
    */
    class getSolutionType {

        private $installIni;

        public function __construct(installIni $installIni) {

            $this->installIni = $installIni;
        }

        /**
         * Формирует адрес для запроса и возвращает его
         * @param $type
         * @param array $params
         * @return string
        */
        private function buildUrl($type, $params = []) {
            $installIni = $this->installIni;
            $params['type'] = $type;
            $params['revision'] = 'last';
            $params['host'] = $installIni->domain;
            $params['key'] = $installIni->key;
            $params['ip'] = $installIni->ip;
            return base64_decode('aHR0cDovL3VwZGF0ZXMudW1pLWNtcy5ydS91cGRhdGVzZXJ2ZXIv') . '?' .
                http_build_query($params, '', '&');
        }

        private function getRemoteFile($url) {
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

        private function checkXmlErrors($xml) {
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

            unset($doc);
        }

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

        private function checkResponseErrors(DOMDocument $doc) {
            if ($doc->documentElement->getAttribute('type') == 'exception') {
                $xpath = new DOMXPath($doc);
                $errors = $xpath->query('//error');

                foreach ($errors as $error) {
                    throw new Exception($error->nodeValue, $error->getAttribute('code'));
                }
            }
        }

        public function getSolutionType($name) {
            $doc = $this->getDemositesList();
            $xpath = new DOMXPath($doc);
            $types = $xpath->query("//solution[@name=\"$name\"]/parent::*");
            foreach ($types as $type) {
                return $type->nodeName;
            }
        }

}