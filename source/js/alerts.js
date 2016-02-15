(function ($) {
    var Alerts = function ($widgetContainer, params) {

        var addAlert = function (alertContent) {
            var $item = $(alertContent);
            $widgetContainer.append($item);
            $item.alert();
            setDuration($item);
        };

        var setDuration = function ($item) {
            setTimeout(function ($alert) {
                $alert.alert('close');
            }, params.duration, $item);
        };

        this.methods = {
            addAlerts: function (alerts) {
                $.each(alerts, function (index, content) {
                    addAlert(content);
                });
            }
        };

        $widgetContainer.find('div.alert').each(function () {
            setDuration($(this));
        });
    };

    $.fn.alerts = $.fn.widgetGenerator(
        {
            duration: 10000
        },
        'alerts-widget',
        function ($widgetContainer, params) {
            return new Alerts($widgetContainer, params);
        }
    );
})(jQuery);
