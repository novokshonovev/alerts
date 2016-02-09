# alerts

## Назначение

Виджет предназначен для отображанеи всплавающих сообщений пользователю. 

## Установка

1. Загрузить через git: https://github.com/novokshonovev/alerts.git
или 
2. Установка через composer 

2.1 Добавить в composer.json проекта:

* репозиторий 
```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/novokshonovev/alerts"
        }
    ],
```
* и зависимость
```json
    "require": {
        "dowlatow/alerts": "dev-master"
    },
```
2.2 Выполнить установку: ``composer install``
 
 
## Общие сведенья

Виджет обеспечивает:
* Отображение 5 типов сообщений в течении заданного промежутка времени
* Втутреннее хранилище сообщений и сбор сообщений заданных типов из хранилища flash-сообщений сессии
* Статический рендер сообщений при генерации страницы и возможность динамической вставки сообщений на стороне клиента

## Встроенные константы и типы всплывающих сообщений

Виджет определяет 5 типов всплывающих сообщений. Каждому типу сообщения соответствует встроенная константа. 
Тип сообщения должен быть указан при добавлении его в хранилище сессии или в хранилище виджета.

Добавление сообщения напрямую в хранилище виджета:
```php
$alertWidget->addAlert($alertWidget::SUCCESS, Yii::t('link', 'Link(s) attached to webinar(s)'));
```
Добавление сообщения в хранилище сессии:
```php
Yii::$app->session->addFlash(AlertsBlock::SUCCESS, Yii::t('webinar', 'Event has been activated'));
```

#### Список встроенных констант:

Название    | Значение  | css-класс
:----------:|:---------:|--------------
**ERROR**   | 'error'   | .alert-error 
**DANGER**  | 'danger'  | .alert-danger
**WARNING** | 'warning' | .alert-warning  
**INFO**    | 'info'    | .alert-info
**SUCCESS** | 'success' | .alert-success


## Html-разметка и клиент-обработчик

Виджет генерирует блок сообщений с классом ```alert-block```, внутри блока располагаются сообщения:
```html
<div id="w0" class="alert-block">
    <div id="w0-success-1" class="alert-success alert fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        Вебинар деактивирован
    </div>
    <div id="w0-error-2" class="alert-error alert fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        Еще какое то сообщение....
    </div>
</div>

```

Клиент-обработчик **alerts** реализован в виде jQuery-плагина установленного на блоке сообщений.
Обработчик имеет метод для добавления новых сообщений:
```JavaScript
    var newAlerts =[
        '<div id="w0-danger-55" class="alert-danger alert fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Сообщение добавленное через js</div>',
        '<div id="w0-info-88" class="alert-info alert fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> И еще одно сообщение добавленное через js</div>',
    ];    

    $('#w0').alerts('addAlerts', newAlerts);
```

## Параметры конфигурации

В данном разделе описаны только параметры конфигурации специфичные для **AlertsBlock**. Стандартные параметры конфигурации yii-виджетов см. в [документации фреймворка](https://github.com/yiisoft/yii2/blob/master/docs/guide-ru/structure-widgets.md)

#### Список параметров (public свойств)
Название            |Тип        |Default    |Зависимости и ограничения  | Описание
:------------------:|:---------:|:---------:|---------------------------|-------------------------------------------
**closeButton**     | array     | []        |                           | Опции для рендера закрывающей кнопки, см. [Alert](http://www.yiiframework.com/doc-2.0/yii-bootstrap-alert.html) 
**onlyWrapper**     | bool      | false     | 1                         | Запрещает рендер сообщений во время рендера контейнера
**fillFlashes**     | bool      | true      | 1                         | Включает автозаполнение сообщений из массива flash-сообщений сессии во время рендера контейнера  см. [Session](http://www.yiiframework.com/doc-2.0/yii-web-session.html)    
**duration**        | int       | 100000    |                           | Длительность отображения сообщения, мс

#### Зависимости и ограничения параметров
1. Если **onlyWrapper** = true, то значение **fillFlashes** ни на что не влияет.   

## Методы
В данном разделе описаны только методы специфичные для **AlertsBlock**. Стандартные методы yii-виджетов см. в [документации фреймворка](https://github.com/yiisoft/yii2/blob/master/docs/guide-ru/structure-widgets.md)

### Public
* **public function run()** - Выполняет рендер контейнера и инициализацию клиент-обработчика, если **onlyWrapper** = false, то выполняет рендер сообщений из внутреннего хранилища, если **fillFlashes** = true, то перед рендером сообщений, во внутренне хранилище добавляются сообщения из хранилища сессии с ключами соответствующими типам сообщений виджета;
* **public function addAlert($type, $message)** - Добавляет сообщение с заданным типом во внутренее хранилище виджета;
* **public function renderItem($type, $message)** - Рендерить сообщение заданного типа, см. [Alert](http://www.yiiframework.com/doc-2.0/yii-bootstrap-alert.html) 
* **public function renderItems($fillFlashes = true)** - Рендерить сообщения из внутреннего хранилища и очищает внутреннее хранилище, если **fillFlashes** = true, то перед рендером сообщений, во внутренне хранилище добавляются сообщения из хранилища сессии с ключами соответствующими типам сообщений виджета; 
* **public function generateJsAlerts($fillFlashes = true, $inFunction = false)** - По сообщениям внутреннего хранилища генерирует js-код вида ```$('#w0').alerts('addAlerts', newAlerts);``` для выполнения вставки сообщений на стороне клиента и очищает внутренне хранилище, если **fillFlashes** = true, то перед рендером сообщений, во внутренне хранилище добавляются сообщения из хранилища сессии с ключами соответствующими типам сообщений виджета, если **inFunction**= true, то сгеннерированный код будет обернут в функцию ```function(){<код>}```.     

## Пример использования

### Пример 1 - Простые всплывающие сообщения
Вывод всех сообщений добавленных в хранилище сессии до рендера страницы, дллительность показа сообщений 5 секунд:
```php
use frontend/widgets/alerts/AlertsBlock;
...
<body>
    <?= AlertsBlock::widget(['duration' = 5000])?>
    ...
</body>
```

### Пример 2 - Глобальный экземпляр виджета и вставка сообщений через ajax

1. Доопределение стандартного View, далее Viev используется во всех контролерах и представлениях:
```php
class View extends \yii\web\View
{
    private $alertsBlock;
    public function init()
    {
        parent::init();
        $this->alertsBlock = AlertsBlock::begin([]);
    }
    public function getAlertsBlock()
    {
        return $this->alertsBlock;
    }
}
```

2. Основной блок сообщений рендерится в общем layout, т.е. при рендере каждой страницы выводятся все накопленные сообщения: 
```php
...
<?php $this->beginContent('@frontend/views/layouts/empty.php'); ?>
<?= $this->alertsBlock->run();?>
<?= $content ?>
<?php $this->endContent(); ?>
```

3. В контроллерах и представлениях можно добавлять сообщения в сессию или в хранилище виджета:
```php
$alertWidget->addAlert($alertWidget::SUCCESS, Yii::t('link', 'Link(s) attached to webinar(s)'));
....
Yii::$app->session->addFlash(AlertsBlock::SUCCESS, Yii::t('webinar', 'Event has been activated'));
```

4. Для вставки сообщений через ajax

    * В контроллере генерируется js 
```php
Yii::$app->response->format = 'json';
$alertWidget = $this->view->alertsBlock;
if ($attacher->attach($eventItems, $documents)) {
    $alertWidget->addAlert($alertWidget::SUCCESS, Yii::t('document', 'Material(s) attached to webinar(s)'));
} else {
    $alertWidget->addAlert($alertWidget::ERROR, Yii::t('document', 'Material(s) do not attached to webinar(s)'));
}
return [
    'success' => true,
    'alerts' => $alertWidget->generateJsAlerts(),
];
```  
    * Который выполняется в браузере после получения ответа по ajax:
```JavaScript
$.ajax({
    url: params.common.postUrl,
    type: 'POST',
    data: $postForm.serialize(),
    success: function (data) {
        eval(data.alerts);
    }
});
```
    
    