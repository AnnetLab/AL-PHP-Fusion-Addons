<?php
require_once "../../maincore.php";

if (isset($_GET['cat_id']) && isnum($_GET['cat_id'])) {

    $result = dbquery("SELECT * FROM ".DB_NEWS_CATS." WHERE news_cat_id='".$_GET['cat_id']."'");
    if (dbrows($result)) {

        $cat = dbarray($result);
        $result = dbquery("SELECT * FROM ".DB_NEWS." WHERE news_cat='".$_GET['cat_id']."' ORDER BY news_datestamp DESC LIMIT 10");

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="windows-1251"?><rss version="2.0" xmlns="http://backend.userland.com/rss2" xmlns:yandex="http://news.yandex.ru"><channel></channel></rss>');
        $channel = $xml->channel;
        $channel->addChild('title',iconv("windows-1251","utf-8",$settings['sitename'].": ".$cat['news_cat_name']));
        $channel->addChild('link',$settings['siteurl']);
        $channel->addChild('description',iconv("windows-1251","utf-8",$settings['description']));
        $logo = $channel->addChild('image');
            $logo->addChild('url',$settings['siteurl'].$settings['sitebanner']);
            $logo->addChild('title',iconv("windows-1251","utf-8",$settings['sitename']));
            $logo->addChild('link',$settings['siteurl']);

        while ($data=dbarray($result)) {
            $item = $channel->addChild('item');
            $item->addChild('title',iconv("windows-1251","utf-8",$data['news_subject']));
            $item->addChild('link',$settings['siteurl']."news.php?readmore=".$data['news_id']);
            $item->addChild('description',htmlspecialchars(iconv("windows-1251","utf-8",$data['news_news'])));
            $item->addChild('yandex__yar__genre','article');
            if ($data['news_image_t2'] != '') {
                $enclosure = $item->addChild('enclosure');
                $enclosure->addAttribute('url',$settings['siteurl']."images/news/thumbs/".$data['news_image_t2']);
                $ext = substr($settings['siteurl']."images/news/thumbs/".$data['news_image_t2'], strrpos($settings['siteurl']."images/news/thumbs/".$data['news_image_t2'], '.')+1);
                switch ($ext) {
                    case "jpg":
                        $type = "image/jpeg";
                    break;
                    case "jpeg":
                        $type = "image/jpeg";
                    break;
                    case "png":
                        $type = "image/png";
                    break;
                    case "gif":
                        $type = "image/gif";
                    break;
                    default:
                        $type = "image/jpeg";
                    break;
                }
                $enclosure->addAttribute('type',$type);
            }
            $item->addChild('pubDate',date('r',$data['news_datestamp']));
            $item->addChild('yandex__yar__full-text',htmlspecialchars(iconv("windows-1251","utf-8",$data['news_extended'])));
        }

        header('Content-type: text/xml; charset=windows-1251');
        echo str_replace("__yar__",":",$xml->asXML());
        //echo $xml->asXML();



    } else {
        die("Invalid category id");
    }

} else {
    die("Set category ID or go to hell");
}

?>