<?php
/**
 */

namespace Mf\FeedYML\Reader;

use DOMDocument;
use Exception;
use DOMXPath;
use Zend\Stdlib\ErrorHandler;
use Zend\Feed\Reader\Reader as ZFReader;

class Reader extends ZFReader
{
    /**
     * Feed type constants
     */
    const TYPE_YML = 'yml';
    

    /**
     * Imports a feed from a file located at $filename.
     *
     * @param  string $filename
     * @throws Exception
     * @return Feed\FeedInterface
     */
    public static function importFile($filename)
    {
        ErrorHandler::start();
        $feed = file_get_contents($filename);
        $err  = ErrorHandler::stop();
        if ($feed === false) {
            throw new Exception("File '{$filename}' could not be loaded", 0, $err);
        }
        return static::importString($feed);
    }

    /**
     * Import a feed from a string
     *
     * @param  string $string
     * @return Feed\FeedInterface
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public static function importString($string)
    {
        $trimmed = trim($string);
        if (! is_string($string) || empty($trimmed)) {
            throw new Exception('Only non empty strings are allowed as input');
        }

        $libxmlErrflag = libxml_use_internal_errors(true);
        $oldValue = libxml_disable_entity_loader(true);
        $dom = new DOMDocument;
        
       $string=static::normalizeXML($string);
        
        $status = $dom->loadXML(trim($string),LIBXML_BIGLINES | LIBXML_PARSEHUGE);
        libxml_disable_entity_loader($oldValue);
        libxml_use_internal_errors($libxmlErrflag);

        if (! $status) {
            // Build error message
            $error = libxml_get_last_error();
            if ($error && $error->message) {
                $error->message = trim($error->message);
                $errormsg = "DOMDocument cannot parse XML: {$error->message}";
            } else {
                $errormsg = "DOMDocument cannot parse XML: Please check the XML document's validity";
            }
            throw new Exception($errormsg);
        }

        $type = static::detectType($dom);
        
        if ($type == self::TYPE_YML) {
            $reader = new Feed\Yml($dom, $type);
        }  else {
            throw new Exception('YML формат не обнаружен');
        }
        return $reader;
    }

    
    /**
     * Detect the feed type of the provided feed
     *
     * @param  Feed\AbstractFeed|DOMDocument|string $feed
     * @param  bool $specOnly
     * @return string
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public static function detectType($feed, $specOnly = false)
    {
        if ($feed instanceof Feed\AbstractFeed) {
            $dom = $feed->getDomDocument();
        } elseif ($feed instanceof DOMDocument) {
            $dom = $feed;
        } elseif (is_string($feed) && ! empty($feed)) {
            ErrorHandler::start(E_NOTICE | E_WARNING);
            ini_set('track_errors', 1);
            $oldValue = libxml_disable_entity_loader(true);
            $dom = new DOMDocument;
            $feed=self::normalizeXML($feed);
            $status = $dom->loadXML($feed,LIBXML_BIGLINES | LIBXML_PARSEHUGE);
            libxml_disable_entity_loader($oldValue);
            ini_restore('track_errors');
            ErrorHandler::stop();
            if (! $status) {
                if (! isset($phpErrormsg)) {
                    if (function_exists('xdebug_is_enabled')) {
                        $phpErrormsg = '(error message not available, when XDebug is running)';
                    } else {
                        $phpErrormsg = '(error message not available)';
                    }
                }
                throw new Exception("DOMDocument cannot parse XML: $phpErrormsg");
            }
        } else {
            throw new Exception('Invalid object/scalar provided: must'
            . ' be of type Mf\FeedYML\Reader\Feed, DomDocument or string');
        }
        $xpath = new DOMXPath($dom);

        if ($xpath->query('/yml_catalog')->length) {
            $date = $xpath->evaluate('string(/yml_catalog/@date)');

            if ($date) {
               return self::TYPE_YML;
            }

            return self::TYPE_ANY;
        }

    }
    
    /*удаление запрещенных символов из XML, ято бы исключить 
    *ошабки парсера
    * возвращает исправленый документ- строку
    */
    protected static function normalizeXML($string)
    {
        $s=mb_substr($string,0,200);
        if (mb_strpos($s,"windows-1251")>0){
            $string=iconv("windows-1251","windows-1251//IGNORE",$string);
        } else {
            $string=iconv("UTF-8","UTF-8//IGNORE",$string);
        }
        return $string;
    }
}
