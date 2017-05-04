<?php

use \Menu\Element;
use \Menu\API;

require_once 'menu/API.php';
require_once 'menu/Element.php';
$cmsPages = new API();

$pagesEditor = new Element('Редактор страниц', 'page_editors');
$pagesEditor->addChild(new Element('Статичные страницы', 'content_editor'));
$pagesEditor->addChild(new Element('Создаваемые страницы', 'articles'));
$pagesEditor->addChild(new Element('Слайдер', 'slider'));
$cmsPages->addElement($pagesEditor);

$products = new Element('Товары', 'products_manager');
$products->addChild(new Element('Категории товаров', 'categories'));
$products->addChild(new Element('Список товаров', 'products'));
$products->addChild(new Element('Отзывы', 'feedback'));
$products->addChild(new Element('Покупки', 'purchases'));
$products->addChild(new Element('Баннеры', 'banners'));
$cmsPages->addElement($products);

$cmsPages->addElement(new Element('Коллекции купонов', 'coupons_collections'));
$cmsPages->addElement(new Element('Пользователи', 'users'));

$blog = new Element('Блог', 'blog');
$blog->addChild(new Element('Категории блога', 'blog_categories'));
$blog->addChild(new Element('Статьи блога', 'blog_articles'));
$cmsPages->addElement($blog);

$subscriptions = new Element('Новостные рассылки', 'subscription_options');
$subscriptions->addChild(new Element('Новости', 'subscriptions'));
$subscriptions->addChild(new Element('Подписанные Email', 'emails_subscriptions'));
$cmsPages->addElement($subscriptions);

$cmsPages->addElement(new Element('Обратная связь', 'contact_feedback'));
$cmsPages->addElement(new Element('Ресурсы', 'resources'));
$cmsPages->addElement(new Element('Настройки', 'settings'));

// Сервисные страницы
$cmsPages->addElement(
    new Element('Авторизация', API::AUTORIZATION, TRUE)
);
$cmsPages->addElement(
    new Element('Выход', API::LOGOUT, TRUE)
);
$cmsPages->addElement(
    new Element('Загрузка файлов', API::FILE_UPLOAD, TRUE)
);

$GLOBALS['cmsPages'] = $cmsPages;
