<?php

class LandingExportPhp
{
    private $endpoint;
    private $exportHash;

    const MONTH = 2592000;

    public function __construct($exportHash)
    {
        $this->endpoint = 'https://www.landingiexport.com';
        $this->exportHash = $exportHash;
    }

    public function getLanding($hash)
    {
        $data = $this->prepareCurl($hash);

        return $this->injectLightboxHandler(
            $this->modifyButtonSubmissionEndpoints($this->modifyLandingContent($data), $data),
            $data
        );
    }

    private function buildUrlQuery()
    {
        return http_build_query(['export_hash' => $this->exportHash]);
    }

    private function buildConversionUrlQuery($hash)
    {
        return http_build_query(
            [
                'conversion_hash' => $hash,
                'export_hash' => $this->exportHash
            ],
            '',
            '&'
        );
    }

    private function chooseWhichUrl($hash)
    {
        if ($hash) {
            return sprintf(
                '%s/api/render?%s',
                $this->endpoint,
                $this->buildConversionUrlQuery($hash)
            );
        }

        return sprintf(
            '%s/api/render?%s',
            $this->endpoint,
            $this->buildUrlQuery()
        );
    }

    /**
     * @param $hash
     * @return bool|mixed
     */
    private function prepareCurl($hash)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->chooseWhichUrl($hash));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        if (isset($_COOKIE['tid'])) {
            $tidCookie = $_COOKIE['tid'];
            curl_setopt($ch, CURLOPT_COOKIE, "stg-tracker=tid=$tidCookie");
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErrorCode = curl_errno($ch);
        $curlErrorMsg = curl_error($ch);
        curl_close($ch);

        if ($curlErrorCode || $curlErrorMsg) {
            exit(sprintf('check curl settings: %s %s', $curlErrorCode, $curlErrorMsg));
        }

        if (in_array($httpcode, [301, 302], true) && is_string($data) && $data = json_decode($data)) {
            $getParams = $this->unsetExportParam($data->redirect);
            header('Location: ' . $getParams);
        }

        if ((404 === $httpcode && is_string($data)) || ($httpcode >= 200 && $httpcode < 300)) {
            $data = json_decode($data);
            setcookie('tid', $data->tid, time() + self::MONTH);

            return $data;
        }

        return $data;
    }

    private function unsetExportParam($queryStr)
    {
        $parser = parse_url($queryStr);
        $newQuery = [];

        if (isset($parser['query'])) {
            parse_str($parser['query'], $newQuery);
        }

        $parser['query'] = array_merge($newQuery, $_REQUEST);

        foreach (['tid', 'export_hash'] as $param) {
            unset($parser['query'][$param]);
        }

        $parser['query'] = http_build_query($parser['query']);

        return $this->buildRedirectUrl($parser);
    }

    private function buildRedirectUrl($parser)
    {
        $return = '';

        if (isset($parser['scheme'])) {
            $return .= $parser['scheme'] . '://';
        }

        if (isset($parser['host'])) {
            $return .= $parser['host'];
        }

        if (isset($parser['path'])) {
            $return .= $parser['path'];
        }

        if (!empty($parser['query'])) {
            $return .= '?' . $parser['query'];
        }

        return $return;
    }

    private function getActualLink()
    {
        return (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    private function injectLightboxHandler($content, $data)
    {
        $javaScript = <<<HTML
<script>
    if (typeof Lightbox !== 'undefined') {
        Lightbox.init({
            exportUrl: '{$this->endpoint}',
            hash: '{$this->exportHash}',
            tid: '{$data->tid}',
            redirectUrl: '{$this->getActualLink()}'
        });
        Lightbox.register();    
    }
</script>
HTML;

        return preg_replace('/<\/head>/', sprintf('%s</head>', $javaScript), $content);
    }

    private function modifyLandingContent($data)
    {
        return preg_replace(
            '/(<input type="hidden" name="_redirect" value)="">/',
            sprintf(
                '$1="%s">',
                $this->getActualLink()
            ),
            preg_replace(
                '/ action="\/([\s\S]*?)"/',
                sprintf(
                    ' action="%s/${1}?export_hash=%s&tid=%s"',
                    $this->endpoint,
                    $this->exportHash,
                    $data->tid
                ),
                $data->content
            )
        );
    }

    private function modifyButtonSubmissionEndpoints($content, $data)
    {
        return preg_replace(
            '/ href="(?:\/[^\/]+)?(\/button\/[a-zA-z0-9]{32})"/',
            sprintf(
                ' href="%s${1}?export_hash=%s&tid=%s"',
                $this->endpoint,
                $this->exportHash,
                $data->tid
            ),
            $content
        );
    }
}

            const BASE = "725d8e4fa2abb2604953";
            $landingHash = null;
            $landing = new LandingExportPhp(BASE);
           
            if (isset($_REQUEST['hash'])) {
                $landingHash = $_REQUEST['hash'];
            }
            
            $landing = $landing->getLanding($landingHash);
            echo $landing;
