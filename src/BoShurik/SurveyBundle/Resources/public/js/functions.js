/**
 * Created by JetBrains PhpStorm.
 * User: BoShurik
 * Date: 24.01.12
 * Time: 1:05
 */

$(document).ready(function(){
    var elementsCount = {};

    var elementsLength = function (collectionId) {
        if (typeof elementsCount[collectionId] == 'undefined') {
            elementsCount[collectionId] = $('#' + collectionId + ' > div.removable').length - 1;
        }

        return elementsCount[collectionId];
    };

    var getNewElement = function (collectionId) {
        elementsLength(collectionId);

        return ++elementsCount[collectionId];
    };

    $('a.add_button').each(function (index) {
        var collectionId = $(this).data('collection');
    });

    var initPrototype = function (prototype, index, collection, level) {
        var nextLevel, currentLevel;
        if (typeof level == "undefined") {
            currentLevel = '';
            nextLevel = '1';
        } else {
            currentLevel = level;
            nextLevel = level + 1;
        }

        var replaces = [];

        replaces.push([new RegExp("__name__label__", "g"), index]);

        // Найдем прототип в прототипе
        var prototypeRegEx = new RegExp("data-prototype");
        if (prototypeRegEx.test(prototype)) {
            var childPrototypeRegEx = new RegExp("data-prototype=\"([^\"]+)\"", "i");
            var match = childPrototypeRegEx.exec(prototype);

            var previousField = collection.substr(collection.lastIndexOf('_') + 1);

            replaces.push([new RegExp(previousField + "___name__", "g"), previousField + "_" + index]);
            replaces.push([new RegExp("\\[" + previousField + "\\]\\[__name__\\]", "g"), "[" + previousField + "][" + index + "]"]);

            replaces.unshift([new RegExp("&gt;__name__label__&lt;", "g"), "&gt;__name___label__&lt;"]);
            replaces.push([new RegExp("&gt;__name___label__&lt;", "g"), "&gt;__name__label__&lt;"]);
        } else {
            replaces.push([new RegExp("__name__", "g"), index]);
        }

        for (var key in replaces) {
            prototype = prototype.replace(replaces[key][0], replaces[key][1]);
        }

        return prototype;
    };

    $('#content')
        .on('click', 'a.add_button', function (event) {
            event.preventDefault();

            var collectionId = $(this).data('collection');
            var collection = $('#' + collectionId);
            var index = getNewElement(collectionId);
            var prototype = initPrototype(collection.data('prototype'), index, collectionId);

            $(this).closest('div').before(prototype);
        })
        .on('click', 'a.remove_button', function (event) {
            event.preventDefault();

            $(this).closest('div.removable').remove();
        })
    ;
});